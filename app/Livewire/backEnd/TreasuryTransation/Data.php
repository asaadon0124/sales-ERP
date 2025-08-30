<?php

namespace App\Livewire\BackEnd\TreasuryTransation;

use App\Models\Admin;
use App\Models\Shift;
use App\Models\Account;
use Livewire\Component;
use App\Models\MoveType;
use App\Models\Supplier;
use App\Models\Treasuries;
use Livewire\WithPagination;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
// use App\Livewire\BackEnd\TreasuryTransation\Data;

class Data extends Component
{
    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh','account_id_changed' => 'accountId','Mtypes' => 'Mtype'];
    public $treasury_id;
    public $account_id;
    public $cash_amount;
    public $moveType_id;
    public $notes;
    public $auto_serial;
    public $isal_number;
    public $move_date;
    public $invoice_type_accounts;


    public $search;
    public $treasury_transation;
    public $get_active_shift;
    public $active_shift_count;
    public $accounts;
    public $moveTypes;
    public $treasry;
    public $treasury_balance    = 0;
    public $adminID;
    public $account;
    public $account_balance;
    public $account_balance_before;

    public function updatingSearch()
    {
        $this->resetPage();
    }



    public function accountId($value)
    {
        $this->account_id = $value;
        $this->account = Account::where('account_number',$value)->with('account_customer','account_customer2','account_servant','account_employee')->first();
        $this->account_balance = $this->account->current_balance;
        // dd($this->account);

    }


    public function Mtype($value)
    {
        $this->moveType_id = $value;
    }


    public function mount()
    {
        $this->adminID                              = auth()->user()->id;
        $this->get_active_shift                     = Shift::where('admin_id',auth()->user()->id)->with('treasury')->where('shift_status','active')->first();
        $this->accounts                             = Account::with('accountType')->get();
        $this->moveTypes                            = MoveType::where('in_screen','collect')->get();

        if ($this->get_active_shift)
        {
            $this->active_shift_count                   = $this->get_active_shift->count();
            $this->treasry                              = $this->get_active_shift->treasury;
            $this->treasury_balance                     = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount');
            $this->treasury_id                          = $this->treasry->id;
        }
    }


    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
          if (!auth()->user()->can('اضافة تحصيل جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة تحصيل جديد');
        }

        $validated = $this->validate(
        [
            'treasury_id'       => 'required',
            'moveType_id'       => 'required|exists:move_types,id',
            'cash_amount'       => 'required|numeric|gt:0',
            'move_date'         => 'required',
            'notes'             => 'required',
            'account_id'        => 'required|exists:accounts,account_number',
        ],
        [
            'treasury_id.required' => 'اسم الخزنة مطلوب.',
            'moveType_id.required' => 'نوع الحركة مطلوب.',
            'cash_amount.required' => 'المبلغ المحصل مطلوب.',
            'cash_amount.numeric'  => 'المبلغ المحصل يجب ان يكون أرقام.',
            'cash_amount.gt'       => 'المبلغ المحصل يجب أن يكون أكبر من 0.',
            'move_date.required'   => 'تاريخ التحصيل مطلوب.',
            'notes.required'       => 'البيان مطلوب.',
            'account_id.required'  => 'اسم الحساب مطلوب.',
            'account_id.exists'    => 'اسم الحساب غير موجود.',
            'moveType_id.exists'   => 'اسم الحركة غير موجود.',
        ]);

        try
        {
            DB::beginTransaction();

                if ($this->account->account_customer != null)           // مورد
                {
                $this->account_balance_before    = $updateAccountBalance->getCurrentBalance($this->account_id,'supplier','supplier_account');

                }elseif($this->account->account_customer2 != null)      // عميل
                {
                    $this->account_balance_before    = $updateAccountBalance->getCurrentBalance($this->account_id,'customer','account_customer2');

                }elseif($this->account->account_servant != null)        // مندوب
                {
                    $this->account_balance_before    = $updateAccountBalance->getCurrentBalance($this->account_id,'servant','account_servant');
                }else
                {
                    $this->account_balance_before    =  $updateAccountBalance->getCurrentBalance($this->account_id,'general','');
                }


              // 1 - انشاء جدول حركة الخزن TREASURY TRANSATIONS ***************************************
                $create = TreasuryTransation::create(
                    [
                        'treasury_id'     => $this->treasury_id,
                        'shift_id'        => $this->get_active_shift->auto_serial,
                        'moveType_id'     => $this->moveType_id,
                        'cash_amount'     => $this->cash_amount,
                        'cash_for_account'=> $this->cash_amount,
                        'account_id'      => $this->account_id,
                        'invoice_type_accounts'  => $this->invoice_type_accounts,
                        'auto_serial'     => get_last_autoSerial_invoices(TreasuryTransation::class,'auto_serial'),
                        'isal_number'     => $this->treasry->last_recept_recive + 1,
                        'account_balance_before'    =>  $this->account_balance_before,
                        'cash_source_type'=> 'account',
                        'is_approve'      => 'approve',
                        'move_date'       => $this->move_date,
                        'notes'           => $this->notes,
                        'company_code'    => auth()->user()->company_code,
                        'created_by'      => auth()->user()->id,
                        'updated_by'      => auth()->user()->id,
                    ]);


                if ($this->account->accountType->name == 'مندوب')
                {
                    $create->servant_cash_amount            = $this->cash_amount;
                    $create->account_balance_servant_before = $this->account_balance_before;
                    $create->servant_account_id             = $this->account->account_number;
                    $create->save();
                }

              // 2 - تعديل جدول الخزن TREASURES ***************************************
                $this->treasry->update(
                [
                    'last_recept_recive' => $create->isal_number
                ]);

                $this->treasury_balance = $this->treasury_balance + $this->cash_amount;

              // 3 - تعديل جدول العملاء او الموردين او المناديب او موظفSUPPLIER OR CUSTOMERS SERVANTS ***************************************
                if ($this->account->account_customer != null)           // مورد
                {
                    // dd('supplier');
                    $this->account->account_customer->current_balance   = $updateAccountBalance->getCurrentBalance($create->account_id,'supplier','supplier_account');
                    $this->account->account_customer->save();

                    $this->account->current_balance                     = $updateAccountBalance->getCurrentBalance($create->account_id,'supplier','supplier_account');
                    $this->account->save();

                    $create->account_type           = 'suppliers';
                    $create->account_balance_after  = $updateAccountBalance->getCurrentBalance($create->account_id,'supplier','supplier_account');
                    $create->save();


                }elseif($this->account->account_customer2 != null)      // عميل
                {
                    //  dd('customer');
                    $this->account->account_customer2->current_balance   = $updateAccountBalance->getCurrentBalance($create->account_id,'customer','account_customer2');
                    $this->account->account_customer2->save();

                    $this->account->current_balance                     = $updateAccountBalance->getCurrentBalance($create->account_id,'customer','account_customer2');
                    $this->account->save();

                    $create->account_type           = 'customers';
                    $create->account_balance_after  = $updateAccountBalance->getCurrentBalance($create->account_id,'customer','account_customer2');
                    $create->save();

                }elseif($this->account->account_servant != null)        // مندوب
                {
                    // servant
                    $this->account->account_servant->current_balance   = $updateAccountBalance->getCurrentBalance($create->account_id,'servant','account_servant');
                    $this->account->account_servant->save();

                    $this->account->current_balance                     = $updateAccountBalance->getCurrentBalance($create->account_id,'servant','account_servant');
                    $this->account->save();

                    $create->account_type                   = 'servants';
                    $create->account_balance_after          = $updateAccountBalance->getCurrentBalance($create->account_id,'servant','account_servant');
                    $create->account_balance_servant_after  = $updateAccountBalance->getCurrentBalance($create->account_id,'servant','account_servant');
                    $create->save();

                }elseif($this->account->account_employee != null)       // موظف
                {
                    //  dd('employee');
                    // $this->account->account_employee->current_balance   = getCoustomerCurrentBalance($create->account_id,'account_employee');
                    $this->account->account_employee->current_balance   = $updateAccountBalance->getCurrentBalance($create->account_id,'employee','account_employee');
                    $this->account->account_employee->save();

                    // $this->account->current_balance                     = getCoustomerCurrentBalance($create->account_id,'account_employee');
                    $this->account->current_balance                     = $updateAccountBalance->getCurrentBalance($create->account_id,'employee','account_employee');
                    $this->account->save();

                    $create->account_type           = 'employee';
                    $create->account_balance_after  = $updateAccountBalance->getCurrentBalance($create->account_id,'employee','account_employee');
                    $create->save();

                }else                                                   // حساب فقط
                {
                    // dd('ds');
                    // $this->account->current_balance                     = getCoustomerCurrentBalance($create->account_id,'account_employee');
                    $this->account->current_balance                     = $updateAccountBalance->getCurrentBalance($create->account_id,'general','');
                    $this->account->save();

                    $create->account_type           = 'general';
                    $create->account_balance_after  = $updateAccountBalance->getCurrentBalance($create->account_id,'general','');
                    $create->save();
                }




              // 4 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('اضافة تحصيل ', "اضافة تحصيل {$create->notes}", 'TreasuryTransaction', $create->id,auth()->user()->id);

            DB::commit();
            $this->reset(['cash_amount','move_date','notes']);

            // Dispatch events to update UI
            $this->dispatch('treasury_transationsCreateMS');
            $this->dispatch('createModalToggle');
            $this->dispatch('refreshData')->to(Data::class);

        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->dispatch('treasury_transationsErrorMS');
            throw $th;
        }




    }


    public function render()
    {
        $data = TreasuryTransation::query()
        ->when($this->search != '', function ($query)
        {
            $query->where(function ($q)
            {
                $q->where('auto_serial', 'like', '%' . $this->search . '%')
                ->orWhereHas('account', function ($q2)
                {
                    $q2->where('name', 'like', '%' . $this->search . '%');
                });
            });
        })
        ->where('treasury_id', $this->treasury_id)
        ->whereHas('move_type', function ($query)
        {
            $query->where('in_screen', 'collect');
        })
        ->with('treasurie', 'account','move_type')
        ->latest()
        ->paginate(5);



        return view('back-end.treasury-transation.data',compact('data'));
    }
}

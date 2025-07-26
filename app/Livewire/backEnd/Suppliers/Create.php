<?php

namespace App\Livewire\BackEnd\Suppliers;

use App\Models\Account;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\AdminSitting;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Suppliers\Data;


class Create extends Component
{

    public $supplier;
    public $account;
    public $typeStatus              = 'active';
    public $getLastSupplier;
    public $getLastAccount;
    public $adminSitting;
    public $suppliers_Category;

    public $name;
    public $status;
    public $start_balance_status;
    public $current_balance;
    public $start_balance;
    public $notes;
    public $account_number;
    public $supplier_Category_id;

    public $supplier_code;
    public $address;
    public $company_code;

    protected $listeners = ['supplierCreate'];


    public function supplierCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }


    public function changeStartBalance($value)
    {
      $this->start_balance_status =  start_balanceStatus($value);
    }




    public function mount()
    {
        $this->getLastSupplier                  = Supplier::select('account_number','supplier_code','company_code','id','name')->where('company_code',auth()->user()->company_code)->orderBy('supplier_code', 'desc')->first();
        $this->getLastAccount                   = Account::select('account_number','company_code','id','name')->where('company_code',auth()->user()->company_code)->latest()->first();
        $this->adminSitting                     = AdminSitting::where('company_code',auth()->user()->company_code)->with('supplierAccount')->first();
        $this->suppliers_Category               = SupplierCategory::where('company_code',auth()->user()->company_code)->get();
        $this->company_code                     = auth()->user()->company_code;
        // dd($this->adminSitting);


    }

    public function submit()
    {

        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('suppliers')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'                        => 'required',
            'start_balance_status'          => 'required',
            'start_balance'                 => 'required',
            'address'                       => 'required',
            'supplier_Category_id'          => 'required|exists:supplier_categories,id',
        ],
        [
            'name.required'                 => 'اسم المورد مطلوب.',
            'address.required'              => 'عنوان المورد مطلوب.',
            'status.required'               => '؛حالة المورد مطلوبة.',
            'name.unique'                   => 'اسم المورد موجود بالفعل.',
            'start_balance.required'        => 'رصيد المورد بداية المدة مطلوب.',
            'start_balance_status.required' => 'حالة المورد اول المدة مطلوب.',
            'supplier_Category_id.required' => 'اسم قسم المورد مطلوب.',
            'supplier_Category_id.exists'   => 'اسم قسم المورد غير موجود.',
        ]);


        if (!auth()->user()->can('اضافة مورد جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة مورد جديد ');
        }

        try
        {
            // dd($this->adminSitting);



            if ($this->adminSitting->supplierAccount && $this->adminSitting->supplierAccount->count() > 0)
            {
                DB::beginTransaction();
                // 1 - CREATE NEW ACCOUNT **************************************************
                $this->account                          = new Account();

                $this->account->name                    = $this->name;
                $this->account->account_type_id         = $this->adminSitting->supplierAccount->account_type_id;
                $this->account->is_parent               = '1';
                $this->account->parent_account_number   = $this->adminSitting->supplierAccount->account_number;
                $this->account->start_balance_status    = $this->start_balance_status;
                $this->account->notes                   = $this->notes;
                if ($this->start_balance_status == 'credit')
                {
                    $this->account->start_balance           = $this->start_balance * 1;
                    $this->account->current_balance         = $this->start_balance * 1;
                }elseif ($this->start_balance_status == 'debit')
                {
                    $this->account->start_balance           = $this->start_balance * (-1);
                    $this->account->current_balance         = $this->start_balance * (-1);
                }else
                {
                    $this->account->start_balance           = 0;
                    $this->account->current_balance         = 0;
                }

                $this->account->account_number               = get_last_autoSerial(Account::class, 'account_number');


                $this->account->status                  = $this->status;
                $this->account->company_code            = auth()->user()->company_code;
                $this->account->created_by              = auth()->user()->id;
                $this->account->updated_by              = auth()->user()->id;
                $this->account->save();
// dd($this->account);

            // 2 - CREATE NEW SUPPLIER **************************************************
                $this->supplier                          = new Supplier();

                $this->supplier->name                    = $this->name;
                $this->supplier->start_balance_status    = $this->start_balance_status;
                $this->supplier->notes                   = $this->notes;
                $this->supplier->address                 = $this->address;
                $this->supplier->start_balance           = $this->account->start_balance;
                $this->supplier->current_balance         = $this->account->current_balance;
                $this->supplier->supplier_Category_id    = $this->supplier_Category_id;
                $this->supplier->supplier_code           = get_last_autoSerial(Supplier::class, 'supplier_code');

                // if ($this->getLastSupplier && $this->getLastSupplier != null)
                // {
                //     $this->supplier->supplier_code = $this->getLastSupplier->supplier_code + 1;

                // }else
                // {
                //     $this->supplier->supplier_code = 1;
                // }


                $this->supplier->account_number          = $this->account->account_number;
                $this->supplier->status                  = $this->status;

                $this->supplier->company_code            = auth()->user()->company_code;
                $this->supplier->created_by              = auth()->user()->id;
                $this->supplier->updated_by              = auth()->user()->id;

                $this->supplier->save();

                 // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة مورد جديد  ';
                    $actionHistory->desc        = "اضافة مورد جديد";
                    $actionHistory->table_name  = 'Supplier';
                    $actionHistory->row_id      = $this->supplier->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                DB::commit();

            } else
            {
                dd('no');
                toastr()->success('برجاء اضافة حساب اب الموردين', 'رسالة خطا'); // ✅
                return redirect()->route('suppliers.index');
            }


            $this->reset(
            [
                'status',
                'start_balance_status',
                'supplier_Category_id',
            ]);
            // Dispatch events
            $this->dispatch('supplierCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('supplierErrorMS');                   // Flash Message
        }

    }





    public function render()
    {
        return view('back-end.suppliers.create',
        [
            'suppliersCategory' => $this->suppliers_Category,
        ]);
    }
}

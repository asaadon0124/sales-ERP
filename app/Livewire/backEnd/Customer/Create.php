<?php

namespace App\Livewire\BackEnd\Customer;

use App\Models\Account;
use Livewire\Component;
use App\Models\Customer;
use App\Models\AdminSitting;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Customer\Data;


class Create extends Component
{

    public $customer;
    public $account;
    public $typeStatus              = 'active';
    public $getLastCustomer;
    public $getLastAccount;
    public $adminSitting;

    public $name;
    public $status;
    public $start_balance_status;
    public $current_balance;
    public $start_balance;
    public $notes;
    public $account_number;

    public $customer_code;
    public $address;
    public $company_code;


    protected $listeners = ['customerCreate'];


    public function customerCreate()
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
        $this->getLastCustomer                  = Customer::select('account_number','company_code','id','name')->where('company_code',auth()->user()->company_code)->latest()->first();
        $this->getLastAccount                   = Account::select('account_number','company_code','id','name')->where('company_code',auth()->user()->company_code)->latest()->first();
        $this->adminSitting                     = AdminSitting::where('company_code',auth()->user()->company_code)->with('account')->first();
        $this->company_code                     = auth()->user()->company_code;
        // dd($this->adminSitting);
    }

    public function submit()
    {
        if (!auth()->user()->can('اضافة عميل جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة عميل جديد');
        }


        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('customers')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'                        => 'required',
            'start_balance_status'          => 'required',
            'start_balance'                 => 'required',
            'address'                       => 'required',
        ],
        [
            'name.required'                 => 'اسم العميل مطلوب.',
            'address.required'              => 'عنوان العميل مطلوب.',
            'status.required'               => '؛حالة العميل مطلوبة.',
            'name.unique'                   => 'اسم العميل موجود بالفعل.',
            'start_balance.required'        => 'رصيد العميل بداية المدة مطلوب.',
            'start_balance_status.required' => 'حالة العميل اول المدة مطلوب.',
        ]);


        try
        {
            // dd($this->all());



            if ($this->adminSitting->account && $this->adminSitting->account->count() > 0)
            {
                DB::beginTransaction();
                // 1 - CREATE NEW ACCOUNT **************************************************
                    $this->account                          = new Account();

                    $this->account->name                    = $this->name;
                    $this->account->account_type_id         = $this->adminSitting->account->account_type_id;
                    $this->account->is_parent               = '1';
                    $this->account->parent_account_number   = $this->adminSitting->account->account_number;
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


            // 2 - CREATE NEW CUSTOMER **************************************************
                $this->customer                          = new Customer();

                $this->customer->name                    = $this->name;
                $this->customer->start_balance_status    = $this->start_balance_status;
                $this->customer->notes                   = $this->notes;
                $this->customer->address                 = $this->address;
                $this->customer->start_balance           = $this->account->start_balance;
                $this->customer->current_balance         = $this->account->current_balance;

                $this->customer->customer_code               = get_last_autoSerial(Customer::class, 'customer_code');


                $this->customer->account_number          = $this->account->account_number;
                $this->customer->status                  = $this->status;

                $this->customer->company_code            = auth()->user()->company_code;
                $this->customer->created_by              = auth()->user()->id;
                $this->customer->updated_by              = auth()->user()->id;

                $this->customer->save();


                 // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة عميل جديد';
                    $actionHistory->desc        = 'اضافة عميل جديد';
                    $actionHistory->table_name  = 'Admin';
                    $actionHistory->row_id      = $this->customer->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                DB::commit();

            } else
            {
                DB::rollback();
                toastr()->success('برجاء اضافة حساب اب للعملاء', 'رسالة خطا'); // ✅
                return redirect()->route('accounts.index');
            }


            $this->reset(
            [
                'status',
                'start_balance_status',
            ]);
            // Dispatch events
            $this->dispatch('customerCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('accountsErrorMS');                   // Flash Message
        }

    }


    public function render()
    {
        return view('back-end.customer.create');
    }
}

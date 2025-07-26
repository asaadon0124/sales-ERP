<?php

namespace App\Livewire\BackEnd\Accounts;

use App\Models\Account;
use Livewire\Component;
use App\Models\AccountType;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Accounts\Data;


class Create extends Component
{
    public $acountsTypes=[];
    public $get_parent_accounts;
    public $account;
    public $typeStatus              = 'active';

    public $name;
    public $status;
    public $account_type_id;
    public $is_parent;
    public $parent_account_number;
    public $account_number;
    public $start_balance_status;
    public $start_balance;
    public $current_balance;
    public $notes;
    public $company_code;


    protected $listeners = ['accountsCreate'];

    public function accountsCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }

    public function submit_is_parent($value)
    {
        $this->is_parent = $value;
    }



    public function mount()
    {
        $this->acountsTypes = AccountType::where('status', 'active')->whereNotIn('name', ['عميل', 'موظف', 'مندوب', 'مورد','عام'])->get();


        $get_account                = Account::select('account_number','company_code','id','name')->latest()->first();
        $this->get_parent_accounts = Account::select('account_number', 'company_code', 'name', 'id')->whereNotIn('name',
        [
            'حساب العملاء العام',
            'حساب الموردين العام',
            'حساب الموظقين العام',
            'حساب المناديب العام',
        ])->where('is_parent','1')->get();

            $this->company_code         = auth()->user()->company_code;


        // if ($get_account && $get_account->count() > 0)
        // {
        //     $this->account_number = $get_account->account_number +1;
        // }else
        // {
        //     $this->account_number = 1;
        // }
    }


    public function changeStartBalance($value)
    {
      $this->start_balance_status =  start_balanceStatus($value);
    }

    public function submit()
    {
        if (!auth()->user()->can('اضافة حساب جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة حساب جديد ');
        }


        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('accounts')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'                    => 'required',
            'account_type_id'           => 'required',
            'is_parent'                 => 'required',
            'parent_account_number'     => 'required_if:is_parent,0',
            'start_balance_status'      => 'required',
            'start_balance'             => 'required',
            // 'current_balance'           => 'required',
        ],
        [
            'name.required'             => 'اسم الحساب مطلوب.',
            'status.required'           => '؛حالة الحساب مطلوبة.',
            'name.unique'               => 'اسم الحساب موجود بالفعل.',
            'account_type_id.required'  => 'نوع الحساب مطلوب.',
            'is_parent.required'        => 'نوع الحساب مطلوب.',
            'start_balance.required'    => 'رصيد الحساب بداية المدة مطلوب.',
            'current_balance.required'  => 'رصيد الحساب الحالي مطلوب.',
            'start_balance_status.required'  => 'حالة الحساب اول المدة مطلوب.',
        ]);


        try
        {

            $this->account                          = new Account();

             DB::beginTransaction();
            $this->account->name                    = $this->name;
            $this->account->account_type_id         = $this->account_type_id;
            $this->account->is_parent               = $this->is_parent;
            $this->account->parent_account_number   = $this->parent_account_number;
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

            // $this->account->account_number          = $this->account_number;
            $this->account->status                  = $this->status;
            $this->account->company_code            = auth()->user()->company_code;
            $this->account->created_by              = auth()->user()->id;
            $this->account->updated_by              = auth()->user()->id;
            $this->account->save();

            // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'اضافة حساب جديد  ';
            $actionHistory->desc        = "اضافة حساب جديد";
            $actionHistory->table_name  = 'Account';
            $actionHistory->row_id      = $this->account->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            $this->reset([
                'name',
                'status',
                'account_type_id',
                'is_parent',
                'parent_account_number',
                'start_balance_status',
                'start_balance',
                'notes',
            ]);
            // Dispatch events
            $this->dispatch('accountsCreateMS');                   // Flash Message
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
        // dd($this->get_parent_accounts);
        return view('back-end.accounts.create',
        [
            'get_parent_accounts'   => $this->get_parent_accounts,
            'acountsTypes'          => $this->acountsTypes,

        ]);
    }
}

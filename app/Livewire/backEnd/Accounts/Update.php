<?php

namespace App\Livewire\BackEnd\Accounts;

use App\Models\Account;
use Livewire\Component;
use App\Models\AccountType;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Accounts\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{
    public $acountsTypes=[];
    public $get_parent_accounts;
    public $account;
    public $accountId;
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
    protected $listeners = ['accountsUpdate'];



    public function accountsUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->account                  = Account::with('transations','chiled_accounts')->where('id',$id)->first();
        // dd($this->account);
        $this->accountId                = $id;
        $this->name                     = $this->account->name;
        $this->status                   = $this->account->status;
        $this->account_type_id          = $this->account->account_type_id;
        $this->account_number           = $this->account->account_number;
        $this->start_balance_status     = $this->account->start_balance_status;
        $this->start_balance            = $this->account->start_balance;
        $this->current_balance          = $this->account->current_balance;
        $this->is_parent                = $this->account->is_parent;
        $this->parent_account_number    = $this->account->parent_account_number;
        $this->notes                    = $this->account->notes;
        // dd($this->account_type_id);
        // show edit modal
        $this->dispatch('updateModalToggle');
    }

    public function submit_is_parent($value)
    {
        $this->is_parent = $value;
    }

    public function changeStartBalance($value)
    {
      $this->start_balance_status =  start_balanceStatus($value);
    }

    public function mount()
    {
        $this->acountsTypes         = AccountType::where('status','active')->get();
        $this->get_parent_accounts  = Account::select('account_number','company_code','name','id')->parent_accounts()->get();
    }


    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('accounts')
                    ->ignore($this->account->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
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
        ];
    }

    public function messages()
    {
        return
        [
            'name.required'             => 'اسم الحساب مطلوب.',
            'status.required'           => '؛حالة الحساب مطلوبة.',
            'name.unique'               => 'اسم الحساب موجود بالفعل.',
            'account_type_id.required'  => 'نوع الحساب مطلوب.',
            'is_parent.required'        => 'نوع الحساب مطلوب.',
            'start_balance.required'    => 'رصيد الحساب بداية المدة مطلوب.',
            // 'current_balance.required'  => 'رصيد الحساب الحالي مطلوب.',
            'start_balance_status.required'  => 'حالة الحساب اول المدة مطلوب.',
        ];
    }

    public function submit()
    {
        if (!auth()->user()->can('تعديل الحساب'))
        {
            abort(403, 'ليس لديك صلاحية تعديل الحساب ');
        }


         // التاكد من الحساب رئيسي و تحته حسابات فرعية مفعلة
        if ($this->account->is_parent == '1' && $this->account->chiled_accounts->where('status', 'active')->count() > 0  && $this->is_parent == '0')
        {
            $this->addError('is_parent', 'لا يمكن تغير نوع الحساب لانه يحتوي علي حسابات فرعية مفعلة .');
            return;
        }

         // لو في اي عمليات نقدية تمت علي الحاسب ده مش حينفع نعدل رصيد اول المدة
        if ($this->account->transations->count() > 0 && $this->account->start_balance != $this->start_balance)
        {
            $this->addError('start_balance', 'لا يمكن تغير قيمة رصيد اول المدة لانه الحساب لديه عمليات صرف و تحصيل تمت عليه .');
            return;
        }



         // لو الحساب عميل او مندوب او مورد او موظف مينفعش اغير نوعه
        if (in_array($this->account->accountType->name, ['عميل', 'مندوب', 'مورد', 'موظف']) && $this->account->account_type_id != $this->account_type_id)
        {
            $this->addError('account_type_id', 'لا يمكن تعديل رصيد اول المدة لانه يوجد عمليات تمت علي هذا الحساب  .');
            return;
        }

        DB::beginTransaction();
        // dd('ds');
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

        $this->account->account_number          = $this->account_number;
        $this->account->status                  = $this->status;
        $this->account->company_code            = auth()->user()->company_code;
        $this->account->created_by              = auth()->user()->id;
        $this->account->updated_by              = auth()->user()->id;
        $this->account->save();

        // 3 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'تعديل حساب  ';
            $actionHistory->desc        = "تعديل حساب {$this->account->name}";
            $actionHistory->table_name  = 'Account';
            $actionHistory->row_id      = $this->account->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();

        DB::commit();
        DB::rollBack();

        //  dd($this->account->start_balance);
        // Dispatch events
        $this->dispatch('accountsUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {
        return view('back-end.accounts.update',
        [
            'get_parent_accounts'   => $this->get_parent_accounts,
            'acountsTypes'          => $this->acountsTypes,
        ]);
    }
}

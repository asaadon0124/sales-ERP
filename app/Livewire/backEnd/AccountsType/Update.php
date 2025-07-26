<?php

namespace App\Livewire\BackEnd\AccountsType;

use Livewire\Component;
use App\Models\AccountType;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\AccountsType\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{
    public $accountType, $name, $status, $related_internal_accounts, $date, $company_code, $updated_by,$acTypeId;
    protected $listeners = ['accountsTypesUpdate'];



    public function accountsTypesUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->accountType                  = AccountType::find($id);
        $this->acTypeId                     = $id;
        $this->name                         = $this->accountType->name;
        $this->status                       = $this->accountType->status;
        $this->related_internal_accounts    = $this->accountType->related_internal_accounts;
        $this->date                         = $this->accountType->date;
        $this->company_code                 = $this->accountType->company_code;

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('account_types')
                    ->ignore($this->accountType->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query) {
                        return $query->where('company_code', $this->accountType->company_code);
                    }),
            ],
            'status'            => 'required',
            'date'              => 'required',
        ];
    }

    public function messages()
    {
        return
        [
            'name.required'             => 'نوع الحساب مطلوب.',
            'date.required'             => ' التاريخ مطلوب.',
            'status.required'           => '؛حالة الحساب مطلوبة.',
            'name.unique'               => 'نوع الحساب موجود بالفعل.',
        ];
    }

    public function submit()
    {
        if (!auth()->user()->can('تعديل نوع الحساب'))
        {
            abort(403, 'ليس لديك صلاحية تعديل نوع الحساب');
        }

        $this->validate($this->rules(), $this->messages());


        DB::beginTransaction();
        $this->accountType->name                  = $this->name;
        $this->accountType->status                = $this->status;
        $this->accountType->date                  = $this->date;
        $this->accountType->company_code          = auth()->user()->company_code;
        $this->accountType->updated_by            = auth()->user()->id;
        // Save the changes
        $this->accountType->save();

        // 3 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'تعديل نوع الحساب  ';
            $actionHistory->desc        = "تعديل نوع الحساب {$this->accountType->name}";
            $actionHistory->table_name  = 'AccountType';
            $actionHistory->row_id      = $this->accountType->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();

        DB::commit();
        DB::rollBack();
        // Dispatch events
        $this->dispatch('accountsTypesUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }


    public function render()
    {
        return view('back-end.accounts-type.update');
    }
}

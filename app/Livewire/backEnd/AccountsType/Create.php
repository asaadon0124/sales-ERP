<?php

namespace App\Livewire\BackEnd\AccountsType;

use Livewire\Component;
use App\Models\AccountType;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\AccountsType\Data;


class Create extends Component
{

    public $name                                    = '';
    public $status                                  = '';
    public $related_internal_accounts               = '0';
    public $date                                    = '';
    public $company_code;


    protected $listeners = ['accountsTypesCreate'];


    public function accountsTypesCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }

    public function mount()
    {
        $this->company_code = auth()->user()->company_code;
    }

    public function submit()
    {
        if (!auth()->user()->can('اضافة نوع حساب جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة نوع حساب جديد');
        }

        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('account_types')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'            => 'required',
            'date'              => 'required',
        ],
        [
            'name.required'             => 'نوع الحساب مطلوب.',
            'date.required'             => ' التاريخ مطلوب.',
            'status.required'           => '؛حالة الحساب مطلوبة.',
            'name.unique'               => 'نوع الحساب موجود بالفعل.',
        ]);


        try
        {

        DB::beginTransaction();
          $create =   AccountType::create(
            [
                'name'                  => $this->name,
                'status'                => $this->status,
                'date'                  => $this->date,
                'company_code'          => auth()->user()->company_code,
                'created_by'            => auth()->user()->id,
                'updated_by'            => auth()->user()->id,
            ]);

             // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'اضافة نوع حساب جديد ';
                $actionHistory->desc        = "اضافة نوع حساب جديد";
                $actionHistory->table_name  = 'AccountType';
                $actionHistory->row_id      = $create->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();
                DB::commit();
            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('accountsTypesCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('accountsTypesErrorMS');                   // Flash Message
        }

    }

    public function render()
    {
        return view('back-end.accounts-type.create');
    }
}

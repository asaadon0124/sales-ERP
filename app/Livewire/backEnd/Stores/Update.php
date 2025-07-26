<?php

namespace App\Livewire\BackEnd\Stores;

use App\Models\Store;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Stores\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح
use App\Services\ActionHistoryService;



class Update extends Component
{
    public $store, $name, $status, $phone, $address, $date, $company_code, $updated_by,$storeId;
    protected $listeners = ['storesUpdate'];



    public function storesUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->store                = Store::find($id);
        $this->storeId              = $id;
        $this->name                 = $this->store->name;
        $this->status               = $this->store->status;
        $this->phone                = $this->store->phone;
        $this->address              = $this->store->address;
        $this->date                 = $this->store->date;
        $this->company_code         = $this->store->company_code;

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('stores')
                    ->ignore($this->store->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->company_code);
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
            'name.required'             => 'اسم القسم مطلوب.',
            'date.required'             => ' التاريخ مطلوب.',
            'status.required'           => '؛حالة القسم مطلوبة.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ];
    }

    public function submit(ActionHistoryService $action_history)
    {
         if (!auth()->user()->can('تعديل المخزن'))
        {
            abort(403, 'ليس لديك صلاحية تعديل المخازن');
        }


        $this->validate($this->rules(), $this->messages());
         DB::beginTransaction();
        $this->store->name                  = $this->name;
        $this->store->status                = $this->status;
        $this->store->phone                 = $this->phone;
        $this->store->address               = $this->address;
        $this->store->date                  = $this->date;
        $this->store->company_code          = auth()->user()->company_code;
        $this->store->updated_by            = auth()->user()->id;
        $this->store->save();


        $action_history->action('تعديل المخزن ', "تعديل المخزن {$this->store->name}", 'Store',$this->store->id,auth()->user()->id);


    DB::commit();
    DB::rollBack();
        // Dispatch events
        $this->dispatch('storesUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }


    public function render()
    {
        return view('back-end.stores.update');
    }
}

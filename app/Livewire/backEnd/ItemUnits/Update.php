<?php

namespace App\Livewire\BackEnd\ItemUnits;

use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\ItemUnits\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{
    public $itemUnit, $name, $status, $date, $company_code, $updated_by,$itemUnitId,$is_master;
    protected $listeners = ['itemUnitsUpdate'];

    public function itemUnitsUpdate($id)
    {

        // fill $slider with the eloquent model of the same id
        $this->itemUnit             = ItemUnit::find($id);
        $this->itemUnitId           = $id;
        $this->name                 = $this->itemUnit->name;
        $this->status               = $this->itemUnit->status;
        $this->is_master               = $this->itemUnit->is_master;
        $this->date                 = $this->itemUnit->date;
        $this->company_code         = $this->itemUnit->company_code;

        // dd($this->all());
        // show edit modal
        $this->dispatch('updateModalToggle');
    }



    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('item_units')
                    ->ignore($this->itemUnit->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query) {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],
            'status'            => 'required',
            'date'              => 'required',
            'is_master'         => 'required',
        ];
    }

    public function messages()
    {
        return
        [
            'name.required'             => 'اسم الوحدة مطلوب.',
            'date.required'             => ' التاريخ مطلوب.',
            'status.required'           => '؛حالة الوحدة مطلوبة.',
            'is_master.required'        => 'نوع الوحدة مطلوبة.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ];
    }

    public function submit()
    {
        if (!auth()->user()->can('تعديل وحدة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية تعديل وحدة الصنف');
        }

     DB::beginTransaction();
        $this->validate($this->rules(), $this->messages());

        $this->itemUnit->name                  = $this->name;
        $this->itemUnit->status                = $this->status;
        $this->itemUnit->is_master             = $this->is_master;
        $this->itemUnit->date                  = $this->date;
        $this->itemUnit->company_code          = auth()->user()->company_code;
        $this->itemUnit->updated_by            = auth()->user()->id;
        // Save the changes
        $this->itemUnit->save();

         // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "تعديل وحدة صنف  ";
            $actionHistory->desc        = "تعديل وحدة صنف  {$this->itemUnit->name}" ;
            $actionHistory->table_name  = 'ItemUnit';
            $actionHistory->row_id      = $this->itemUnit->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();

        // Dispatch events
        $this->dispatch('ItemUnitsUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }

    public function render()
    {
        return view('back-end.item-units.update');
    }
}

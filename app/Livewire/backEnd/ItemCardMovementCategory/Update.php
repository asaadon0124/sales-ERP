<?php

namespace App\Livewire\BackEnd\ItemCardMovementCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementCategory;
use App\Livewire\BackEnd\ItemCardMovementCategory\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{
    public $item, $name, $company_code, $updated_by,$itemId;
    protected $listeners = ['iteCardMovementsCategoryUpdate'];



    public function iteCardMovementsCategoryUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->item                = ItemCardMovementCategory::find($id);
        $this->itemId              = $id;
        $this->name                 = $this->item->name;
        $this->company_code         = $this->item->company_code;

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('item_card_movement_categories')
                    ->ignore($this->item->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],

        ];
    }

    public function messages()
    {
        return
        [
            'name.required'             => 'اسم القسم مطلوب.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ];
    }

    public function submit()
    {
        if (!auth()->user()->can('تعديل فئة حركة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية تعديل فئة حركة الصنف ');
        }

        DB::beginTransaction();
            $this->validate($this->rules(), $this->messages());
            $this->item->name                  = $this->name;
            $this->item->company_code          = auth()->user()->company_code;
            $this->item->updated_by            = auth()->user()->id;
            // Save the changes
            $this->item->save();

            // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'تعديل فئة حركة الصنف ';
            $actionHistory->desc        = "تعديل فئة حركة الصنف  {$this->item->name}";
            $actionHistory->table_name  = 'ItemCardMovementCategory';
            $actionHistory->row_id      = $this->item->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
        DB::commit();
        DB::rollBack();
        // Dispatch events
        $this->dispatch('ItemCardMovementCategoryUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {
        return view('back-end.item-card-movement-category.update');
    }
}

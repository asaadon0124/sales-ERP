<?php

namespace App\Livewire\BackEnd\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\ItemCategory\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{
    public $itemCategory, $name, $status, $date, $company_code, $updated_by,$itemCategoryId;
    protected $listeners = ['itemCategoriesUpdate'];

    public function itemCategoriesUpdate($id)
    {

        // fill $slider with the eloquent model of the same id
        $this->itemCategory             = ItemCategory::find($id);
        $this->itemCategoryId           = $id;
        $this->name                 = $this->itemCategory->name;
        $this->status               = $this->itemCategory->status;
        $this->date                 = $this->itemCategory->date;
        $this->company_code         = $this->itemCategory->company_code;

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
                Rule::unique('item_categories')
                    ->ignore($this->itemCategory->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query) {
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
            'name.required'             => 'اسم الوحدة مطلوب.',
            'date.required'             => ' التاريخ مطلوب.',
            'status.required'           => '؛حالة الوحدة مطلوبة.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ];
    }

    public function submit()
    {
        if (!auth()->user()->can('تعديل فئة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية تعديل فئة الصنف');
        }

        
    DB::beginTransaction();
        $this->validate($this->rules(), $this->messages());
        $this->itemCategory->name                  = $this->name;
        $this->itemCategory->status                = $this->status;
        $this->itemCategory->date                  = $this->date;
        $this->itemCategory->company_code          = auth()->user()->company_code;
        $this->itemCategory->updated_by            = auth()->user()->id;
        $this->itemCategory->save();

        // 3 - CREATE ACTION HISTORY TABLE *****************
        $actionHistory              = new ActionHistory();
        $actionHistory->title       = 'تعديل فئة الصنف  ';
        $actionHistory->desc        = "تعديل فئة الصنف {$this->itemCategory->name}";
        $actionHistory->table_name  = 'ItemCategory';
        $actionHistory->row_id      = $this->itemCategory->id;
        $actionHistory->created_by  = auth()->user()->id;
        $actionHistory->save();

    DB::commit();
    DB::rollBack();

        // Dispatch events
        $this->dispatch('itemCategoriesUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {
        return view('back-end.item-category.update');
    }
}

<?php

namespace App\Livewire\BackEnd\SuppliersCategory;

use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\SuppliersCategory\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{

    public $supplierCategory, $name, $status,$company_code, $updated_by,$SupplierCategoryId;
    protected $listeners = ['suppliersCategoryUpdate'];



    public function suppliersCategoryUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->supplierCategory                 = SupplierCategory::find($id);
        $this->SupplierCategoryId               = $id;
        $this->name                             = $this->supplierCategory->name;
        $this->status                           = $this->supplierCategory->status;
        $this->company_code                     = $this->supplierCategory->company_code;

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function rules()
    {
        return
        [
            // 'name'              => 'required|unique:supplier_categories,name,' .$this->SupplierCategoryId,
            'name' => [
                'required',
                Rule::unique('supplier_categories')
                    ->ignore($this->supplierCategory->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],
            'status'            => 'required',
        ];
    }

    public function messages()
    {
        return
        [
            'name.required'             => 'اسم القسم مطلوب.',
            'status.required'           => '؛حالة القسم مطلوبة.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ];
    }

    public function submit()
    {
        if (!auth()->user()->can('تعديل القسم'))
        {
            abort(403, 'ليس لديك صلاحية تعديل القسم ');
        }
        try
        {
            DB::beginTransaction();
            $this->validate($this->rules(), $this->messages());
            $this->supplierCategory->name                  = $this->name;
            $this->supplierCategory->status                = $this->status;
            $this->supplierCategory->company_code          = auth()->user()->company_code;
            $this->supplierCategory->updated_by            = auth()->user()->id;
            // Save the changes
            $this->supplierCategory->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'تعديل القسم';
                $actionHistory->desc        = "تعديل القسم {$this->supplierCategory->name}";
                $actionHistory->table_name  = 'supplierCategory';
                $actionHistory->row_id      = $this->supplierCategory->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();

            DB::commit();


              // Dispatch events
            $this->dispatch('suppliersCategoryUpdateMS');
            $this->dispatch('updateModalToggle');
            $this->dispatch('refreshData')->to(Data::class);
        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('storesErrorMS');                   // Flash Message
        }
    }






    public function render()
    {
        return view('back-end.-suppliers-category.update');
    }
}

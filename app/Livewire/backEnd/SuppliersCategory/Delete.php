<?php

namespace App\Livewire\BackEnd\SuppliersCategory;

use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\SuppliersCategory\Data;

class Delete extends Component
{


    protected $listeners = ['suppliersCategoryDelete','refreshData' => '$refresh'];

    public $supplierCategoryId;
    public $supplierCategory;

    public function suppliersCategoryDelete($id)
    {
        $this->supplierCategoryId   = $id;
        // $this->supplierCategory     = SupplierCategory::select('id', 'name', 'status')->where('id',$this->supplierCategoryId)->with('suppliers')->first();

        $this->supplierCategory = SupplierCategory::with(['suppliers.supplier_account'])
        ->select('id', 'name', 'status')
        ->where('id', $this->supplierCategoryId)
        ->first();

        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {
        if (!auth()->user()->can('حذف القسم'))
        {
            abort(403, 'ليس لديك صلاحية حذف القسم');
        }

        if (!$this->supplierCategory)
        {
            $this->dispatch('deleteModalToggle');
            return;
        }

        DB::beginTransaction();

        try
        {
            // لو الحساب أب وعنده حسابات فرعية
            if ($this->supplierCategory->suppliers->count() > 0)
            {
                $total_balance = $this->supplierCategory->suppliers->sum('current_balance');

                if (bccomp($total_balance, 0, 2) !== 0)
                {
                    $this->addError('current_balance', 'لا يمكن تعديل الحساب لأن مجموع الرصيد الحالي له ولجميع الموردين الخاصة به لا يساوي صفر.');
                    DB::rollBack();
                    return;
                }

                $this->supplierCategory->status = 'un_active';
                $this->supplierCategory->save();

                foreach ($this->supplierCategory->suppliers as $supplier)
                {
                    $supplier->status = 'un_active';
                    $supplier->save();

                    $supplier->supplier_account->status = 'un_active';
                    $supplier->supplier_account->save();
                }
            } else
            {
                // الحساب مش أب أو ملوش أبناء
                $this->supplierCategory->status = 'un_active';
                $this->supplierCategory->save();
            }

            // سجل في تاريخ العمليات
            ActionHistory::create(
            [
                'title'      => 'حذف القسم',
                'desc'       => "حذف القسم {$this->supplierCategory->name}",
                'table_name' => 'SupplierCategory',
                'row_id'     => $this->supplierCategory->id,
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();

            $this->dispatch('storesDeleteMS');
        } catch (\Exception $e)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء حذف القسم');
        }

        $this->dispatch('deleteModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {
        return view('back-end.-suppliers-category.delete');
    }
}

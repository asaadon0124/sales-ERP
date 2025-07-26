<?php

namespace App\Livewire\BackEnd\SuppliersCategory;

use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\SupplierCategory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\SuppliersCategory\SoftDelete;

class Restore extends Component
{


    protected $listeners = ['supplierCategoryRestore','refreshData' => '$refresh'];

    public $supplierCategoryId;
    public $supplierCategory;

    public function supplierCategoryRestore($id)
    {
        $this->supplierCategoryId   = $id;
        $this->supplierCategory     = SupplierCategory::withoutGlobalScope(ActiveScope::class)->where('id',$this->supplierCategoryId)->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل القسم'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل القسم');
        }

        DB::beginTransaction();
        try
        {
            if ($this->supplierCategory)
            {

                // تفعيل القسم نفسه
                $this->supplierCategory->status = 'active';
                $this->supplierCategory->save();


                // سجل العملية
                ActionHistory::create(
                [
                    'title'      => 'تفعيل القسم',
                    'desc'       => "تفعيل القسم {$this->supplierCategory->name}",
                    'table_name' => 'SupplierCategory',
                    'row_id'     => $this->supplierCategory->id,
                    'created_by' => auth()->user()->id,
                ]);

                DB::commit();

                $this->dispatch('storesRestoreMS');
                $this->dispatch('restoreModalToggle');
                $this->dispatch('refreshData')->to(SoftDelete::class);

            } else {
                $this->dispatch('storeValidationMS');
                $this->dispatch('restoreModalToggle');
            }

        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء تفعيل القسم');
        }
    }

    public function render()
    {
        return view('back-end.-suppliers-category.restore');
    }
}

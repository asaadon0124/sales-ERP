<?php

namespace App\Livewire\BackEnd\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;

class Restore extends Component
{

    protected $listeners = ['SupplierRestore','refreshData' => '$refresh'];

    public $supplierId;
    public $supplier;

    public function SupplierRestore($id)
    {
        // dd('ds');
        $this->supplierId       = $id;
        $this->supplier     = Supplier::withoutGlobalScope(ActiveScope::class)->where('id',$this->supplierId)->with(['supplierCategory' => function($q)
        {
            $q->withoutGlobalScope(ActiveScope::class);

        },
        'supplier_account' => function($q2)
        {
            $q2->withoutGlobalScope(ActiveScope::class);
        }])->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل مورد'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل مورد');
        }

        DB::beginTransaction();
        try
        {
            if ($this->supplier)
            {
                 // إعادة تحميل العلاقة supplier_account بدون Global Scope
                $this->supplier->load(['supplier_account' => function ($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class);
                }]);

                 // إعادة تحميل العلاقة supplierCategory بدون Global Scope
                $this->supplier->load(['supplierCategory' => function ($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class);
                }]);



                // تفعيل المورد نفسه
                $this->supplier->status = 'active';
                $this->supplier->save();

                // لو قسم المورد محذوف فعله
                if ($this->supplier->supplierCategory)
                {
                    $this->supplier->supplierCategory->status = 'active';
                    $this->supplier->supplierCategory->save();
                }

                // لو حساب المورد محذوف فعله
                if ($this->supplier->supplier_account)
                {
                    $this->supplier->supplier_account->status = 'active';
                    $this->supplier->supplier_account->save();
                }

                // سجل العملية
                ActionHistory::create(
                [
                    'title'      => 'تفعيل المورد',
                    'desc'       => "تفعيل المورد {$this->supplier->name}",
                    'table_name' => 'Supplier',
                    'row_id'     => $this->supplier->id,
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
            $this->addError('general', 'حدث خطأ أثناء تفعيل المورد');
        }
    }




    public function render()
    {
        return view('back-end.suppliers.restore');
    }
}

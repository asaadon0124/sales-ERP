<?php

namespace App\Livewire\BackEnd\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Suppliers\Data;

class Delete extends Component
{
    protected $listeners = ['supplierDelete','refreshData' => '$refresh'];

    public $supplierId;
    public $supplier;
    // public $stor_batch_qty;

    public function supplierDelete($id)
    {
        $this->supplierId   = $id;
        $this->supplier     = Supplier::where('id',$this->supplierId)->with('transations','supplier_account','supplierCategory')->first();

        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {
        if (!auth()->user()->can('حذف مورد'))
        {
            abort(403, 'ليس لديك صلاحية حذف مورد');
        }

         $this->supplier->load(['supplier_account', 'supplierCategory']);

        if (!$this->supplier)
        {
            $this->dispatch('deleteModalToggle');
            return;
        }

        DB::beginTransaction();

        try
        {

                $total_balance = $this->supplier->current_balance;

                if (bccomp($total_balance, 0, 2) !== 0)
                {
                    $this->addError('current_balance', 'لا يمكن حذف المورد لأن مجموع الرصيد الحالي له لا يساوي صفر.');
                    DB::rollBack();
                    return;
                }

                $this->supplier->status = 'un_active';
                $this->supplier->save();


                $this->supplier->supplier_account->status = 'un_active';
                $this->supplier->supplier_account->save();


            // سجل في تاريخ العمليات
            ActionHistory::create(
            [
                'title'      => 'حذف المورد',
                'desc'       => "حذف المورد {$this->supplier->name}",
                'table_name' => 'Supplier',
                'row_id'     => $this->supplier->id,
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();

            $this->dispatch('storesDeleteMS');
        } catch (\Exception $e)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء حذف المورد');
        }

        $this->dispatch('deleteModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }




    public function render()
    {
        return view('back-end.suppliers.delete');
    }
}

<?php

namespace App\Livewire\BackEnd\Emoloyees;

use App\Models\Admin;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Emoloyees\Data;

class Delete extends Component
{
    protected $listeners = ['adminDelete','refreshData' => '$refresh'];

    public $adminId;
    public $employee;
    // public $stor_batch_qty;

    public function adminDelete($id)
    {
        $this->adminId   = $id;
        $this->employee     = Admin::where('id',$this->adminId)->with('employee_account')->first();


        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {
        if (!auth()->user()->can('حذف الموظف'))
        {
            abort(403, 'ليس لديك صلاحية حذف الموظف');
        }


         $this->employee->load(['employee_account']);

        if (!$this->employee)
        {
            $this->dispatch('deleteModalToggle');
            return;
        }

        DB::beginTransaction();

        try
        {

                $total_balance = $this->employee->current_balance;

                if (bccomp($total_balance, 0, 2) !== 0)
                {
                    $this->addError('current_balance', 'لا يمكن حذف الموظف لأن مجموع الرصيد الحالي له لا يساوي صفر.');
                    DB::rollBack();
                    return;
                }

                $this->employee->status = 'un_active';
                $this->employee->save();


                $this->employee->employee_account->status = 'un_active';
                $this->employee->employee_account->save();


            // سجل في تاريخ العمليات
            ActionHistory::create(
            [
                'title'      => 'حذف الموظف',
                'desc'       => "حذف الموظف {$this->employee->name}",
                'table_name' => 'Admin',
                'row_id'     => $this->employee->id,
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();

            $this->dispatch('storesDeleteMS');
        } catch (\Exception $e)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء حذف الموظف');
        }

        $this->dispatch('deleteModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }





    public function render()
    {
        return view('back-end.emoloyees.delete');
    }
}

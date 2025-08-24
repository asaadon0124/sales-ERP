<?php

namespace App\Livewire\BackEnd\Emoloyees;

use App\Models\Admin;
use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Emoloyees\SoftDelete;

class Restore extends Component
{



    protected $listeners = ['adminRestore','refreshData' => '$refresh'];

    public $employeeId;
    public $employee;

    public function adminRestore($id)
    {
        // dd('ds');
        $this->employeeId       = $id;
        $this->employee     = Admin::withoutGlobalScope(ActiveScope::class)->where('id',$this->employeeId)->with(['employee_account' => function($q)
        {
            $q->withoutGlobalScope(ActiveScope::class);

        }])->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل الموظف'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل الموظف');
        }

        DB::beginTransaction();
        try
        {
            if ($this->employee)
            {
                 // إعادة تحميل العلاقة employee بدون Global Scope
                $this->employee->load(['employee_account' => function ($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class);
                }]);





                // تفعيل الموظف نفسه
                $this->employee->status = 'active';
                $this->employee->save();



                // لو حساب الموظف محذوف فعله
                if ($this->employee->employee_account)
                {
                    $this->employee->employee_account->status = 'active';
                    $this->employee->employee_account->save();
                }

                // سجل العملية
                ActionHistory::create(
                [
                    'title'      => 'تفعيل الموظف',
                    'desc'       => "تفعيل الموظف {$this->employee->name}",
                    'table_name' => 'Admin',
                    'row_id'     => $this->employee->id,
                    'created_by' => auth()->user()->id,
                ]);

                DB::commit();

                $this->dispatch('emoloyeesRestoreMS');
                $this->dispatch('restoreModalToggle');
                $this->dispatch('refreshData')->to(SoftDelete::class);

            } else {
                $this->dispatch('employeesValidationMS');
                $this->dispatch('restoreModalToggle');
            }

        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء تفعيل الموظف');
        }
    }

    
    public function render()
    {
        return view('back-end.emoloyees.restore');
    }
}

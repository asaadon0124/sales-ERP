<?php

namespace App\Livewire\BackEnd\Shifts;

use App\Models\Admin;
use App\Models\Shift;
use Livewire\Component;
use App\Models\Treasuries;
use App\Models\ActionHistory;
use App\Models\AdminTreasuries;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Shifts\Data;

class Create extends Component
{


    public $treasury_id               = '';
    public $auto_serial;

    public $company_code;
    public $adminUnActiveTreasuries;
    public $check_admin_active_Treasury_Shift;

    protected $listeners = ['shiftsCreate'];


    public function shiftsCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }

    public function mount()
    {
        $this->company_code = auth()->user()->company_code;
        $adminId = auth()->user()->id;

         // علشان اجيب الخزن الخاصة بالمستخدم وليس لديها شيفت مفعل
         $this->adminUnActiveTreasuries = Treasuries::whereDoesntHave('shifts', function($q)
         {
            $q->where('shift_status', 'active');
        })->whereHas('admins',function($q)
        {
            $q->where('admins.id',auth()->user()->id);
        })->get();



        $get_last_shift                = Shift::select('admin_id','company_code','auto_serial','id','treasury_id')->latest()->first();

        if ($get_last_shift && $get_last_shift->count() > 0)
        {
            $this->auto_serial = $get_last_shift->auto_serial +1;
        }else
        {
            $this->auto_serial = 1;
        }
    }


    public function submit()
    {
        if (!auth()->user()->can('اضافة شيفت'))
        {
            abort(403, 'ليس لديك صلاحية اضافة شيفت');
        }
        // dd($this->all());
        $validated = $this->validate(
        [
            'treasury_id'            => 'required',
        ],
        [
            'treasury_id.required'             => 'اسم الخزنة مطلوب.',
        ]);


        try
        {
            DB::beginTransaction();
            // $this->activeTreasuryShift =
           $create =  Shift::create(
            [
                'admin_id'                      => auth()->user()->id,
                'treasury_id'                   => $this->treasury_id,
                'auto_serial'                   => $this->auto_serial,
                'start_date'                    => now(),
                'company_code'                  => auth()->user()->company_code,
                'created_by'                    => auth()->user()->id,
                'updated_by'                    => auth()->user()->id,
                'date'                          => date('y-m-d'),
            ]);
             // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'فتح شيفت جديد للمستخدم';
                    $actionHistory->desc        = "فتح شيفت جديد للمستخدم {$create->admin->name}";
                    $actionHistory->table_name  = 'Shift';
                    $actionHistory->row_id      = $create->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            DB::commit();

            // Dispatch events
            $this->dispatch('shiftCreated');
            $this->dispatch('shiftsCreateMS');                   // Flash Message
            $this->dispatch('closeModalByJs');                  // JS Event لإغلاق المودال فعليًا

            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollback();
            throw $th;
            $this->dispatch('shiftsErrorMS');                   // Flash Message
        }

    }





    public function render()
    {
        return view('back-end.shifts.create',
        [
            'admin'            => $this->adminUnActiveTreasuries,

        ]);
    }
}

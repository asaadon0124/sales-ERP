<?php

namespace App\Livewire\BackEnd\Shifts;

use App\Models\Shift;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Shifts\Data;

class Update extends Component
{
    protected $listeners = ['shiftsUpdate','refreshData' => '$refresh'];

    public $itemId;
    public $active_shift;

    public function shiftsUpdate($id)
    {

        $this->itemId       = $id;

        $this->active_shift = Shift::where('auto_serial',$id)->with('treasury')->where('shift_status','active')->where('is_delevered_review','no')->first();
        // show Create modal
        $this->dispatch('updateModalToggle');
    }




    public function submit()
    {
        // dd('dd');
        if (!auth()->user()->can('انهاء الشيفت'))
        {
            abort(403, 'ليس لديك صلاحية انهاء الشيفت ' );
        }


        //  dd($this->active_shift);
        if ($this->active_shift)          // لو الخزنة مفيش تحتيها خزن فرعية و الخزن الفرعية ليست لديها شيفتات او مسلمة
        {
            DB::beginTransaction();
            $this->active_shift->admin_id       = $this->active_shift->admin_id;
            $this->active_shift->treasury_id    = $this->active_shift->treasury_id;
            $this->active_shift->end_date       = now();
            $this->active_shift->shift_status   = 'un_active';
            $this->active_shift->updated_by     = auth()->user()->id;
            $this->active_shift->date           = date('y-m-d');
            $this->active_shift->save();


            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'انهاء شيفت  المستخدم';
            $actionHistory->desc        = "انهاء شيفت  المستخدم {$this->active_shift->admin->name}";
            $actionHistory->table_name  = 'Shift';
            $actionHistory->row_id      = $this->active_shift->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();

            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('shiftFinshed');
            $this->dispatch('shiftsUpdateMS');
            $this->dispatch('updateModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('treasuriesValidationMS');
           $this->dispatch('updateModalToggle');
        }
        $this->dispatch('refreshData')->to(Data::class);

    }

    public function render()
    {
        return view('back-end.shifts.update');
    }
}

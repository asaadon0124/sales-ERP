<?php

namespace App\Livewire\BackEnd\MoveTypes;

use Livewire\Component;
use App\Models\MoveType;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{


     protected $listeners = ['moveTypesDelete','refreshData' => '$refresh'];

    public $moveTypeId;
    public $moveType;

    public function moveTypesDelete($id)
    {
        $this->moveTypeId           = $id;
        $this->moveType     = MoveType::select('id','name','status')->where('id',$this->moveTypeId)->first();

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {
         if (!auth()->user()->can('حذف انواع حركات النقدية'))
        {
            abort(403, 'ليس لديك صلاحية حذف انواع حركات النقدية');
        }

         //  dd($this->active_shift);
        if ($this->moveType)
        {
            DB::beginTransaction();
            $this->moveType->status = 'un_active';
            $this->moveType->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "حذف نوع الحركة  ";
            $actionHistory->desc        = "حذف نوع الحركة  {$this->moveType->name}" ;
            $actionHistory->table_name  = 'MoveType';
            $actionHistory->row_id      = $this->moveType->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('moveTypesDeleteMS');
            $this->dispatch('deleteModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('moveTypesValidationMS');
           $this->dispatch('deleteModalToggle');
        }
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {
        return view('back-end.move-types.delete');
    }
}

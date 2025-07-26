<?php

namespace App\Livewire\BackEnd\MoveTypes;

use Livewire\Component;
use App\Models\MoveType;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;

class Restore extends Component
{

     protected $listeners = ['moveTypeRestore','refreshData' => '$refresh'];

    public $moveTypeId;
    public $MoveType;

    public function moveTypeRestore($id)
    {

        $this->moveTypeId       = $id;
        $this->MoveType     = MoveType::select('id','name','status')->withoutGlobalScope(ActiveScope::class)->where('id',$this->moveTypeId)->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل انواع حركات النقدية'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل انواع حركات النقدية');
        }


         //  dd($this->active_shift);
        if ($this->MoveType)
        {
            DB::beginTransaction();
            $this->MoveType->status = 'active';
            $this->MoveType->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "تفعيل نوع حركة النقدية  ";
            $actionHistory->desc        = "تفعيل نوع حركة النقدية  {$this->MoveType->name}" ;
            $actionHistory->table_name  = 'MoveType';
            $actionHistory->row_id      = $this->MoveType->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('MoveTypesRestoreMS');
            $this->dispatch('restoreModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('MoveTypesValidationMS');
           $this->dispatch('restoreModalToggle');
        }
        $this->dispatch('refreshData')->to(SoftDelete::class);
    }
    public function render()
    {
        return view('back-end.move-types.restore');
    }
}

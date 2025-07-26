<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\TreasuriesDetailes;
use App\Livewire\BackEnd\Treasuries\Show;

class DeleteSubTreasuries extends Component
{
    protected $listeners = ['SubTreasuriesDelete','refreshData' => '$refresh'];

    public $itemId;
    public $treasuries;

    public function SubTreasuriesDelete($id)
    {
        $this->itemId               = $id;
        $this->treasuries = TreasuriesDetailes::select('id','sub_treasuries_id')->with('treasurie')->where('id',$this->itemId)->first();

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('حذف خزنة فرعية للخزنة الرئيسية'))
        {
            abort(403, 'ليس لديك صلاحية حذف خزنة فرعية' );
        }

        $this->treasuries->delete();

         // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'حذف الخزنة الفرعية ';
            $actionHistory->desc        = "حذف الخزنة الفرعية  {$this->treasuries->name}";
            $actionHistory->table_name  = 'TreasuriesDetailes';
            $actionHistory->row_id      = $this->treasuries->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();

         // Dispatch events
         $this->dispatch('SubTreasuresDeleteMS');
         $this->dispatch('deleteModalToggle');
         $this->dispatch('refreshData')->to(Show::class);
    }

    public function render()
    {
        return view('back-end.treasuries.delete-sub-treasuries');
    }
}

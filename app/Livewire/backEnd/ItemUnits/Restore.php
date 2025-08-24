<?php

namespace App\Livewire\BackEnd\ItemUnits;

use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\ItemUnits\SoftDelete;

class Restore extends Component
{
    protected $listeners = ['itemUnitsRestore','refreshData' => '$refresh'];

    public $itemId;
    public $itemUnit;

    public function itemUnitsRestore($id)
    {
        $this->itemId       = $id;
        $this->itemUnit     = ItemUnit::select('id','name','status')->withoutGlobalScope(ActiveScope::class)->where('id',$this->itemId)->first();

        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل وحدة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل وحدة الصنف');
        }


         //  dd($this->active_shift);
        if ($this->itemUnit)
        {
            DB::beginTransaction();
            $this->itemUnit->status = 'active';
            $this->itemUnit->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "تفعيل وحدة صنف  ";
            $actionHistory->desc        = "تفعيل وحدة صنف  {$this->itemUnit->name}" ;
            $actionHistory->table_name  = 'ItemUnit';
            $actionHistory->row_id      = $this->itemUnit->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('ItemUnitsRestoreMS');
            $this->dispatch('restoreModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('ItemUnitsValidationMS');
           $this->dispatch('restoreModalToggle');
        }
        $this->dispatch('refreshData')->to(SoftDelete::class);
    }


    public function render()
    {
        return view('back-end.item-units.restore');
    }
}

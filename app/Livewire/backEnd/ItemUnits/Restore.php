<?php

namespace App\Livewire\BackEnd\ItemUnits;

use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
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


    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('تفعيل وحدة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل وحدة الصنف');
        }


        try
        {
             //  dd($this->active_shift);
            if ($this->itemUnit)
            {
                DB::beginTransaction();
                $this->itemUnit->status = 'active';
                $this->itemUnit->save();



                // 2 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('تفعيل وحدة الصنف   ', "تفعيل وحدة الصنف   {$this->itemUnit->name}", 'ItemUnit', $this->itemUnit->id,auth()->user()->id);
                DB::commit();

                // Dispatch events
                $this->dispatch('ItemUnitsRestoreMS');
            }else
            {
                DB::rollBack();
                // dd($this->active_shift);
                $this->dispatch('ItemUnitsValidationMS');

            }
        } catch (\Throwable $th)
        {
            //throw $th;
        }



        $this->dispatch('restoreModalToggle');
        $this->dispatch('refreshData')->to(SoftDelete::class);
    }


    public function render()
    {
        return view('back-end.item-units.restore');
    }
}

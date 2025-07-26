<?php

namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Items\SoftDelete;

class Restore extends Component
{
     protected $listeners = ['itemRestore','refreshData' => '$refresh'];

    public $itemId;
    public $item;

    public function itemRestore($id)
    {
        $this->itemId    = $id;
        $this->item     = Item::select('id','name','status')->withoutGlobalScope(ActiveScope::class)->where('id',$this->itemId)->first();

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
        if ($this->item)
        {
            DB::beginTransaction();
            $this->item->status = 'active';
            $this->item->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "تفعيل الصنف  ";
            $actionHistory->desc        = "تفعيل الصنف  {$this->item->name}" ;
            $actionHistory->table_name  = 'Item';
            $actionHistory->row_id      = $this->item->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('ItemsRestoreMS');
            $this->dispatch('restoreModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('ItemsValidationMS');
           $this->dispatch('restoreModalToggle');
        }
        $this->dispatch('refreshData')->to(SoftDelete::class);
    }



    public function render()
    {
        return view('back-end.items.restore');
    }
}

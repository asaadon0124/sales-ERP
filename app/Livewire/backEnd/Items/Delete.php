<?php

namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Items\Data;

class Delete extends Component
{

    protected $listeners = ['itemsDelete','refreshData' => '$refresh'];

    public $itemId;
    public $item;
    public $qty_after_all_stores;

    public function itemsDelete($id)
    {
        $this->itemId   = $id;
        $this->item     = Item::select('id','name','status')->where('id',$this->itemId)->first();
        $this->qty_after_all_stores = ItemBatch::where('item_code', $this->item->item_code)->sum('qty');


        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('حذف صنف'))
        {
            abort(403, 'ليس لديك صلاحية حذف الصنف ');
        }


         //  dd($this->active_shift);
        if ($this->item && $this->qty_after_all_stores == 0)
        {
            DB::beginTransaction();
            $this->item->status = 'un_active';
            $this->item->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "حذف صنف  ";
            $actionHistory->desc        = "حذف صنف  {$this->item->name}" ;
            $actionHistory->table_name  = 'Item';
            $actionHistory->row_id      = $this->item->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('itemsDeleteMS');
            $this->dispatch('deleteModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('itemsValidationMS');
           $this->dispatch('deleteModalToggle');
        }
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {

        return view('back-end.items.delete');
    }
}

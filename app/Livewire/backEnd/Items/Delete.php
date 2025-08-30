<?php

namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Items\Data;
use App\Services\ActionHistoryService;

class Delete extends Component
{

    protected $listeners = ['itemsDelete','refreshData' => '$refresh'];

    public $itemId;
    public $item;
    public $qty_after_all_stores;
    public $total_deduction;
    public $current_qty;

    public function itemsDelete($id)
    {
        $this->itemId   = $id;
        $this->item     = Item::select('id','name','status','item_code')->where('id',$this->itemId)->first();
        $this->qty_after_all_stores   = ItemBatch::where('item_code', $this->item->item_code)->sum('qty');
        $this->total_deduction        = ItemBatch::where('item_code', $this->item->item_code)->sum('deduction');
        $this->current_qty = $this->qty_after_all_stores - $this->total_deduction;

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('حذف صنف'))
        {
            abort(403, 'ليس لديك صلاحية حذف الصنف ');
        }


        try
        {
            if ($this->item && $this->current_qty == 0)
            {
                DB::beginTransaction();
                $this->item->status = 'un_active';
                $this->item->save();



                 // 2 - CREATE ACTION HISTORY TABLE *****************
                 $action_history->action('حذف صنف  ', "حذف صنف  {$this->item->name}", 'Item', $this->item->id,auth()->user()->id);

                DB::commit();


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
        } catch (\Throwable $e)
        {
            DB::rollBack();
            $this->dispatch('itemsValidationMS', ['message' => $e->getMessage()]);
            $this->dispatch('deleteModalToggle');
        }

    }



    public function render()
    {

        return view('back-end.items.delete');
    }
}

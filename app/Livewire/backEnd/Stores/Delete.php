<?php

namespace App\Livewire\BackEnd\Stores;

use App\Models\Store;
use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{

    protected $listeners = ['storesDelete','refreshData' => '$refresh'];

    public $storeId;
    public $store;
    public $stor_batch_qty;

    public function storesDelete($id)
    {
        $this->storeId           = $id;
        $this->store     = Store::select('id','name','status')->where('id',$this->storeId)->with('item_batches')->first();



        $this->stor_batch_qty = ItemBatch::select(
        'store_id',
        'item_code',
        DB::raw('SUM(qty) as total_qty')
        )
        ->where('store_id', $this->storeId)
        ->where('status', 'active')
        ->groupBy('store_id', 'item_code')
        ->having('total_qty', '>', 0)
        ->exists(); // لو فيه صنف واحد كميته > 0





        // dd($hasOnlyPositiveQty  );
        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('حذف المخزن'))
        {
            abort(403, 'ليس لديك صلاحية حذف المخزن');
        }



         //  dd($this->active_shift);
        if ($this->store)
        {
             if ($this->stor_batch_qty == false)    // لو المخزن كل الباتشات الي فيه كمياتها  == 0
            {
                 DB::beginTransaction();
                $this->store->status = 'un_active';
                $this->store->save();

                // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'حذف المخزن';
                $actionHistory->desc        = "حذف المخزن {$this->store->name}";
                $actionHistory->table_name  = 'Store';
                $actionHistory->row_id      = $this->store->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();
                DB::commit();
                DB::rollBack();
                // Dispatch events
                $this->dispatch('storesDeleteMS');
                $this->dispatch('deleteModalToggle');
            }else
            {
                 // dd($this->active_shift);
                $this->dispatch('storesValidation2MS');
                $this->dispatch('deleteModalToggle');
            }

        }else
        {
            // dd($this->active_shift);
           $this->dispatch('storesValidationMS');
           $this->dispatch('deleteModalToggle');
        }
        $this->dispatch('refreshData')->to(Data::class);
    }





    public function render()
    {
        return view('back-end.stores.delete');
    }
}

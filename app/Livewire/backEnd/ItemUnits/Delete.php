<?php

namespace App\Livewire\BackEnd\ItemUnits;

use Exception;
use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Livewire\BackEnd\ItemUnits\Data;

class Delete extends Component
{
    protected $listeners = ['itemUnitsDelete','refreshData' => '$refresh'];

    public $itemId;
    public $itemUnit;
    public $relation_name;

    public function itemUnitsDelete($id)
    {
        $this->itemId           = $id;

        $this->itemUnit         = ItemUnit::select('id','name','status')->with(['sub_master_items.item_batches' => function($q)
        {
            $q->select('item_code',DB::raw('sum(qty) as total_qty'))->groupBy('item_code');

        }])->where('id',$this->itemId)->first();


        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('حذف وحدة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية حذف وحدة الصنف');
        }


       try
       {
            DB::transaction(function() use ($action_history)
            {

                // 🔒 هنا القفل
                if ($this->itemUnit->is_master == 'master')
                {

                    $this->itemUnit = ItemUnit::with(
                        [
                            'master_items.item_batches' => function($q)
                            {
                                $q->select('item_code', DB::raw('sum(qty) as total_qty'))->groupBy('item_code');
                            },

                        ])
                        ->where('id', $this->itemId)
                        ->lockForUpdate()
                        ->first();

                        $this->relation_name = 'master_items';
                }else
                {
                    $this->itemUnit = ItemUnit::with(
                        [
                            'sub_master_items.item_batches' => function($q)
                            {
                                $q->select('item_code', DB::raw('sum(qty) as total_qty'))->groupBy('item_code');
                            },

                        ])
                        ->where('id', $this->itemId)
                        ->lockForUpdate()
                        ->first();

                        $this->relation_name = 'sub_master_items';
                }




                if (!$this->itemUnit)
                {
                    $this->dispatch('ItemUnitsValidationMS');
                    $this->dispatch('deleteModalToggle');
                    return;
                }


                $totalQty = $this->itemUnit->{$this->relation_name}
                ->flatMap(fn($item) => $item->item_batches)
                ->sum('total_qty');

                // dd($this->itemUnit->{$this->relation_name}->total_qty_for_sub_items);
                // dd($totalQty);

                // لو فيها اصناف و كميات الاصناف دي  == 0
                if ($this->itemUnit->{$this->relation_name}->count() > 0 && $totalQty == 0)
                {
                    // dd(' 0 =  بها اصناف و كميات');
                    // 1 - حذف الاصناف DELETE ITEMS
                    $this->itemUnit->{$this->relation_name}()->update(['status' => 'un_active']);

                    // 2- حذف وحدة الصنف DELETE ITEM UNIT
                    $this->itemUnit->status = 'un_active';
                    $this->itemUnit->save();

                    // 3 - CREATE ACTION HISTORY TABLE *****************
                    $action_history->action('حذف وحدة صنف  ', "حذف وحدة صنف  {$this->itemUnit->name}", 'ItemUnit', $this->itemUnit->id,auth()->user()->id);

                } elseif($this->itemUnit->{$this->relation_name}->count() == 0)      // لو مفيهاش اصناف
                {
                    // dd('مفيهاش اصناف');
                    // 1- حذف وحدة الصنف DELETE ITEM UNIT
                    $this->itemUnit->status = 'un_active';
                    $this->itemUnit->save();

                    // 2 - CREATE ACTION HISTORY TABLE *****************
                    $action_history->action('حذف وحدة صنف  ', "حذف وحدة صنف  {$this->itemUnit->name}", 'ItemUnit', $this->itemUnit->id,auth()->user()->id);

                    // Dispatch events
                    $this->dispatch('ItemUnitsDeleteMS');
                }else
                {
                    // dd('فيها اصناف و بها كميات');
                    $this->dispatch('ItemUnitsValidationMS');
                    $this->dispatch('deleteModalToggle');
                    return;
                }

                $this->dispatch('deleteModalToggle');
                $this->dispatch('refreshData')->to(Data::class);

            });
       } catch (Exception $e)
       {
            $this->dispatch('ItemUnitsValidationMS', ['message' => $e->getMessage()]);
            $this->dispatch('deleteModalToggle');
       }

    }





    public function render()
    {
        return view('back-end.item-units.delete');
    }
}

<?php

namespace App\Livewire\BackEnd\ItemUnits;

use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\ItemUnits\Data;

class Delete extends Component
{
    protected $listeners = ['itemUnitsDelete','refreshData' => '$refresh'];

    public $itemId;
    public $itemUnit;

    public function itemUnitsDelete($id)
    {
        $this->itemId           = $id;
        // $this->itemUnit     = ItemUnit::select('id','name','status')->where('id',$this->itemId)->first();

         $this->itemUnit         = ItemUnit::select('id','name','status')->with(['items.item_batches' => function($q)
        {
            $q->select('item_code',DB::row('sum(qty) as total_qty'))->groupBy('item_code');

        }])->where('id',$this->itemId)->first();

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('حذف وحدة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية حذف وحدة الصنف');
        }


         //  dd($this->active_shift);

          if ($this->itemUnit)        // لو فيها اصناف و كميات الاصناف دي  == 0
        {
            if (
                    $this->itemUnit->items->count() > 0 &&
                    $this->itemUnit->items->every(function ($item)
                    {
                        return $item->item_batches->sum('qty') == 0;
                    })
                )
            {
                dd('كل كميات الاصناف == 0');


            } elseif($this->itemUnit->items->count() == 0)      // لو مفيهاش اصناف
            {
                DB::beginTransaction();
                    $this->itemUnit->status = 'un_active';
                    $this->itemUnit->save();

                    // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = "حذف وحدة صنف  ";
                    $actionHistory->desc        = "حذف وحدة صنف  {$this->itemUnit->name}" ;
                    $actionHistory->table_name  = 'ItemUnit';
                    $actionHistory->row_id      = $this->itemUnit->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                DB::commit();
                DB::rollBack();
                // Dispatch events
                $this->dispatch('ItemUnitsDeleteMS');
                $this->dispatch('deleteModalToggle');

            }else
            {
                $this->dispatch('itemCategoriesValidationMS');
                $this->dispatch('deleteModalToggle');
            }


            DB::rollBack();
            // Dispatch events
            $this->dispatch('itemCategoriesDeleteMS');
            $this->dispatch('deleteModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('itemCategoriesValidationMS');
           $this->dispatch('deleteModalToggle');
        }

        if ($this->itemUnit)
        {

        }else
        {
            // dd($this->active_shift);
           $this->dispatch('ItemUnitsValidationMS');
           $this->dispatch('deleteModalToggle');
        }
        $this->dispatch('refreshData')->to(Data::class);
    }





    public function render()
    {
        return view('back-end.item-units.delete');
    }
}

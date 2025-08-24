<?php

namespace App\Livewire\BackEnd\ItemCategory;

use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\ItemCategory\Data;

class Delete extends Component
{


    protected $listeners = ['itemCategoriesDelete','refreshData' => '$refresh'];

    public $itemId;
    public $itemCategory;
    public $qty_after_all_stores;

    public function itemCategoriesDelete($id)
    {
        $this->itemId               = $id;
        $this->itemCategory         = ItemCategory::select('id','name','status')
        ->with(['items.item_batches' => function($q)
        {
            $q->select('item_code', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('item_code');
        }])
    ->where('id', $this->itemId)
    ->first();
        // $this->qty_after_all_stores = ItemBatch::where('item_code', $this->new_order_detailes->item_code)->sum('qty');

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('حذف فئة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية حذف فئة الصنف');
        }


        if ($this->itemCategory)
        {
            if (    // لو فيها اصناف و كميات الاصناف دي  == 0
                    $this->itemCategory->items->count() > 0 &&
                    $this->itemCategory->items->every(function ($item)
                    {
                        return $item->item_batches->sum('qty') == 0;
                    })
                )
            {
                dd('كل كميات الاصناف == 0');


            } elseif($this->itemCategory->items->count() == 0)      // لو مفيهاش اصناف
            {
                DB::beginTransaction();

                    // 1 - DELETE ITEM CATEGORY TABLE *****************
                    $this->itemCategory->status = 'un_active';
                    $this->itemCategory->save();

                    // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'حذف فئة الصنف  ';
                    $actionHistory->desc        = "حذف فئة الصنف {$this->itemCategory->name}";
                    $actionHistory->table_name  = 'ItemCategory';
                    $actionHistory->row_id      = $this->itemCategory->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

                DB::commit();

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
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {
        return view('back-end.item-category.delete');
    }
}

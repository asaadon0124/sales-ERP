<?php

namespace App\Livewire\BackEnd\ItemCategory;

use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
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


    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('حذف فئة الصنف')) {
            abort(403, 'ليس لديك صلاحية حذف فئة الصنف');
        }

        if (!$this->itemCategory) {
            $this->dispatch('itemCategoriesValidationMS');
            $this->dispatch('deleteModalToggle');
            return;
        }

        // لو فيها أصناف
        if ($this->itemCategory->items->count() > 0) {

            // كل الأصناف كمياتها = 0
            if ($this->itemCategory->items->every(fn($item) => $item->item_batches->sum('qty') == 0)) {

                DB::beginTransaction();
                try {
                    // 1- تعطيل كل الأصناف
                    $this->itemCategory->items()->update(['status' => 'un_active']);

                    // 2- تعطيل فئة الصنف
                    $this->itemCategory->update(['status' => 'un_active']);

                    // 3- إضافة Action History
                    $action_history->action(
                        'حذف فئة الصنف',
                        "حذف فئة الصنف {$this->itemCategory->name}",
                        'ItemCategory',
                        $this->itemCategory->id,
                        auth()->id()
                    );

                    DB::commit();
                    $this->dispatch('deleteModalToggle');
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }

            } else {
                // فيها أصناف وكمياتها > 0
                $this->dispatch('itemCategoriesValidationMS');
                $this->dispatch('deleteModalToggle');
            }

        } else {
            // لو مفيهاش أصناف خالص
            DB::beginTransaction();
            try {
                $this->itemCategory->update(['status' => 'un_active']);

                $action_history->action(
                    'حذف فئة الصنف',
                    "حذف فئة الصنف {$this->itemCategory->name}",
                    'ItemCategory',
                    $this->itemCategory->id,
                    auth()->id()
                );

                DB::commit();
                $this->dispatch('itemCategoriesDeleteMS');
                $this->dispatch('deleteModalToggle');
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }

        // Refresh Data
        $this->dispatch('refreshData')->to(Data::class);
    }




    public function render()
    {
        return view('back-end.item-category.delete');
    }
}

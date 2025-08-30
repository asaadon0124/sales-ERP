<?php

namespace App\Livewire\BackEnd\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Livewire\BackEnd\ItemCategory\SoftDelete;

class Restore extends Component
{


    protected $listeners = ['itemCategoriesRestore','refreshData' => '$refresh'];

    public $itemId;
    public $itemCategory;

    public function itemCategoriesRestore($id)
    {
        $this->itemId           = $id;
        $this->itemCategory     = ItemCategory::select('id','name','status')->withoutGlobalScope(ActiveScope::class)->where('id',$this->itemId)->first();

        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('تفعيل فئة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل فئة الصنف');
        }

        try
        {
            if ($this->itemCategory)
            {
                DB::beginTransaction();

                $this->itemCategory->status = 'active';
                $this->itemCategory->save();




                 // 2 - CREATE ACTION HISTORY TABLE *****************
                 $action_history->action('تفعيل فئة الصنف   ', "تفعيل فئة الصنف   {$this->itemCategory->name}", 'ItemCategory', $this->itemCategory->id,auth()->user()->id);

                DB::commit();

                // Dispatch events
                $this->dispatch('itemcategoryRestoreMS');
                $this->dispatch('restoreModalToggle');
            }else
            {
                // dd($this->active_shift);
               $this->dispatch('itemCategoriesValidationMS');
               $this->dispatch('restoreModalToggle');
            }

        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->dispatch('itemCategoriesValidationMS');
            $this->dispatch('restoreModalToggle');
        }

        $this->dispatch('refreshData')->to(SoftDelete::class);


    }


    public function render()
    {
        return view('back-end.item-category.restore');
    }
}

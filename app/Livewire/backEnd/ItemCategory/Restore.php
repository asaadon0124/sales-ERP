<?php

namespace App\Livewire\BackEnd\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
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


    public function submit()
    {
        if (!auth()->user()->can('تفعيل فئة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل فئة الصنف');
        }


        if ($this->itemCategory)
        {
            DB::beginTransaction();

            $this->itemCategory->status = 'active';
            $this->itemCategory->save();


             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'تفعيل فئة الصنف  ';
            $actionHistory->desc        = "تفعيل فئة الصنف {$this->itemCategory->name}";
            $actionHistory->table_name  = 'ItemCategory';
            $actionHistory->row_id      = $this->itemCategory->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();

            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('itemcategoryRestoreMS');
            $this->dispatch('restoreModalToggle');
        }else
        {
            // dd($this->active_shift);
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

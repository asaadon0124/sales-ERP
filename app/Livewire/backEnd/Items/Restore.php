<?php

namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Livewire\BackEnd\Items\SoftDelete;

class Restore extends Component
{
    protected $listeners = ['itemRestore', 'refreshData' => '$refresh'];

    public $itemId;
    public $item;

    public function itemRestore($id)
    {
        $this->itemId    = $id;
        // $this->item     = Item::select('id','name','status')->withoutGlobalScope(ActiveScope::class)->where('id',$this->itemId)->first();
        $this->item = Item::select('id', 'name', 'status','item_unit_id','sub_item_unit_id','item_category_id','retail_unit')->withoutGlobalScope(ActiveScope::class)->where('id', $this->itemId)
        ->whereHas('itemUnit', function ($q)
        {
            $q->where('status', 'active');   // الوحدة الأساسية لازم تكون مفعلة
        })
        ->whereHas('itemCategory', function ($q)
        {
            $q->where('status', 'active');   // الفئة لازم تكون مفعلة
        })
        ->where(function ($q)
        {
            $q->whereDoesntHave('itemUnitChild')   // لو مفيش وحدة فرعية
                ->orWhereHas('itemUnitChild', function ($q2)
                {
                    $q2->where('status', 'active'); // ولو فيه وحدة فرعية تبقى مفعلة
                });
        })
        ->with(['itemUnit', 'itemUnitChild', 'itemCategory'])
        ->first();

        // dd($this->item);


        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('تفعيل وحدة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل وحدة الصنف');
        }


        // dd($this->item->retail_unit);
        try
        {
            if ($this->item && $this->item->retail_unit == '1' && !empty($this->item->itemUnitChild))        // لو الصنف موجود و عنده وحدة فرعية مفعلة
            {
                DB::beginTransaction();
                    $this->item->status = 'active';
                    $this->item->save();


                    // 2 - CREATE ACTION HISTORY TABLE *****************
                    $action_history->action('تفعيل الصنف   ', "تفعيل الصنف   {$this->item->name}", 'Item', $this->item->id, auth()->user()->id);




                    // Dispatch events
                    $this->dispatch('ItemsRestoreMS');

            } elseif($this->item && $this->item->retail_unit == '0')  // لو الصنف موجود و معندوش وحدة فرعية
            {
                $this->item->status = 'active';
                $this->item->save();


                // 2 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('تفعيل الصنف   ', "تفعيل الصنف   {$this->item->name}", 'Item', $this->item->id, auth()->user()->id);




                // Dispatch events
                $this->dispatch('ItemsRestoreMS');

            }
            else
            {

                $this->dispatch('ItemsValidationMS');
            }

            DB::commit();
            $this->dispatch('restoreModalToggle');
            $this->dispatch('refreshData')->to(SoftDelete::class);
        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->dispatch('ItemsValidationMS');
            throw $th; 
        }


    }



    public function render()
    {
        return view('back-end.items.restore');
    }
}

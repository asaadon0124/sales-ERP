<?php

namespace App\Livewire\BackEnd\ItemCardMovementCategory;

use Livewire\Component;

class Restore extends Component
{
     protected $listeners = ['storesRestore','refreshData' => '$refresh'];

    public $storeId;
    public $Store;

    public function storesRestore($id)
    {
        $this->storeId       = $id;
        $this->Store     = Store::select('id','name','status')->withoutGlobalScope(ActiveScope::class)->where('id',$this->storeId)->first();

        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل فئة حركة الصنف'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل فئة حركة الصنف');
        }


         //  dd($this->active_shift);
        if ($this->Store)
        {
            DB::beginTransaction();
            $this->Store->status = 'active';
            $this->Store->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "تفعيل المخزن  ";
            $actionHistory->desc        = "تفعيل المخزن  {$this->Store->name}" ;
            $actionHistory->table_name  = 'Store';
            $actionHistory->row_id      = $this->Store->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('storesRestoreMS');
            $this->dispatch('restoreModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('storeValidationMS');
           $this->dispatch('restoreModalToggle');
        }
        $this->dispatch('refreshData')->to(SoftDelete::class);
    }




    public function render()
    {
        return view('back-end.item-card-movement-category.restore');
    }
}

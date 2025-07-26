<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Treasuries\SoftDelete;

class Restore extends Component
{


    protected $listeners = ['treasuriesRestore','refreshData' => '$refresh'];

    public $itemId;
    public $treasury;

    public function treasuriesRestore($id)
    {

        $this->itemId       = $id;
        $this->treasury     = Treasuries::select('id','name','status')->where('id',$this->itemId)->withoutGlobalScope(ActiveScope::class)->first();
        // dd($this->treasury);
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }



    public function submit()
    {

        if (!auth()->user()->can('تفعيل الخزن'))
        {
            abort(403, 'ليس لديك صلاحية تفعيل الخزنة ' );
        }
        DB::beginTransaction();

        $this->treasury->status = 'active';
        $this->treasury->save();

         // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'تفعيل الخزنة';
                $actionHistory->desc        = "تفعيل الخزنة {$this->treasury->name}";
                $actionHistory->table_name  = 'Treasuries';
                $actionHistory->row_id      = $this->treasury->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();
            DB::commit();
            DB::rollBack();
         // Dispatch events
        $this->dispatch('treasuriesRestoreMS');
        $this->dispatch('restoreModalToggle');
        $this->dispatch('refreshData')->to(SoftDelete::class);

    }



    public function render()
    {
        return view('back-end.treasuries.restore');
    }
}

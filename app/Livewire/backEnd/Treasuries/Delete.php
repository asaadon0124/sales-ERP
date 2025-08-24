<?php

namespace App\Livewire\BackEnd\Treasuries;

use App\Models\Shift;
use Livewire\Component;
use App\Models\Treasuries;
use Illuminate\Support\Str;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Treasuries\Data;
use App\Livewire\BackEnd\Treasuries\Show;

class Delete extends Component
{

    protected $listeners = ['treasuriesDelete','refreshData' => '$refresh'];

    public $itemId;
    public $treasury;
    public $active_shift;

    public function treasuriesDelete($id)
    {
        $this->itemId       = $id;
        $this->treasury     = Treasuries::select('id','name','status')->whereDoesntHave('treasuriesDetailes')->where('id',$this->itemId)->first();
        $this->active_shift = Shift::where('treasury_id',$id)->with('treasury')->where('shift_status','active')->orWhere('is_delevered_review','no')->count();

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {

        if (!auth()->user()->can('حذف الخزن'))
        {
            abort(403, 'ليس لديك صلاحية حذف الخزنة ' );
        }

        //  dd($this->active_shift);
        if ($this->treasury && $this->active_shift == 0)          // لو الخزنة مفيش تحتيها خزن فرعية و الخزن الفرعية ليست لديها شيفتات او مسلمة
        {

            DB::beginTransaction();
                $this->treasury->status = 'un_active';
                $this->treasury->save();

                // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'حذف الخزنة';
                    $actionHistory->desc        = "حذف الخزنة {$this->treasury->name}";
                    $actionHistory->table_name  = 'Treasuries';
                    $actionHistory->row_id      = $this->treasury->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                DB::commit();
                DB::rollBack();
            // Dispatch events
            $this->dispatch('treasuriesDeleteMS');
            $this->dispatch('deleteModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('treasuriesValidationMS');
           $this->dispatch('deleteModalToggle');
        }
        $this->dispatch('refreshData')->to(Data::class);

    }




    public function render()
    {
        return view('back-end.treasuries.delete');
    }
}

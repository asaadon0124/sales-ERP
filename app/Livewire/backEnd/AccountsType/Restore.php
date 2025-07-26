<?php

namespace App\Livewire\BackEnd\AccountsType;

use Livewire\Component;
use App\Models\AccountType;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;

class Restore extends Component
{

 protected $listeners = ['accountTypeRestore','refreshData' => '$refresh'];

    public $accountTypeId;
    public $accountType;

    public function accountTypeRestore($id)
    {

        $this->accountTypeId    = $id;
        $this->accountType     = AccountType::select('id','name','status')->withoutGlobalScope(ActiveScope::class)->where('id',$this->accountTypeId)->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل نوع الحساب'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل نوع الحساب');
        }


         //  dd($this->active_shift);
        if ($this->accountType)
        {
            DB::beginTransaction();
            $this->accountType->status = 'active';
            $this->accountType->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = "تفعيل نوع الحساب  ";
            $actionHistory->desc        = "تفعيل نوع الحساب  {$this->accountType->name}" ;
            $actionHistory->table_name  = 'accountType';
            $actionHistory->row_id      = $this->accountType->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();
            DB::commit();
            DB::rollBack();
            // Dispatch events
            $this->dispatch('accountsTypesRestoreMS');
            $this->dispatch('restoreModalToggle');
        }else
        {
            // dd($this->active_shift);
           $this->dispatch('accountsTypesValidationMS');
           $this->dispatch('restoreModalToggle');
        }
        $this->dispatch('refreshData')->to(SoftDelete::class);
    }




    public function render()
    {
        return view('back-end.accounts-type.restore');
    }
}

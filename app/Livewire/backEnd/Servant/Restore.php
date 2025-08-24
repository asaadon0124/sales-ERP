<?php

namespace App\Livewire\BackEnd\Servant;

use App\Models\Servant;
use Livewire\Component;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Servant\SoftDelete;

class Restore extends Component
{


     protected $listeners = ['servantRestore','refreshData' => '$refresh'];

    public $servantId;
    public $servant;

    public function servantRestore($id)
    {
        // dd('ds');
        $this->servantId   = $id;
        $this->servant     = Servant::withoutGlobalScope(ActiveScope::class)->where('id',$this->servantId)->with(['account_customer' => function($q)
        {
            $q->withoutGlobalScope(ActiveScope::class);

        }])->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل المندوب'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل المندوب');
        }

        DB::beginTransaction();
        try
        {
            if ($this->servant)
            {
                 // إعادة تحميل العلاقة servant بدون Global Scope
                $this->servant->load(['account_customer' => function ($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class);
                }]);





                // تفعيل المندوب نفسه
                $this->servant->status = 'active';
                $this->servant->save();



                // لو حساب المندوب محذوف فعله
                if ($this->servant->account_customer)
                {
                    $this->servant->account_customer->status = 'active';
                    $this->servant->account_customer->save();
                }

                // سجل العملية
                ActionHistory::create(
                [
                    'title'      => 'تفعيل المندوب',
                    'desc'       => "تفعيل المندوب {$this->servant->name}",
                    'table_name' => 'Servant',
                    'row_id'     => $this->servant->id,
                    'created_by' => auth()->user()->id,
                ]);

                DB::commit();

                $this->dispatch('servantsRestoreMS');
                $this->dispatch('restoreModalToggle');
                $this->dispatch('refreshData')->to(SoftDelete::class);

            } else {
                $this->dispatch('servantsValidationMS');
                $this->dispatch('restoreModalToggle');
            }

        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء تفعيل المندوب');
        }
    }



    public function render()
    {
        return view('back-end.servant.restore');
    }
}

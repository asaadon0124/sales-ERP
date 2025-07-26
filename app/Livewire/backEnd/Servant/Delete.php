<?php

namespace App\Livewire\BackEnd\Servant;

use App\Models\Servant;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;

class Delete extends Component
{
    protected $listeners = ['servantDelete','refreshData' => '$refresh'];

    public $servantId;
    public $servant;
    // public $stor_batch_qty;

    public function servantDelete($id)
    {
        $this->servantId   = $id;
        $this->servant     = Servant::where('id',$this->servantId)->with('account_customer')->first();


        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {
        if (!auth()->user()->can('حذف المندوب'))
        {
            abort(403, 'ليس لديك صلاحية حذف المندوب');
        }


        $this->servant->load(['account_customer']);

        if (!$this->servant)
        {
            $this->dispatch('deleteModalToggle');
            return;
        }

        DB::beginTransaction();

        try
        {

                $total_balance = $this->servant->current_balance;

                if (bccomp($total_balance, 0, 2) !== 0)
                {
                    $this->addError('current_balance', 'لا يمكن حذف المندوب لأن مجموع الرصيد الحالي له لا يساوي صفر.');
                    DB::rollBack();
                    return;
                }

                $this->servant->status = 'un_active';
                $this->servant->save();


                $this->servant->account_customer->status = 'un_active';
                $this->servant->account_customer->save();


            // سجل في تاريخ العمليات
            ActionHistory::create(
            [
                'title'      => 'حذف المندوب',
                'desc'       => "حذف المندوب {$this->servant->name}",
                'table_name' => 'servant',
                'row_id'     => $this->servant->id,
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();

            $this->dispatch('servantDeleteMS');
        } catch (\Exception $e)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء حذف المندوب');
        }

        $this->dispatch('deleteModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }






    public function render()
    {
        return view('back-end.servant.delete');
    }
}

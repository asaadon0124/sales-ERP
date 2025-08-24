<?php

namespace App\Livewire\BackEnd\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Customer\Data;

class Delete extends Component
{

    protected $listeners = ['customerDelete','refreshData' => '$refresh'];

    public $customerId;
    public $customer;
    // public $stor_batch_qty;

    public function customerDelete($id)
    {
        $this->customerId   = $id;
        $this->customer     = Customer::where('id',$this->customerId)->with('customer_account')->first();


        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {
        if (!auth()->user()->can('حذف العميل'))
        {
            abort(403, 'ليس لديك صلاحية حذف العميل');
        }


        $this->customer->load(['customer_account']);

        if (!$this->customer)
        {
            $this->dispatch('deleteModalToggle');
            return;
        }

        DB::beginTransaction();

        try
        {

                $total_balance = $this->customer->current_balance;

                if (bccomp($total_balance, 0, 2) !== 0)
                {
                    $this->addError('current_balance', 'لا يمكن حذف العميل لأن مجموع الرصيد الحالي له لا يساوي صفر.');
                    DB::rollBack();
                    return;
                }

                $this->customer->status = 'un_active';
                $this->customer->save();


                $this->customer->customer_account->status = 'un_active';
                $this->customer->customer_account->save();


            // سجل في تاريخ العمليات
            ActionHistory::create(
            [
                'title'      => 'حذف العميل',
                'desc'       => "حذف العميل {$this->customer->name}",
                'table_name' => 'Admin',
                'row_id'     => $this->customer->id,
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();

            $this->dispatch('customerDeleteMS');
        } catch (\Exception $e)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء حذف العميل');
        }

        $this->dispatch('deleteModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }





    public function render()
    {
        return view('back-end.customer.delete');
    }
}

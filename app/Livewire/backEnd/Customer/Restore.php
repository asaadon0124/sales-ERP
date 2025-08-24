<?php

namespace App\Livewire\BackEnd\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Customer\SoftDelete;

class Restore extends Component
{
    protected $listeners = ['customerRestore','refreshData' => '$refresh'];

    public $customerId;
    public $customer;

    public function customerRestore($id)
    {
        // dd('ds');
        $this->customerId   = $id;
        $this->customer     = Customer::withoutGlobalScope(ActiveScope::class)->where('id',$this->customerId)->with(['customer_account' => function($q)
        {
            $q->withoutGlobalScope(ActiveScope::class);

        }])->first();
        // show Create modal
        $this->dispatch('restoreModalToggle');
    }


    public function submit()
    {
        if (!auth()->user()->can('تفعيل العميل'))
        {
            abort(403, 'ليس لديك صلاحية اعادة تفعيل العميل');
        }

        DB::beginTransaction();
        try
        {
            if ($this->customer)
            {
                 // إعادة تحميل العلاقة customer بدون Global Scope
                $this->customer->load(['customer_account' => function ($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class);
                }]);





                // تفعيل العملاء نفسه
                $this->customer->status = 'active';
                $this->customer->save();



                // لو حساب العملاء محذوف فعله
                if ($this->customer->customer_account)
                {
                    $this->customer->customer_account->status = 'active';
                    $this->customer->customer_account->save();
                }

                // سجل العملية
                ActionHistory::create(
                [
                    'title'      => 'تفعيل العملاء',
                    'desc'       => "تفعيل العملاء {$this->customer->name}",
                    'table_name' => 'Customer',
                    'row_id'     => $this->customer->id,
                    'created_by' => auth()->user()->id,
                ]);

                DB::commit();

                $this->dispatch('customersRestoreMS');
                $this->dispatch('restoreModalToggle');
                $this->dispatch('refreshData')->to(SoftDelete::class);

            } else {
                $this->dispatch('customersValidationMS');
                $this->dispatch('restoreModalToggle');
            }

        } catch (\Throwable $th)
        {
            DB::rollBack();
            $this->addError('general', 'حدث خطأ أثناء تفعيل العملاء');
        }
    }


    public function render()
    {
        return view('back-end.customer.restore');
    }
}

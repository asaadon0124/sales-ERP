<?php

namespace App\Livewire\BackEnd\TreasuryTransation;

use App\Models\Shift;
use App\Models\Account;
use Livewire\Component;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Livewire\BackEnd\TreasuryTransation\DataPay;

class DeletePay extends Component
{
    protected $listeners = ['treasuriesDeletePay','refreshData' => '$refresh'];

    public $transationId;
    public $treansaction;
    public $get_active_shift;
    public $adminID;
    public $treasry;
    public $treasury_balance;
    public $treasury_id;
    public $cash_amount;
    public $account;



    public function treasuriesDeletePay($id)
    {

        $this->transationId     = $id;
        $this->treansaction     = TreasuryTransation::where('id',$this->transationId)->first();
        $this->account          = Account::where('account_number',$this->treansaction->account_id)->with('account_customer','account_customer2','account_servant','account_employee')->first();
        $this->cash_amount      = $this->treansaction->cash_amount;

        // show Create modal
        $this->dispatch('deleteModalToggle');
    }



     public function mount()
    {
        $this->adminID                              = auth()->user()->id;
        $this->get_active_shift                     = Shift::where('admin_id',auth()->user()->id)->with('treasury')->where('shift_status','active')->first();

        if ($this->get_active_shift)
        {
            $this->treasry                              = $this->get_active_shift->treasury;
            $this->treasury_balance                     = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount');
            $this->treasury_id                          = $this->treasry->id;
        }
    }




      public function submit(ActionHistoryService $action_history)
    {

        if (!auth()->user()->can('حذف حركة صرف'))
        {
            abort(403, 'ليس لديك صلاحية حذف حركة صرف');
        }






        DB::beginTransaction();

        // 1 - حذف جدول حركة الخزن TREASURY TRANSATIONS ***************************************
        $this->treansaction->delete();
        $this->treasury_balance = $this->treasury_balance + $this->cash_amount;


           // 2 - تعديل جدول العملاء او الموردين او المناديب SUPPLIER OR CUSTOMERS SERVANTS ***************************************
        if ($this->account->account_customer != null)
        {
            $this->account->account_customer->current_balance   = getSupplierCurrentBalance($this->treansaction->account_id);
            $this->account->account_customer->save();

            $this->account->current_balance                     = getSupplierCurrentBalance($this->treansaction->account_id);
            $this->account->save();


        }elseif($this->account->account_customer2 != null)
        {
            $this->account->account_customer2->current_balance   = getCoustomerCurrentBalance($this->treansaction->account_id,'account_customer2');
            $this->account->account_customer2->save();

            $this->account->current_balance                     = getCoustomerCurrentBalance($this->treansaction->account_id,'account_customer2');
            $this->account->save();
        }elseif($this->account->account_servant != null)
        {
            $this->account->account_servant->current_balance   = getServantCurrentBalance($this->treansaction->account_id,'account_servant');
            $this->account->account_servant->save();

            $this->account->current_balance                     = getServantCurrentBalance($this->treansaction->account_id,'account_servant');
            $this->account->save();
        }


         // 3 - CREATE ACTION HISTORY TABLE *****************
            $action_history->action('حذف ًصرف ', "حذف ًصرف {$this->treansaction->notes}", 'TreasuryTransaction', $this->treansaction->id,auth()->user()->id);
        DB::commit();

        // Dispatch events to update UI
        $this->dispatch('treasury_transationsDeleteMS');
        $this->dispatch('deleteModalToggle');
        $this->dispatch('refreshData')->to(DataPay::class);

        DB::rollBack();
    }




    public function render()
    {
        return view('back-end.treasury-transation.delete-pay');
    }
}

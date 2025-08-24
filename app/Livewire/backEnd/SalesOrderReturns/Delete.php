<?php

namespace App\Livewire\BackEnd\SalesOrderReturns;

use App\Models\Servant;
use Livewire\Component;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Livewire\BackEnd\SalesOrderReturns\Data;

class Delete extends Component
{
     protected $listeners = ['salesOrderReturnsDelete','refreshData' => '$refresh'];

    public $salesOrderID;

    public $get_active_shift;
    public $itemCardMoveType;
    public $itemCardMoveCategory;
    public $order;
    public $treasury_transation_table;
    public $servant;
    public $item_batches;
    public $item;
    public $item_card_movement;


   public function salesOrderReturnsDelete($id)
    {

        $this->salesOrderID         = $id;
        $this->order                        = SalesOrder::where('auto_serial',$this->salesOrderID)->with('order_detailes','treasury_tranaction','customer')->first();
        $this->treasury_transation_table    = $this->order->treasury_tranaction;
        $this->servant                      = Servant::where('servant_code',$this->order->servant_code)->with('account_customer')->first();

        $this->dispatch('deleteModalToggle');
    }

      public function mount()
    {
        $this->get_active_shift         = Active_shift();
    }


    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('حذف فاتورة مرتجع المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية حذف فاتورة مرتجع المبيعات ');
        }

        try
        {
            DB::beginTransaction();


                if ($this->order->order_detailes->count() > 0)    // لو في اكتر من صنف داخل فاتورة المبيعات
                {
                    // ********************************  الحسابات ******************************************
                        foreach ($this->order->order_detailes as $orderDetailes)
                        {
                            // 1 - DELETE ORDER DETAILES TABLE حذف من جدول تفاصيل فاتورة المبيعات *************************************************************************
                                $orderDetailes->delete();
                        }

                    // 2 - CREATE ACTION HISTORY TABLE *****************
                        $action_history->action('حذف فاتورة مرتجع المبيعات حالية ', "حذف فاتورة مرتجع المبيعات حالية {$this->order->auto_serial}", 'SalesOrder', $this->order->auto_serial,auth()->user()->id);

                    // 3 - DELETE SALES ORDER  حذف جدول فاتورة المبيعات
                        $this->order->delete();
                }else
                {
                       // 2 - CREATE ACTION HISTORY TABLE *****************
                        $action_history->action('حذف فاتورة مرتجع المبيعات حالية ', "حذف فاتورة مرتجع المبيعات حالية {$this->order->auto_serial}", 'SalesOrder', $this->order->auto_serial,auth()->user()->id);

                    // 3 - DELETE SALES ORDER  حذف جدول فاتورة المبيعات
                        $this->order->delete();
                }


            DB::commit();



                $this->dispatch('salesOrdersDeleteMS');
                $this->dispatch('deleteModalToggle');
                $this->dispatch('refreshData')->to(Data::class);


        } catch (\Throwable $th)
        {
            DB::rollBack();
            dd($th);
            $this->dispatch($th->getMessage()); // أو أضف رسالة واضحة للمستخدم
            return;
        }
    }




    public function render()
    {
        return view('back-end.sales-order-returns.delete');
    }
}

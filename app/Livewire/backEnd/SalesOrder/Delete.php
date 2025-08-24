<?php

namespace App\Livewire\BackEnd\SalesOrder;

use App\Models\Servant;
use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\SalesOrder;
use App\Models\ItemCardMovement;
use App\Services\StoreAllocator;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Livewire\BackEnd\SalesOrder\Data;

class Delete extends Component
{
    protected $listeners = ['salesOrderDelete','refreshData' => '$refresh'];

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


    public function salesOrderDelete($id)
    {

        $this->salesOrderID         = $id;
        $this->order                        = SalesOrder::where('auto_serial',$this->salesOrderID)->with('order_detailes','treasury_tranaction','customer')->first();
        $this->treasury_transation_table    = $this->order->treasury_tranaction;
        $this->servant                      = Servant::where('servant_code',$this->order->servant_code)->with('account_customer')->first();
        // $this->item                         = Item::where('item_code',$this->salesOrderDetailes->item_code)->first();

        $this->dispatch('deleteModalToggle');
    }


    public function mount()
    {
        $this->get_active_shift         = Active_shift();
    }


    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('حذف فاتورة المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية حذف فاتورة المبيعات ');
        }

        try
        {
            DB::beginTransaction();


                if ($this->order->order_detailes->count() > 0)    // لو في اكتر من صنف داخل فاتورة المبيعات
                {
                    // ********************************  الحسابات ******************************************


                        // 1 - DELETE SALES ORDER  حذف جدول فاتورة المبيعات
                            $this->order->delete();

                        // 2 - DELETE TREASURY TRANSACTION  حذف جدول حركة النقدية *******************************
                            $this->order->treasury_tranaction->delete();

                        if ($this->order->invoice_type == '0')                          // لو الفاتورة كاش كلها
                        {
                            // 4  -- UPDATE SERVANT TABLE تعديل جدول المناديب *******************************
                                $this->servant->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                                $this->servant->save();

                            // 5 -- UPDATE SERVANT ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************
                                $this->servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                                $this->servant->account_customer->save();

                        }elseif($this->order->paid > 0 && $this->order->unpaid > 0)    // لو الفاتورة نص اجل
                        {
                            // 4  -- UPDATE SERVANT TABLE تعديل جدول المناديب *******************************
                                $this->servant->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                                $this->servant->save();

                            // 5 -- UPDATE SERVANT ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************
                                $this->servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                                $this->servant->account_customer->save();

                            // 6  -- UPDATE CUSTOMER TABLE تعديل جدول العملاء *******************************
                                $this->order->customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->order->customer->account_number,'customer','account_customer2');
                                $this->order->customer->save();


                            // 7 -- UPDATE CUSTOMER ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعملاء  *******************************
                                $this->order->customer->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->order->customer->customer_account->account_number,'customer','account_customer2');
                                $this->order->customer->customer_account->save();

                        }else                                                           // لو الفتورة اجل كلها
                        {
                            // 4  -- UPDATE CUSTOMER TABLE تعديل جدول العملاء *******************************
                                $this->order->customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->order->customer->account_number,'customer','account_customer2');
                                $this->order->customer->save();


                            // 5 -- UPDATE CUSTOMER ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعملاء  *******************************
                                $this->order->customer->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->order->customer->customer_account->account_number,'customer','account_customer2');
                                $this->order->customer->customer_account->save();
                        }


                    // ********************************  المخازن ******************************************

                        foreach ($this->order->order_detailes as $orderDetailes)
                        {
                            // 1 - DELETE ORDER DETAILES TABLE حذف من جدول تفاصيل فاتورة المبيعات *************************************************************************
                                $orderDetailes->delete();

                            // 1 - A - نجيب الكمية الي حتتخصم من المخزن -- الكمية المباعة من الصنف -- بالوحدة الاب
                                $this->item_batches                 = ItemBatch::where('auto_serial',$orderDetailes->batch_id)->first();

                                $temp_item_cost_price                   = $this->item_batches->item_cost_price;
                                $temp_itemUnit_type                     = $orderDetailes->item_unit->is_master;
                                $temp_item_name                         = $orderDetailes->item_unit->name;
                                $temp_qty                               = $orderDetailes->qty;

                                $item_data                              = $orderDetailes->toArray();
                                $item_data['item_cost_price']           = $temp_item_cost_price;
                                $item_data['itemUnit_type']             = $temp_itemUnit_type;
                                $item_data['item_name']                 = $temp_item_name;
                                $item_data['qty']                       = $temp_qty;

                                $result         = app(StoreAllocator::class)->allocate($item_data);
                                $batch          = $result->batches->first();
                                $required_qty   = $result->required_qty;
                                $deductions     = [];

                            // 2 - اجيب الكميات في المخازن قبل خصم الصنف
                                $qty_before_all_stores = ItemBatch::where('item_code', $item_data['item_code'])->selectRaw('SUM(qty - deduction) as total')->value('total');
                                $qty_before_this_store = ItemBatch::where('item_code', $item_data['item_code'])->where('store_id', $item_data['store_id'])->selectRaw('SUM(qty - deduction) as total')->value('total');

                            // 3 - تعديل جدول الباتشات ITEM BATCHES
                                $this->item_batches->deduction = $this->item_batches->deduction - $required_qty;
                                $this->item_batches->save();

                            // 4- ❖ نحسب الكمية بعد الإضافة
                                $qty_after_all_stores = ItemBatch::where('item_code', $orderDetailes->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                                $qty_after_this_store = ItemBatch::where('item_code', $orderDetailes->item_code)
                                    ->where('store_id', $orderDetailes->store_id)
                                    ->selectRaw('SUM(qty - deduction) as total')->value('total');

                            // 5 - DELETE ITEM CARD MOVEMENTS  حذف جدول حركة الصنف
                                $this->item_card_movement           = ItemCardMovement::where('sales_orderdetiles__id',$orderDetailes->id)->first();
                                $this->item_card_movement->delete();


                            // 6 -- UPDATE ITEMS TABLE تعديل جدول الخاص الاصناف  *******************************
                                $qty_after_all_stores_parent                            = $qty_after_all_stores;
                                $qty_after_all_stores_sub                               = $qty_after_all_stores_parent * $orderDetailes->item->qty_sub_item_unit;
                                $qty_fraction_only                                      = round($qty_after_all_stores_parent - floor($qty_after_all_stores_parent), 2);
                                $qty_value_fraction_only                                = number_format($qty_fraction_only, 4, '.', '') * $orderDetailes->item->qty_sub_item_unit;


                                $orderDetailes->item->total_qty_for_parent     = floor($qty_after_all_stores_parent);
                                $orderDetailes->item->total_qty_for_sub_items  = $qty_after_all_stores_sub;
                                $orderDetailes->item->sub_item_qty             = $qty_value_fraction_only;
                                $orderDetailes->item->updated_by               = auth()->user()->id;
                                $orderDetailes->item->save();
                        }

                        // 7 - CREATE ACTION HISTORY TABLE *****************
                            $action_history->action('حذف فاتورة مبيعات حالية ', "حذف فاتورة مبيعات حالية {$this->order->auto_serial}", 'SalesOrderDetail', $this->order->auto_serial,auth()->user()->id);





                }


            DB::commit();



                $this->dispatch('salesOrderDeleteMS');
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
        return view('back-end.sales-order.delete');
    }
}

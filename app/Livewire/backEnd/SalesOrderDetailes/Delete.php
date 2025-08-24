<?php

namespace App\Livewire\BackEnd\SalesOrderDetailes;

use App\Models\Item;
use App\Models\Servant;
use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\SalesOrder;
use App\Models\ItemCardMovement;
use App\Models\SalesOrderDetail;
use App\Services\StoreAllocator;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementType;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Models\ItemCardMovementCategory;
use App\Livewire\BackEnd\SalesOrder\Data;
use App\Livewire\BackEnd\SalesOrder\Show;

class Delete extends Component
{

    protected $listeners = ['salesOrderDetailesDelete','refreshData' => '$refresh'];

    public $salesOrderDetailesID;
    public $salesOrderDetailes;

    public $get_active_shift;
    public $itemCardMoveType;
    public $itemCardMoveCategory;
    public $order;
    public $treasury_transation_table;
    public $servant;
    public $item_batches;
    public $item_card_movement;
    public $item;

    public function salesOrderDetailesDelete($id)
    {

        $this->salesOrderDetailesID         = $id;
        $this->salesOrderDetailes           = SalesOrderDetail::where('id',$this->salesOrderDetailesID)->with('item','store')->first();
        $this->order                        = SalesOrder::where('auto_serial',$this->salesOrderDetailes->auto_serial_sales_order)->with('order_detailes','treasury_tranaction','customer')->first();
        $this->treasury_transation_table    = TreasuryTransation::where('auto_serial',$this->order->treasures_transactions_id)->first();
        $this->item_batches                 = ItemBatch::where('auto_serial',$this->salesOrderDetailes->batch_id)->first();
        $this->servant                      = Servant::where('servant_code',$this->order->servant_code)->with('account_customer')->first();
        $this->item_card_movement           = ItemCardMovement::where('sales_orderdetiles__id',$this->salesOrderDetailesID)->first();
        $this->item                         = Item::where('item_code',$this->salesOrderDetailes->item_code)->first();


        $this->dispatch('deleteModalToggle');
    }


    public function mount()
    {
        $this->get_active_shift         = Active_shift();
        $this->itemCardMoveType         = ItemCardMovementType::where('company_code',auth()->user()->company_code)->where('name','اضافة الي المخزن')->first();
        $this->itemCardMoveCategory     = ItemCardMovementCategory::where('company_code',auth()->user()->company_code)->where('name','اضافة كمية من الصنف في المخزن نظير تعديل و حذف صنف من فاتورة المبيعات')->first();
    }


    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('حذف صنف من فاتورة المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية حذف صنف من فاتورة المبيعات ');
        }

        try
        {
            DB::beginTransaction();

//  dd($this->item_card_movement);

                if ($this->order->order_detailes->count() > 1)    // لو في اكتر من صنف داخل فاتورة المبيعات
                {
                    // ********************************  الحسابات ******************************************
                        // 1 - DELETE ORDER DETAILES TABLE حذف من جدول تفاصيل فاتورة المبيعات *************************************************************************
                            $this->salesOrderDetailes->delete();

                        // 2 - update SALES ORDER  تعديل جدول فاتورة المبيعات ******************************************

                            $this->order->total_cost_before_all     = $this->order->order_detailes->sum('total') - $this->salesOrderDetailes->total;
                            $this->order->tax_value                 = ($this->order->total_cost_before_all * $this->order->tax_percent) / 100;
                            $this->order->total_before_discount     = $this->order->total_cost_before_all + $this->order->tax_value;
                            $this->order->updated_by                = auth()->user()->id;
                            // dd($this->order->total_cost_before_all);
                            if ($this->order->discount_type == '1')     // لو نوع الخصم نسبة يبقي حنعدل نسبة الخصم
                            {
                                $this->order->discount_amount       =  ($this->order->total_before_discount * $this->order->discount_percent) / 100;
                                $this->order->total_cost        = $this->order->total_before_discount - $this->order->discount_amount;
                            }else
                            {
                                $this->order->total_cost        = $this->order->total_before_discount - $this->order->discount_amount;
                            }
                            $this->order->save();


                            if ($this->order->invoice_type == '0')                          // لو الفاتورة كاش كلها
                            {
                                // 2 - update SALES ORDER  تعديل جدول فاتورة المبيعات
                                    $this->order->paid                      = $this->order->total_cost;
                                    $this->order->mony_for_account          = $this->order->paid;
                                    $this->order->save();

                                // 3 - update TREASURY TRANSACTION  تعديل جدول حركة النقدية ***************************
                                    $this->treasury_transation_table->cash_for_account         = 0;
                                    $this->treasury_transation_table->updated_by                = auth()->user()->id;
                                    $this->treasury_transation_table->cash_amount               = $this->order->paid;
                                    $this->treasury_transation_table->servant_cash_amount       = $this->order->paid * (-1);
                                    $this->treasury_transation_table->save();

                                // 4  -- UPDATE SERVANT TABLE تعديل جدول المناديب *******************************
                                    $this->servant->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                                    $this->servant->save();

                                // 5 -- UPDATE SERVANT ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************
                                    $this->servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                                    $this->servant->account_customer->save();

                            }elseif($this->order->paid > 0 && $this->order->unpaid > 0)    // لو الفاتورة نص اجل
                            {
                                // 2 - update SALES ORDER  تعديل جدول فاتورة المبيعات
                                    $this->order->unpaid                    = $this->order->total_cost - $this->order->paid;
                                    $this->order->mony_for_account          = $this->order->unpaid * (-1);
                                    $this->order->save();

                                // 3 - update TREASURY TRANSACTION  تعديل جدول حركة النقدية *******************************
                                    $this->treasury_transation_table->cash_for_account          = $this->order->unpaid *(-1);
                                    $this->treasury_transation_table->updated_by                = auth()->user()->id;
                                    $this->treasury_transation_table->cash_amount               = $this->order->paid;
                                    $this->treasury_transation_table->servant_cash_amount       = $this->order->paid * (-1);
                                    $this->treasury_transation_table->save();

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
                                // 2 - update SALES ORDER  تعديل جدول فاتورة المبيعات
                                    $this->order->unpaid                    = $this->order->total_cost;
                                    $this->order->mony_for_account          = $this->order->unpaid * (-1);
                                    $this->order->save();

                                // 3 - update TREASURY TRANSACTION  تعديل جدول حركة النقدية *******************************
                                    $this->treasury_transation_table->cash_for_account          = $this->order->unpaid *(-1);
                                    $this->treasury_transation_table->updated_by                = auth()->user()->id;
                                    // $this->treasury_transation_table->cash_amount               = $this->order->paid;
                                    // $this->treasury_transation_table->servant_cash_amount       = $this->order->paid * (-1);
                                    $this->treasury_transation_table->save();

                                // 4  -- UPDATE CUSTOMER TABLE تعديل جدول العملاء *******************************
                                    $this->order->customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->order->customer->account_number,'customer','account_customer2');
                                    $this->order->customer->save();


                                // 5 -- UPDATE CUSTOMER ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعملاء  *******************************
                                    $this->order->customer->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->order->customer->customer_account->account_number,'customer','account_customer2');
                                    $this->order->customer->customer_account->save();
                            }

                    // ********************************  المخازن ******************************************

                        // 1 - A - نجيب الكمية الي حتتخصم من المخزن -- الكمية المباعة من الصنف -- بالوحدة الاب
                            $temp_item_cost_price                   = $this->item_batches->item_cost_price;
                            $temp_itemUnit_type                     = $this->salesOrderDetailes->item_unit->is_master;
                            $temp_item_name                         = $this->salesOrderDetailes->item_unit->name;
                            $temp_qty                               = $this->salesOrderDetailes->qty;

                            $item_data                              = $this->salesOrderDetailes->toArray();
                            $item_data['item_cost_price']           = $temp_item_cost_price;
                            $item_data['itemUnit_type']             = $temp_itemUnit_type;
                            $item_data['item_name']                 = $temp_item_name;
                            $item_data['qty']                       = $temp_qty;


                                $result         = app(StoreAllocator::class)->allocate($item_data);
                                $batch          = $result->batches->first();
                                $required_qty   = $result->required_qty;
                                $deductions = [];

                        // 2 - اجيب الكميات في المخازن قبل خصم الصنف
                                $qty_before_all_stores = ItemBatch::where('item_code', $item_data['item_code'])->selectRaw('SUM(qty - deduction) as total')->value('total');
                                $qty_before_this_store = ItemBatch::where('item_code', $item_data['item_code'])->where('store_id', $item_data['store_id'])->selectRaw('SUM(qty - deduction) as total')->value('total');

                        // 3 - تعديل جدول الباتشات ITEM BATCHES
                            $this->item_batches->deduction = $this->item_batches->deduction - $required_qty;
                            $this->item_batches->save();


                        // 4- ❖ نحسب الكمية بعد الإضافة
                                $qty_after_all_stores = ItemBatch::where('item_code', $this->salesOrderDetailes->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                                $qty_after_this_store = ItemBatch::where('item_code', $this->salesOrderDetailes->item_code)
                                    ->where('store_id', $this->salesOrderDetailes->store_id)
                                    ->selectRaw('SUM(qty - deduction) as total')->value('total');

                        // 5 - حذف جدول حركات الصنف  ITEM CARD MOVEMENTS
                            $this->item_card_movement->delete();
                            // dd($this->item_card_movement);
                        // 6 -- UPDATE ITEMS TABLE تعديل جدول الخاص الاصناف  *******************************

                                $qty_after_all_stores_parent                            = $qty_after_all_stores;
                                $qty_after_all_stores_sub                               = $qty_after_all_stores_parent * $this->item->qty_sub_item_unit;
                                $qty_fraction_only                                      = round($qty_after_all_stores_parent - floor($qty_after_all_stores_parent), 2);
                                $qty_value_fraction_only                                = number_format($qty_fraction_only, 4, '.', '') * $this->item->qty_sub_item_unit;

                                $this->item->total_qty_for_parent     = floor($qty_after_all_stores_parent);
                                $this->item->total_qty_for_sub_items  = $qty_after_all_stores_sub;
                                $this->item->sub_item_qty             = $qty_value_fraction_only;
                                $this->item->updated_by               = auth()->user()->id;
                                $this->item->save();


                        // 7 - CREATE ACTION HISTORY TABLE *****************
                            $action_history->action('حذف صنف  من فاتورة مبيعات حالية ', "حذف صنف  من فاتورة مبيعات حالية {$this->item->name}", 'SalesOrderDetail', $this->salesOrderDetailes->id,auth()->user()->id);

                } else                                          // لو الصنف المحذوف اخر صنف في فاتورة المبيعات
                {
                    // ********************************  الحسابات ******************************************
                        // 1 - DELETE ORDER DETAILES TABLE حذف من جدول تفاصيل فاتورة المبيعات *************************************************************************
                            $this->salesOrderDetailes->delete();

                        // 2 - CREATE SALES ORDER  تعديل جدول فاتورة المبيعات
                            $this->order->delete();

                        // 3 - update TREASURY TRANSACTION  تعديل جدول حركة النقدية *******************************
                            $this->treasury_transation_table->delete();


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
                        // 1 - A - نجيب الكمية الي حتتخصم من المخزن -- الكمية المباعة من الصنف -- بالوحدة الاب
                            $temp_item_cost_price                   = $this->item_batches->item_cost_price;
                            $temp_itemUnit_type                     = $this->salesOrderDetailes->item_unit->is_master;
                            $temp_item_name                         = $this->salesOrderDetailes->item_unit->name;
                            $temp_qty                               = $this->salesOrderDetailes->qty;

                            $item_data                              = $this->salesOrderDetailes->toArray();
                            $item_data['item_cost_price']           = $temp_item_cost_price;
                            $item_data['itemUnit_type']             = $temp_itemUnit_type;
                            $item_data['item_name']                 = $temp_item_name;
                            $item_data['qty']                       = $temp_qty;


                                $result         = app(StoreAllocator::class)->allocate($item_data);
                                $batch          = $result->batches->first();
                                $required_qty   = $result->required_qty;
                                $deductions = [];

                        // 2 - اجيب الكميات في المخازن قبل خصم الصنف
                                $qty_before_all_stores = ItemBatch::where('item_code', $item_data['item_code'])->selectRaw('SUM(qty - deduction) as total')->value('total');
                                $qty_before_this_store = ItemBatch::where('item_code', $item_data['item_code'])->where('store_id', $item_data['store_id'])->selectRaw('SUM(qty - deduction) as total')->value('total');

                        // 3 - تعديل جدول الباتشات ITEM BATCHES
                            $this->item_batches->deduction = $this->item_batches->deduction - $required_qty;
                            $this->item_batches->save();


                        // 4- ❖ نحسب الكمية بعد الإضافة
                                $qty_after_all_stores = ItemBatch::where('item_code', $this->salesOrderDetailes->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                                $qty_after_this_store = ItemBatch::where('item_code', $this->salesOrderDetailes->item_code)
                                    ->where('store_id', $this->salesOrderDetailes->store_id)
                                    ->selectRaw('SUM(qty - deduction) as total')->value('total');

                        // 5 - DELETE ITEM CARD MOVEMENTS  حذف جدول حركة الصنف
                            $this->item_card_movement->delete();


                        // 6 -- UPDATE ITEMS TABLE تعديل جدول الخاص الاصناف  *******************************
                            $qty_after_all_stores_parent                            = $qty_after_all_stores;
                            $qty_after_all_stores_sub                               = $qty_after_all_stores_parent * $this->item->qty_sub_item_unit;
                            $qty_fraction_only                                      = round($qty_after_all_stores_parent - floor($qty_after_all_stores_parent), 2);
                            $qty_value_fraction_only                                = number_format($qty_fraction_only, 4, '.', '') * $this->item->qty_sub_item_unit;

                            $this->item->total_qty_for_parent     = floor($qty_after_all_stores_parent);
                            $this->item->total_qty_for_sub_items  = $qty_after_all_stores_sub;
                            $this->item->sub_item_qty             = $qty_value_fraction_only;
                            $this->item->updated_by               = auth()->user()->id;
                            $this->item->save();


                        // 7 - CREATE ACTION HISTORY TABLE *****************
                            $action_history->action('حذف صنف  من فاتورة مبيعات حالية ', "حذف صنف  من فاتورة مبيعات حالية {$this->item->name}", 'SalesOrderDetail', $this->salesOrderDetailes->id,auth()->user()->id);
                }

                // dd('ahmed');
            DB::commit();

                if ($this->order->order_detailes->count() > 0)
                {
                    // لو لسه فيه أصناف
                    $this->dispatch('salesOrderDtailesDeleteMS');
                    $this->dispatch('deleteModalToggle');
                    $this->dispatch('refreshData')->to(Show::class);
                } else
                {
                    // لو مفيش أصناف بعد الحذف (آخر صنف اتحذف)
                    toastr()->error('تم حذف الصنف و الفاتورة بنجاح', 'رسالة حذف');
                    return redirect()->route('salesOrder.index');
                }
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
        return view('back-end.sales-order-detailes.delete');
    }
}

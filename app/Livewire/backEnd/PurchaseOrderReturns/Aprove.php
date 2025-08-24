<?php

namespace App\Livewire\BackEnd\PurchaseOrderReturns;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Account;
use Livewire\Component;
use App\Models\MoveType;
use App\Models\ItemBatch;
use App\Models\PurchaseOrder;
use App\Models\ItemCardMovement;
use App\Services\StoreAllocator;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementType;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Models\ItemCardMovementCategory;
use App\Services\GetAverageItemPriceService;
use App\Services\GetAverageSubItemPriceService;

class Aprove extends Component
{

     public $total_cost_before_all;
    public $tax_percent = 0;
    public $tax_value   = 0;
    public $total_before_discount;
    public $total_cost;
    public $company_code;
    public $auto_serial;
    public $discount_type;
    public $discount_percent    = 0;
    public $discount_amount     = 0;
    public $discount_value;
    public $paid                = 0;
    public $unpaid              = 0;

    public $order;
    public $orderID;
    public $total_cost_before_discount;
    public $invoice_type;
    public $order_items;
    public $convert_qty;
    public $item_batch;
    public $itemCardMoveType;
    public $itemCardMoveCategory;

    public $get_active_shift;
    public $active_treasury;
    public $treasury_balance;


    public $treasury_transation_table;
    public $treasury_table;
    public $moveType;

     protected $listeners = ['purchaseOrderReturnsApprove'];



    public function purchaseOrderReturnsApprove($id)
    {
        $this->order                            = PurchaseOrder::where('auto_serial', $id)->with('order_detailes')->first();
        $this->orderID                          = $id;
        $this->moveType                         = MoveType::select('id','name','company_code')->where('company_code',auth()->user()->company_code)->where('name','تحصيل نظير مرتجع مشتريات الي مورد')->first();
        $this->itemCardMoveType                 = ItemCardMovementType::where('company_code',auth()->user()->company_code)->where('name','صرف من المخزن')->first();
        $this->itemCardMoveCategory             = ItemCardMovementCategory::where('company_code',auth()->user()->company_code)->where('name','صرف كمية من الصنف من المخزن نظير مرتجع فاتورة مشتريات')->first();

        // dd($this->order);

        $this->total_cost_before_all            = $this->order->total_cost_before_all;
        $this->total_before_discount            = $this->order->total_cost_before_all;
        $this->total_cost                       = $this->order->total_cost_before_all;
        $this->company_code                     = $this->order->company_code;
        $this->auto_serial                      = $this->order->auto_serial;
        $this->invoice_type                     = $this->order->InvoiceType();
        $this->paid                             = $this->total_cost;
        $this->get_active_shift                 = Active_shift();

        // GET OTHER DATA **********************************************

        if ($this->get_active_shift)
        {
            $this->active_treasury                      = $this->get_active_shift->treasury;
            $this->treasury_balance                     = Treasry_balance($this->get_active_shift);
        }

        // show edit modal
        $this->dispatch('aproveModalToggle');
    }


    public function change_tax_percent($value)
    {

        $this->tax_value                = ($this->total_cost_before_all * $value) / 100;
        $this->total_before_discount    = $this->total_cost_before_all + $this->tax_value;
        $this->total_cost               = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;
        $this->paid                     = $this->total_cost;
    }


    public function change_discount_type($value)
    {
        $this->discount_type                = $value;
    }


    public function change_discount_percent($value)
    {
        $subtotal                   = $this->total_cost_before_all + $this->tax_value;

        if ($subtotal > 0 && is_numeric($value)) {
            $this->discount_amount  = round(($subtotal * $value) / 100, 2);
            $this->discount_percent = round($value, 2); // تأكيد تنسيقه برضو
            $this->total_cost       = round($subtotal - $this->discount_amount, 2);
            if ($this->invoice_type == 'كاش') {
                $this->paid                         = $this->total_cost;
            }
        }
    }


    public function change_discount_amount($value)
    {
        $subtotal                       = $this->total_cost_before_all + $this->tax_value;

        if ($subtotal > 0 && is_numeric($value))
        {
            $this->discount_amount      = round($value, 2);
            $this->discount_percent     = round(($this->discount_amount / $subtotal) * 100, 2);
            $this->total_cost           = round($subtotal - $this->discount_amount, 2);
            $this->paid                 = $this->total_cost;
        }
    }


    public function updateTreasuryBalance()
    {
        $this->treasury_balance = Treasry_balance(Active_shift());
    }

    public function rules()
    {
        return
            [

                'tax_percent'    => 'required|numeric|min:0|max:100',
                'tax_value'     =>
                [
                    'required_if:tax_percent,"!=",0',  // تعديل المقارنة إلى صيغة صحيحة
                    'numeric',
                    function ($attribute, $value, $fail)
                    {
                        if ($value > $this->total_cost_before_all) {
                            $fail('قيمة الضريبة لا يمكن أن تتجاوز إجمالي التكلفة قبل الخصم.');
                        }
                    },
                ],

                'discount_type'     => 'nullable|in:0,1',
                'discount_percent'  => 'bail|required_if:discount_type,1|numeric|min:0|lte:100',  // أضفنا `gt:0` و `lte:100` لضمان أن تكون أكبر من 0 وأقل أو تساوي 100
                'discount_amount'   => 'bail|required_if:discount_type,0|numeric|min:0|lte:total_cost',  // أضفنا `gt:0` و `lte:100` لضمان أن تكون أكبر من 0 وأقل أو تساوي 100
                'paid'              => 'bail|required|numeric|min:1',

            ];
    }



    public function messages()
    {
        return
            [
                'tax_percent.required'          => 'نسبة الضريبة مطلوبة.',
                'tax_percent.numeric'           => 'نسبة الضريبة يجب أن تكون رقماً.',
                'tax_percent.min'               => 'نسبة الضريبة لا يمكن أن تكون أقل من 0.',
                'tax_percent.max'               => 'نسبة الضريبة لا يمكن أن تزيد عن 100%.',

                'tax_value.required_if'         => 'قيمة الضريبة مطلوبة.',
                'tax_percent.numeric'           => 'قيمة الضريبة يجب أن تكون رقمًا.',
                'tax_percent.min'               => 'قيمة الضريبة لا يمكن أن تكون أقل من 0.',
                'tax_value.numeric'             => 'قيمة الضريبة يجب أن تكون رقمًا.',

                'discount_type.in'              => 'نوع الخصم يجب أن يكون قيمة أو نسبة فقط.',

                'discount_percent.required_if'  => 'نسبة الخصم مطلوبة إذا كان نوع الخصم نسبة.',
                'discount_percent.numeric'      => 'نسبة الخصم يجب أن تكون رقمًا.',
                'discount_percent.min'          => 'نسبة الخصم يجب أن تكون أكبر من 0.',
                'discount_percent.lte'          => 'نسبة الخصم يجب أن تكون أقل من أو تساوي 100.',

                'discount_amount.required_if'   => 'قيمة الخصم مطلوبة إذا كان نوع الخصم قيمة.',
                'discount_amount.numeric'       => 'قيمة الخصم يجب أن تكون رقمًا.',
                'discount_amount.min'           => 'قيمة الخصم يجب أن تكون أكبر من 0.',
                'discount_amount.lte'           => 'قيمة الخصم يجب أن تكون أقل من أو تساوي فيمة الفاتورة.',


                'paid.required'   => 'المبلغ المدفوع مطلوب.',
                'paid.numeric'    => 'المبلغ المدفوع يجب أن تكون رقمًا.',
                'paid.min'        => 'المبلغ المدفوع يجب أن تكون أكبر من 0.',


            ];
    }


    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('اعتماد فاتورة مرتجع المشتريات'))
        {
            abort(403, 'ليس لديك صلاحية اعتماد فاتورة مرتجع المشتريات ');
        }

        // 1 - CREATE VALIDATIONS التحقق
            $this->validate($this->rules(), $this->messages());

            // التحقق من المبلغ المدفوع
            if ($this->paid != $this->total_cost)
            {
                $this->addError('paid', 'المبلغ المدفوع يجب أن يساوي قيمة الفاتورة.');
                return;
            }


            if ($this->discount_type == null || $this->discount_type == '')  // اذا كان مفيش خصم
            {
                if ($this->discount_percent != 0 || $this->discount_percent == '')
                {
                    $this->addError('discount_percent', 'يجب ان تكون نسبة الخصم  تساوي 0.');
                    return;
                }

                if ($this->discount_amount != 0 || $this->discount_amount == '')
                {
                    $this->addError('discount_amount', 'يجب ان تكون قيمة الخصم  تساوي 0.');
                    return;
                }
            } elseif ($this->discount_type == 0)
            {
                if ($this->discount_amount == 0 || $this->discount_amount == '')
                {
                    $this->addError('discount_amount', 'يجب ان تكون قيمة الخصم  اكبر من 0.');
                    return;
                }
            } elseif ($this->discount_type == 1)
            {
                if ($this->discount_percent == 0 || $this->discount_percent == '')
                {
                    $this->addError('discount_percent', 'يجب ان تكون نسبة الخصم  اكبر من 0.');
                    return;
                }
            }

        DB::beginTransaction();

            $supplier_account = Account::where('account_number',$this->order->account_number)->with('account_customer')->first();

        // 2  -- UPDATE PURSHACES ORDER TABLE تعديل جدول الفواتير *******************************
            $this->order->paid                          = $this->paid;
            $this->order->unpaid                        = 0;
            $this->order->total_cost                    = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;
            $this->order->mony_for_account              = 0;
            $this->order->approve                       = '1';
            $this->order->discount_type                 = $this->discount_type;
            $this->order->discount_percent              = $this->discount_percent;
            $this->order->discount_amount               = $this->discount_amount;
            $this->order->tax_percent                   = $this->tax_percent;
            $this->order->tax_value                     = $this->tax_value;
            $this->order->updated_by                    = auth()->user()->id;
            $this->order->approved_by                   = auth()->user()->id;

            $this->order->save();

        // 3  -- CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
            $this->treasury_transation_table                            =  new TreasuryTransation();

            $this->treasury_transation_table->treasury_id               = $this->active_treasury->id;
            $this->treasury_transation_table->cash_amount               = $this->paid;
            $this->treasury_transation_table->shift_id                  = $this->get_active_shift->auto_serial;
            $this->treasury_transation_table->isal_number               = $this->active_treasury->last_recept_recive + 1;
            $this->treasury_transation_table->moveType_id               = $this->moveType->id;

            $this->treasury_transation_table->invoice_type_accounts     = 'purchase_returns';
            $this->treasury_transation_table->invoice_type              = $this->order->invoice_type;
            $this->treasury_transation_table->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
            $this->treasury_transation_table->account_id                = $this->order->account_number;
            $this->treasury_transation_table->move_date                 = Carbon::today()->toDateString();
            $this->treasury_transation_table->cash_source_type          = 'account';
            $this->treasury_transation_table->is_approve                = 'approve';
            $this->treasury_transation_table->company_code              = auth()->user()->company_code;
            $this->treasury_transation_table->created_by                = auth()->user()->id;
            $this->treasury_transation_table->updated_by                = auth()->user()->id;
            $this->treasury_transation_table->account_type              = 'suppliers';
            $this->treasury_transation_table->account_balance_before    = $updateAccountBalance->getCurrentBalance($this->order->account_number,'supplier','account_customer');
            $this->treasury_transation_table->cash_for_account          = $this->paid;
            $this->treasury_transation_table->save();




        // 4  -- UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
            $this->treasury_table                       =  $this->active_treasury;
            $this->treasury_table->last_recept_recive   = $this->treasury_transation_table->isal_number;
            $this->treasury_table->save();


        //  --  ************************************** التعامل مع المخازن *******************************

            $this->order_items = $this->order->order_detailes;

            foreach ($this->order_items as $order_detail)
            {

                // 5 -- UPDATE ITEM BATCHES TABLE تعديل جدول بتشات الصنف *******************************
                    // 1 - نخصم الكميات  من المخازن حسب الباتش
                        $item_data = $order_detail->toArray();
                        $item_data['store_id']          = $this->order->store_id;
                        $item_data['item_cost_price']   = $order_detail->batch->item_cost_price;
                        $item_data['item_name']   = $order_detail->item->name;
                        $item_data['itemUnit_type']   = $item_data['is_master'];

                // dd($item_data['item_name']);

                        $result         = app(StoreAllocator::class)->allocate($item_data);
                        $required_qty   = $result->required_qty;
//  dd('ds');
                    // اجيب الكميات في المخازن قبل خصم الصنف
                    $qty_before_all_stores = ItemBatch::where('item_code', $order_detail->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                    $qty_before_this_store = ItemBatch::where('item_code', $order_detail->item_code)->where('store_id', $this->order->store_id)->selectRaw('SUM(qty - deduction) as total')->value('total');

                    $converted_qty = $order_detail->is_master == 'master'
                        ? $order_detail->qty
                        : $order_detail->qty / $order_detail->item->qty_sub_item_unit;

                      if ($required_qty <= 0) break;
                            $order_detail->batch->deduction += $required_qty;
                            $order_detail->batch->save();
                            // dd($order_detail->batch);

                    // $this->item_batch                  = new ItemBatch();
                    // $this->item_batch->qty             = $converted_qty * (-1);
                    // $this->item_batch->deduction       += $converted_qty;
                    // $this->item_batch->store_id        = $this->order->store_id;
                    // $this->item_batch->item_code       = $order_detail->item_code;
                    // $this->item_batch->item_unit_id    = $order_detail->item_units_id;
                    // $this->item_batch->item_cost_price = $order_detail->unit_price;
                    // $this->item_batch->total           = $order_detail->total;

                    // if ($order_detail->item_type == '1')
                    // {
                    //     $this->item_batch->production_date = $order_detail->production_date;
                    //     $this->item_batch->expire_date     = $order_detail->expire_date;
                    // }

                    // $this->item_batch->auto_serial   = get_last_autoSerial_invoices(ItemBatch::class, 'auto_serial');
                    // $this->item_batch->company_code  = auth()->user()->company_code;
                    // $this->item_batch->created_by    = auth()->user()->id;
                    // $this->item_batch->updated_by    = auth()->user()->id;
                    // $this->item_batch->save();

                // 6 -- UPDATE ITEM CARD MOVEMENTS TABLE تعديل جدول حركة الصنف *******************************
                    $qty_after_all_stores = ItemBatch::where('item_code', $order_detail->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');

                    $qty_after_this_store = ItemBatch::where('item_code', $order_detail->item_code)
                                ->where('store_id', $this->order->store_id)
                                ->selectRaw('SUM(qty - deduction) as total')->value('total');
                    // ❖ نجهز حركة الصنف
                    $create_item_movement                                   = new ItemCardMovement();
                    $create_item_movement->item_code                        = $order_detail->item_code;
                    $create_item_movement->store_id                         = $this->order->store_id;
                    $create_item_movement->item_card_movements_category_id  = $this->itemCardMoveCategory->id;
                    $create_item_movement->item_card_movements_type_id      = $this->itemCardMoveType->id;
                    $create_item_movement->item_batch_id                    = $order_detail->batch->auto_serial;
                    $create_item_movement->purchase_order_id                = $this->order->auto_serial;
                    $create_item_movement->purchase_orderdetiles__id        = $order_detail->id;
                    $create_item_movement->qty_before_movement              = $qty_before_all_stores;
                    $create_item_movement->qty_after_movement               = $qty_after_all_stores;
                    $create_item_movement->qty_before_movement_in_store     = $qty_before_this_store;
                    $create_item_movement->qty_after_movement_in_store      = $qty_after_this_store;
                    $create_item_movement->notes                            = $this->order->notes;
                    $create_item_movement->date                             = Carbon::now();
                    $create_item_movement->company_code                     = auth()->user()->company_code;
                    $create_item_movement->created_by                       = auth()->user()->id;
                    $create_item_movement->updated_by                       = auth()->user()->id;

                    $create_item_movement->save();
            }



        // 6 -- UPDATE ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمورد  *******************************
            $supplier_account->current_balance = $updateAccountBalance->getCurrentBalance($this->order->account_number,'supplier','account_customer');
            $supplier_account->save();

        // 7 -- UPDATE SUPPLIERS TABLE تعديل جدول الخاص بالمورد  *******************************
            $supplier                   = $supplier_account->account_customer;
            $supplier->current_balance  = $updateAccountBalance->getCurrentBalance($this->order->account_number,'supplier','account_customer');
            $supplier->save();

            $this->treasury_transation_table->account_balance_after     = $updateAccountBalance->getCurrentBalance($this->order->account_number,'supplier','account_customer');
            $this->treasury_transation_table->save();

        // 8 -- UPDATE ITEMS TABLE تعديل جدول الخاص الاصناف  *******************************
            foreach ($this->order_items as $order_detail)
            {

                $get_item2                      = Item::where('item_code', $order_detail->item_code)->with('itemUnit')->first();
                $qty_after_all_stores_parent    = ItemBatch::where('item_code', $order_detail->item_code)->sum('qty');
                $qty_after_all_stores_sub       = $qty_after_all_stores_parent * $get_item2->qty_sub_item_unit;
                $qty_fraction_only              = $qty_after_all_stores_parent - floor($qty_after_all_stores_parent); // نسبة الكسر في الوحدة الرئيسية
                $qty_value_fraction_only        = $qty_fraction_only * $get_item2->qty_sub_item_unit;


                 // 3 - UPDATE ITEMS TABLE
                if ($order_detail->is_master == 'master')   //   لو الوحدة الي مختارة اساسية
                {
                    $master_price_Service                                       = new GetAverageItemPriceService();
                    $new_master_cost_price                                      = number_format($master_price_Service->calculate($order_detail->item_code,$order_detail->item_units_id), 2, '.', '') != 0 ? number_format($master_price_Service->calculate($order_detail->item_code,$order_detail->item_units_id), 2, '.', '') : $get_item2->item_cost_price;
                    $new_sub_master_cost_price                                  = number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$get_item2->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$get_item2->qty_sub_item_unit), 2, '.', '') : $get_item2->sub_item_cost_price;
                    // تعديل اسعار الوحدة الاب *************************
                    $get_item2->item_wholesale_price         =  number_format($master_price_Service->get_new_item_wholesale_price($get_item2,$new_master_cost_price), 2, '.', '');
                    $get_item2->item_Half_wholesale_price    =  number_format($master_price_Service->get_new_item_Half_wholesale_price($get_item2,$new_master_cost_price), 2, '.', '');
                    $get_item2->item_retail_price            =  number_format($master_price_Service->get_new_item_retail_price($get_item2,$new_master_cost_price), 2, '.', '');
                    $get_item2->item_cost_price              =  $new_master_cost_price;

                    // تعديل اسعار الوحدة الابن *************************
                    $get_item2->sub_item_wholesale_price     = number_format($master_price_Service->get_new_sub_item_wholesale_price($get_item2,$new_sub_master_cost_price), 2, '.', '');
                    $get_item2->sub_item_Half_wholesale_price= number_format($master_price_Service->get_new_sub_item_Half_wholesale_price($get_item2,$new_sub_master_cost_price), 2, '.', '');
                    $get_item2->sub_item_retail_price        = number_format($master_price_Service->get_new_sub_item_retail_price($get_item2,$new_sub_master_cost_price), 2, '.', '');
                    $get_item2->sub_item_cost_price          = $new_sub_master_cost_price;
                    $get_item2->save();

                }else           //  لو الوحدة المختارة فرعية
                {
                    $sub_master_price_Service                                   = new GetAverageSubItemPriceService();
                    $new_sub_master_cost_price                                  = number_format($sub_master_price_Service->calculate_sub_cost($order_detail->item_code,$order_detail->item_units_id), 2, '.', '')  != 0 ? number_format($sub_master_price_Service->calculate_sub_cost($order_detail->item_code,$order_detail->item_units_id), 2, '.', '') : $get_item2->sub_item_cost_price ;
                    $new_master_cost_price                                      = number_format($sub_master_price_Service->calculate($new_sub_master_cost_price,$get_item2->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($sub_master_price_Service->calculate($new_sub_master_cost_price,$get_item2->qty_sub_item_unit), 2, '.', '') : $get_item2->item_cost_price;


                    // تعديل اسعار الوحدة الاب *************************
                    $get_item2->item_wholesale_price         =  number_format($sub_master_price_Service->get_new_item_wholesale_price($get_item2,$new_master_cost_price), 2, '.', '');
                    $get_item2->item_Half_wholesale_price    =  number_format($sub_master_price_Service->get_new_item_Half_wholesale_price($get_item2,$new_master_cost_price), 2, '.', '');
                    $get_item2->item_retail_price            =  number_format($sub_master_price_Service->get_new_item_retail_price($get_item2,$new_master_cost_price), 2, '.', '');
                    $get_item2->item_cost_price              =  $new_master_cost_price;



                    // تعديل اسعار الوحدة الابن *************************
                    $get_item2->sub_item_wholesale_price     = number_format($sub_master_price_Service->get_new_sub_item_wholesale_price($get_item2,$new_sub_master_cost_price), 2, '.', '');
                    $get_item2->sub_item_Half_wholesale_price= number_format($sub_master_price_Service->get_new_sub_item_Half_wholesale_price($get_item2,$new_sub_master_cost_price), 2, '.', '');
                    $get_item2->sub_item_retail_price        = number_format($sub_master_price_Service->get_new_sub_item_retail_price($get_item2,$new_sub_master_cost_price), 2, '.', '');
                    $get_item2->sub_item_cost_price          = $new_sub_master_cost_price;

                    $get_item2->save();
                }

                // if ($order_detail->is_master == 'master')
                // {
                //     // لو السعر جاي بوحدة الصنف الأساسية
                //     $get_item2->item_cost_price     = $order_detail->unit_price;
                //     $get_item2->sub_item_cost_price = sub_item_cost_price_from_parent($order_detail->unit_price, $get_item2->qty_sub_item_unit);

                // } else
                // {
                //     // لو السعر جاي بوحدة الصنف الفرعية
                //     $get_item2->item_cost_price     = parent_item_cost_price_from_sub_price($order_detail->unit_price, $get_item2->qty_sub_item_unit);
                //     $get_item2->sub_item_cost_price = $order_detail->unit_price;
                // }

                $get_item2->total_qty_for_parent    = floor($qty_after_all_stores_parent);
                $get_item2->total_qty_for_sub_items = $qty_after_all_stores_sub;
                $get_item2->sub_item_qty            = $qty_value_fraction_only;
                $get_item2->save();
            }


        // 9 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('اعتماد فاتورة مرتجع مشتريات ', "اعتماد فاتورة مرتجع مشتريات {$this->order->supplier->name}", 'PurchaseOrder', $this->order->auto_serial,auth()->user()->id);
                //  dd($get_item2);
        DB::commit();

        // Dispatch events
        $this->dispatch('purchaseOrdersApproveMS');
        $this->dispatch('aproveModalToggle');
        $this->dispatch('refreshData')->to(Data::class);



        DB::rollBack();
    }







    public function render()
    {
        return view('back-end.purchase-order-returns.aprove');
    }
}

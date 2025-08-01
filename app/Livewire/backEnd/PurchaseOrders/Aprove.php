<?php

namespace App\Livewire\BackEnd\PurchaseOrders;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Shift;
use App\Models\Account;
use Livewire\Component;
use App\Models\MoveType;
use App\Models\ItemBatch;
use App\Models\Treasuries;
use App\Models\PurchaseOrder;
use App\Models\ItemCardMovement;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementType;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Models\ItemCardMovementCategory;
use App\Livewire\BackEnd\PurchaseOrders\Data;

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

    protected $listeners = ['purchaseOrderApprove'];



    public function purchaseOrderApprove($id)
    {

        $this->order                            = PurchaseOrder::where('auto_serial', $id)->with('order_detailes')->first();
        $this->orderID                          = $id;
        $this->moveType                         = MoveType::select('id','name','company_code')->where('company_code',auth()->user()->company_code)->where('name','صرف نظير مشتريات من مورد')->first();
        $this->itemCardMoveType                 = ItemCardMovementType::where('company_code',auth()->user()->company_code)->where('name','اضافة الي المخزن')->first();
        $this->itemCardMoveCategory             = ItemCardMovementCategory::where('company_code',auth()->user()->company_code)->where('name','اضافة كمية من الصنف الي المخزن نظير انشاء فاتورة مشتريات')->first();

        // dd($this->order);

        $this->total_cost_before_all            = $this->order->total_cost_before_all;
        $this->total_before_discount            = $this->order->total_cost_before_all;
        $this->total_cost                       = $this->order->total_cost_before_all;
        $this->company_code                     = $this->order->company_code;
        $this->auto_serial                      = $this->order->auto_serial;
        $this->invoice_type                     = $this->order->InvoiceType();
        // dd($this->invoice_type);

        if ($this->invoice_type == 'كاش')
        {
            $this->paid                         = $this->total_cost;
        }

        // GET OTHER DATA **********************************************

        $this->get_active_shift                     = Active_shift();

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
        if ($this->invoice_type == 'كاش') {
            $this->paid                         = $this->total_cost;
        }
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

        if ($subtotal > 0 && is_numeric($value)) {
            $this->discount_amount      = round($value, 2);
            $this->discount_percent     = round(($this->discount_amount / $subtotal) * 100, 2);
            $this->total_cost           = round($subtotal - $this->discount_amount, 2);
            if ($this->invoice_type == 'كاش') {
                $this->paid                         = $this->total_cost;
            }
        }
    }


    public function change_paid($value)
    {
        if ($this->order->invoice_type == '1') {
            $this->unpaid = $this->total_cost - $value;
        }
    }

    public function change_unpaid($value)
    {
        if ($this->order->invoice_type == '1') {
            $this->paid = $this->total_cost - $value;
        }
    }


    public function updateTreasuryBalance()
    {
        $this->treasury_balance = Treasry_balance(Active_shift());
        // $this->treasury_balance_after_payment = $this->treasury_balance - $this->paid;
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
                // 'paid'              => 'bail|required_if:invoice_type,1|numeric|min:1|lt:total_cost',  // أضفنا `gt:0` و `lte:100` لضمان أن تكون أكبر من 0 وأقل أو تساوي 100
                // 'unpaid'            => 'bail|required_if:invoice_type,1|numeric|min:1|lt:total_cost',  // أضفنا `gt:0` و `lte:100` لضمان أن تكون أكبر من 0 وأقل أو تساوي 100

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


                'paid.required_if'   => 'المبلغ المدفوع مطلوب.',
                'paid.numeric'       => 'المبلغ المدفوع يجب أن تكون رقمًا.',
                'paid.min'           => 'المبلغ المدفوع يجب أن تكون أكبر من 0.',
                'paid.lt'           => 'المبلغ المدفوع يجب أن تكون أقل من  فيمة الفاتورة.',

                // 'unpaid.required_if'   => 'المبلغ المتبقي مطلوب.',
                // 'unpaid.numeric'       => 'المبلغ المتبقي يجب أن تكون رقمًا.',
                // 'unpaid.min'           => 'المبلغ المتبقي يجب أن تكون أكبر من 0.',
                // 'unpaid.lt'           => 'المبلغ المتبقي يجب أن تكون أقل من  فيمة الفاتورة.',

            ];
    }



    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('اعتماد فاتورة المشتريات'))
        {
            abort(403, 'ليس لديك صلاحية اعتماد فاتورة المشتريات ');
        }

        $this->validate($this->rules(), $this->messages());

        DB::beginTransaction();

        $supplier_account = Account::where('account_number',$this->order->account_number)->with('account_customer')->first();
        // dd($supplier_account);

        // 1  -- UPDATE PURSHACES ORDER TABLE تعديل جدول الفواتير *******************************

        // A - نوع الفاتورة كاش او اجل ########################################
        if ($this->order->invoice_type == '0')      // اذا كان نوع الفاتورة كاش
        {

            if ($this->paid != $this->total_cost) {
                $this->addError('cash_amount', 'المبلغ المدفوع يجب ان يساوي قيمة الفاتورة.');
                return;
            }

            if ($this->treasury_balance < $this->paid)
            {
                $this->addError('treasury_balance', 'رصيد الخزنة لا يكفي لصرف المبلغ المطلوب.');
                return;
            }

            $this->order->paid                          = $this->paid * (-1);
            $this->order->unpaid                        = 0;
            $this->order->total_cost                    = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;
            $this->order->mony_for_account              = 0;
        } else                                      // اذا كان نوع الفاتورة اجل
        {

            if ($this->unpaid == 0 || $this->paid >= $this->total_cost)
            {
                $this->addError('unpaid', 'المبلغ الغير مدفوع  يجب ان يكون اكبر من ال 0 و المبلغ المدفوع يجب ان يكون اقل من قيمة الفاتورة.');
                return;
            }

            $this->order->paid                          = $this->paid * (-1);
            $this->order->unpaid                        = $this->unpaid;
            $this->order->total_cost                    = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;
            $this->order->mony_for_account              = $this->total_cost * (-1);
            // dd('dd');

        }

        // B - تعديل حقول الخصم ################################################
        if ($this->discount_type == null || $this->discount_type == '')  // اذا كان مفيش خصم
        {
            if ($this->discount_percent != 0 || $this->discount_percent == '') {
                $this->addError('discount_percent', 'يجب ان تكون نسبة الخصم  تساوي 0.');
                return;
            }

            if ($this->discount_amount != 0 || $this->discount_amount == '') {
                $this->addError('discount_amount', 'يجب ان تكون قيمة الخصم  تساوي 0.');
                return;
            }

        } elseif ($this->discount_type == 0) {
            if ($this->discount_amount == 0 || $this->discount_amount == '') {
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

        $this->order->approve                       = '1';
        $this->order->discount_type                 = $this->discount_type;
        $this->order->discount_percent              = $this->discount_percent;
        $this->order->discount_amount               = $this->discount_amount;
        $this->order->tax_percent                   = $this->tax_percent;
        $this->order->tax_value                     = $this->tax_value;
        $this->order->updated_by                    = auth()->user()->id;
        $this->order->approved_by                   = auth()->user()->id;

        $this->order->save();
        // 2  -- UPDATE TREASURY TRANSACTIONS TABLE تعديل جدول حركة النقدية *******************************
            if ($this->paid > 0)
            {
                // dd($this->invoice_type);
                $this->treasury_transation_table                            =  new TreasuryTransation();

                $this->treasury_transation_table->treasury_id               = $this->active_treasury->id;
                $this->treasury_transation_table->cash_amount               = $this->paid * (-1);
                $this->treasury_transation_table->shift_id                  = $this->get_active_shift->auto_serial;
                $this->treasury_transation_table->isal_number               = $this->active_treasury->last_recept_pay + 1;
                if ($this->paid > 0)
                {
                    $this->treasury_transation_table->moveType_id               = $this->moveType->id;    // مؤقتا لحين تعديلها
                }

                $this->treasury_transation_table->invoice_type_accounts     = 'purchases';
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

                if ($this->order->invoice_type == "0")      // اذا كان نوع الفاتورة كاش
                {
                    $this->treasury_transation_table->cash_for_account          = 0;
                } else
                {
                    $this->treasury_transation_table->cash_for_account          = $this->unpaid * (-1);
                }
                $this->treasury_transation_table->save();




            // 3  -- UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
                $this->treasury_table                       =  $this->active_treasury;
                $this->treasury_table->last_recept_pay      = $this->treasury_transation_table->isal_number;
                //  dd($this->active_treasury);
                $this->treasury_table->save();
            }



        // 4 -- UPDATE ITEM BATCHES TABLE تعديل جدول بتشات الصنف *******************************

            $this->order_items = $this->order->order_detailes;

            foreach ($this->order_items as $order_detail)
            {
                // ❖ نحسب الكمية قبل الإضافة
                $order_detail->qty_before_all_stores = ItemBatch::where('item_code', $order_detail->item_code)->sum('qty');
                $order_detail->qty_before_this_store = ItemBatch::where('item_code', $order_detail->item_code)->where('store_id', $this->order->store_id)->sum('qty');

                $get_item = Item::where('item_code', $order_detail->item_code)->with(['itemUnit' => function($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class);

                }])->first();
                $get_parent_item_unit = $get_item->itemUnit->name;

                // ❖ نحدد النوع (استهلاكي / مخزني)
                $is_item_found = ItemBatch::where('store_id', $this->order->store_id)
                    ->where('item_code', $order_detail->item_code)
                    ->where('item_cost_price', $order_detail->unit_price)
                    ->where('item_unit_id', $order_detail->item_units_id);

                if ($order_detail->item_type == '1')
                { // استهلاكي
                    $is_item_found = $is_item_found
                        // ->where('production_date', $order_detail->production_date)
                        ->where('expire_date', $order_detail->expire_date)
                        ->first();
                } else {
                    $is_item_found = $is_item_found->first();
                }

                // ❖ لو الصنف موجود مسبقًا
                if (!empty($is_item_found))
                {
                    $converted_qty = $order_detail->is_master == 'master'
                        ? $order_detail->qty
                        : $order_detail->qty / $order_detail->item->qty_sub_item_unit;

                    $is_item_found->qty           += $converted_qty;
                    // $is_item_found->deduction     += $converted_qty;    /////////
                    $is_item_found->deduction     += 0;    /////////
                    $is_item_found->total         += $order_detail->total;
                    $is_item_found->updated_by     = auth()->user()->id;

                    $is_item_found->save();
                } else
                {
                    $converted_qty = $order_detail->is_master == 'master'
                        ? $order_detail->qty
                        : $order_detail->qty / $order_detail->item->qty_sub_item_unit;

                    $this->item_batch                  = new ItemBatch();
                    $this->item_batch->qty             = $converted_qty;
                    $this->item_batch->deduction       += 0;
                    $this->item_batch->store_id        = $this->order->store_id;
                    $this->item_batch->item_code       = $order_detail->item_code;
                    $this->item_batch->item_unit_id    = $order_detail->item_units_id;
                    $this->item_batch->item_cost_price = $order_detail->unit_price;
                    $this->item_batch->total           = $order_detail->total;

                    if ($order_detail->item_type == '1')
                    {
                        $this->item_batch->production_date = $order_detail->production_date;
                        $this->item_batch->expire_date     = $order_detail->expire_date;
                    }

                    $this->item_batch->auto_serial   = get_last_autoSerial_invoices(ItemBatch::class, 'auto_serial');
                    $this->item_batch->company_code  = auth()->user()->company_code;
                    $this->item_batch->created_by    = auth()->user()->id;
                    $this->item_batch->updated_by    = auth()->user()->id;

                    $this->item_batch->save();

                }

                // 5 -- UPDATE ITEM CARD MOVEMENTS TABLE تعديل جدول حركة الصنف *******************************
                    // ❖ نحسب الكمية بعد الإضافة
                    $qty_after_all_stores = ItemBatch::where('item_code', $order_detail->item_code)->sum('qty');
                    $qty_after_this_store = ItemBatch::where('item_code', $order_detail->item_code)
                        ->where('store_id', $this->order->store_id)
                        ->sum('qty');

                    // ❖ نجهز حركة الصنف
                    $create_item_movement = new ItemCardMovement();
                    $create_item_movement->item_code                        = $order_detail->item_code;
                    $create_item_movement->store_id                         = $this->order->store_id;
                    $create_item_movement->item_card_movements_category_id  = $this->itemCardMoveCategory->id;
                    $create_item_movement->item_card_movements_type_id      = $this->itemCardMoveType->id;
                    if (!empty($is_item_found))
                    {
                        $create_item_movement->item_batch_id                    = $is_item_found->auto_serial;

                    }else
                    {
                        $create_item_movement->item_batch_id                    = $this->item_batch->auto_serial;
                    }
                    $create_item_movement->purchase_order_id                = $this->order->auto_serial;
                    $create_item_movement->purchase_orderdetiles__id        = $order_detail->id;
                    $create_item_movement->qty_before_movement              = $order_detail->qty_before_all_stores;
                    $create_item_movement->qty_after_movement               = $qty_after_all_stores;
                    $create_item_movement->qty_before_movement_in_store     = $order_detail->qty_before_this_store;
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

                if ($order_detail->is_master == 'master')
                {
                    // لو السعر جاي بوحدة الصنف الأساسية
                    $get_item2->item_cost_price     = $order_detail->unit_price;
                    $get_item2->sub_item_cost_price = sub_item_cost_price_from_parent($order_detail->unit_price, $get_item2->qty_sub_item_unit);

                } else
                {
                    // لو السعر جاي بوحدة الصنف الفرعية
                    $get_item2->item_cost_price     = parent_item_cost_price_from_sub_price($order_detail->unit_price, $get_item2->qty_sub_item_unit);
                    $get_item2->sub_item_cost_price = $order_detail->unit_price;
                }

                $get_item2->total_qty_for_parent    = floor($qty_after_all_stores_parent);
                $get_item2->total_qty_for_sub_items = $qty_after_all_stores_sub;
                $get_item2->sub_item_qty            = $qty_value_fraction_only;
                $get_item2->save();

            }


        // 9 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('اعتماد فاتورة مشتريات ', "اعتماد فاتورة مشتريات {$this->order->supplier->name}", 'PurchaseOrder', $this->order->auto_serial,auth()->user()->id);
                //  dd($this->itemCardMoveCategory);
        DB::commit();

        // Dispatch events
        $this->dispatch('purchaseOrdersApproveMS');
        $this->dispatch('aproveModalToggle');
        $this->dispatch('refreshData')->to(Data::class);



        DB::rollBack();
    }



    public function render()
    {
        return view('back-end.purchase-orders.aprove');
    }
}

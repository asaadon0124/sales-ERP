<?php

namespace App\Livewire\BackEnd\SalesOrderReturns;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Account;
use App\Models\Servant;
use Livewire\Component;
use App\Models\MoveType;
use App\Models\ItemBatch;
use App\Models\SalesOrder;
use App\Models\ItemCardMovement;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementType;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Models\ItemCardMovementCategory;
use App\Services\GetAverageItemPriceService;
use App\Services\GetAverageSubItemPriceService;
use App\Livewire\BackEnd\SalesOrderReturns\Data;

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
    public $customer_account;
    public $servant_account;

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

    public $treasury_transation_table_customer;
    public $treasury_transation_table_servant;
    public $treasury_table;
    public $moveType;
    public $servant;
    public $total_cost_after_deduct_customer_balance = 0;



      protected $listeners = ['purchaseOrderRetuensApprove'];



    public function purchaseOrderRetuensApprove($id)
    {
        $this->order                            = SalesOrder::where('auto_serial', $id)->with('order_detailes')->first();
        $this->orderID                          = $id;
        $this->moveType                         = MoveType::select('id','name','company_code')->where('company_code',auth()->user()->company_code)->where('name','صرف نظير مرتجع مبيعات')->first();
        $this->itemCardMoveType                 = ItemCardMovementType::where('company_code',auth()->user()->company_code)->where('name','اضافة الي المخزن')->first();
        $this->itemCardMoveCategory             = ItemCardMovementCategory::where('company_code',auth()->user()->company_code)->where('name','اضافة كمية من الصنف في المخزن نظير مرتجع فاتورة مبيعات')->first();



        $this->total_cost_before_all            = $this->order->total_cost_before_all;
        $this->total_before_discount            = $this->order->total_cost_before_all;
        $this->total_cost                       = $this->order->total_cost_before_all;
        $this->company_code                     = $this->order->company_code;
        $this->auto_serial                      = $this->order->auto_serial;
        $this->invoice_type                     = $this->order->InvoiceType();
        $this->paid                             = $this->total_cost;


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
        $this->paid                     = $this->total_cost;
    }



    public function change_discount_type($value)
    {

        if ($value != '')
        {
            $this->discount_type                = $value;
        }else
        {

            $this->discount_amount            = 0;
            $this->discount_percent           = 0;
            $this->total_cost       = $this->total_cost_before_all + $this->tax_value;
        }

    }



    public function change_discount_percent($value)
    {
        $subtotal                   = $this->total_cost_before_all + $this->tax_value;

        if ($subtotal > 0 && is_numeric($value))
        {
            $this->discount_amount  = round(($subtotal * $value) / 100, 2);
            $this->discount_percent = round($value, 2); // تأكيد تنسيقه برضو
            $this->total_cost       = round($subtotal - $this->discount_amount, 2);
            $this->paid             = $this->total_cost;
        }
    }


    public function change_discount_amount($value)
    {
        $subtotal                       = $this->total_cost_before_all + $this->tax_value;

        if ($subtotal > 0 && is_numeric($value)) {
            $this->discount_amount      = round($value, 2);
            $this->discount_percent     = round(($this->discount_amount / $subtotal) * 100, 2);
            $this->total_cost           = round($subtotal - $this->discount_amount, 2);
            $this->paid                 = $this->total_cost;
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
            ];
    }



    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('اعتماد فاتورة مرتجع المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية اعتماد فاتورة مرتجع المبيعات ');
        }


        // 1 - VALIDATIONS *******************************************************************************
            $this->validate($this->rules(), $this->messages());

            if ($this->paid != $this->total_cost && $this->order->invoice_type == '0')
            {
                $this->addError('paid', 'المبلغ المدفوع غير صحيح.');
                return;
            }

            if ($this->order->invoice_type == '1' && $this->unpaid == 0)
            {
                $this->addError('unpaid', 'المبلغ الغير مدفوع غير صحيح.');
                return;
            }


            if ($this->treasury_balance < $this->paid)
            {
                $this->addError('treasury_balance', 'رصيد الخزنة لا يكفي لصرف المبلغ المطلوب.');
                return;
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

            } elseif ($this->discount_type == 0)
            {
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

            DB::beginTransaction();

            $this->customer_account = Account::where('account_number',$this->order->customer_account_number)->with('account_customer2')->first();
            $this->servant  = Servant::where('servant_code',$this->order->servant_code)->with('account_customer')->first();


        // 2  -- UPDATE PURSHACES ORDER TABLE تعديل جدول الفواتير *******************************
            $this->order->paid                          = $this->paid * (-1);
            $this->order->unpaid                        = $this->unpaid;
            $this->order->total_cost                    = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;
            // if ($this->order->invoice_type == '0')  // كاش
            // {
            //     $this->order->mony_for_account              = 0;
            // } else
            // {
            //     $this->order->mony_for_account              = $this->unpaid * (-1);
            // }


            $this->order->approve                       = '1';
            $this->order->discount_type                 = $this->discount_type;
            $this->order->auto_serial_servant_invoice   = get_last_autoSerial(Servant::class, 'auto_serial');
            $this->order->total_cost_before_all         = $this->total_cost_before_all;
            $this->order->total_before_discount         = $this->total_before_discount;
            $this->order->customer_balance_before       = $this->customer_account->current_balance;
            $this->order->discount_percent              = $this->discount_percent;
            $this->order->discount_amount               = $this->discount_amount;
            $this->order->tax_percent                   = $this->tax_percent;
            $this->order->tax_value                     = $this->tax_value;
            $this->order->updated_by                    = auth()->user()->id;
            $this->order->approved_by                   = auth()->user()->id;

            $this->order->save();


            // dd($this->customer_account->current_balance);
            // A - نجيب باقي قيمة الفاتورة بعد الخصم من رصيد العميل
            $debt = $this->customer_account->current_balance;
            $this->total_cost_after_deduct_customer_balance = $this->order->total_cost + $debt;

            // dd($debt);




        // 3 - *****************************************************************************************

            // العميل له فلوس عندنا
            if ($this->total_cost_after_deduct_customer_balance > 0)      // لو لسة باقي فلوس في الفاتورة بعد الخصم من رصيد العميل
            {
                if ($this->order->invoice_type == '0' && $this->unpaid == 0)      // لو نوع الفاتورة كاش
                {
                    // B - نعمل ايصال تحصيل الفلوس لحساب العميل
                    // CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
                        $this->treasury_transation_table_customer                            =  new TreasuryTransation();

                        $this->treasury_transation_table_customer->treasury_id               = $this->active_treasury->id;
                        $this->treasury_transation_table_customer->account_balance_before    = $this->customer_account->current_balance;
                        // $this->treasury_transation_table_customer->account_balance_servant_before    = $this->servant->current_balance;
                        $this->treasury_transation_table_customer->cash_amount               = 0;
                        $this->treasury_transation_table_customer->cash_for_account          = $debt * (-1);
                        $this->treasury_transation_table_customer->shift_id                  = $this->get_active_shift->auto_serial;

                        $this->treasury_transation_table_customer->isal_number               = $this->active_treasury->last_recept_recive + 1;
                        $this->treasury_transation_table_customer->moveType_id               = $this->moveType->id;    // مؤقتا لحين تعديلها
                        $this->treasury_transation_table_customer->account_type              = 'customers';
                        $this->treasury_transation_table_customer->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                        $this->treasury_transation_table_customer->account_id                = $this->customer_account->account_number;

                        $this->treasury_transation_table_customer->move_date                 = Carbon::today()->toDateString();
                        $this->treasury_transation_table_customer->cash_source_type          = 'account';
                        $this->treasury_transation_table_customer->is_approve                = 'approve';
                        $this->treasury_transation_table_customer->company_code              = auth()->user()->company_code;
                        $this->treasury_transation_table_customer->created_by                = auth()->user()->id;
                        $this->treasury_transation_table_customer->updated_by                = auth()->user()->id;
                        $this->treasury_transation_table_customer->invoice_type_accounts     = 'sales_returns';
                        $this->treasury_transation_table_customer->invoice_type              = $this->order->invoice_type;
                        $this->treasury_transation_table_customer->save();


                    // C - نعمل ايصال صرف الفلوس لحساب المندوب
                    // CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
                        $this->treasury_transation_table_servant                            =  new TreasuryTransation();

                        $this->treasury_transation_table_servant->treasury_id               = $this->active_treasury->id;
                        $this->treasury_transation_table_servant->account_balance_before    = $this->servant->current_balance;
                        $this->treasury_transation_table_servant->cash_amount               = $this->paid + $debt;
                        $this->treasury_transation_table_servant->cash_for_account          = 0;
                        $this->treasury_transation_table_servant->shift_id                  = $this->get_active_shift->auto_serial;

                        $this->treasury_transation_table_servant->isal_number               = $this->active_treasury->last_recept_pay + 1;
                        $this->treasury_transation_table_servant->moveType_id               = $this->moveType->id;
                        $this->treasury_transation_table_servant->account_type              = 'servants';
                        $this->treasury_transation_table_servant->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                        $this->treasury_transation_table_servant->account_id                = $this->customer_account->account_number;
                        $this->treasury_transation_table_servant->servant_cash_amount       = $this->paid + $debt;
                        $this->treasury_transation_table_servant->servant_account_id       = $this->servant->account_number;
                        $this->treasury_transation_table_servant->move_date                 = Carbon::today()->toDateString();
                        $this->treasury_transation_table_servant->cash_source_type          = 'account';
                        $this->treasury_transation_table_servant->is_approve                = 'approve';
                        $this->treasury_transation_table_servant->company_code              = auth()->user()->company_code;
                        $this->treasury_transation_table_servant->created_by                = auth()->user()->id;
                        $this->treasury_transation_table_servant->updated_by                = auth()->user()->id;
                        $this->treasury_transation_table_servant->invoice_type_accounts     = 'sales_returns';
                        $this->treasury_transation_table_servant->invoice_type              = $this->order->invoice_type;
                        $this->treasury_transation_table_servant->save();


                    // D - نعدل حساب العميل
                    //  UPDATE ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعميل  *******************************
                        $this->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->customer_account->save();

                    // E - نعدل جدول العميل
                    //  UPDATE CUSTOMERS TABLE تعديل الجدول الخاص بالعميل  *******************************
                        $this->customer_account->account_customer2->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->customer_account->account_customer2->save();


                    // F - نعدل حساب المندوب
                     //  UPDATE ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************
                        $this->servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                        $this->servant->account_customer->save();


                    // G - نعدل جدول المندوب
                    //  UPDATE SERVANTS TABLE تعديل جدول الخاص بالمندوب  *******************************
                        $this->servant->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                        $this->servant->save();

                    // H - تعديل جدول حركة للعميل الخزن
                    // UPDATE TREASURY TRANSACTIONS TABLE  تعديل جدول حركة النقدية
                        $this->treasury_transation_table_customer->account_balance_after              = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->treasury_transation_table_customer->save();

                    // I - تعديل جدول حركة الخزن للمندوب
                    // UPDATE TREASURY TRANSACTIONS TABLE  تعديل جدول حركة النقدية
                        $this->treasury_transation_table_servant->account_balance_after              = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                        $this->treasury_transation_table_servant->save();

                    // J - تعديل جدول الخزن
                    // UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
                    $this->treasury_table                       =  $this->active_treasury;
                    $this->treasury_table->last_recept_pay      = $this->treasury_transation_table_servant->isal_number;
                    $this->treasury_table->last_recept_recive   = $this->treasury_transation_table_customer->isal_number;
                    $this->treasury_table->save();


                    // K - تعديل جدول الفاتورة
                    // UPDATE SALES ORDER TABLE ******************************************************************
                    $this->order->treasures_transactions_id         = $this->treasury_transation_table_customer->auto_serial;
                    $this->order->treasures_transactions__servant_id = $this->treasury_transation_table_servant->auto_serial;
                    $this->order->customer_balance_after            = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->order->save();


                    // dd($this->order);

                }elseif ($this->order->invoice_type == '1' && $this->paid != 0)      // لو نوع الفاتورة نص كاش
                {
                   // B - نعمل ايصال تحصيل الفلوس لحساب العميل
                    // CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
                        $this->treasury_transation_table_customer                            =  new TreasuryTransation();

                        $this->treasury_transation_table_customer->treasury_id               = $this->active_treasury->id;
                        $this->treasury_transation_table_customer->account_balance_before    = $this->customer_account->current_balance;
                        $this->treasury_transation_table_customer->cash_amount               = 0;
                        $this->treasury_transation_table_customer->cash_for_account          = $debt * (-1);
                        $this->treasury_transation_table_customer->shift_id                  = $this->get_active_shift->auto_serial;

                        $this->treasury_transation_table_customer->isal_number               = $this->active_treasury->last_recept_recive + 1;
                        $this->treasury_transation_table_customer->moveType_id               = $this->moveType->id;    // مؤقتا لحين تعديلها
                        $this->treasury_transation_table_customer->account_type              = 'customers';
                        $this->treasury_transation_table_customer->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                        $this->treasury_transation_table_customer->account_id                = $this->customer_account->account_number;
                        $this->treasury_transation_table_customer->move_date                 = Carbon::today()->toDateString();
                        $this->treasury_transation_table_customer->cash_source_type          = 'account';
                        $this->treasury_transation_table_customer->is_approve                = 'approve';
                        $this->treasury_transation_table_customer->company_code              = auth()->user()->company_code;
                        $this->treasury_transation_table_customer->created_by                = auth()->user()->id;
                        $this->treasury_transation_table_customer->updated_by                = auth()->user()->id;
                        $this->treasury_transation_table_customer->invoice_type_accounts     = 'sales_returns';
                        $this->treasury_transation_table_customer->invoice_type              = $this->order->invoice_type;
                        $this->treasury_transation_table_customer->save();


                    // C - نعمل ايصال صرف الفلوس لحساب المندوب
                    // CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
                        $this->treasury_transation_table_servant                            =  new TreasuryTransation();

                        $this->treasury_transation_table_servant->treasury_id               = $this->active_treasury->id;
                        $this->treasury_transation_table_servant->account_balance_before    = $this->servant->current_balance;
                        $this->treasury_transation_table_servant->cash_amount               = $this->paid + $debt;
                        $this->treasury_transation_table_servant->cash_for_account          = $this->unpaid;
                        $this->treasury_transation_table_servant->shift_id                  = $this->get_active_shift->auto_serial;

                        $this->treasury_transation_table_servant->isal_number               = $this->active_treasury->last_recept_pay + 1;
                        $this->treasury_transation_table_servant->moveType_id               = $this->moveType->id;
                        $this->treasury_transation_table_servant->account_type              = 'servants';
                        $this->treasury_transation_table_servant->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                        $this->treasury_transation_table_servant->account_id                = $this->customer_account->account_number;
                        $this->treasury_transation_table_servant->servant_cash_amount       = $this->paid + $debt;
                        $this->treasury_transation_table_servant->servant_account_id       = $this->servant->account_number;
                        $this->treasury_transation_table_servant->move_date                 = Carbon::today()->toDateString();
                        $this->treasury_transation_table_servant->cash_source_type          = 'account';
                        $this->treasury_transation_table_servant->is_approve                = 'approve';
                        $this->treasury_transation_table_servant->company_code              = auth()->user()->company_code;
                        $this->treasury_transation_table_servant->created_by                = auth()->user()->id;
                        $this->treasury_transation_table_servant->updated_by                = auth()->user()->id;
                        $this->treasury_transation_table_servant->invoice_type_accounts     = 'sales_returns';
                        $this->treasury_transation_table_servant->invoice_type              = $this->order->invoice_type;
                        $this->treasury_transation_table_servant->save();




                    // E - نعدل حساب العميل
                    //  UPDATE ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعميل  *******************************
                        $this->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->customer_account->save();


                    // F - نعدل جدول العميل
                    //  UPDATE CUSTOMERS TABLE تعديل الجدول الخاص بالعميل  *******************************
                        $this->customer_account->account_customer2->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->customer_account->account_customer2->save();


                    // G - نعدل حساب المندوب
                     //  UPDATE ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************
                        $this->servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                        $this->servant->account_customer->save();


                    // H - نعدل جدول المندوب
                    //  UPDATE SERVANTS TABLE تعديل جدول الخاص بالمندوب  *******************************
                        $this->servant->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                        $this->servant->save();



                    // I - تعديل جدول حركة للعميل الخزن
                    // UPDATE TREASURY TRANSACTIONS TABLE  تعديل جدول حركة النقدية
                        $this->treasury_transation_table_customer->account_balance_after              = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->treasury_transation_table_customer->save();


                    // J - تعديل جدول حركة الخزن للمندوب
                    // UPDATE TREASURY TRANSACTIONS TABLE  تعديل جدول حركة النقدية
                        $this->treasury_transation_table_servant->account_balance_after              = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                        $this->treasury_transation_table_servant->save();


                    // K - تعديل جدول الخزن
                    // UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
                    $this->treasury_table                       =  $this->active_treasury;
                    $this->treasury_table->last_recept_pay      = $this->treasury_transation_table_servant->isal_number;
                    $this->treasury_table->last_recept_recive   = $this->treasury_transation_table_customer->isal_number;
                    $this->treasury_table->save();



                    // D - تعديل جدول الفاتورة
                    // UPDATE SALES ORDER TABLE ******************************************************************
                    $this->order->treasures_transactions_id             = $this->treasury_transation_table_customer->auto_serial;
                    $this->order->treasures_transactions__servant_id    = $this->treasury_transation_table_servant->auto_serial;
                    $this->order->customer_balance_after                = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->order->mony_for_account                      = $this->unpaid;
                    $this->order->save();


                    // dd($this->order);
                }else                                       // لو نوع الفاتورة اجل
                {
                    // B - نعمل ايصال تحصيل الفلوس لحساب العميل
                    // CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
                        $this->treasury_transation_table_customer                            =  new TreasuryTransation();

                        $this->treasury_transation_table_customer->treasury_id               = $this->active_treasury->id;
                        $this->treasury_transation_table_customer->account_balance_before    = $this->customer_account->current_balance;
                        $this->treasury_transation_table_customer->cash_amount               = 0;
                        $this->treasury_transation_table_customer->cash_for_account          = $debt * (-1);
                        $this->treasury_transation_table_customer->shift_id                  = $this->get_active_shift->auto_serial;

                        $this->treasury_transation_table_customer->isal_number               = $this->active_treasury->last_recept_recive + 1;
                        $this->treasury_transation_table_customer->moveType_id               = $this->moveType->id;    // مؤقتا لحين تعديلها
                        $this->treasury_transation_table_customer->account_type              = 'customers';
                        $this->treasury_transation_table_customer->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                        $this->treasury_transation_table_customer->account_id                = $this->customer_account->account_number;
                        $this->treasury_transation_table_customer->move_date                 = Carbon::today()->toDateString();
                        $this->treasury_transation_table_customer->cash_source_type          = 'account';
                        $this->treasury_transation_table_customer->is_approve                = 'approve';
                        $this->treasury_transation_table_customer->company_code              = auth()->user()->company_code;
                        $this->treasury_transation_table_customer->created_by                = auth()->user()->id;
                        $this->treasury_transation_table_customer->updated_by                = auth()->user()->id;
                        $this->treasury_transation_table_customer->invoice_type_accounts     = 'sales_returns';
                        $this->treasury_transation_table_customer->invoice_type              = $this->order->invoice_type;
                        $this->treasury_transation_table_customer->save();



                    // D - نعدل حساب العميل
                    //  UPDATE ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعميل  *******************************
                        $this->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->customer_account->save();


                    // E - نعدل جدول العميل
                    //  UPDATE CUSTOMERS TABLE تعديل الجدول الخاص بالعميل  *******************************
                        $this->customer_account->account_customer2->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->customer_account->account_customer2->save();


                    // F - تعديل جدول حركة للعميل الخزن
                    // UPDATE TREASURY TRANSACTIONS TABLE  تعديل جدول حركة النقدية
                        $this->treasury_transation_table_customer->account_balance_after              = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->treasury_transation_table_customer->save();


                    // G - تعديل جدول الخزن
                    // UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
                    $this->treasury_table                       =  $this->active_treasury;
                    $this->treasury_table->last_recept_recive   = $this->treasury_transation_table_customer->isal_number;
                    $this->treasury_table->save();


                     // C - تعديل جدول الفاتورة
                    // UPDATE SALES ORDER TABLE ******************************************************************
                    $this->order->treasures_transactions_id             = $this->treasury_transation_table_customer->auto_serial;
                    $this->order->mony_for_account                      = $this->unpaid + $debt;
                    $this->order->customer_balance_after                = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->order->save();



                }

            }elseif($this->total_cost_after_deduct_customer_balance <= 0)   // لو مش باقي فلوس في الفاتورة بعد الخصم من رصيد العميل
            {
                if ($this->order->invoice_type == '1' )
                {
                    $this->addError('invoice_type', 'هذه الفاتورة يجب ان تكون كاش.');
                    return;
                }
                // B - نعمل ايصال تحصيل الفلوس لحساب العميل
                    // CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
                    $this->treasury_transation_table_customer                            =  new TreasuryTransation();

                    $this->treasury_transation_table_customer->treasury_id               = $this->active_treasury->id;
                    $this->treasury_transation_table_customer->account_balance_before    = $this->customer_account->current_balance;
                    // $this->treasury_transation_table_customer->account_balance_servant_before    = $this->servant->current_balance;
                    $this->treasury_transation_table_customer->cash_amount               = 0;
                    $this->treasury_transation_table_customer->cash_for_account          = $this->total_cost;
                    $this->treasury_transation_table_customer->shift_id                  = $this->get_active_shift->auto_serial;

                    $this->treasury_transation_table_customer->isal_number               = $this->active_treasury->last_recept_recive + 1;
                    $this->treasury_transation_table_customer->moveType_id               = $this->moveType->id;    // مؤقتا لحين تعديلها
                    $this->treasury_transation_table_customer->account_type              = 'customers';
                    $this->treasury_transation_table_customer->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                    $this->treasury_transation_table_customer->account_id                = $this->customer_account->account_number;

                    $this->treasury_transation_table_customer->move_date                 = Carbon::today()->toDateString();
                    $this->treasury_transation_table_customer->cash_source_type          = 'account';
                    $this->treasury_transation_table_customer->is_approve                = 'approve';
                    $this->treasury_transation_table_customer->company_code              = auth()->user()->company_code;
                    $this->treasury_transation_table_customer->created_by                = auth()->user()->id;
                    $this->treasury_transation_table_customer->updated_by                = auth()->user()->id;
                    $this->treasury_transation_table_customer->invoice_type_accounts     = 'sales_returns';
                    $this->treasury_transation_table_customer->invoice_type              = $this->order->invoice_type;
                    $this->treasury_transation_table_customer->save();


                // C - نعدل حساب العميل
                //  UPDATE ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعميل  *******************************
                    $this->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->customer_account->save();

                // D - نعدل جدول العميل
                //  UPDATE CUSTOMERS TABLE تعديل الجدول الخاص بالعميل  *******************************
                    $this->customer_account->account_customer2->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->customer_account->account_customer2->save();


                // E - تعديل جدول حركة للعميل الخزن
                // UPDATE TREASURY TRANSACTIONS TABLE  تعديل جدول حركة النقدية
                    $this->treasury_transation_table_customer->account_balance_after              = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->treasury_transation_table_customer->save();


                // F - تعديل جدول الخزن
                // UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
                    $this->treasury_table                       =  $this->active_treasury;
                    $this->treasury_table->last_recept_pay      = $this->treasury_transation_table_customer->isal_number;
                    $this->treasury_table->save();




                // dd($this->customer_account->current_balance);
                // dd($this->treasury_transation_table_customer->isal_number);
                // dd($this->treasury_table);


            }

                // 4 -- UPDATE ITEM BATCHES TABLE تعديل جدول بتشات الصنف *******************************

                $this->order_items = $this->order->order_detailes;


                foreach ($this->order_items as $order_detail)
                {
                    // ❖ نحسب الكمية قبل الإضافة
                    $qty_before_all_stores = ItemBatch::where('item_code', $order_detail->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total') ?? 0;
                    $qty_before_this_store = ItemBatch::where('item_code', $order_detail->item_code)->where('store_id', $order_detail->store_id)->selectRaw('SUM(qty - deduction) as total')->value('total') ?? 0;

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
                            // dd($is_item_found);
                    } else
                    {
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
                        $this->item_batch->store_id        = $order_detail->store_id;
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


                        // UPDATE SAES ORDER DETAILES TABLE
                        $order_detail->batch_id = $this->item_batch->auto_serial;
                        $order_detail->save();


                    }


                    // 5 -- UPDATE ITEM CARD MOVEMENTS TABLE تعديل جدول حركة الصنف *******************************
                        // ❖ نحسب الكمية بعد الإضافة
                        $qty_after_all_stores = ItemBatch::where('item_code', $order_detail->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');

                        $qty_after_this_store = ItemBatch::where('item_code', $order_detail->item_code)
                                    ->where('store_id', $order_detail->store_id)
                                    ->selectRaw('SUM(qty - deduction) as total')->value('total');

                        // ❖ نجهز حركة الصنف
                        $create_item_movement = new ItemCardMovement();
                        $create_item_movement->item_code                        = $order_detail->item_code;
                        $create_item_movement->store_id                         = $order_detail->store_id;
                        $create_item_movement->item_card_movements_category_id  = $this->itemCardMoveCategory->id;
                        $create_item_movement->item_card_movements_type_id      = $this->itemCardMoveType->id;
                        if (!empty($is_item_found))
                        {
                            $create_item_movement->item_batch_id                = $is_item_found->auto_serial;

                        }else
                        {
                            $create_item_movement->item_batch_id                = $this->item_batch->auto_serial;
                        }
                        $create_item_movement->sales_order_id                   = $this->order->auto_serial;
                        $create_item_movement->sales_orderdetiles__id           = $order_detail->id;
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




        // 6 -- UPDATE ITEMS TABLE تعديل جدول الخاص الاصناف  *******************************
            foreach ($this->order_items as $order_detail)
            {

                $get_item2                      = Item::where('item_code', $order_detail->item_code)->with('itemUnit')->first();
                $qty_after_all_stores_parent    = $qty_after_all_stores;
                $qty_after_all_stores_sub       = $qty_after_all_stores_parent * $get_item2->qty_sub_item_unit;
                $qty_fraction_only              = $qty_after_all_stores_parent - floor($qty_after_all_stores_parent); // نسبة الكسر في الوحدة الرئيسية
                $qty_value_fraction_only        = $qty_fraction_only * $get_item2->qty_sub_item_unit;

                if ($order_detail->is_master == 'master')   //   لو الوحدة الي مختارة اساسية
                {
                    $master_price_Service                                       = new GetAverageItemPriceService();
                    $new_master_cost_price                                      = number_format($master_price_Service->calculate($order_detail->item_code,$order_detail->item_units_id), 2, '.', '') != 0 ? number_format($master_price_Service->calculate($order_detail->item_code,$order_detail->item_units_id), 2, '.', '') : $get_item2->item_cost_price;
                    $new_sub_master_cost_price                                  = number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$get_item2->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$get_item2->qty_sub_item_unit), 2, '.', '') : $get_item2->sub_item_cost_price;
                    // dd($master_price_Service);

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
                $get_item2->total_qty_for_parent    = floor($qty_after_all_stores_parent);
                $get_item2->total_qty_for_sub_items = $qty_after_all_stores_sub;
                $get_item2->sub_item_qty            = $qty_value_fraction_only;
                $get_item2->save();
            }

        // 7 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('اعتماد فاتورة مرتجع المبيعات ', "اعتماد فاتورة مرتجع المبيعات {$this->order->customer->name}", 'SalesOrder', $this->order->auto_serial,auth()->user()->id);
        DB::commit();

        // Dispatch events
        $this->dispatch('salesOrdersApproveMS');
        $this->dispatch('aproveModalToggle');
        $this->dispatch('refreshData')->to(Data::class);



        DB::rollBack();
    }

    public function render()
    {
        return view('back-end.sales-order-returns.aprove');
    }
}

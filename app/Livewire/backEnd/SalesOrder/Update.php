<?php

namespace App\Livewire\BackEnd\SalesOrder;

use App\Models\Servant;
use Livewire\Component;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\MaterialType;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Livewire\BackEnd\SalesOrder\Data;

class Update extends Component
{


     // بيانات الفاتورة
    public $order_date;
    public $is_fixed_customer;
    public $items_type;
    public $sales_item_type;
    public $matrial_types_id;
    public $invoice_type;
    public $auto_serial;
    // public $order_number;
    public $customer_account_number;
    public $customer_code;
    public $servant_code;
    public $tax_percent                 = 0;
    public $tax_value                   = 0;
    public $total_cost_before_all;
    public $total_before_discount;
    public $discount_type;
    public $discount_percent            = 0;
    public $discount_amount             = 0;
    public $total_cost;
    public $paid = 0;
    public $unpaid = 0;
    public $notes;

    // بيانات تفاصيل الفاتور
    public $item_code;
    public $item_units_id;
    public $qty;
    public $unit_price;
    public $total;
    public $is_bouns;
    public $sales_item_type_detailes;
    public $store_value;
    public $store_id;
    public $batch_id;

    // بيانات لعرض جدول تفاصيل الفاتورة قبل الحفظ
    public $SalesItemType_name;
    public $item_name;
    public $itemUnit_name;


    // المتغيرات
    public $moveType;
    public $customers;
    public $servants;
    public $lastOrder;
    public $select_store;
    public $store_qty;
    public $item_is_change;
    public $items;
    public $items_detailes;
    public $item_selected_detailes;
    public $check_itemUnit_type;
    public $reservedQtyMaster;
    public $allBatches;
    public $stores;
    public $order;
    public $get_customer;
    public $get_servant;
    public $customer_account;
    public $servant_account;
    public $matrial_types;
    public $order_detailes;
    public $new_item_batches;
    public $new_item_card_movements;
    public $treasury_transation_table;
    public $get_active_shift;
    public $active_treasury;
    public $treasury_table;
    public $treasury_balance;
    public $customer_balance    = 0;
    public $servant_balance     = 0;
    public $itemCardMoveType;
    public $itemCardMoveCategory;

    public $orderId;
    public $old_customer;
    public $old_customer_account;
    public $old_servant;
    public $old_servant_account;


    protected $listeners = ['salesOrderUpdate', 'supplierChanged'];


    public function salesOrderUpdate($id)
    {

        $this->order            = SalesOrder::where('auto_serial',$id)->first();
        $this->orderId          = $id;

        $this->customers        = Customer::select('name', 'customer_code', 'id')->where('status','active')->get();
        $this->servants         = Servant::select('name', 'servant_code', 'id')->where('status','active')->get();
        $this->lastOrder        = get_last_autoSerial_invoices(SalesOrder::class, 'auto_serial');
        $this->get_active_shift = Active_shift();
        $this->matrial_types    = MaterialType::all();

        $this->order_date                   = $this->order->order_date;
        $this->matrial_types_id             = (int) $this->order->matrial_types_id;
        $this->customer_code                = $this->order->customer_code;
        $this->servant_code                 = $this->order->servant_code;
        $this->customer_balance             = $this->order->customer->current_balance;
        $this->servant_balance              = $this->order->servant->current_balance;
        $this->notes                        = $this->order->notes;
        $this->treasury_balance             = Treasry_balance($this->get_active_shift);
        $this->total_cost_before_all        = $this->order->total_cost_before_all;
        $this->total_before_discount        = $this->order->total_before_discount;
        $this->total_cost                   = $this->order->total_cost;
        $this->invoice_type                 = $this->order->invoice_type;
        $this->discount_type                = $this->order->discount_type;
        $this->tax_percent                  = $this->order->tax_percent;
        $this->tax_value                    = $this->order->tax_value;
        $this->discount_percent             = $this->order->discount_percent;
        $this->discount_amount              = $this->order->discount_amount;
        $this->paid                         = $this->order->paid;
        $this->unpaid                       = $this->order->unpaid;
        $this->old_customer             = Customer::where('customer_code',$this->order->customer_code)->with('customer_account')->first();
        $this->old_customer_account     = $this->old_customer->customer_account;

        $this->old_servant             = Servant::where('servant_code',$this->order->servant_code)->with('account_customer')->first();
        $this->old_servant_account     = $this->old_servant->account_customer;


// dd($this->old_customer);

        if ($this->orderId)
        {
            $this->active_treasury  = $this->get_active_shift->treasury;
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount');
        }
        // show Create modal
        $this->dispatch('updateModalToggle');
    }



     // لما اختار عميل
    public function customerChanged($newContent)
    {
        $this->resetErrorBag('customer_code'); // مسح الخطأ المتعلق بالعميل
        $this->customer_code = $newContent;
        $this->get_customer = Customer::where('customer_code', $newContent)->with('customer_account')->where('status','active')->first();

        if ($this->get_customer)
        {
            $this->customer_account = $this->get_customer->customer_account;
            $this->customer_balance = $this->get_customer->current_balance;

        } else
        {
            $this->customer_account = null;
            $this->customer_balance = 0;
            $this->addError('customer_code', 'العميل غير موجود.');
        }
            // dd($this->get_customer);

    }

    // لما اختار مندوب
    public function servantChanged($newContent)             // SERVANT CODE
    {
        $this->resetErrorBag('servant_code'); // 👈 يمسح الخطأ المتعلق بالمندوب
        $this->servant_code     = $newContent;
        $this->get_servant      = Servant::where('servant_code',$newContent)->where('status','active')->with('account_customer')->first();


        if ($this->get_servant)
        {
            $this->servant_account = $this->get_servant->servant_account;
            $this->servant_balance = $this->get_servant->current_balance;

        } else
        {
            $this->servant_account = null;
            $this->servant_balance = 0;
            $this->addError('servant_code', 'المندوب غير موجود.');
        }
    }



    // لما اغير نوع الفاتورة من كاش لاجل و العكس
    public function change_invoice_type($newContent)        // INVOICE TYPE
    {
        $this->invoice_type                 = $newContent;


        $this->total_cost                   = $this->order->total_cost;
        $this->resetErrorBag('invoice_type'); // ← هذا السطر يزيل الخطأ فوريًا

         if ($this->invoice_type == '0')
            {
                $this->paid                         = ($this->total_cost + $this->tax_value) - $this->discount_amount;
                // dd($this->paid);
                $this->unpaid                       = 0;
            }else
            {

                $this->unpaid                        = ($this->total_cost + $this->tax_value) - $this->discount_amount;
                $this->paid                         = 0;
            }

        $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;

    }


      // عند تغير نسبة الضريبة
    public function change_tax_percent($value)
    {
        // dd($this->customer_balance);
        if (!empty($value) || $value == 0)
        {
            $this->tax_value                        = ($this->total_cost_before_all * $value) / 100;
            $this->total_before_discount            = $this->total_cost_before_all + $this->tax_value;
             $this->discount_amount                 = round(($this->total_before_discount * $this->discount_percent) / 100, 2);
            $this->total_cost                       = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;
             if ($this->invoice_type == '0')
            {
                $this->paid                         = $this->total_cost;
                $this->unpaid                       = 0;
                if (session()->has('order'))
                {
                    $this->order[0]['customer_balance']         = $this->get_customer->customer_balance;
                    $this->order[0]['tax_percent']              = $value;
                    $this->order[0]['paid']                     = $this->total_cost;
                    $this->order[0]['total_before_discount']    = $this->total_before_discount;
                    $this->order[0]['total_cost']               = $this->total_cost;
                    $this->order[0]['unpaid']                   = 0;
                    session()->put('order', $this->order);
                }
            }else
            {
                $this->unpaid                       = $this->total_cost;
                $this->paid                         = 0;
                if (session()->has('order'))
                {
                    // $this->order[0]['customer_balance'] = $this->get_customer->customer_balance + $this->total_cost;
                    $this->order[0]['tax_percent']              = $value;
                    $this->order[0]['paid']                     = 0;
                    $this->order[0]['total_before_discount']    = $this->total_before_discount;
                    $this->order[0]['total_cost']               = $this->total_cost;
                    $this->order[0]['unpaid']                   = $this->total_cost;
                    session()->put('order', $this->order);
                    // dd($this->order[0]);
                }
            }
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
            // $this->customer_balance = $this->get_customer->current_balance +  session('order')[0]['unpaid'];
        }else
        {
            $this->tax_percent = 0;
        }

    }




    // عند تغير  نوع الخصم
    public function change_discount_type($value)
    {
        if ($value !== null && $value !== '')
        {
            // dd($value);
            $this->discount_type                        = $value;
            $this->unpaid                               = $this->total_cost;
            $this->order->customer_balance              = $this->customer_balance;

            $this->treasury_balance                     = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
        }else
        {
            $this->discount_type    = '0';
            $this->discount_percent = 0;
            $this->discount_amount  = 0;
            $this->total_cost       = $this->total_before_discount;
            if ($this->invoice_type == '0')
            {
                $this->paid             = $this->total_before_discount;
                $this->unpaid           = 0;
            }else
            {
                $this->paid             = 0;
                $this->unpaid           = $this->total_before_discount;
            }

        }
    }


     // عند تغير نسبة الخصم
    public function change_discount_percent($value)
    {
        $subtotal                   = $this->total_cost_before_all + $this->tax_value;


        if ($subtotal > 0 && is_numeric($value))
        {

            $this->discount_amount  = round(($subtotal * $value) / 100, 2);
            $this->discount_percent = round($value, 2); // تأكيد تنسيقه برضو
            $this->total_cost       = round($subtotal - $this->discount_amount, 2);



             if ($this->invoice_type == '0')
            {
                $this->paid                         = $this->total_cost;
                $this->unpaid                       = 0;

            }else
            {
                $this->unpaid                       = $this->total_cost;
                $this->paid                         = 0;



            }
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
        }
    }


    // عند تغير قيمة الخصم
    public function change_discount_amount($value)
    {
        $subtotal                       = $this->total_cost_before_all + $this->tax_value;
        if ($subtotal > 0 && is_numeric($value))
        {
            $this->discount_amount      = round($value, 2);
            $this->discount_percent     = round(($this->discount_amount / $subtotal) * 100, 2);
            $this->total_cost           = round($subtotal - $this->discount_amount, 2);

            if ($this->invoice_type == '0')
            {
                $this->paid                         = $this->total_cost;
                $this->unpaid                       = 0;

            }else
            {
                $this->unpaid                       = $this->total_cost;
                $this->paid                         = 0;


            }
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
        }
    }


  // عند تغير المبلغ المدفوع
    public function change_paid($value)
    {
        $this->unpaid                    = $this->total_cost - $value;
        $this->treasury_balance          = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
    }



    // عند تغير المبلغ الغير المدفوع
    public function change_unpaid($value)
    {
       $this->paid                  = $this->total_cost - $value;
       $this->treasury_balance      = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
    }



     public function submit_rules()
    {
        return
        [
            'order_date'                => 'required|date',
            // 'is_fixed_customer'         => 'required|in:0,1',
            'invoice_type'              => 'required|in:0,1',
            'customer_code'             => 'required|exists:customers,customer_code',
            'servant_code'              => 'required|exists:servants,servant_code',
            // 'total_cost_before_all'     => 'required|min:1',
            'tax_percent'               => 'required|numeric|min:0|max:100',
            'tax_value'                 =>
            [
                'required_if:tax_percent,"!=",0',  // تعديل المقارنة إلى صيغة صحيحة
                'numeric',
                function ($attribute, $value, $fail)
                {
                    if ($value > $this->total_cost_before_all)
                    {
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


                'order_date.required'                           => 'تاريخ شراء الفاتورة مطلوب.',
                'order_date.date'                               => 'هذا الحقل يج ان يكون من نوع تاريخ.',
                // 'is_fixed_customer.required'                    => 'نوع العميل مطلوب',
                // 'is_fixed_customer.in'                          => 'قيمة الحقل يجب ان تكون ب نعم او لا',

                'invoice_type.required'                         => 'نوع الفاتورة مطلوب',
                'invoice_type.in'                               => 'قيمة الحقل يجب ان تكون ب كاش او اجل',
                'customer_code.required'                        => 'اسم العميل مطلوب.',
                'customer_code.exists'                          => 'هذا العميل غير موجود .',
                'servant_code.required'                        => 'اسم المندوب مطلوب.',
                'servant_code.exists'                          => 'هذا المندوب غير موجود .',
                'tax_percent.required'          => 'نسبة الضريبة مطلوبة.',
                'tax_percent.numeric'           => 'نسبة الضريبة يجب أن تكون رقماً.',
                'tax_percent.min'               => 'نسبة الضريبة لا يمكن أن تكون أقل من 0.',
                'tax_percent.max'               => 'نسبة الضريبة لا يمكن أن تزيد عن 100%.',
                'tax_value.required_if'         => 'قيمة الضريبة مطلوبة.',
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
        if (!auth()->user()->can('تعديل فاتورة المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية تعديل فاتورة المبيعات ');
        }


        $this->validate($this->submit_rules(), $this->messages());
        $this->treasury_table   =  $this->active_treasury;
        try
        {



            // 1 - START VALIDATIONS *************************************************************************

                 // لو مش مختار عميل
                if ($this->customer_account == null || $this->customer_account == '')
                {
                    $this->addError('customer_code', 'يجب اختيار عميل للفاتورة.');
                    return;
                }



                // لو الفاتورة اجل و المدفوع  = اجمالي الفاتورة
                if ($this->invoice_type == 1 && $this->customer_account != '' && $this->paid == $this->total_cost)
                {
                    $this->addError('paid', 'يجب ان يكون المبلغ المدفوع اقل من قيمة الفاتورة.');
                    return;
                }




                 if (($this->paid + $this->unpaid) > $this->total_cost || $this->paid < 0 || $this->unpaid < 0)
                {
                    $this->addError('paid', 'الاجمالي لا يساوي اجمالي الفاتورة.');
                    return;
                }





                // التاكد من رصيد الخزنة
                if ($this->treasury_balance !=  (TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid))
                {
                    $this->addError('treasury_balance', 'رصيد الخزنة غير صحيح.');
                    return;
                }


                // dd($this->qty);
                // // التاكد من ان الكمية متاحة في المخزن
                // if ($this->store_qty == 0 || $this->store_qty < $this->qty)
                // {
                //     $this->addError('qty', 'الكمية المباعة غير متاحة في المخزن.');
                //     return;
                // }


                // التاكد من رصيد العميل
                if ($this->customer_balance !=  $this->get_customer->current_balance)
                {
                    $this->addError('customer_balance', 'رصيد العميل غير صحيح.');
                    return;
                }

                DB::beginTransaction();


            // 2 - UPDATE SALES ORDER  تعديل جدول فاتورة المبيعات

                $this->order->order_date                    = $this->order_date;
                $this->order->matrial_types_id              = $this->matrial_types_id;
                $this->order->customer_code                 = $this->customer_code;
                $this->order->servant_code                  = $this->servant_code;
                // $this->order->is_fixed_customer             = $this->is_fixed_customer;
                $this->order->total_cost_before_all         = $this->total_cost_before_all;
                $this->order->discount_type                 = $this->discount_type;
                $this->order->discount_percent              = $this->discount_percent;
                $this->order->discount_amount               = $this->discount_amount;
                $this->order->total_before_discount         = $this->total_before_discount;
                $this->order->tax_percent                   = $this->tax_percent;
                $this->order->tax_value                     = $this->tax_value;
                $this->order->total_cost                    = $this->total_cost;
                $this->order->invoice_type                  = $this->invoice_type;

                if ($this->invoice_type == '0')
                {
                    $this->order->mony_for_account          = $this->paid;
                }else
                {
                    $this->order->mony_for_account          = $this->unpaid * (-1);
                }


                $this->order->paid                          = $this->paid;
                $this->order->approve                       = '1';
                $this->order->unpaid                        = $this->unpaid;
                $this->order->customer_account_number       = $this->customer_account->account_number;
                $this->order->customer_balance_before       = $this->customer_account->current_balance;
                $this->order->notes                         = $this->notes;
                $this->order->updated_by                    = auth()->user()->id;
                $this->order->save();



            // 3  -- UPDATE TREASURY TRANSACTIONS TABLE تعديل جدول حركة النقدية *******************************
                if ($this->paid > 0)
                {
                    $this->treasury_transation_table                            =  new TreasuryTransation();

                    $this->treasury_transation_table->cash_amount               = $this->paid;

                    if ($this->order->invoice_type == '0')      // اذا كان نوع الفاتورة كاش
                    {
                        $this->treasury_transation_table->cash_for_account      = 0;
                    } else
                    {
                        $this->treasury_transation_table->cash_for_account      = $this->unpaid *(-1);
                    }

                    $this->treasury_transation_table->account_id                = $this->customer_account->account_number;
                    $this->treasury_transation_table->servant_cash_amount       = $this->paid * (-1);
                    $this->treasury_transation_table->servant_account_id       = $this->get_servant->account_number;

                    $this->treasury_transation_table->updated_by                = auth()->user()->id;
                    $this->treasury_transation_table->invoice_type              = $this->invoice_type;

                    $this->treasury_transation_table->save();


            // 4  -- UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
                    $this->treasury_table                       =  $this->active_treasury;
                    $this->treasury_table->last_recept_recive   = $this->treasury_transation_table->isal_number +1;
                    $this->treasury_table->save();

                    $this->treasury_balance = $this->treasury_balance;

            // 5  -- UPDATE CUSTOMER TABLE تعديل جدول العملاء *******************************
                    if ($this->old_customer->customer_code != $this->get_customer->customer_code)
                    {
                        $this->old_customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->old_customer->account_number,'customer','account_customer2');
                        $this->old_customer->save();

                        $this->get_customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->get_customer->save();
                    } else
                    {
                        $this->get_customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                        $this->get_customer->save();
                    }



            // 6 -- UPDATE CUSTOMER ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعملاء  *******************************

                if ($this->old_customer->customer_code != $this->get_customer->customer_code)
                {
                    $this->old_customer->customer_account->current_balance  = $updateAccountBalance->getCurrentBalance($this->old_customer->account_number,'customer','account_customer2');
                    $this->old_customer->customer_account->save();

                    $this->get_customer->customer_account->current_balance  = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->get_customer->customer_account->save();
                }else
                {
                    $this->get_customer->customer_account->current_balance  = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->get_customer->customer_account->save();
                }


            // 7  -- UPDATE SERVANT TABLE تعديل جدول المناديب *******************************
                if ($this->old_servant->customer_code != $this->get_servant->customer_code)
                {
                    $this->old_servant->current_balance = $updateAccountBalance->getCurrentBalance($this->old_servant->account_number,'servant','account_customer');
                    $this->old_servant->save();

                    $this->get_servant->current_balance = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_customer');
                    $this->get_servant->save();
                }else
                {
                    $this->get_servant->current_balance = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_customer');
                    $this->get_servant->save();
                }

                // dd($this->treasury_transation_table->servant_account_id);
                // dd($this->get_servant->current_balance);

            // 8 -- UPDATE SERVANT ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************

                    if ($this->old_servant->customer_code != $this->get_servant->customer_code)
                    {
                        $this->old_servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->old_servant->account_number,'servant','account_customer');
                        $this->old_servant->account_customer->save();

                        $this->get_servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_customer');
                        $this->get_servant->account_customer->save();
                    }else
                    {
                        $this->get_servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_customer');
                        $this->get_servant->account_customer->save();
                    }


                }else
                {

                    // 6  -- UPDATE CUSTOMER TABLE تعديل جدول العملاء *******************************
                        $this->get_customer->current_balance  = $this->get_customer->current_balance + ($this->unpaid *(-1));
                        $this->get_customer->save();


                    // 7 -- UPDATE CUSTOMER ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعملاء  *******************************
                        $this->get_customer->customer_account->current_balance = $this->get_customer->customer_account->current_balance + ($this->unpaid *(-1));
                        $this->get_customer->customer_account->save();

                }



                // dd('ahmed');



            // 9 -- CREATE ACTION HISTORY TABLE *****************
                $action_history->action('تعديل فاتورة مبيعات ', "تعديل فاتورة مبيعات {$this->get_customer->customer_account->name}", 'SalesOrder', $this->order->auto_serial,auth()->user()->id);
            DB::commit();

                // Dispatch events
                $this->dispatch('salesOrderUpdateMS');
                $this->dispatch('updateModalToggle');
                $this->dispatch('refreshData')->to(Data::class);


        } catch (\Throwable $th)
        {
            DB::rollBack();
            dd($th);
            // [
            //     'message' => $th->getMessage(),
            //     'file' => $th->getFile(),
            //     'line' => $th->getLine()
            // ]);

            $this->dispatch($th->getMessage()); // أو أضف رسالة واضحة للمستخدم
            return;
        }
    }


    public function render()
    {
        return view('back-end.sales-order.update');
    }
}

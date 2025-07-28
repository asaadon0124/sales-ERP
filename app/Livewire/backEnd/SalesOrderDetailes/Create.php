<?php

namespace App\Livewire\BackEnd\SalesOrderDetailes;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Servant;
use Livewire\Component;
use App\Models\Customer;
use App\Models\ItemUnit;
use App\Models\ItemBatch;
use App\Models\SalesOrder;
use App\Models\Treasuries;
use App\Models\ItemCardMovement;
use App\Models\SalesOrderDetail;
use App\Services\StoreAllocator;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementType;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Models\ItemCardMovementCategory;
use App\Livewire\BackEnd\SalesOrder\Show;

class Create extends Component
{

    // المتغيرات
    public $orderID;
    public $order;
    public $items;
    public $check_itemUnit_type     = null;
    public $item_is_change;
    public $store_id;
    public $batch_id;
    public $item_selected_detailes  = null;    // الصنف الي تم اختياره للاضافة
    public $stores;
    public $allBatches;
    public $select_store;
    public $reservedQtyMaster;
    public $treasury_balance;
    public $get_active_shift;
    public $customer;
    public $customer_account;
    public $treasury_transation_table;
    public $treasury_transation_table_servant;
    public $servant;
    public $new_order_detailes;
    public $new_item_batches;
    public $new_item_card_movements;
    public $treasury_table;
    public $itemCardMoveType;



    // الحقول
    public $items_type;
    public $sales_item_type_detailes;
    public $item_code;
    public $item_units_id = null;
    public $store_value;
    public $store_qty;
    public $qty             = 0;
    public $is_bouns;
    public $unit_price      = 0;
    public $total           = 0;
    public $sales_item_type;
    public $itemCardMoveCategory;






    protected $listeners = ['orderDetailesCreate'];


    public function orderDetailesCreate($id)
    {
        $this->orderID              = $id;
        $this->order                = SalesOrder::where('auto_serial',$id)->with('order_detailes','treasury_tranaction')->first();
        $this->items                = Item::where('company_code',auth()->user()->company_code)->with('itemUnit','itemUnitChild')->get();
        $this->customer             = Customer::where('customer_code', $this->order->customer_code)->first();
        $this->customer_account     = $this->customer->customer_account;
        $this->servant              = Servant::where('servant_code',$this->order->servant_code)->with('account_customer')->first();

        // dd($this->order);

        $this->items_type                       = $this->order->items_type;
        $this->sales_item_type                  = $this->order->sales_item_type;
        $this->sales_item_type_detailes         = $this->order->items_type == '0' ? $this->order->items_type : null;



        // dd($this->sales_item_type_detailes);
        // show Create modal
        $this->dispatch('createModalToggle');
    }


    public function mount()
    {
        $this->get_active_shift = Active_shift();
        $this->itemCardMoveType         = ItemCardMovementType::where('company_code',auth()->user()->company_code)->where('name','صرف من المخزن')->first();
        $this->itemCardMoveCategory     = ItemCardMovementCategory::where('company_code',auth()->user()->company_code)->where('name','صرف كمية من الصنف من المخزن نظير تعديل و اضافة صنف جديد لفاتورة المبيعات')->first();
    }



    // اختيار نوع الصنف جملة ولا قطاعي ولا نص جملة لكل صنف علي حدي
    public function sales_item_type_detailes_changed($value) // SALES ITEM TYPE DETILES
    {
        if ($value != '' && $this->items_type == '1')
        {
            $this->sales_item_type_detailes  = $value;
        } else
        {
            $this->sales_item_type_detailes = '';
        }


        $this->item_code                = '';
        $this->item_units_id            = '';
        $this->store_value              = '';
        $this->store_id                 = '';
        $this->qty                      = 0;
        $this->total                    = 0;
        $this->unit_price               = 0;
        $this->check_itemUnit_type      =  '';
    }


    // امسح البيانات الي في حقول الصنف عند تغير نوع الصنف
    public function resetItemSelection()
    {
        $this->item_units_id            = '';
        $this->qty                      = 0;
        $this->unit_price               = 0;
        $this->store_value              = '';
        $this->store_id                 = '';
        $this->stores                   = null;
    }


    // لما اختار الصنف
    public function item_select($value)
    {
        if (!empty($value))
        {
            $this->item_selected_detailes   = Item::where('item_code', $value)->with('itemUnitChild', 'itemUnit')->first();
            if ($this->item_selected_detailes)
            {
                $this->item_is_change          = $this->item_selected_detailes->is_change;
                $this->resetItemSelection();
            }
        } else
        {
           $this->item_code = '';
           $this->item_selected_detailes = '';
           $this->resetItemSelection();
        }
    }



     // لما  اختار وحدة الصنف
    public function check_item_unit_type($value)
    {
        if (empty($value))
        {
            $this->resetStoreData();
            return;
        }

        $this->unit_price     = 0;
        $this->store_value    = '';
        $this->store_id       = '';
        $this->batch_id       = '';
        $this->select_store   = '';
        $this->store_qty      = 0;
        $this->qty            = 0;
        $this->total          = 0;

        $this->check_itemUnit_type = ItemUnit::select('id', 'is_master', 'name')->find($value);

        // تحميل المخازن حسب نوع الصنف استهلاكي او شيء اخر
        $query          = ItemBatch::with('store')->where('item_code', $this->item_selected_detailes->item_code);





        // نجيب كل الـ batches
        $this->allBatches = ItemBatch::with('store')->where('item_code', $this->item_selected_detailes->item_code)->get();

        // لو الصنف استهلاكي (type == 1)، ندمجهم حسب (المخزن + الصلاحية + السعر)
        $unitType = $this->check_itemUnit_type->is_master ?? 'master';
        $subQty   = $this->item_selected_detailes->qty_sub_item_unit ?: 1;

        if ($this->item_selected_detailes->item_type == '1')
        {
            $this->allBatches = mergeConsumableBatches($this->allBatches, $unitType, $subQty);

        } else
        {
            $this->allBatches = mergeNonConsumableBatches($this->allBatches, $unitType, $subQty);
        }


        // dd($this->allBatches);
        $this->stores = $this->allBatches->filter(function ($batch)
        {
            return $batch->qty > 0;
        })->values();


        // تحديد السعر
        $is_master  = $this->check_itemUnit_type->is_master;
        $type       = $this->items_type == '0' ? $this->sales_item_type : $this->sales_item_type_detailes;

        if ($is_master === 'master')
        {
            $this->unit_price = match ($type)
            {
                '0' => $this->item_selected_detailes->item_retail_price,
                '1' => $this->item_selected_detailes->item_Half_wholesale_price,
                '2' => $this->item_selected_detailes->item_wholesale_price,
                default => 0
            };
        } elseif ($is_master === 'sub_master')
        {
            $this->unit_price = match ($type)
            {
                '0' => $this->item_selected_detailes->sub_item_retail_price,
                '1' => $this->item_selected_detailes->sub_item_Half_wholesale_price,
                '2' => $this->item_selected_detailes->sub_item_wholesale_price,
                default => 0
            };
        }
    }


    // لما اختار وحدة الصنف  امسح بيانات المخازن
    private function resetStoreData()
    {
        $this->check_itemUnit_type = '';
        $this->select_store = '';
        $this->stores = '';
        $this->store_id = '';
    }



    // لما  اختار المخزن
    public function change_store($value)
    {
        // ندمج الكميات لو الصنف  استهلاكي او مخزني في الباتشات
        $this->allBatches   = ItemBatch::with('store')->where('item_code', $this->item_selected_detailes->item_code)->get();
        $unitType           = $this->check_itemUnit_type->is_master ?? 'master';
        $subQty             = $this->item_selected_detailes->qty_sub_item_unit ?: 1;

        if ($this->item_selected_detailes->item_type == '1')
        {
            $this->allBatches = mergeConsumableBatches($this->allBatches, $unitType, $subQty);
        } else
        {
            $this->allBatches = mergeNonConsumableBatches($this->allBatches, $unitType, $subQty);
        }

        $this->store_value      = $value;
        [$storeId, $batchId]    = explode('|', $value);
        $this->store_id         = $storeId;

        if (!empty($value) && !empty($this->check_itemUnit_type))
        {
            $this->select_store         = $this->allBatches->where('id',$batchId)->first();
            $this->select_store->qty = $this->select_store->qty - $this->select_store->deduction;

            // dd($this->select_store->qty);
            // حساب الكمية في المخزن بناءً على الوحدة اساسية ولا فرعية
            if (!empty($this->select_store))
            {
                $this->store_qty = $this->check_itemUnit_type->is_master === 'master'
                    ? $this->select_store->qty
                    : $this->select_store->qty * $this->item_selected_detailes->qty_sub_item_unit;
            }

            $this->reservedQtyMaster    = (float) collect(session()->get('sales_order_items', []))
            ->filter(function ($item) use ($storeId, $batchId)
            {
                return $item['store_id'] == $storeId
                    && $item['batch_id'] == $batchId
                    && $item['item_code'] == $this->item_code
                    && $item['itemUnit_type'] == 'master';
            })
            ->sum('qty');


             $reservedQtySubMaster  = (float) collect(session()->get('sales_order_items', []))
            ->filter(function ($item) use ($storeId, $batchId)
            {
                return $item['store_id'] == $storeId
                    && $item['batch_id'] == $batchId
                    && $item['item_code'] == $this->item_code
                    && $item['itemUnit_type'] == 'sub_master';
            })
            ->sum('qty');

            $this->batch_id        = $batchId;
            $this->store_id        = $this->select_store->store_id;

            if ($this->check_itemUnit_type->is_master == 'master' && $this->item_selected_detailes->qty_sub_item_unit != 0)              // لو الوحدة اب
            {
                //  dd($this->item_selected_detailes);
                $store_qty = $reservedQtySubMaster / $this->item_selected_detailes->qty_sub_item_unit;


            } elseif ($this->check_itemUnit_type->is_master == 'sub_master')     // لو الوحدة ابن
            {
                $convertToMaster    = $reservedQtySubMaster / $this->item_selected_detailes->qty_sub_item_unit;
                $store_qty   = $this->store_qty - ($convertToMaster + $this->reservedQtyMaster);
            }
        } else
        {
            // dd('dd');
            $this->select_store = '';
        }
        $this->batch_id = $batchId;



        //  dd($store_qty_master);

        // $this->check_itemUnit_type = ItemUnit::select('id','is_master')->where('id',$value)->first();
    }



    public function updateQty($value)
    {
        $this->qty = $value;
        $this->total = (float)$this->unit_price * (float)$this->qty;

            if ($value > $this->store_qty)
            {
                $this->qty = $this->store_qty;
            } else
            {
                $this->qty = $value;
            }
            // $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;

    }



    public function updateUnit_price($value)
    {
        $this->unit_price = $value;
        $this->total = $this->unit_price * $this->qty;
        // $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;

    }



     public function submit_rules()
    {
          $qtyRules =
            [
                'required_with:store_id',
                'numeric',
                'gt:0',
            ];


        if (!is_null($this->store_qty))
        {
            $qtyRules[] = 'max:' . $this->store_qty;
        }


        $priceRules =
        [
            'required',
            'numeric',
            'gt:0',
        ];


        if (!is_null($this->item_selected_detailes) && ($this->check_itemUnit_type?->is_master ?? '') === 'master')
        {
            // dd($this->check_itemUnit_type->is_master);
            $priceRules[] = 'min:' . $this->item_selected_detailes->item_wholesale_price;

        } elseif (!is_null($this->item_selected_detailes) && ($this->check_itemUnit_type?->is_master ?? '') === 'sub_master')
        {
            // dd($this->check_itemUnit_type->is_master);
            $priceRules[] = 'min:' . $this->item_selected_detailes->sub_item_wholesale_price;
        }

        return
        [
            'sales_item_type_detailes'  => 'required_if:items_type,1|in:0,1,2',
            'item_units_id'             => 'required|exists:item_units,id',
            'store_id'                  => 'required|exists:stores,id',
            'qty'                       => $qtyRules,
            'unit_price'                => $priceRules,
            'is_bouns'                  => 'required',
        ];
    }



    public function messages()
    {
        return
            [
                'sales_item_type_detailes.required_if'          => 'نوع البيع للصنف مطلوب.',
                'sales_item_type_detailes.in'                   => 'اختار من ااقيمة المتاحة فقط',
                'item_units_id.required'                        => 'اسم وحدة الصنف مطلوب.',
                'item_units_id.exists'                          => 'هذه وحدة الصنف غير موجوده.',
                'store_id.required'                             => 'اسم المخزن مطلوب.',
                'store_id.exists'                               => 'هذه المخزن غير موجوده.',
                'store_id.exists'                               => 'هذه المخزن غير موجوده.',
                'qty.required'                                  => 'الكمية مطلوبة.',
                'qty.numeric'                                   => 'الكمية يجب أن تكون رقمًا.',
                'qty.max'                                       => 'الكمية لا يمكن أن تكون اكبر من الكمية المتاحة في المخزن.',
                'qty.gt'                                        => 'الكمية لا يمكن أن تقل عن 1.',
                'unit_price.required'                           => 'السعر مطلوب.',
                'unit_price.numeric'                            => 'السعر يجب أن تكون رقمًا.',
                'unit_price.gt'                                 => 'السعر لا يمكن أن تقل عن 1.',
                'unit_price.min'                                => 'السعر لا يمكن أن يكون أقل من :min جنيه.',
                'is_bouns.required'                             => 'نوع السعر مطلوب.',
            ];
    }







    public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('اضافة صنف جديد لفاتورة المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية اضافة صنف جديد لفاتورة المبيعات ');
        }

        $this->validate($this->submit_rules(), $this->messages());

        try
        {

            // 1 - START VALIDATIONS *************************************************************************
                // التاكد من ان الكمية متاحة في المخزن
                if ($this->store_qty == 0 || $this->store_qty < $this->qty)
                {
                    $this->addError('qty', 'الكمية المباعة غير متاحة في المخزن.');
                    return;
                }

                DB::beginTransaction();
                // ********************************  الحسابات ******************************************

                    // 2 - CREATE SALES ORDER  تعديل جدول فاتورة المبيعات
                        if ($this->order->items_type == 0)
                        {
                            $this->order->sales_item_type           = $this->sales_item_type;
                        }
                        $this->order->total_cost_before_all     = $this->order->order_detailes->sum('total') + $this->total;
                        $this->order->tax_value                = ($this->order->total_cost_before_all * $this->order->tax_percent) / 100;
                        $this->order->total_before_discount     = ($this->order->total_cost_before_all + $this->order->tax_value);

                        if ($this->order->discount_type == '1')     // لو نوع الخصم نسبة يبقي حنعدل نسبة الخصم
                        {
                            $this->order->discount_amount       =  ($this->order->total_before_discount * $this->order->discount_percent) / 100;
                            $this->order->total_cost        = $this->order->total_before_discount - $this->order->discount_amount;
                        }else
                        {
                            $this->order->total_cost        = $this->order->total_before_discount - $this->order->discount_amount;
                        }
                        $this->order->updated_by                = auth()->user()->id;
                        $this->order->save();


                    if ($this->order->invoice_type == '0')      // لو نوع الفاتورة كاش
                    {
                        // 2 - CREATE SALES ORDER  تعديل جدول فاتورة المبيعات
                            $this->order->mony_for_account          = $this->order->total_cost;
                            $this->order->paid                      = $this->order->total_cost;
                            $this->order->save();

                        // 3  -- UPDATE TREASURY TRANSACTIONS TABLE تعديل جدول حركة النقدية *******************************
                            $this->treasury_transation_table                            =  $this->order->treasury_tranaction;
                            $this->treasury_transation_table->cash_amount               = $this->treasury_transation_table->cash_amount + $this->total;
                            $this->treasury_transation_table->servant_cash_amount       = $this->treasury_transation_table->servant_cash_amount + ($this->total * (-1));
                            $this->treasury_transation_table->updated_by                = auth()->user()->id;
                            $this->treasury_transation_table->save();

                          // 4  -- UPDATE SERVANT TABLE تعديل جدول المناديب *******************************
                            $this->servant->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                            $this->servant->save();

                        // 5 -- UPDATE SERVANT ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************
                            $this->servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                            $this->servant->account_customer->save();

                    }else                                      // لو نوع الفاتورة اجل
                    {

                        // 2 - CREATE SALES ORDER  تعديل جدول فاتورة المبيعات
                            $this->order->mony_for_account          = ($this->order->mony_for_account) + ($this->total * (-1));
                            $this->order->unpaid                    = $this->order->unpaid + $this->total;
                            $this->order->customer_balance_after    = $this->order->customer_balance_after + ($this->total * (-1));
                            $this->order->save();

                        // 3  -- UPDATE TREASURY TRANSACTIONS TABLE تعديل جدول حركة النقدية *******************************
                            $this->treasury_transation_table                            =  $this->order->treasury_tranaction;
                            $this->treasury_transation_table->cash_for_account          = $this->treasury_transation_table->cash_for_account + ($this->total * (-1));
                            $this->treasury_transation_table->save();

                        // 4  -- UPDATE CUSTOMER TABLE تعديل جدول العملاء *******************************
                                $this->order->customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->order->customer->account_number,'customer','account_customer2');
                                $this->order->customer->save();



                        // 5 -- UPDATE CUSTOMER ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعملاء  *******************************
                                $this->customer_account->current_balance = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                                $this->customer_account->save();


                        // 6  -- UPDATE SERVANT TABLE تعديل جدول المناديب *******************************
                            $this->servant->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                            $this->servant->save();

                        //  -- UPDATE SERVANT ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************
                            $this->servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->servant->account_number,'servant','account_customer');
                            $this->servant->account_customer->save();
                    }

                // ********************************  المخازن ******************************************

                    // 1 - CREATE SALES ORDER DETAILES  انشاء جدول تفاصيل فاتورة المبيعات
                        $this->new_order_detailes                                               = new SalesOrderDetail();

                        if ($this->order->items_type == '0')    // لو نوع البيع في الفاتورة الرئيسية ثابت
                        {
                            $this->new_order_detailes->sales_item_type_detailes                     = $this->order->sales_item_type;
                        } else                                  // لو نوع البيع في الفاتورة الرئيسية متغير
                        {
                            $this->new_order_detailes->sales_item_type_detailes                     = $this->sales_item_type_detailes;
                        }
                        $this->new_order_detailes->item_type                                    = $this->item_selected_detailes->item_type;
                        $this->new_order_detailes->auto_serial_sales_order                      = $this->order->auto_serial;
                        $this->new_order_detailes->item_code                                    = $this->item_code;
                        $this->new_order_detailes->item_units_id                                = $this->item_units_id;
                        $this->new_order_detailes->store_id                                     = $this->store_id;
                        $this->new_order_detailes->batch_id                                     = $this->batch_id;
                        $this->new_order_detailes->is_master                                    = $this->check_itemUnit_type->is_master;
                        $this->new_order_detailes->is_bouns                                     = $this->is_bouns;
                        $this->new_order_detailes->qty                                          = $this->qty;
                        $this->new_order_detailes->unit_price                                   = $this->unit_price;
                        $this->new_order_detailes->total                                        = $this->unit_price * $this->qty;
                        $this->new_order_detailes->order_date                                   = $this->order->order_date;
                        $this->new_order_detailes->company_code                                 = auth()->user()->company_code;
                        $this->new_order_detailes->created_by                                   = auth()->user()->id;
                        $this->new_order_detailes->updated_by                                   = auth()->user()->id;
                        $this->new_order_detailes->save();
                        $temp_item_cost_price                                                   = $this->select_store->item_cost_price;
                        $temp_itemUnit_type                                                     = $this->check_itemUnit_type->is_master;
                        $temp_item_name                                                         = $this->item_selected_detailes->name;
                        $temp_qty                                                               = $this->qty;


                    // 2 - A - نجيب الكمية الي حتتخصم من المخزن -- الكمية المباعة من الصنف -- بالوحدة الاب
                        $item_data                      = $this->new_order_detailes->toArray();
                        $item_data['item_cost_price']   = $temp_item_cost_price;
                        $item_data['itemUnit_type']     = $temp_itemUnit_type;
                        $item_data['item_name']         = $temp_item_name;
                        $item_data['qty']               = $temp_qty;

                        $result         = app(StoreAllocator::class)->allocate($item_data);
                        $batch          = $result->batches->first();
                        $required_qty   = $result->required_qty;
                        $deductions     = [];



                        // B - نخصم الكميات  من المخازن حسب الباتش
                         if ($required_qty <= 0) return;

                        // اجيب الكميات في المخازن قبل خصم الصنف
                        $qty_before_all_stores = ItemBatch::where('item_code', $item_data['item_code'])->selectRaw('SUM(qty - deduction) as total')->value('total');
                        $qty_before_this_store = ItemBatch::where('item_code', $item_data['item_code'])->where('store_id', $item_data['store_id'])->selectRaw('SUM(qty - deduction) as total')->value('total');

                        $batch->deduction       += $required_qty;
                        $batch->save();

                        // ❖ نحسب الكمية بعد الإضافة
                        $qty_after_all_stores = ItemBatch::where('item_code', $this->new_order_detailes->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                        $qty_after_this_store = ItemBatch::where('item_code', $this->new_order_detailes->item_code)
                            ->where('store_id', $this->new_order_detailes->store_id)
                            ->selectRaw('SUM(qty - deduction) as total')->value('total');


                    // 3 - UPDATE SALES ORDER DETAILES  تعديل جدول تفاصيل فاتورة المبيعات
                        $this->new_order_detailes->production_date                              = $batch->production_date;
                        $this->new_order_detailes->expire_date                                  = $batch->expire_date;
                        $this->new_order_detailes->save();

                    // 4 - CREATE ITEM CARD MOVEMENTS  انشاء جدول حركة الصنف
                        $this->new_item_card_movements                                     = new ItemCardMovement();
                        $this->new_item_card_movements->store_id                            = $this->store_id;
                        $this->new_item_card_movements->item_code                           = $item_data['item_code'];
                        $this->new_item_card_movements->item_card_movements_category_id     = $this->itemCardMoveCategory->id;
                        $this->new_item_card_movements->item_card_movements_type_id         = $this->itemCardMoveType->id;
                        $this->new_item_card_movements->item_batch_id                       = $this->new_order_detailes->batch_id;
                        $this->new_item_card_movements->sales_order_id                      = $this->order->auto_serial;
                        $this->new_item_card_movements->sales_orderdetiles__id              = $this->new_order_detailes->id;
                        $this->new_item_card_movements->qty_before_movement                 = $qty_before_all_stores;
                        $this->new_item_card_movements->qty_before_movement_in_store        = $qty_before_this_store;
                        $this->new_item_card_movements->qty_after_movement                  = $qty_after_all_stores;
                        $this->new_item_card_movements->qty_after_movement_in_store         = $qty_after_this_store;
                        $this->new_item_card_movements->notes                               = $this->order->notes;
                        $this->new_item_card_movements->company_code                        = auth()->user()->company_code;
                        $this->new_item_card_movements->created_by                          = auth()->user()->id;
                        $this->new_item_card_movements->updated_by                          = auth()->user()->id;
                        $this->new_item_card_movements->date                                = Carbon::now();
                        $this->new_item_card_movements->save();


                    // 6 -- UPDATE ITEMS TABLE تعديل جدول الخاص الاصناف  *******************************

                        $get_item2                      = Item::where('item_code', $batch->item_code)->with('itemUnit')->first();
                        $qty_after_all_stores_parent    = ItemBatch::where('item_code', $batch->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                        $qty_after_all_stores_sub       = $qty_after_all_stores_parent * $get_item2->qty_sub_item_unit;
                        $qty_fraction_only              = $qty_after_all_stores_parent - floor($qty_after_all_stores_parent); // نسبة الكسر في الوحدة الرئيسية
                        $qty_value_fraction_only        = $qty_fraction_only * $get_item2->qty_sub_item_unit;

                        // dd($qty_value_fraction_only);


                        $get_item2->total_qty_for_parent    = floor($qty_after_all_stores_parent);
                        $get_item2->total_qty_for_sub_items = $qty_after_all_stores_sub;
                        $get_item2->sub_item_qty            = $qty_value_fraction_only;
                        $get_item2->save();

                    // 7 - CREATE ACTION HISTORY TABLE *****************
                        $action_history->action('اضافة صنف جديد لفاتورة مبيعات حالية ', "اضافة صنف جديد لفاتورة مبيعات حالية {$this->order->customer->name}", 'SalesOrder', $this->order->auto_serial,auth()->user()->id);
                DB::commit();

                // Dispatch events


                $this->dispatch('salesOrderDtailesCreateMS');
                $this->dispatch('createModalToggle');
                $this->dispatch('refreshData')->to(Show::class);


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
        return view('back-end.sales-order-detailes.create');
    }
}

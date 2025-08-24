<?php

namespace App\Livewire\BackEnd\SalesOrder;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Store;
use App\Models\Account;
use App\Models\Servant;
use Livewire\Component;
use App\Models\Customer;
use App\Models\ItemUnit;
use App\Models\MoveType;
use App\Models\ItemBatch;
use App\Models\SalesOrder;
use App\Models\MaterialType;
use Illuminate\Validation\Rule;
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

class Create extends Component
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
    public $account_balance_before_customer;
    public $account_balance_before_servant;


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
    public $new_order;
    public $new_order_detailes;
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


    protected $listeners = ['salesOrderCreate', 'supplierChanged'];


    public function salesOrderCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }


    // لما اختار عميل
    public function customerChanged($newContent,AccountBalanceService $updateAccountBalance)
    {
        $this->resetErrorBag('customer_code'); // مسح الخطأ المتعلق بالعميل
        $this->customer_code = $newContent;
        $this->get_customer = Customer::where('customer_code', $newContent)->with('customer_account')->where('status','active')->first();

        if ($this->get_customer)
        {
            $this->customer_account                 = $this->get_customer->customer_account;
            $this->customer_balance                 = $this->get_customer->current_balance;
            $this->account_balance_before_customer  = $updateAccountBalance->getCurrentBalance($this->get_customer->account_number,'customer','account_customer2');
            // dd($this->account_balance_before_customer);
            if (session()->has('order'))
            {
                // تحديث بيانات الجلسة
                $order = session('order');

                // إذا كان هناك رصيد غير مدفوع من عميل سابق، نضيفه للرصيد الحالي
                if (isset($order[0]['unpaid']) && $order[0]['customer_code'] != $newContent)
                {
                    $this->customer_balance += $order[0]['unpaid'];
                }

                // تحديث بيانات العميل في الجلسة
                $order[0]['customer_code'] = $newContent;
                $order[0]['customer_balance'] = $this->customer_balance;

                session()->put('order', $order);
            }
        } else
        {
            $this->customer_account = null;
            $this->customer_balance = 0;

            if (session()->has('order')) {
                $order = session('order');
                $order[0]['customer_code'] = null;
                $order[0]['customer_balance'] = 0;
                session()->put('order', $order);
            }

            $this->addError('customer_code', 'العميل غير موجود.');
        }
        // dd(session('order'));
    }





    // لما اختار مندوب
    public function servantChanged($newContent,AccountBalanceService $updateAccountBalance)             // SERVANT CODE
    {
        $this->resetErrorBag('servant_code'); // 👈 يمسح الخطأ المتعلق بالمندوب
        $this->servant_code                     = $newContent;
        $this->get_servant                      = Servant::where('servant_code',$newContent)->where('status','active')->with('account_customer')->first();



        if ($this->get_servant)
        {
            $this->servant_account                  = $this->get_servant->servant_account;
            $this->servant_balance                  = $this->get_servant->current_balance;
            $this->account_balance_before_servant   = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_servant');
            if (session()->has('order'))
            {
                    $order                          = session()->get('order');
                    $order[0]['servant_balance']   = $this->servant_balance;
                    session()->put('order', $order);
            }
        } else
        {
            $this->servant_account = null;
            $this->servant_balance = 0;
             if (session()->has('order'))
            {
                $order                          = session()->get('order');
                $order[0]['servant_balance']   = 0;
                session()->put('order', $order);
            }

            $this->addError('servant_code', 'المندوب غير موجود.');
        }
    }



    // لما اغير نوع الفاتورة من كاش لاجل و العكس
    public function change_invoice_type($newContent)        // INVOICE TYPE
    {
        $this->invoice_type                 = $newContent;

        if (!empty($this->order[0]['total_cost']))
        {
            $this->order[0]['invoice_type']     = $newContent;
            $this->total_cost                   = session('order')[0]['total_cost'];
            $this->resetErrorBag('invoice_type'); // ← هذا السطر يزيل الخطأ فوريًا

            if ($this->invoice_type == '0')
            {

                $this->paid                         = $this->total_cost;
                $this->unpaid                       = 0;
                $this->order[0]['paid']             = $this->total_cost;;
                $this->order[0]['unpaid']           = 0;
            }else
            {
                $this->unpaid                       = session('order')[0]['total_cost'];
                $this->order[0]['unpaid']           = session('order')[0]['total_cost'];
                $this->order[0]['paid']             = 0;
                $this->paid                         = 0;
            }
        }
         // تحدث السيشن بالقيمة الجديدة
        session()->put('order', $this->order);


        $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;

        // $this->customer_balance = $this->get_customer->customer_balance + $this->order[0]['unpaid'];
        // $this->order[0]['customer_balance'] = $this->customer_balance;


        // تحدث السيشن بالقيمة الجديدة
        session()->put('order', $this->order);


    }



    // لما اختار نوع الصنف في الاصناف ثابت او متغير يظهر  حقل نوع  الصنف في كل صنف سواء جملة او نصف جملة او قطاعي
    public function change_items_type($value)               // items_type
    {

         $this->items_type = $value;

        if ($value == '1')                              // متغير
        {
            $this->sales_item_type          = '';
        }

        session()->forget('sales_order_items');     // مسح البيانات من السيشن




         $this->reset(
        [
            'total_cost',
            'paid',
            'unpaid',
            'tax_percent',
            'tax_value',
            'total_cost_before_all',
            'total_before_discount',
            'discount_percent',
            'discount_amount',
            'item_code',
            'item_units_id',
            'store_value',
            'store_id',
            'qty',
            'total',
            'unit_price',
            'check_itemUnit_type',
            'sales_item_type_detailes',
        ]);
    }




    // لما اختار نوع الصنف في الفاتورة الاب انه يبقيثابت و اختار نوع الصنف جملة مثلا في الفاتورة الاب يبقي نوع الصنف ثابت في كل الاصناف داخل الفاتورة جملة
    public function change_sales_item_type($value)          // SALES ITEM TYPE
    {
        if ($value != '')
        {
            $this->sales_item_type_detailes  = $value;
            $this->unit_price                = '';

            session()->forget('sales_order_items');     // مسح البيانات من السيشن
            $this->items_detailes           = [];                 // مسح البيانات من المصفوفة المحلية
        } else
        {
            $this->sales_item_type_detailes = '';
        }
        // dd($this->sales_item_type_detailes);
        session()->forget('sales_order_items');     // مسح البيانات من السيشن
        $this->total_cost               = 0;
        $this->paid                     = 0;
        $this->unpaid                   = 0;
        $this->tax_percent              = 0;
        $this->tax_value                = 0;
        $this->total_cost_before_all    = 0;
        $this->total_before_discount    = 0;
        $this->discount_percent         = 0;
        $this->discount_amount          = 0;
        $this->item_code                = '';
        $this->item_units_id            = '';
        $this->store_value              = '';
        $this->store_id                 = '';
        $this->qty                      = 0;
        $this->total                    = 0;
        $this->unit_price               = 0;
        $this->check_itemUnit_type      =  '';
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




    public function mount()
    {

        //  session()->forget('sales_order_items');     // مسح البيانات من السيشن
        //  session()->forget('order');     // مسح البيانات من السيشن
        //  dd(session()->get('order'));     // مسح البيانات من السيشن

        $this->customers        = Customer::select('name', 'customer_code', 'id')->where('status','active')->get();
        $this->servants         = Servant::select('name', 'servant_code', 'id')->where('status','active')->get();
        $this->lastOrder        = get_last_autoSerial_invoices(SalesOrder::class, 'auto_serial');
        $this->items            = Item::where('company_code', auth()->user()->company_code)->where('status','active')->with('itemUnit', 'itemUnitChild')->whereHas('item_batches')->get();
        $this->matrial_types    =   MaterialType::all();
        $this->get_active_shift = Active_shift();
        $this->moveType         = MoveType::select('id','name','company_code')->where('company_code',auth()->user()->company_code)->where('name','تحصيل ايراد مبيعات')->first();
        $this->itemCardMoveType         = ItemCardMovementType::where('company_code',auth()->user()->company_code)->where('name','صرف من المخزن')->first();
        $this->itemCardMoveCategory     = ItemCardMovementCategory::where('company_code',auth()->user()->company_code)->where('name','صرف كمية من الصنف من المخزن نظير انشاء فاتورة مبيعات')->first();


        if ($this->get_active_shift)
        {
            $this->active_treasury  = $this->get_active_shift->treasury;
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount');
        }



        // dd(session()->get('order'));

        if (session()->has('order'))
        {
            $order = session('order')[0] ?? null;
            // dd(session()->get('order', []));
            $this->order_date                   = $order['order_date'] ?? null;
            $this->matrial_types_id             = $order['matrial_types_id'] ?? null;
            $this->is_fixed_customer            = $order['is_fixed_customer'] ?? null;
            $this->items_type                   = $order['items_type'] ?? null;
            $this->sales_item_type              = $order['sales_item_type'] ?? null;
            $this->sales_item_type_detailes     = $order['sales_item_type'] ?? null;
            $this->invoice_type                 = $order['invoice_type'] ?? null;
            $this->customer_code                = $order['customer_code'] ?? null;
            $this->servant_code                 = $order['servant_code'] ?? null;
            $this->tax_percent                  = $order['tax_percent'] ?? 0;
            $this->tax_value                    = $order['tax_value'] ?? 0;
            $this->total_cost_before_all        = $order['total_cost_before_all'] ?? 0;
            $this->total_before_discount        = $order['total_before_discount'] ?? 0;
            $this->discount_type                = $order['discount_type'] ?? null;
            $this->discount_percent             = $order['discount_percent'] ?? 0;
            $this->discount_amount              = $order['discount_amount'] ?? 0;
            $this->total_cost                   = $order['total_cost'] ?? 0;
            $this->paid                         = $order['paid'] ?? 0;
            $this->unpaid                       = $order['unpaid'] ?? 0;
            $this->notes                        = $order['notes'] ?? null;
            $this->treasury_balance             = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + session('order')[0]['paid'];



            $this->get_customer                 = Customer::where('customer_code',$order['customer_code'])->with('customer_account')->first();
            if (!empty($this->get_customer))
            {
                $this->customer_account             = $this->get_customer->customer_account;

                // $this->customer_balance = $this->get_customer->current_balance +  session('order')[0]['unpaid'];
                $this->servant_balance = $order['servant_balance'];

            }

        }

        // ⬅️ دي الإضافة المهمة
        $this->items_detailes = session('sales_order_items', []);

    }




    // ********************************************************************************

    // لما اختار الصنف
    public function item_select($value)
    {

        if (!empty($value))
        {

            $this->item_selected_detailes   = Item::where('item_code', $value)->with('itemUnitChild', 'itemUnit')->first();

            // dd($this->item_selected_detailes);
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
            dd('dd');
            $this->select_store = '';
        }



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
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;

    }



    public function updateUnit_price($value)
    {
        $this->unit_price = $value;
        $this->total = $this->unit_price * $this->qty;
        $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;

    }


    // ******************************************************************************************************



    public function rules()
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
                'sales_item_type'           => 'required_if:items_type,0|in:0,1,2',
                'item_code'                 => 'required|exists:items,item_code',
                'item_units_id'             => 'required|exists:item_units,id',
                'store_id'                  => 'required|exists:stores,id',
                // 'qty'                       => ['required_with:store_id', 'numeric', 'gt:0', 'max:' . $this->store_qty,],
                 'qty'                      => $qtyRules,
                // 'unit_price'                => ['required', 'numeric', 'gt:0','max:' . $this->item_selected_detailes->item_wholesale_price],
                'unit_price'                => $priceRules,
                'is_bouns'                  => 'required',
            ];
    }



    // اضافة اصناف الفاتورة في السيشن قبل الحفظ في الداتا بيز
    public function add_item()
    {
        if ($this->store_qty <= 0)
        {
            $this->addError('store_value', 'هذا المخزن لا يوجد به رصيد .');
            return;
        }

        $this->validate($this->rules(), $this->messages());

        $new_item =
            [
                'sales_item_type_detailes'      => $this->sales_item_type_detailes,
                'item_code'                     => $this->item_code,
                'item_units_id'                 => $this->item_units_id,
                'store_id'                      => $this->store_id,
                'batch_id'                      => $this->batch_id,
                'qty'                           => $this->qty,
                'unit_price'                    => $this->unit_price,
                'total'                         => $this->total,
                'is_bouns'                      => $this->is_bouns,
                'store_name'                    => $this->select_store->store->name,
                'item_name'                     => $this->item_selected_detailes->name,
                'itemUnit_name'                 => $this->check_itemUnit_type->name,
                'itemUnit_type'                 => $this->check_itemUnit_type->is_master,
                'item_type'                     => $this->item_selected_detailes->item_type,
                'is_master'                     => $this->check_itemUnit_type->is_master,
                'item_cost_price'               => $this->select_store->item_cost_price,
                'item_type'                     => $this->item_selected_detailes->item_type,

            ];

            // dd($this->item_selected_detailes->item_type);

        $order_items            = session()->get('sales_order_items', []);
        $order_items[]          = $new_item;
        session()->put('sales_order_items', $order_items);
        $this->items_detailes   = $order_items;



        $this->total_cost_before_all    = collect($this->items_detailes)->sum('total');
        $this->tax_value                = ($this->total_cost_before_all * $this->tax_percent) / 100;
        $this->total_before_discount    = $this->total_cost_before_all + $this->tax_value;

        if ($this->discount_type == '1')
        {
            $this->discount_amount          = round(($this->total_before_discount * $this->discount_percent) / 100, 2);
        }

        $this->total_cost               = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;
        if ($this->invoice_type == '0')
        {
            $this->paid                         = $this->total_cost;
        }

        if ($this->invoice_type == '1')
        {
            $this->unpaid = $this->total_cost - $this->paid;
        }

        $new_order =
            [
                'order_date'              => $this->order_date,
                'matrial_types_id'        => $this->matrial_types_id,
                'is_fixed_customer'       => '0',
                'items_type'              => $this->items_type,
                'sales_item_type'         => $this->sales_item_type,
                'invoice_type'            => $this->invoice_type,
                'auto_serial'             => get_last_autoSerial_invoices(SalesOrder::class, 'auto_serial'),
                'customer_code'           => $this->customer_code,
                'servant_code'            => $this->servant_code,
                'tax_percent'             => $this->tax_percent,
                'total_cost_before_all'   => $this->total_cost_before_all,
                'total_before_discount'   => $this->total_before_discount,
                'discount_type'           => $this->discount_type,
                'discount_percent'        => $this->discount_percent,
                'discount_amount'         => $this->discount_amount,
                'total_cost'              => $this->total_cost,
                'paid'                    => $this->paid,
                'unpaid'                  => $this->unpaid,
                'notes'                   => $this->notes,
                'customer_balance'        => $this->customer_balance,
                'servant_balance'         => $this->servant_balance,
            ];



        $order_data = session()->get('order', []);
        $order_data = array_filter($order_data, function ($item)
        {
            return is_array($item) && !empty($item);
        });

        $order_data = array_values($order_data); // إعادة ترتيب المفاتيح تبدأ من 0
        //  dd($order_data);
        if (!empty($order_data) && is_array($order_data[0] ?? null))
        {
            // dd($new_order);
            $order          = $order_data[0];
            $order_data[0]  = $new_order;


            // $this->order = $new_order;
        } else
        {
            // dd('no');
            $order_data[]   = $new_order;
            $order         = $order_data[0];
            // $this->order    = $new_order;
        }
        session()->put('order', $order_data);

        $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + session('order')[0]['paid'];
        // $this->customer_balance = $this->get_customer->current_balance +  session('order')[0]['unpaid'];
        // $this->servant_balance = $this->get_servant->current_balance +  session('order')[0]['unpaid'];
        $this->stores               = ItemBatch::with('store')->where('qty','>',0)->where('item_code', $this->item_selected_detailes->item_code)->orderBy('expire_date', 'asc')->get();

        $this->qty                  = 0;
        $this->unit_price           = 0;
        $this->total                = 0;
        $this->item_code            = null;
        $this->is_bouns             = null;
        $this->item_units_id        = null;
    }



    // حذف صنف في الفاتورة من السيشن
    public function removeItem($index)
    {


        // استرجاع العناصر الحالية من السيشن
        $items = session()->get('sales_order_items', []);
        $order = session()->get('order', []);


        // حذف العنصر بالمفتاح المحدد
        if (isset($items[$index]))
        {

            unset($items[$index]);

            $items = array_values($items); // إعادة ترتيب المفاتيح بعد الحذف
            $this->total_cost_before_all    = collect($this->items_detailes)->sum('total');
            $this->total_cost               = ($this->total_cost_before_all + $this->tax_value) - $this->discount_amount;


             // تحديث السيشن والمصفوفة المحلية
            session()->put('sales_order_items', $items);

            $this->items_detailes           = $items;
            $this->total_cost_before_all    = collect($this->items_detailes)->sum('total');

            $this->tax_value                = ($this->total_cost_before_all * $this->tax_percent) / 100;
            $this->total_before_discount    = $this->total_cost_before_all + $this->tax_value;
            if ($this->discount_type == '1')
            {
                $this->discount_amount          = round(($this->total_before_discount * $this->discount_percent) / 100, 2);
            }
            $this->total_cost = $this->total_before_discount - $this->discount_amount;

            if ($this->invoice_type == '0')
            {
                $this->paid         = $this->total_cost;
                $this->unpaid       = 0;
                $order[0]['paid']   = $this->total_cost;
                $order[0]['total_cost']   = $this->total_cost;
                $order[0]['unpaid'] = 0;
                //   dd($this->total_cost);

            } elseif ($this->invoice_type == '1')
            {
                $this->unpaid       = $this->total_cost;
                $this->paid         = 0;
                $order[0]['paid']   = 0;
                 $order[0]['total_cost']   = $this->total_cost;
                $order[0]['unpaid'] = $this->total_cost;
            }

              session()->put('order', $order);
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->total_cost;
            // $this->customer_balance = $this->get_customer->current_balance +  session('order')[0]['unpaid'];
            // $this->servant_balance = $this->get_servant->current_balance +  session('order')[0]['unpaid'];


        }



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

            if (session()->has('order'))
            {
                // dd('sd');
                // $this->customer_balance             = $this->get_customer->customer_balance + $this->order[0]['unpaid'];
                $this->order[0]['customer_balance'] = $this->customer_balance;
                $this->order[0]['discount_type']    = $value;
                 session()->put('order', $this->order);
                //  dd($this->order[0]);
            }

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


             if (session()->has('order'))
            {
                // dd('sd');
                // $this->customer_balance             = $this->get_customer->customer_balance + $this->order[0]['unpaid'];
                $this->order[0]['customer_balance'] = $this->customer_balance;
                $this->order[0]['discount_type']    = "0";
                $this->order[0]['discount_percent'] = 0;
                $this->order[0]['discount_amount']  = 0;
                $this->order[0]['total_cost']       = $this->order[0]['total_before_discount'];
                if ($this->invoice_type == '0')
                {
                    $this->order[0]['paid']     = $this->total_before_discount;
                    $this->order[0]['unpaid']   = 0;
                }else
                {
                    $this->order[0]['unpaid']               = $this->total_before_discount;
                    $this->order[0]['unpaid']               = $this->total_before_discount;
                    // $this->order[0]['customer_balance']     =$this->get_customer->customer_balance + $this->total_before_discount;
                    $this->order[0]['paid']     = 0;
                }


                session()->put('order', $this->order);
                $this->treasury_balance                     = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
                // $this->customer_balance                     = $this->get_customer->customer_balance + $this->order[0]['unpaid'];

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
                 if (session()->has('order'))
                {
                    // dd('sd');
                    // $this->customer_balance             = $this->get_customer->customer_balance + $this->order[0]['unpaid'];
                    $this->order[0]['customer_balance'] = $this->customer_balance;
                    $this->order[0]['discount_percent'] = $value;
                    $this->order[0]['discount_amount']  = round($subtotal - $this->discount_amount, 2);
                    $this->order[0]['total_cost']       = $this->total_cost;
                    $this->order[0]['paid']             = $this->total_cost;
                    $this->order[0]['unpaid']           = 0;
                    session()->put('order', $this->order);
                    //  dd($this->order[0]);
                }
            }else
            {
                $this->unpaid                       = $this->total_cost;
                $this->paid                         = 0;

                if (session()->has('order'))
                {
                    // dd('sd');
                    // $this->customer_balance             = $this->get_customer->customer_balance + $this->order[0]['unpaid'];
                    $this->order[0]['customer_balance'] = $this->customer_balance;
                    $this->order[0]['discount_percent'] = $value;
                    $this->order[0]['discount_amount']  = round($subtotal - $this->discount_amount, 2);
                    $this->order[0]['total_cost']       = $this->total_cost;
                    $this->order[0]['paid']             = 0;
                    $this->order[0]['unpaid']           = $this->total_cost;;
                    session()->put('order', $this->order);
                    //  dd($this->order[0]);
                }
            }
            // $this->customer_balance = $this->get_customer->current_balance +  session('order')[0]['unpaid'];
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + session('order')[0]['paid'];
            // dd($this->treasury_balance);
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
                if (session()->has('order'))
                {
                    $this->order[0]['customer_balance'] = $this->get_customer->current_balance;
                    $this->order[0]['discount_percent'] = $this->discount_percent;
                    $this->order[0]['discount_amount']  = $this->discount_amount;
                    $this->order[0]['total_cost']       = $this->total_cost;
                    $this->order[0]['paid']             = $this->total_cost;
                    $this->order[0]['unpaid']           = 0;
                    session()->put('order', $this->order);
                }
            }else
            {
                $this->unpaid                       = $this->total_cost;
                $this->paid                         = 0;

                if (session()->has('order'))
                {
                    // dd('sd');
                    // $this->order[0]['customer_balance'] = $this->get_customer->customer_balance + $this->total_cost;
                    $this->order[0]['discount_percent'] = $this->discount_percent;
                    $this->order[0]['discount_amount']  = $this->discount_amount;
                    $this->order[0]['total_cost']       = $this->total_cost;
                    $this->order[0]['paid']             = 0;
                    $this->order[0]['unpaid']           = $this->total_cost;

                    session()->put('order', $this->order);
                    //  dd($this->order[0]);
                }
            }
            //  dd($this->order[0]['customer_balance']);
            $this->treasury_balance = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
            // $this->customer_balance = $this->get_customer->current_balance +  session('order')[0]['unpaid'];
        }
    }


    // عند تغير المبلغ المدفوع
    public function change_paid($value)
    {
        if ($this->order[0]['invoice_type'] == '1')
        {
            $this->unpaid                       = $this->total_cost - $value;
            $this->treasury_balance             = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
            // $this->customer_balance             = $this->get_customer->current_balance +  $this->unpaid;
            // $this->order[0]['customer_balance'] = $this->get_customer->customer_balance + $this->order[0]['unpaid'];
            $this->order[0]['paid']             = round($this->paid,2);
            $this->order[0]['unpaid']           = round($this->unpaid,2);
            session()->put('order', $this->order);
        }
    }



    // عند تغير المبلغ الغير المدفوع
    public function change_unpaid($value)
    {
        if ($this->order[0]['invoice_type'] == '1')
        {
            $this->paid                 = $this->total_cost - $value;
            $this->treasury_balance     = TreasuryTransation::where('shift_id',$this->get_active_shift->auto_serial)->sum('cash_amount') + $this->paid;
            // $this->customer_balance     = $this->get_customer->current_balance +  $value;
            $this->order[0]['paid']     = $this->paid;
            $this->order[0]['unpaid']   = $value;
            session()->put('order', $this->order);
        }
    }



    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->submit_rules());
    }


    public function saveData()
    {
        $order_data = session()->get('order', []);
         if (!empty($order_data) && is_array($order_data[0] ?? null))
        {
            $order_data[0]['tax_percent']           = $this->tax_percent;
            $order_data[0]['invoice_type']          = $this->invoice_type;
            $order_data[0]['total_cost_before_all'] = $this->total_cost_before_all;
            $order_data[0]['tax_value']             = $this->tax_value;
            $order_data[0]['discount_type']         = $this->discount_type;
            $order_data[0]['discount_percent']      = $this->discount_percent;
            $order_data[0]['discount_amount']       = $this->discount_amount;
            $order_data[0]['total_cost']            = $this->total_cost;
            $order_data[0]['paid']                  = $this->paid;
            $order_data[0]['unpaid']                = $this->unpaid;
            $order_data[0]['servant_code']          = $this->servant_code;
            $order_data[0]['customer_code']         = $this->customer_code;
            $order_data[0]['order_date']            = $this->order_date;
            $order_data[0]['matrial_types_id']      = $this->matrial_types_id;
            $order_data[0]['is_fixed_customer']     = '0';
            $order_data[0]['items_type']            = $this->items_type;
            $order_data[0]['sales_item_type']       = $this->sales_item_type;
            $order_data[0]['customer_balance']       = $this->customer_balance;
        }
         session()->put('order', $order_data);
        //  dd(session()->get('order'));
    }



    public function submit_rules()
    {
        return
        [
            'order_date'                => 'required|date',
            // 'is_fixed_customer'         => 'required|in:0,1',
            'items_type'                => 'required|in:0,1',
            'sales_item_type'           => 'required_if:items_type,0|in:0,1,2',
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
                'sales_item_type_detailes.required_if'          => 'نوع البيع للصنف مطلوب.',
                'sales_item_type_detailes.in'                   => 'اختار من ااقيمة المتاحة فقط',
                'item_code.required'                            => 'اسم الصنف مطلوب.',
                'item_code.exists'                              => 'هذا الصنف غير موجود .',
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
                'order_date.required'                           => 'تاريخ شراء الفاتورة مطلوب.',
                'order_date.date'                               => 'هذا الحقل يج ان يكون من نوع تاريخ.',
                // 'is_fixed_customer.required'                    => 'نوع العميل مطلوب',
                // 'is_fixed_customer.in'                          => 'قيمة الحقل يجب ان تكون ب نعم او لا',
                'items_type.required'                           => 'نوع حالة الاصناف داخل الفاتورة مطلوب',
                'items_type.in'                                 => 'قيمة الحقل يجب ان تكون ب ثابت او متغير',
                'sales_item_type.required_if'                   => ' حالة الفاتورة مطلوب',
                'sales_item_type.in'                            => 'قيمة الحقل يجب ان تكون ب جملة از نصف جملة او قطاعي',
                'invoice_type.required'                         => 'نوع الفاتورة مطلوب',
                'invoice_type.in'                               => 'قيمة الحقل يجب ان تكون ب كاش او اجل',
                'customer_code.required'                        => 'اسم العميل مطلوب.',
                'customer_code.exists'                          => 'هذا العميل غير موجود .',
                // 'servant_code.required'                        => 'اسم المندوب مطلوب.',
                // 'servant_code.exists'                          => 'هذا المندوب غير موجود .',

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

        if (!auth()->user()->can('اضافة فاتورة المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية اضافة فاتورة المبيعات ');
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


                // لو مفيش اصناف داخل الفاتورة
                if (!session()->has('sales_order_items'))
                {
                    $this->dispatch('salesOrderErrorVarMS',
                    [
                        'type'      => 'رسالة خطا',
                        'message'   => 'الصنف موجود بالفعل في الفاتورة'
                    ]);
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


            // 2 - CREATE SALES ORDER  انشاء جدول فاتورة المبيعات
                $this->new_order                                = new SalesOrder();
                $this->new_order->auto_serial_servant_invoice   = get_last_autoSerial(Servant::class, 'auto_serial');
                $this->new_order->order_date                    = $this->order_date;
                $this->new_order->matrial_types_id              = $this->matrial_types_id;
                $this->new_order->customer_code                 = $this->customer_code;
                $this->new_order->servant_code                  = $this->servant_code;
                $this->new_order->is_fixed_customer             = '0';
                $this->new_order->items_type                    = $this->items_type;
                if ($this->new_order->items_type == 0)
                {
                    $this->new_order->sales_item_type           = $this->sales_item_type;
                }
                $this->new_order->total_cost_before_all         = $this->total_cost_before_all;
                $this->new_order->discount_type                 = $this->discount_type;
                $this->new_order->discount_percent              = $this->discount_percent;
                $this->new_order->discount_amount               = $this->discount_amount;
                $this->new_order->total_before_discount         = $this->total_before_discount;
                $this->new_order->tax_percent                   = $this->tax_percent;
                $this->new_order->tax_value                     = $this->tax_value;
                $this->new_order->total_cost                    = $this->total_cost;
                $this->new_order->invoice_type                  = $this->invoice_type;

                if ($this->invoice_type == '0')
                {
                    $this->new_order->mony_for_account          = $this->paid;
                }else
                {
                    $this->new_order->mony_for_account          = $this->unpaid * (-1);
                }


                $this->new_order->paid                          = $this->paid;
                $this->new_order->approve                       = '1';
                $this->new_order->unpaid                        = $this->unpaid;
                $this->new_order->customer_account_number       = $this->customer_account->account_number;
                $this->new_order->customer_balance_before       = $this->customer_account->current_balance;
                $this->new_order->notes                         = $this->notes;
                $this->new_order->auto_serial                   = get_last_autoSerial_invoices(SalesOrder::class, 'auto_serial');
                $this->new_order->company_code                  = auth()->user()->company_code;
                $this->new_order->created_by                    = auth()->user()->id;
                $this->new_order->updated_by                    = auth()->user()->id;
                $this->new_order->approved_by                   = auth()->user()->id;
                $this->new_order->save();



            // 3  -- CREATE TREASURY TRANSACTIONS TABLE انشاء جدول حركة النقدية *******************************
                if ($this->paid > 0)
                {
                    $this->treasury_transation_table                            =  new TreasuryTransation();

                    $this->treasury_transation_table->treasury_id               = $this->active_treasury->id;
                    $this->treasury_transation_table->account_balance_before    = $this->account_balance_before_customer;
                    $this->treasury_transation_table->account_balance_servant_before    = $this->account_balance_before_customer;
                    $this->treasury_transation_table->cash_amount               = $this->paid;

                    if ($this->new_order->invoice_type == '0')      // اذا كان نوع الفاتورة كاش
                    {
                        $this->treasury_transation_table->cash_for_account      = 0;
                    } else
                    {
                        $this->treasury_transation_table->cash_for_account      = $this->unpaid *(-1);
                    }

                    $this->treasury_transation_table->shift_id                  = $this->get_active_shift->auto_serial;
                    $this->treasury_transation_table->isal_number               = $this->active_treasury->last_recept_recive + 1;
                    $this->treasury_transation_table->moveType_id               = $this->moveType->id;    // مؤقتا لحين تعديلها
                    $this->treasury_transation_table->account_type              = 'customers';
                    $this->treasury_transation_table->auto_serial               = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                    $this->treasury_transation_table->account_id                = $this->customer_account->account_number;
                    $this->treasury_transation_table->servant_cash_amount       = $this->paid * (-1);
                    $this->treasury_transation_table->servant_account_id       = $this->get_servant->account_number;

                    $this->treasury_transation_table->move_date                 = Carbon::today()->toDateString();
                    $this->treasury_transation_table->cash_source_type          = 'account';
                    $this->treasury_transation_table->is_approve                = 'approve';
                    $this->treasury_transation_table->company_code              = auth()->user()->company_code;
                    $this->treasury_transation_table->created_by                = auth()->user()->id;
                    $this->treasury_transation_table->updated_by                = auth()->user()->id;
                    $this->treasury_transation_table->invoice_type_accounts     = 'sales';
                    $this->treasury_transation_table->invoice_type              = $this->invoice_type;
                    $this->treasury_transation_table->save();

                    $this->treasury_transation_table->account_balance_after              = $updateAccountBalance->getCurrentBalance($this->get_customer->account_number,'customer','account_customer2');
                    $this->treasury_transation_table->save();

                    // dd($this->treasury_transation_table);
            // 4  -- UPDATE SALES ORDER TABLE تعديل جدول فواتير المبيعات *******************************

                    $this->new_order->treasures_transactions_id                 = $this->treasury_transation_table->auto_serial;
                    $this->new_order->save();


            // 5  -- UPDATE TREASURIES TABLE تعديل جدول الخزن *******************************
                    $this->treasury_table                       =  $this->active_treasury;
                    $this->treasury_table->last_recept_recive   = $this->treasury_transation_table->isal_number +1;
                    $this->treasury_table->save();

                    $this->treasury_balance = $this->treasury_balance;

            // 6  -- UPDATE CUSTOMER TABLE تعديل جدول العملاء *******************************

                    // $this->get_customer->current_balance  = getCoustomerCurrentBalance($this->customer_account->account_number,'account_customer2');
                    $this->get_customer->current_balance  = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->get_customer->save();

            // 7 -- UPDATE CUSTOMER ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالعملاء  *******************************
                    // $this->get_customer->customer_account->current_balance = getCoustomerCurrentBalance($this->customer_account->account_number,'account_customer2');
                    $this->get_customer->customer_account->current_balance  = $updateAccountBalance->getCurrentBalance($this->customer_account->account_number,'customer','account_customer2');
                    $this->get_customer->customer_account->save();

            // 8  -- UPDATE SERVANT TABLE تعديل جدول المناديب *******************************
                $this->get_servant      = Servant::where('servant_code',$this->servant_code)->with('account_customer')->first();
                // $this->get_servant->current_balance = getServantCurrentBalance($this->get_servant->account_number,'account_customer');
                $this->get_servant->current_balance = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_customer');
                $this->get_servant->save();
                // dd($this->treasury_transation_table->servant_account_id);
                // dd($this->get_servant->current_balance);

            // 9 -- UPDATE SERVANT ACCOUNTS TABLE تعديل جدول الحسابات الخاص بالمندوب  *******************************

                    // $this->get_servant->account_customer->current_balance = getServantCurrentBalance($this->get_servant->account_number,'account_customer');
                    $this->get_servant->account_customer->current_balance = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_customer');
                    $this->get_servant->account_customer->save();
                    // dd($this->get_servant->account_customer->current_balance);

            // 10  -- UPDATE TREASURY TRANSACTIONS TABLE تعديل جدول حركة النقدية *******************************
                    $this->treasury_transation_table->account_balance_servant_after              = $updateAccountBalance->getCurrentBalance($this->get_servant->account_number,'servant','account_servant');
                    $this->treasury_transation_table->save();

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

            // التعامل مع المخازن
                foreach (session()->get('sales_order_items') as $item_data)
                {
                    // 1 - نخصم الكميات  من المخازن حسب الباتش
                        $result         = app(StoreAllocator::class)->allocate($item_data);
                        $item_batches   = $result->batches;
                        $required_qty   = $result->required_qty;

                        $deductions = [];

                        foreach ($item_batches as $batch)
                        {
                            // اجيب الكميات في المخازن قبل خصم الصنف
                            $qty_before_all_stores = ItemBatch::where('item_code', $item_data['item_code'])->selectRaw('SUM(qty - deduction) as total')->value('total');
                            $qty_before_this_store = ItemBatch::where('item_code', $item_data['item_code'])->where('store_id', $item_data['store_id'])->selectRaw('SUM(qty - deduction) as total')->value('total');

                            if ($required_qty <= 0) break;
                            $batch->deduction += $required_qty;
                            $batch->save();
                            // dd($this->new_order_detailes->item_code);



                            // 2 - CREATE SALES ORDER DETAILES  انشاء جدول تفاصيل فاتورة المبيعات
                                $this->new_order_detailes                                               = new SalesOrderDetail();
                                $this->new_order_detailes->sales_item_type_detailes                     = $item_data['sales_item_type_detailes'];
                                $this->new_order_detailes->item_type                                    = $item_data['item_type'];
                                $this->new_order_detailes->auto_serial_sales_order                      = $this->new_order->auto_serial;
                                $this->new_order_detailes->item_code                                    = $item_data['item_code'];
                                $this->new_order_detailes->item_units_id                                = $item_data['item_units_id'];
                                $this->new_order_detailes->batch_id                                     = $batch->auto_serial;

                                $this->new_order_detailes->store_id                                     = $item_data['store_id'];
                                $this->new_order_detailes->is_master                                    = $item_data['is_master'];
                                $this->new_order_detailes->is_bouns                                     = $item_data['is_bouns'];
                                $this->new_order_detailes->qty                                          = $item_data['qty'];
                                $this->new_order_detailes->unit_price                                   = $item_data['unit_price'];
                                $this->new_order_detailes->total                                        = $item_data['qty'] * $item_data['unit_price'];


                                $this->new_order_detailes->order_date                                   = $this->order_date;
                                $this->new_order_detailes->production_date                              = $batch->production_date;
                                $this->new_order_detailes->expire_date                                  = $batch->expire_date;
                                $this->new_order_detailes->company_code                                 = auth()->user()->company_code;
                                $this->new_order_detailes->created_by                                   = auth()->user()->id;
                                $this->new_order_detailes->updated_by                                   = auth()->user()->id;

                                $this->new_order_detailes->save();
                                // dd($item_data['sales_item_type_detailes']);

                                //  dd($item_data['qty']);
                                // dd($this->new_order_detailes->qty);
                                // dd($this->new_order_detailes->sales_item_type_detailes);
                                // dd($item_data['sales_item_type_detailes']);

                                // ❖ نحسب الكمية بعد الإضافة
                                    $qty_after_all_stores = ItemBatch::where('item_code', $this->new_order_detailes->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                                    // dd($qty_after_all_stores);
                                    $qty_after_this_store = ItemBatch::where('item_code', $this->new_order_detailes->item_code)
                                        ->where('store_id', $this->new_order_detailes->store_id)
                                        ->selectRaw('SUM(qty - deduction) as total')->value('total');

                            // 3 - CREATE ITEM CARD MOVEMENTS  انشاء جدول حركة الصنف
                                $this->new_item_card_movements                                  = new ItemCardMovement();

                                $this->new_item_card_movements->store_id                        = $item_data['store_id'];
                                $this->new_item_card_movements->item_code                       = $item_data['item_code'];
                                $this->new_item_card_movements->item_card_movements_category_id = $this->itemCardMoveCategory->id;
                                $this->new_item_card_movements->item_card_movements_type_id     = $this->itemCardMoveType->id;
                                $this->new_item_card_movements->sales_order_id                  = $this->new_order->auto_serial;
                                $this->new_item_card_movements->sales_orderdetiles__id          = $this->new_order_detailes->id;
                                $this->new_item_card_movements->item_batch_id                   = $batch->auto_serial;
                                $this->new_item_card_movements->qty_before_movement             = $qty_before_all_stores;
                                $this->new_item_card_movements->qty_before_movement_in_store    = $qty_before_this_store;
                                $this->new_item_card_movements->qty_after_movement              = $qty_after_all_stores;
                                $this->new_item_card_movements->qty_after_movement_in_store     = $qty_after_this_store;
                                $this->new_item_card_movements->notes                           = $this->notes;
                                $this->new_item_card_movements->date                            = Carbon::now();
                                $this->new_item_card_movements->company_code                    = auth()->user()->company_code;
                                $this->new_item_card_movements->created_by                      = auth()->user()->id;
                                $this->new_item_card_movements->updated_by                      = auth()->user()->id;
                                $this->new_item_card_movements->save();


                            // 4 -- UPDATE ITEMS TABLE تعديل جدول الخاص الاصناف  *******************************

                                $get_item2                      = Item::where('item_code', $batch->item_code)->with('itemUnit')->first();
                                $qty_after_all_stores_parent    = ItemBatch::where('item_code', $batch->item_code)->selectRaw('SUM(qty - deduction) as total')->value('total');
                                $qty_after_all_stores_sub       = $qty_after_all_stores_parent * $get_item2->qty_sub_item_unit;
                                $qty_fraction_only              = $qty_after_all_stores_parent - floor($qty_after_all_stores_parent); // نسبة الكسر في الوحدة الرئيسية
                                $qty_value_fraction_only        = $qty_fraction_only * $get_item2->qty_sub_item_unit;

                                // dd($qty_after_all_stores_sub);


                                $get_item2->total_qty_for_parent    = floor($qty_after_all_stores_parent);
                                $get_item2->total_qty_for_sub_items = $qty_after_all_stores_sub;
                                $get_item2->sub_item_qty            = $qty_value_fraction_only;
                                $get_item2->save();

                    }
                }

            // 10 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('اضافة فاتورة مبيعات ', "اضافة فاتورة مبيعات {$this->get_customer->customer_account->name}", 'SalesOrder', $this->new_order->auto_serial,auth()->user()->id);


                DB::commit();

                // Dispatch events
                session()->forget('sales_order_items');     // مسح البيانات من السيشن
                session()->forget('order');                // مسح البيانات من السيشن
                $this->order = null;                  // مسح البيانات من السيشن

                $this->remove_session();
                $this->dispatch('salesOrderCreateMS');
                $this->dispatch('createModalToggle');
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


    // رزار مسح بيانات الفاتورة الي في السيشن
    public function remove_session()
    {
        session()->forget('sales_order_items');     // مسح البيانات من السيشن
        session()->forget('order');                 // مسح البيانات من السيشن
        $this->customer_code        = null;
        $this->servant_code         = null;
        $this->customer_account     = null;
        $this->servant_account      = null;
        $this->sales_item_type      = null;
        $this->items_type           = null;
        $this->customer_balance     = 0;
        $this->servant_balance      = 0;
        $this->order = null;
    }



    public function render()
    {

        $this->items_detailes = session()->get('sales_order_items', []);
        $this->order = session()->get('order', []);
        // dd($this->order);
        return view(
            'back-end.sales-order.create',
            [
                'customers'    => $this->customers,
                'stores'        => $this->stores,
            ]
        );
    }
}

<?php

namespace App\Livewire\BackEnd\SalesOrdersReturnsDetailes;

use App\Models\Item;
use App\Models\Store;
use App\Models\Servant;
use Livewire\Component;
use App\Models\Customer;
use App\Models\ItemUnit;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementType;
use App\Services\ActionHistoryService;
use App\Services\AccountBalanceService;
use App\Models\ItemCardMovementCategory;
use App\Livewire\BackEnd\SalesOrderReturns\Show;

class Update extends Component
{

     // المتغيرات
    public $itemID;
    public $orderID;
    public $order;
    public $items;
    public $item;
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
    public $item_order_detailes_qty;
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
    public $qty             = 0;
    public $is_bouns;
    public $unit_price      = 0;
    public $total           = 0;
    public $sales_item_type;
    public $itemCardMoveCategory;
    public $total_qty_sold;
    public $production_date;
    public $expire_date;



    protected $listeners = ['salesOrderDetailesReturnsUpdate'];

    public function salesOrderDetailesReturnsUpdate($id)
    {
        $this->itemID              = $id;
        $this->item                = SalesOrderDetail::where('id',$id)->with('order','item')->first();
        $this->order               = SalesOrder::where('auto_serial',$this->item->auto_serial_sales_order)->with('order_detailes')->first();
        $this->orderID             = $this->item->auto_serial_sales_order;

        $this->items                = SalesOrderDetail::with('item')
        ->where('company_code', auth()->user()->company_code)
        ->whereHas('order.customer', function ($query)
        {
            $query->where('account_number', $this->order->customer_account_number);
        })
        ->selectRaw('item_code, company_code, SUM(qty) as total_qty')
        ->groupBy('item_code', 'company_code')
        ->get()
        ->map(function ($detail)
        {
            return (object)
            [
                'item_name'     => $detail->item->name,   // جاي من العلاقة
                'company_code'  => $detail->company_code,
                'item_code'     => $detail->item->item_code,
                'total_qty'     => $detail->item->total_qty,
            ];
        });


        $this->customer             = Customer::where('customer_code', $this->order->customer_code)->first();
        $this->customer_account     = $this->customer->customer_account;
        $this->servant              = Servant::where('servant_code',$this->order->servant_code)->with('account_customer')->first();


        $this->items_type                       = $this->order->items_type;
        $this->sales_item_type                  = $this->order->sales_item_type;
        $this->sales_item_type_detailes         = $this->order->items_type == '0' ? $this->order->items_type : null;


        $this->item_selected_detailes   = Item::where('item_code', $this->item->item_code)->with(['itemUnitChild', 'itemUnit'])->withSum(['sales_order_detailes as total_qty_sold' => function($q)
        {
            $q->whereHas('order',function($q2)
            {
                $q2->where('order_type','0');
            });
        }], 'qty')->first();


        $this->stores                   = Store::all();


        // $items_order = $this->order->order_detailes->where('item_code',$this->item->item_code)->get();
        // if ($this->item->is_master == 'master')
        // {
        //     $item_order_detailes_qty_master = $this->order->order_detailes->where('item_code',$this->item->item_code)->where('is_master','master')->sum('qty');
        //     $this->item_order_detailes_qty = $this->item_order_detailes_qty;
        //     $this->total_qty_sold           = $this->order->order_detailes->count() == 0 ? $this->item_selected_detailes->total_qty_sold : ($this->item_selected_detailes->total_qty_sold - $this->item_order_detailes_qty);

        // }else
        // {
        //     $item_order_detailes_qty_sub_master = $this->order->order_detailes->where('item_code',$this->item->item_code)->where('is_master','sub_master')->sum('qty');
        //     $this->item_order_detailes_qty = $this->item_order_detailes_qty  /$this->item_selected_detailes->qty_sub_item_unit;
        //     $this->total_qty_sold           = $this->order->order_detailes->count() == 0 ? $this->item_selected_detailes->total_qty_sold : ($this->item_selected_detailes->total_qty_sold - $this->item_order_detailes_qty);
        // }
        // $this->item_order_detailes_qty = $item_order_detailes_qty_master * $item_order_detailes_qty_sub_master;
        // dd($this->item_order_detailes_qty);


        // الكمية الأساسية
$item_order_detailes_qty_master = $this->order->order_detailes
    ->where('item_code', $this->item->item_code)
    ->where('is_master', 'master')
    ->sum('qty');

// الكمية الفرعية
$item_order_detailes_qty_sub_master = $this->order->order_detailes
    ->where('item_code', $this->item->item_code)
    ->where('is_master', 'sub_master')
    ->sum('qty');

// نحول الكمية الفرعية إلى أساسية
$item_order_detailes_qty_sub_as_master = $item_order_detailes_qty_sub_master / ($this->item_selected_detailes->qty_sub_item_unit ?: 1);

// اجمالي الكمية داخل الفاتورة (بالوحدة الأساسية)
$this->item_order_detailes_qty = $item_order_detailes_qty_master + $item_order_detailes_qty_sub_as_master;

// اجمالي المباع
$this->total_qty_sold = $this->item_selected_detailes->total_qty_sold - $this->item_order_detailes_qty;

// للتأكد
// dd([
//     'qty_master' => $item_order_detailes_qty_master,
//     'qty_sub_master' => $item_order_detailes_qty_sub_master,
//     'qty_sub_as_master' => $item_order_detailes_qty_sub_as_master,
//     'total_order_qty_master' => $this->item_order_detailes_qty,
//     'total_qty_sold' => $this->total_qty_sold,
// ]);

        $this->check_itemUnit_type      = ItemUnit::select('id', 'is_master', 'name')->find($this->item->item_units_id);



        $this->item_code                        = $this->item->item_code;
        $this->sales_item_type_detailes         = $this->item->sales_item_type_detailes;
        $this->is_bouns                         = $this->item->is_bouns;
        $this->item_units_id                    = $this->item->item_units_id;
        $this->store_id                         = $this->item->store_id;
        $this->qty                              = $this->item->qty;
        $this->unit_price                       = $this->item->unit_price;
        $this->total                            = $this->item->total;
        $this->production_date                  = $this->item->production_date;
        $this->expire_date                      = $this->item->expire_date;


        // show Create modal
        $this->dispatch('updateModalToggle');
    }


    public function mount()
    {
        $this->get_active_shift         = Active_shift();
        $this->itemCardMoveType         = ItemCardMovementType::where('company_code',auth()->user()->company_code)->where('name','اضافة الي المخزن')->first();
        $this->itemCardMoveCategory     = ItemCardMovementCategory::where('company_code',auth()->user()->company_code)->where('name','اضافة كمية من الصنف في المخزن نظير مرتجع فاتورة مبيعات')->first();
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
        // dd($value);
        if (!empty($value))
        {
            $this->item_selected_detailes   = Item::where('item_code', $value)->with(['itemUnitChild', 'itemUnit'])->withSum(['sales_order_detailes as total_qty_sold' => function($q)
            {
                $q->whereHas('order',function($q2)
                {
                    $q2->where('order_type','0');
                });
            }], 'qty')->first();


            if ($this->item_selected_detailes)
            {
                $this->item_is_change           = $this->item_selected_detailes->is_change;
                $this->total_qty_sold           = $this->order->order_detailes->where('item_code',$this->item_selected_detailes->item_code)->count() == 0 ? $this->item_selected_detailes->total_qty_sold : ($this->item_selected_detailes->total_qty_sold - $this->order->order_detailes->where('item_code',$this->item_selected_detailes->item_code)->sum('qty')) ;
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
        $this->qty            = 0;
        $this->total          = 0;

        $this->check_itemUnit_type  = ItemUnit::select('id', 'is_master', 'name')->find($value);
        $this->stores               = Store::all();
        $this->total_qty_sold       = $this->check_itemUnit_type->is_master == 'master' ? $this->total_qty_sold : $this->total_qty_sold * $this->item_selected_detailes->qty_sub_item_unit;
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
        $this->store_id         = $value;

    }




    public function updateQty($value)
    {
        $this->qty = $value;
        $this->total = (float)$this->unit_price * (float)$this->qty;

            if ($value > $this->total_qty_sold)
            {
                $this->qty = $this->total_qty_sold;
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


        if (!is_null($this->total_qty_sold))
        {
            $qtyRules[] = 'max:' . $this->total_qty_sold;
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
            'production_date'           => $this->item_selected_detailes && $this->item_selected_detailes->item_type == 1 ? 'required|date' : 'nullable',
            'expire_date'               => $this->item_selected_detailes && $this->item_selected_detailes->item_type == 1 ? 'required|date|after_or_equal:production_date' : 'nullable',
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
                'production_date.required'              => 'تاريخ الإنتاج مطلوب للصنف المحدد.',
                'expire_date.required'                  => 'تاريخ الانتهاء مطلوب للصنف المحدد.',
                'expire_date.after_or_equal'            => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ الإنتاج.',
            ];
    }



  public function submit(ActionHistoryService $action_history,AccountBalanceService $updateAccountBalance)
    {
        if (!auth()->user()->can('تعديل صنف لفاتورة مرتجع المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية تعديل صنف جديد لفاتورة مرتجع المبيعات ');
        }

        $this->validate($this->submit_rules(), $this->messages());

        try
        {

            // 1 - START VALIDATIONS *************************************************************************
                // التاكد من ان الكمية متاحة في المخزن
                if ($this->total_qty_sold == 0 || $this->total_qty_sold < $this->qty)
                {
                    $this->addError('qty', 'الكمية المباعة غير متاحة في المخزن.');
                    return;
                }

                DB::beginTransaction();

                    // 2 - CREATE SALES ORDER DETAILES تعديل جدول تفاصيل فاتورة المبيعات
                        $this->item->sales_item_type_detailes                     = $this->sales_item_type_detailes;
                        $this->item->item_type                                    = $this->item_selected_detailes->item_type;
                        $this->item->item_code                                    = $this->item_code;
                        $this->item->item_units_id                                = $this->item_units_id;
                        $this->item->store_id                                     = $this->store_id;
                        $this->item->is_master                                    = $this->check_itemUnit_type->is_master;
                        $this->item->is_bouns                                     = $this->is_bouns;
                        $this->item->qty                                          = $this->qty;
                        $this->item->unit_price                                   = $this->unit_price;
                        $this->item->total                                        = $this->unit_price * $this->qty;
                        $this->item->production_date                              = $this->production_date;
                        $this->item->expire_date                                  = $this->expire_date;
                        $this->item->updated_by                                   = auth()->user()->id;
                        $this->item->save();





                    // 3 - CREATE SALES ORDER   تعديل جدول  فاتورة المبيعات
                        $this->order->total_cost_before_all     = $this->order->order_detailes->sum('total');
                        $this->order->tax_value                 = ($this->order->total_cost_before_all * $this->order->tax_percent) / 100;
                        $this->order->total_before_discount     = ($this->order->total_cost_before_all + $this->order->tax_value);

                        if ($this->order->discount_type == '1')     // لو نوع الخصم نسبة يبقي حنعدل نسبة الخصم
                        {
                            $this->order->discount_amount       =  ($this->order->total_before_discount * $this->order->discount_percent) / 100;
                            $this->order->total_cost            = $this->order->total_before_discount - $this->order->discount_amount;
                        }else
                        {
                            $this->order->total_cost            = $this->order->total_before_discount - $this->order->discount_amount;
                        }
                        $this->order->mony_for_account          = $this->order->total_cost;
                        $this->order->paid                      = $this->order->total_cost;
                        $this->order->updated_by                = auth()->user()->id;
                        $this->order->save();

//  dd($this->order);
                DB::commit();

                // Dispatch events


                $this->dispatch('purcgaseOrderDetailesUpdateMS');
                $this->dispatch('updateModalToggle');
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
          $is_item_type_date_required         = $this->item_selected_detailes && $this->item_selected_detailes->item_type == "1";


        return view('back-end.sales-orders-returns-detailes.update',
        [
            'is_item_type_date_required'    => $is_item_type_date_required,
            // 'item' => $this->item,
        ]);
    }
}

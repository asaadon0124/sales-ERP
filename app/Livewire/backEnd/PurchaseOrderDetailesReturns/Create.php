<?php

namespace App\Livewire\BackEnd\PurchaseOrderDetailesReturns;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\ItemBatch;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetailes;
use App\Services\GetAverageItemPriceService;
use App\Services\GetAverageSubItemPriceService;
use App\Livewire\BackEnd\PurchaseOrderReturns\Show;

class Create extends Component
{
    public $orderID;
    public $order;
    public $items;
    public $item_selected_detailes  = null;
    public $check_itemUnit_type     = null;
    public $item_batchs             = null;
    public $item_batch              = null;
    public $store_qty              = 0;


    public $item_code;
    public $item_units_id = null;
    public $item_type;
    public $qty             = 0;
    public $unit_price      = 0;
    public $total           = 0;
    public $production_date;
    public $expire_date;
    public $batch_id;


    protected $listeners = ['orderDetailesReturnsCreate'];


    public function orderDetailesReturnsCreate($id)
    {
        $this->orderID  = $id;
        $this->order    = PurchaseOrder::where('auto_serial', $id)->with('order_detailes')->first();
        $this->items    = Item::where('company_code', auth()->user()->company_code)->with('itemUnit', 'itemUnitChild')->whereHas('item_batches', function ($q) {
            $q->where('store_id', $this->order->store_id);
        })->get();

        // show Create modal
        $this->dispatch('createModalToggle');
    }




    public function item_select($value)
    {
        $this->item_batchs      = null;
        $this->item_batch       = null;
        $this->batch_id         = null;
        $this->store_qty        = null;    // ← تصفير الكمية
        $this->qty              = 0;
        $this->unit_price       = 0;
        $this->total            = 0;
        $this->production_date  = 0;
        $this->expire_date      = 0;
        $this->item_units_id     = null;

        if ($value != '') {
            $this->item_selected_detailes = Item::where('item_code', $value)->with('itemUnitChild', 'itemUnit')->first();
            $this->item_type = $this->item_selected_detailes->item_type;
        } else {
            $this->item_code = '';
            $this->item_selected_detailes = null;
            $this->item_units_id = null;
        }
    }



    public function check_item_unit_type($value)
    {
        $this->item_batchs      = null;
        $this->item_batch       = null;
        $this->batch_id         = null;
        $this->store_qty        = null;    // ← تصفير الكمية
        $this->qty              = 0;
        $this->unit_price       = 0;
        $this->total            = 0;
        $this->production_date  = 0;
        $this->expire_date      = 0;

        if ($value)
        {
            $this->check_itemUnit_type  = ItemUnit::select('id', 'is_master')->where('id', $value)->first();

            // $this->item_batchs          = ItemBatch::where('store_id', $this->order->store_id)->where('item_code', $this->item_selected_detailes->item_code)->get();
            $this->item_batchs = ItemBatch::select(
                'id',
                'store_id',
                'auto_serial',
                'item_code',
                DB::raw('(qty - deduction) as remaining_quantity')
            )
                ->where('store_id', $this->order->store_id)
                ->where('item_code', $this->item_selected_detailes->item_code)
                ->having('remaining_quantity', '>', 0)
                ->get();
        }
    }


    public function change_store($value)
    {
         $this->item_batch = ItemBatch::select(
                'id',
                'store_id',
                'auto_serial',
                'item_cost_price',
                'production_date',
                'expire_date',
                'item_code',
                DB::raw('(qty - deduction) as remaining_quantity')
            )
                ->where('store_id', $this->order->store_id)
                ->where('auto_serial', $value)
                ->where('item_code', $this->item_selected_detailes->item_code)
                ->having('remaining_quantity', '>', 0)
                ->with('item')
                ->first();


        if ($value)
        {

            if (!empty($this->item_batch))
            {
                $this->store_qty = $this->check_itemUnit_type->is_master === 'master'
                    ? $this->item_batch->remaining_quantity
                    : $this->item_batch->remaining_quantity * $this->item_batch->item->qty_sub_item_unit;

                $this->qty                  = $this->check_itemUnit_type->is_master == 'master' ? $this->store_qty : ($this->store_qty);
                $this->unit_price           = $this->check_itemUnit_type->is_master == 'master' ? $this->item_batch->item_cost_price : ($this->item_batch->item_cost_price / $this->item_batch->item->qty_sub_item_unit);
                $this->total                = $this->qty * $this->unit_price;
                $this->production_date      = $this->item_batch->production_date;
                $this->expire_date          = $this->item_batch->expire_date;
                

            }
        } else
        {
            $this->item_batch = null;
        }
    }




    public function updateQty($value)
    {
        $this->qty      = $value;
        $this->total    = $this->unit_price * $this->qty;
    }


    public function updateUnit_price($value)
    {
        $this->unit_price   = $value;
        $this->total        = $this->unit_price * $this->qty;
    }




    public function submit()
    {
        $validated = $this->validate(
            [
                'item_code'                 => 'required',
                'item_units_id'             => 'required_if:item_code,!=,',
                'qty'                       => 'required_if:item_code,!=,|numeric|gt:0',
                'unit_price'                => 'required_if:item_code,!=,|numeric|gt:0',
                'total'                     => 'required_if:item_code,!=,|numeric|gt:0',
                'production_date'           => $this->item_selected_detailes && $this->item_selected_detailes->item_type == 1 ? 'required|date' : 'nullable',
                'expire_date'               => $this->item_selected_detailes && $this->item_selected_detailes->item_type == 1 ? 'required|date|after_or_equal:production_date' : 'nullable',
            ],
            [
                'item_code.required'                    => 'اسم الصنف مطلوب.',
                'item_units_id.required'                => 'وحدة الصنف مطلوب.',
                'qty.required'                          => 'كمية الصنف مطلوبة.',
                'qty.numeric'                           => 'كمية الصنف يجب ان تكون ارقام فقط.',
                'qty.gt'                                => 'كمية الصنف يجب ان تكون اكبر من 0.',
                'unit_price.required'                   => 'سعر الصنف مطلوبة.',
                'unit_price.numeric'                    => 'سعر الصنف يجب ان يكون ارقام فقط.',
                'unit_price.gt'                         => 'سعر الصنف يجب ان يكون اكبر من 0.',
                'total.required'                        => 'اجمالي الصنف مطلوب.',
                'total.numeric'                         => 'اجمالي الصنف يجب ان يكون ارقام فقط.',
                'total.gt'                              => 'اجمالي الصنف يجب ان يكون اكبر من 0.',
                'production_date.required'              => 'تاريخ الإنتاج مطلوب للصنف المحدد.',
                'expire_date.required'                  => 'تاريخ الانتهاء مطلوب للصنف المحدد.',
                'expire_date.after_or_equal'            => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ الإنتاج.',
            ]
        );

        if ($this->store_qty <= 0) {
            $this->addError('batch_id', 'هذا المخزن لا يوجد به رصيد .');
            return;
        }


        if ($this->store_qty <= 0 || $this->store_qty < $this->qty) {
            $this->addError('batch_id', 'هذا المخزن لا يوجد به رصيد .');
            return;
        }


        try {
            DB::beginTransaction();
            // 1 - CREATE PURCHASES ORDERS DETAILES
            $create = PurchaseOrderDetailes::create(
                [
                    'auto_serial_purchase_orders'   => $this->orderID,
                    'item_code'                     => $this->item_code,
                    'item_units_id'                 => $this->item_units_id,
                    'item_type'                     => $this->item_type,
                    'is_master'                     => $this->check_itemUnit_type->is_master,
                    'qty'                           => $this->qty,
                    'unit_price'                    => $this->unit_price,
                    'total'                         => $this->total,
                    'order_date'                    => $this->order->order_date,
                    'production_date'               => $this->production_date,
                    'expire_date'                   => $this->expire_date,
                    'batch_id'                      => $this->batch_id,
                    'company_code'                  => auth()->user()->company_code,
                    'created_by'                    => auth()->user()->id,
                    'updated_by'                    => auth()->user()->id,
                ]
            );


            // 2 - UPDATE PURCHASES ORDER TOTAL COST
            $this->order->update(
                [
                    'total_cost_before_all' => total_cost_before_all($this->orderID),
                    'total_cost'            => total_cost_after_all($this->order),
                    'updated_by'            => auth()->user()->id,
                ]
            );


            // // 3 - UPDATE ITEMS TABLE
            // if ($this->check_itemUnit_type->is_master == 'master')   //   لو الوحدة الي مختارة اساسية
            // {
            //     $master_price_Service                                       = new GetAverageItemPriceService();
            //     $new_master_cost_price                                      = number_format($master_price_Service->calculate($this->item_code,$this->item_units_id), 2, '.', '') != 0 ? number_format($master_price_Service->calculate($this->item_code,$this->item_units_id), 2, '.', '') : $this->item_selected_detailes->item_cost_price;
            //     $new_sub_master_cost_price                                  = number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') : $this->item_selected_detailes->sub_item_cost_price;

            //     // تعديل اسعار الوحدة الاب *************************
            //     $this->item_selected_detailes->item_wholesale_price         =  number_format($master_price_Service->get_new_item_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->item_Half_wholesale_price    =  number_format($master_price_Service->get_new_item_Half_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->item_retail_price            =  number_format($master_price_Service->get_new_item_retail_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->item_cost_price              =  $new_master_cost_price;

            //     // تعديل اسعار الوحدة الابن *************************
            //     $this->item_selected_detailes->sub_item_wholesale_price     = number_format($master_price_Service->get_new_sub_item_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->sub_item_Half_wholesale_price= number_format($master_price_Service->get_new_sub_item_Half_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->sub_item_retail_price        = number_format($master_price_Service->get_new_sub_item_retail_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->sub_item_cost_price          = $new_sub_master_cost_price;
            //     $this->item_selected_detailes->save();

            // }else           //  لو الوحدة المختارة فرعية
            // {
            //     $sub_master_price_Service                                   = new GetAverageSubItemPriceService();
            //     $new_sub_master_cost_price                                  = number_format($sub_master_price_Service->calculate_sub_cost($this->item_code,$this->item_units_id), 2, '.', '')  != 0 ? number_format($sub_master_price_Service->calculate_sub_cost($this->item_code,$this->item_units_id), 2, '.', '') : $this->item_selected_detailes->sub_item_cost_price ;
            //     $new_master_cost_price                                      = number_format($sub_master_price_Service->calculate($new_sub_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($sub_master_price_Service->calculate($new_sub_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') : $this->item_selected_detailes->item_cost_price;


            //      // تعديل اسعار الوحدة الاب *************************
            //     $this->item_selected_detailes->item_wholesale_price         =  number_format($sub_master_price_Service->get_new_item_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->item_Half_wholesale_price    =  number_format($sub_master_price_Service->get_new_item_Half_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->item_retail_price            =  number_format($sub_master_price_Service->get_new_item_retail_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->item_cost_price              =  $new_master_cost_price;



            //      // تعديل اسعار الوحدة الابن *************************
            //     $this->item_selected_detailes->sub_item_wholesale_price     = number_format($sub_master_price_Service->get_new_sub_item_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->sub_item_Half_wholesale_price= number_format($sub_master_price_Service->get_new_sub_item_Half_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->sub_item_retail_price        = number_format($sub_master_price_Service->get_new_sub_item_retail_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
            //     $this->item_selected_detailes->sub_item_cost_price          = $new_sub_master_cost_price;

            //     $this->item_selected_detailes->save();
            // }
            // dd($this->item_selected_detailes);

            DB::commit();


            $this->reset(
                [
                    'item_code',
                    'production_date',
                    'qty',
                    'unit_price',
                    'expire_date',
                    'total',
                    'item_selected_detailes',
                    'item_units_id',
                    'check_itemUnit_type',
                ]
            );
            $this->item_code = '';
            // dd('ds');
            // Dispatch events
            $this->dispatch('purcgaseOrderDetailesCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Show::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            $this->dispatch('accountsErrorMS');                   // Flash Message
        }
    }


    public function render()
    {
        $is_item_type_date_required         = $this->item_selected_detailes && $this->item_selected_detailes->item_type == "1";
        return view(
            'back-end.purchase-order-detailes-returns.create',
            [
                'items'                         => $this->items,
                'is_item_type_date_required'    => $is_item_type_date_required,
            ]
        );
    }
}

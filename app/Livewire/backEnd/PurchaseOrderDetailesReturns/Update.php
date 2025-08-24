<?php

namespace App\Livewire\BackEnd\PurchaseOrderDetailesReturns;

use App\Models\Item;
use App\Models\Store;
use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\Supplier;
use App\Models\ItemBatch;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetailes;
use App\Services\GetAverageItemPriceService;
use App\Services\GetAverageSubItemPriceService;
use App\Livewire\BackEnd\PurchaseOrderReturns\Show;

class Update extends Component
{

     public $itemID;
    public $orderID;
    public $order;
    public $item;
    public $items;
    public $item_selected_detailes;
    public $check_itemUnit_type;

    public $item_batchs;
    public $item_batch;
    public $batch_id;
    public $store_qty;
    public $total_returns_qty_master;

    public $item_code;
    public $item_units_id;
    public $item_type;
    public $qty = 0;
    public $unit_price = 0;
    public $total = 0;
    public $production_date;
    public $expire_date;
    public $is_master;


     protected $listeners = ['purchaseOrderDetailesReturnsUpdate'];

    public function purchaseOrderDetailesReturnsUpdate($id)
    {

        $this->item                     = PurchaseOrderDetailes::find($id);
        $this->itemID                   = $id;
        $this->item_code                = $this->item->item_code;
        $this->item_selected_detailes   = Item::where('item_code', $this->item_code)->with('itemUnitChild', 'itemUnit')->first();

        $this->item_units_id            = $this->item->item_units_id;
        $this->item_type                = $this->item->item_type;
        $this->qty                      = $this->item->qty;
        $this->unit_price               = $this->item->unit_price;
        $this->total                    = $this->item->total;
        $this->production_date          = $this->item->production_date;
        $this->expire_date              = $this->item->expire_date;
        $this->is_master                = $this->item->is_master;
        $this->batch_id                 = $this->item->batch_id;

        $this->orderID                  = $this->item->auto_serial_purchase_orders;
        $this->order                    = PurchaseOrder::where('auto_serial', $this->orderID)->with('order_detailes')->first();
        $this->items                    = Item::where('company_code', auth()->user()->company_code)->with('itemUnit', 'itemUnitChild')->get();
        $this->item_batch               = ItemBatch::where('auto_serial',$this->batch_id)->with('item')->first();
        $this->item_batchs              = ItemBatch::where('store_id',$this->order->store_id)->where('item_code',$this->item_selected_detailes->item_code)->get();
        $this->check_itemUnit_type      = ItemUnit::select('id','is_master')->where('id',$this->item_units_id)->first();

        $returns_qty_master         = PurchaseOrderDetailes::where('is_master','master')->whereHas('order',function($q)
        {
            $q->where('order_type','2')->where('approve','0');
        })->sum('qty');

        $returns_qty_sub_master      = PurchaseOrderDetailes::where('is_master','sub_master')->whereHas('order',function($q)
        {
            $q->where('order_type','2')->where('approve','0');
        })->sum('qty');

        $total_returns_qty_master    = $returns_qty_master + ($returns_qty_sub_master / $this->item_batch->item->qty_sub_item_unit);
         if (!empty($this->item_batch))
        {
            $this->store_qty = $this->check_itemUnit_type->is_master === 'master'
                ? $this->item_batch->qty - $total_returns_qty_master
                : ($this->item_batch->qty - $total_returns_qty_master) * $this->item_batch->item->qty_sub_item_unit;
        }

    // dd($this->store_qty);

        $this->dispatch('updateModalToggle');
    }




    public function item_select($value)
    {
       $this->resetItemFields();

        if ($value != '')
        {
            $this->item_selected_detailes = Item::where('item_code',$value)->with('itemUnitChild','itemUnit')->first();
            $this->item_type = $this->item_selected_detailes->item_type;
        }else
        {
            $this->item_code = '';
            $this->item_selected_detailes = null;
            $this->item_units_id = null;
        }
    }



    public function check_item_unit_type($value)
    {
        $this->resetItemFields(['item_units_id','barch_id']);
        if ($value)
        {
            $this->check_itemUnit_type  = ItemUnit::select('id','is_master')->where('id',$value)->first();
            $this->item_batchs          = ItemBatch::where('store_id',$this->order->store_id)->where('item_code',$this->item_selected_detailes->item_code)->get();
        }
    }


    private function calculateTotalReturnsQty($item)
    {
        $returns_master = PurchaseOrderDetailes::where('is_master', 'master')
            ->whereHas('order', fn($q) => $q->where('order_type', '2')->where('approve', '0'))
            ->sum('qty');

        $returns_sub = PurchaseOrderDetailes::where('is_master', 'sub_master')
            ->whereHas('order', fn($q) => $q->where('order_type', '2')->where('approve', '0'))
            ->sum('qty');

        return $this->total_returns_qty_master =  $returns_master + ($returns_sub / $item->qty_sub_item_unit);
    }


    public function change_store($value)
    {
        $this->item_batch               = ItemBatch::where('auto_serial',$value)->with('item')->first();
        if ($this->item_batch)
        {
            $this->calculateTotalReturnsQty($this->item_batch->item);

             if ($value)
            {
                // dd($this->check_itemUnit_type);
                if (!empty($this->item_batch))
                {
                    $this->store_qty = $this->check_itemUnit_type->is_master === 'master'
                        ? $this->item_batch->qty - $this->total_returns_qty_master
                        : ($this->item_batch->qty - $this->total_returns_qty_master) * $this->item_batch->item->qty_sub_item_unit;
                }

                $this->qty                  = $this->check_itemUnit_type->is_master == 'master' ? $this->store_qty : ($this->store_qty);
                $this->unit_price           = $this->check_itemUnit_type->is_master == 'master' ? $this->item_batch->item_cost_price : ($this->item_batch->item_cost_price / $this->item_batch->item->qty_sub_item_unit);
                $this->total                = $this->qty * $this->unit_price;
                $this->production_date      = $this->item_batch->production_date;
                $this->expire_date          = $this->item_batch->expire_date;
            }


        }else
        {
            $this->resetItemFields(['item_code','item_units_id','item_batchs']);
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



    private function resetItemFields(array $except = [])
    {
        if (!in_array('item_batchs', $except))         $this->item_batchs = null;
        if (!in_array('item_batch', $except))          $this->item_batch = null;
        if (!in_array('batch_id', $except))            $this->batch_id = null;
        if (!in_array('store_qty', $except))           $this->store_qty = null;
        if (!in_array('qty', $except))                 $this->qty = 0;
        if (!in_array('unit_price', $except))          $this->unit_price = 0;
        if (!in_array('total', $except))               $this->total = 0;
        if (!in_array('production_date', $except))     $this->production_date = null;
        if (!in_array('expire_date', $except))         $this->expire_date = null;
        if (!in_array('item_units_id', $except))       $this->item_units_id = null;
    }




    public function submit()
    {
        $validated = $this->validate(
            [
                'item_code'                 => 'required',
                'item_units_id'             => 'required|exists:item_units,id',
                'qty'                       => 'required_if:item_code,!=,,numeric|gt:0',
                'unit_price'                => 'required_if:item_code,!=,|numeric|gt:0',
                'total'                     => 'required_if:item_code,!=,|numeric|gt:0',
                'production_date'           => $this->item_selected_detailes && $this->item_selected_detailes->item_type == 1 ? 'required|date' : 'nullable',
                'expire_date'               => $this->item_selected_detailes && $this->item_selected_detailes->item_type == 1 ? 'required|date|after_or_equal:production_date' : 'nullable',


            ],
            [
                'item_code.required'                    => 'اسم الصنف مطلوب.',
                'item_units_id.required'                => 'وحدة الصنف مطلوبة.',
                'item_units_id.exists'                  => 'وحدة الصنف غير موجودة.',
                'qty.required_if'                       => 'كمية الصنف مطلوبة.',
                'qty.numeric'                           => 'كمية الصنف يجب ان تكون ارقام فقط.',
                'qty.gt'                                => 'كمية الصنف يجب ان تكون اكبر من 0.',
                'unit_price.required'                   => 'سعر الوحدة مطلوب.',
                'unit_price.numeric'                    => 'سعر الوحدة يجب ان يكون ارقام فقط.',
                'unit_price.gt'                         => 'سعر الوحدة يجب ان يكون اكبر من 0.',
                'total.required'                        => 'اجمالي الصنف مطلوب.',
                'total.numeric'                         => 'اجمالي الصنف يجب ان يكون ارقام فقط.',
                'total.gt'                              => 'اجمالي الصنف يجب ان يكون اكبر من 0.',
                'production_date.required'              => 'تاريخ الإنتاج مطلوب للصنف المحدد.',
                'expire_date.required'                  => 'تاريخ الانتهاء مطلوب للصنف المحدد.',
                'expire_date.after_or_equal'            => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ الإنتاج.',
            ]
        );

        try
        {
        // dd($this->store_qty);
             if ($this->store_qty <= 0 || $this->store_qty < $this->qty)
            {
                $this->addError('batch_id', 'هذا المخزن لا يوجد به رصيد .');
                return;
            }
            DB::beginTransaction();
            // 1 - UPDATE PURCHASES ORDERS DETAILES
                $this->item->update(
                [
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
                    'company_code'                  => auth()->user()->company_code,
                    'created_by'                    => auth()->user()->id,
                    'updated_by'                    => auth()->user()->id,
                ]);

            // 2 - UPDATE PURCHASES ORDER TOTAL COST
            $this->order->update(
            [
                'total_cost_before_all' => total_cost_before_all($this->orderID),
                'total_cost'            => total_cost_after_all($this->order),
                'updated_by'            => auth()->user()->id,
            ]);

//  dd('ds');
            // 3 - UPDATE ITEMS TABLE
            if ($this->check_itemUnit_type->is_master == 'master')   //   لو الوحدة الي مختارة اساسية
            {
                $master_price_Service                                       = new GetAverageItemPriceService();
                $new_master_cost_price                                      = number_format($master_price_Service->calculate($this->item_code,$this->item_units_id), 2, '.', '') != 0 ? number_format($master_price_Service->calculate($this->item_code,$this->item_units_id), 2, '.', '') : $this->item_selected_detailes->item_cost_price;
                $new_sub_master_cost_price                                  = number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($master_price_Service->calculate_sub_cost($new_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') : $this->item_selected_detailes->sub_item_cost_price;

                // تعديل اسعار الوحدة الاب *************************
                $this->item_selected_detailes->item_wholesale_price         =  number_format($master_price_Service->get_new_item_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->item_Half_wholesale_price    =  number_format($master_price_Service->get_new_item_Half_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->item_retail_price            =  number_format($master_price_Service->get_new_item_retail_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->item_cost_price              =  $new_master_cost_price;

                // تعديل اسعار الوحدة الابن *************************
                $this->item_selected_detailes->sub_item_wholesale_price     = number_format($master_price_Service->get_new_sub_item_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->sub_item_Half_wholesale_price= number_format($master_price_Service->get_new_sub_item_Half_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->sub_item_retail_price        = number_format($master_price_Service->get_new_sub_item_retail_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->sub_item_cost_price          = $new_sub_master_cost_price;
                $this->item_selected_detailes->save();

            }else           //  لو الوحدة المختارة فرعية
            {
                $sub_master_price_Service                                   = new GetAverageSubItemPriceService();
                $new_sub_master_cost_price                                  = number_format($sub_master_price_Service->calculate_sub_cost($this->item_code,$this->item_units_id), 2, '.', '')  != 0 ? number_format($sub_master_price_Service->calculate_sub_cost($this->item_code,$this->item_units_id), 2, '.', '') : $this->item_selected_detailes->sub_item_cost_price ;
                $new_master_cost_price                                      = number_format($sub_master_price_Service->calculate($new_sub_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($sub_master_price_Service->calculate($new_sub_master_cost_price,$this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') : $this->item_selected_detailes->item_cost_price;


                 // تعديل اسعار الوحدة الاب *************************
                $this->item_selected_detailes->item_wholesale_price         =  number_format($sub_master_price_Service->get_new_item_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->item_Half_wholesale_price    =  number_format($sub_master_price_Service->get_new_item_Half_wholesale_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->item_retail_price            =  number_format($sub_master_price_Service->get_new_item_retail_price($this->item_selected_detailes,$new_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->item_cost_price              =  $new_master_cost_price;



                 // تعديل اسعار الوحدة الابن *************************
                $this->item_selected_detailes->sub_item_wholesale_price     = number_format($sub_master_price_Service->get_new_sub_item_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->sub_item_Half_wholesale_price= number_format($sub_master_price_Service->get_new_sub_item_Half_wholesale_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->sub_item_retail_price        = number_format($sub_master_price_Service->get_new_sub_item_retail_price($this->item_selected_detailes,$new_sub_master_cost_price), 2, '.', '');
                $this->item_selected_detailes->sub_item_cost_price          = $new_sub_master_cost_price;

                $this->item_selected_detailes->save();
            }

            DB::commit();




            // Dispatch events
            $this->dispatch('purcgaseOrderDetailesUpdateMS');     // Flash Message
            $this->dispatch('updateModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Show::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('purcgaseOrderDetailesErrorMS');                   // Flash Message
        }

    }




    public function render()
    {
        $is_item_type_date_required = $this->item_selected_detailes && $this->item_selected_detailes->item_type == "1";

        return view('back-end.purchase-order-detailes-returns.update',
        [
            'items'                         => $this->items,
            'is_item_type_date_required'    => $is_item_type_date_required,
        ]);
    }
}

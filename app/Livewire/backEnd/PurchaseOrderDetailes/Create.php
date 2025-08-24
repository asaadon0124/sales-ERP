<?php

namespace App\Livewire\BackEnd\PurchaseOrderDetailes;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetailes;
use App\Services\GetAverageItemPriceService;
use App\Livewire\BackEnd\PurchaseOrders\Show;
use App\Services\GetAverageSubItemPriceService;


class Create extends Component
{
    public $orderID;
    public $order;
    public $items;
    public $item_selected_detailes = null;
    public $check_itemUnit_type = null;


    public $item_code;
    public $item_units_id = null;
    public $item_type;
    public $qty             = 0;
    public $unit_price      = 0;
    public $total           = 0;
    public $production_date;
    public $expire_date;




    protected $listeners = ['orderDetailesCreate'];


    public function orderDetailesCreate($id)
    {
        $this->orderID  = $id;
        $this->order    = PurchaseOrder::where('auto_serial',$id)->with('order_detailes')->first();
        $this->items    = Item::where('company_code',auth()->user()->company_code)->with('itemUnit','itemUnitChild')->get();

        // show Create modal
        $this->dispatch('createModalToggle');
    }


    public function item_select($value)
    {
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
        $this->check_itemUnit_type = ItemUnit::select('id','is_master')->where('id',$value)->first();
    }




    public function updateQty($value)
    {
        $this->qty = $value;
        $this->total = $this->unit_price * $this->qty;
    }



    public function updateUnit_price($value)
    {
        $this->unit_price = $value;
        $this->total = $this->unit_price * $this->qty;
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
           'production_date'            => $this->item_selected_detailes && $this->item_selected_detailes->item_type == 1 ? 'required|date' : 'nullable',
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
        ]);

        try
        {
            // update_item_prices($this->item_code,$this->unit_price,$this->check_itemUnit_type->is_master);
            DB::beginTransaction();
            // 1 - CREATE PURCHASES ORDERS DETAILES
                PurchaseOrderDetailes::create(
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
            ]);
            $this->item_code = '';

            // Dispatch events
            $this->dispatch('purcgaseOrderDetailesCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Show::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('accountsErrorMS');                   // Flash Message
        }

    }



    public function render()
    {
        $is_item_type_date_required         = $this->item_selected_detailes && $this->item_selected_detailes->item_type == "1";

        return view('back-end.purchase-order-detailes.create',
        [
            'items'                         => $this->items,
            'is_item_type_date_required'    => $is_item_type_date_required,
        ]);
    }
}

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

class Update extends Component
{
    public $itemID;
    public $orderID;
    public $order;
    public $item;
    public $items;
    public $item_selected_detailes;
    public $check_itemUnit_type;

    public $item_code;
    public $item_units_id;
    public $item_type;
    public $qty = 0;
    public $unit_price = 0;
    public $total = 0;
    public $production_date;
    public $expire_date;
    public $is_master;

    protected $listeners = ['purchaseOrderDetailesUpdate'];

    public function purchaseOrderDetailesUpdate($id)
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

        $this->check_item_unit_type($this->item_units_id);

        $this->orderID                  = $this->item->auto_serial_purchase_orders;
        $this->order                    = PurchaseOrder::where('auto_serial', $this->orderID)->with('order_detailes')->first();
        $this->items                    = Item::where('company_code', auth()->user()->company_code)->with('itemUnit', 'itemUnitChild')->get();


        // Show the update modal
        $this->dispatch('updateModalToggle');
    }

    // Handle item selection change and update details
    public function item_select($value)
    {
        if ($value != '')
        {
            $this->item_selected_detailes   = Item::where('item_code', $value)->with('itemUnitChild', 'itemUnit')->first();
            $this->item_type                = $this->item_selected_detailes->item_type;
            $this->item_units_id            = null;
            $this->qty                      = 0;
            $this->unit_price               = 0;
            $this->total                    = 0;
            $this->production_date          = null;
            $this->expire_date              = null;

        } else
        {
            $this->item_code                = '';
            $this->item_selected_detailes   = null;
            $this->item_units_id            = null;
        }
    }


    // Check the item unit type for further processing
    public function check_item_unit_type($value)
    {
        $this->check_itemUnit_type = ItemUnit::select('id', 'is_master')->where('id', $value)->first();
    }


    // Update quantity and total calculation
    public function updateQty($value)
    {
        $this->qty      = $value;
        $this->total    = $this->unit_price * $this->qty;
    }

    // Update unit price and total calculation
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




    // Render the component
    public function render()
    {
        $is_item_type_date_required = $this->item_selected_detailes && $this->item_selected_detailes->item_type == "1";

        return view('back-end.purchase-order-detailes.update',
        [
            'items'                         => $this->items,
            'is_item_type_date_required'    => $is_item_type_date_required,
        ]);
    }
}

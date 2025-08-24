<?php

namespace App\Livewire\BackEnd\PurchaseOrderDetailes;

use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetailes;
use App\Services\GetAverageItemPriceService;
use App\Livewire\BackEnd\PurchaseOrders\Show;
use App\Services\GetAverageSubItemPriceService;

class Delete extends Component
{
    public $item;
    public $itemName;
    public $orderID;
    public $order;

    public $item_code;
    public $item_units_id = null;
    public $item_type;
    public $production_date;
    public $expire_date;
    public $item_selected_detailes;
    public $check_itemUnit_type;


    protected $listeners = ['purchaseOrderDetailesDelete'];



    public function purchaseOrderDetailesDelete($id)
    {
        $this->item                     = PurchaseOrderDetailes::with('item')->where('id',$id)->first();
        $this->orderID                  = $this->item->auto_serial_purchase_orders;
        $this->order                    = PurchaseOrder::where('auto_serial', $this->orderID)->with('order_detailes')->first();
        $this->itemName                 = $this->item->item->name;
        $this->item_code                = $this->item->item->item_code;
        $this->item_units_id            = $this->item->item_units_id;
        $this->item_selected_detailes   = $this->item->item;
        $this->check_itemUnit_type = ItemUnit::select('id','is_master')->where('id',$this->item->item_units_id)->first();


        // show edit modal
        $this->dispatch('deleteModalToggle');
    }



    public function submit()
    {

        try
        {
            DB::beginTransaction();
            // 1 - DELETE PURCHASES ORDERS DETAILES
                $this->item->delete();

            // 2 - UPDATE PURCHASES ORDER TOTAL COST
            $this->order->update(
            [
                'total_cost_before_all' => total_cost_before_all($this->orderID),
                'total_cost'            => total_cost_after_all($this->order),
                'updated_by'            => auth()->user()->id,
            ]);

            DB::commit();



            // Dispatch events
            $this->dispatch('purchaseOrderDetailesDeleteMS');     // Flash Message
            $this->dispatch('deleteModalToggle');                // Close Create Model
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
        return view('back-end.purchase-order-detailes.delete');
    }
}

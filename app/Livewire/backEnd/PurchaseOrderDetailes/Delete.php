<?php

namespace App\Livewire\BackEnd\PurchaseOrderDetailes;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetailes;
use App\Livewire\BackEnd\PurchaseOrders\Show;

class Delete extends Component
{
    public $item;
    public $itemName;
    public $orderID;
    public $order;

    protected $listeners = ['purchaseOrderDetailesDelete'];



    public function purchaseOrderDetailesDelete($id)
    {
        $this->item                = PurchaseOrderDetailes::with('item')->where('id',$id)->first();
        $this->orderID             = $this->item->auto_serial_purchase_orders;
        $this->order               = PurchaseOrder::where('auto_serial', $this->orderID)->with('order_detailes')->first();
        $this->itemName            = $this->item->item->name;

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

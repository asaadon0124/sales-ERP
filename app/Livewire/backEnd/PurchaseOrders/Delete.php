<?php

namespace App\Livewire\BackEnd\PurchaseOrders;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\PurchaseOrders\Data;

class Delete extends Component
{

    public $items;
    public $auto_serial;
    public $order;

    protected $listeners = ['purchaseOrderDelete'];



    public function purchaseOrderDelete($id)
    {
        $this->order               = PurchaseOrder::where('auto_serial', $id)->with('order_detailes')->first();
        $this->auto_serial            = $this->order->auto_serial;
        $this->items                = $this->order->order_detailes->count();

        // show edit modal
        $this->dispatch('deleteModalToggle');
    }




    public function submit()
    {

        try
        {
            DB::beginTransaction();
            // 1 - DELETE PURCHASES ORDERS DETAILES
            $order_items = $this->order->order_detailes;
            // dd($order_items);

            if ($order_items && $order_items->count() > 0)
            {
                foreach ($order_items as $data)
                {
                    $data->delete();
                }
            }


            // 2 - DELETE ORDER
            $this->order->delete();

            DB::commit();



            // Dispatch events
            $this->dispatch('purchaseOrdersDeleteMS');            // Flash Message
            $this->dispatch('deleteModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('purchaseOrdersErrorMS');                   // Flash Message
        }

    }




    public function render()
    {
        return view('back-end.purchase-orders.delete');
    }
}

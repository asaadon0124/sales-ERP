<?php

namespace App\Livewire\BackEnd\SalesOrderReturnsDetailes;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\SalesOrderReturns\Show;

class Delete extends Component
{

    public $item;
    public $itemName;
    public $orderID;
    public $order;
    public $check_itemUnit_type;
    public $item_units_id;
    public $item_code;
    public $item_selected_detailes;

    protected $listeners = ['salesOrderDetailesReturnsDelete'];


    public function salesOrderDetailesReturnsDelete($id)
    {
        $this->item                     = SalesOrderDetail::with('item')->where('id',$id)->first();
        $this->orderID                  = $this->item->auto_serial_sales_order;
        $this->order                    = SalesOrder::where('auto_serial', $this->orderID)->with('order_detailes')->first();
        $this->itemName                 = $this->item->item->name;
        $this->item_units_id            = $this->item->item_units_id;
        $this->item_code                = $this->item->item_code;
        $this->item_selected_detailes   = Item::where('item_code', $this->item_code)->with('itemUnitChild', 'itemUnit')->first();
        $this->check_itemUnit_type      = ItemUnit::select('id','is_master')->where('id',$this->item_units_id)->first();

        // dd($this->order);
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
                if ($this->order->order_detailes && $this->order->order_detailes->count() > 0)
                {
                    // dd($this->order->order_detailes->sum('total'));
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

                }else
                {
                    $this->order->total_cost_before_all     = 0;
                    $this->order->tax_value                 = 0;
                    $this->order->total_before_discount     = 0;

                    if ($this->order->discount_type == '1')     // لو نوع الخصم نسبة يبقي حنعدل نسبة الخصم
                    {
                        $this->order->discount_amount       =  0;
                        $this->order->total_cost            =  0;
                    }else
                    {
                        $this->order->total_cost            = 0;
                    }
                    $this->order->mony_for_account          = 0;
                    $this->order->paid                      = 0;

                }

                $this->order->updated_by                = auth()->user()->id;
                $this->order->save();

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
        return view('back-end.sales-order-returns-detailes.delete');
    }
}

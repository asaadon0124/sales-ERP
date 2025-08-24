<?php

namespace App\Livewire\BackEnd\PurchaseOrderDetailesReturns;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetailes;
use App\Services\GetAverageItemPriceService;
use App\Services\GetAverageSubItemPriceService;
use App\Livewire\BackEnd\PurchaseOrderReturns\Show;

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

     protected $listeners = ['purchaseOrderDetailesReturnsDelete'];



    public function purchaseOrderDetailesReturnsDelete($id)
    {
        $this->item                     = PurchaseOrderDetailes::with('item')->where('id',$id)->first();
        $this->orderID                  = $this->item->auto_serial_purchase_orders;
        $this->order                    = PurchaseOrder::where('auto_serial', $this->orderID)->with('order_detailes')->first();
        $this->itemName                 = $this->item->item->name;
        $this->item_units_id            = $this->item->item_units_id;
        $this->item_code                = $this->item->item_code;
        $this->item_selected_detailes   = Item::where('item_code', $this->item_code)->with('itemUnitChild', 'itemUnit')->first();
        $this->check_itemUnit_type = ItemUnit::select('id','is_master')->where('id',$this->item_units_id)->first();

        // dd($this->check_itemUnit_type);
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
        return view('back-end.purchase-order-detailes-returns.delete');
    }
}

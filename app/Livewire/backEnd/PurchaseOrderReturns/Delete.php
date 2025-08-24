<?php

namespace App\Livewire\BackEnd\PurchaseOrderReturns;

use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\Services\GetAverageItemPriceService;
use App\Services\GetAverageSubItemPriceService;
use App\Livewire\BackEnd\PurchaseOrderReturns\Data;

class Delete extends Component
{
    public $items;
    public $auto_serial;
    public $order;

    public $item_code;
    public $item_units_id = null;
    public $item_type;
    public $production_date;
    public $expire_date;
    public $item_selected_detailes;
    public $check_itemUnit_type;

    protected $listeners = ['purchaseOrderReturnsDelete'];



    public function purchaseOrderReturnsDelete($id)
    {
        $this->order                        = PurchaseOrder::where('auto_serial', $id)->with('order_detailes')->first();
        $this->auto_serial                  = $this->order->auto_serial;
        $this->items                        = $this->order->order_detailes->count();





        // show edit modal
        $this->dispatch('deleteModalToggle');
    }


    public function submit()
    {

        try {
            DB::beginTransaction();
            // 1 - DELETE PURCHASES ORDERS DETAILES
            $order_items = $this->order->order_detailes;
            // dd($order_items);

            if ($order_items && $order_items->count() > 0)
            {
                foreach ($order_items as $item)
                {
                    $item->delete();
                    $this->item_code                    = $item->item->item_code;
                    $this->item_units_id                = $item->item_units_id;
                    $this->item_selected_detailes             = $item->item;
                    $this->check_itemUnit_type          = ItemUnit::select('id', 'is_master')->where('id', $item->item_units_id)->first();
                    // 3 - UPDATE ITEMS TABLE
                    if ($this->check_itemUnit_type->is_master == 'master')   //   لو الوحدة الي مختارة اساسية
                    {
                        $master_price_Service                                       = new GetAverageItemPriceService();

                        $new_master_cost_price                                      = number_format($master_price_Service->calculate($this->item_code, $this->item_units_id), 2, '.', '') != 0 ? number_format($master_price_Service->calculate($this->item_code, $this->item_units_id), 2, '.', '') : $this->item_selected_detailes->item_cost_price;
                        $new_sub_master_cost_price                                  = number_format($master_price_Service->calculate_sub_cost($new_master_cost_price, $this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($master_price_Service->calculate_sub_cost($new_master_cost_price, $this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') : $this->item_selected_detailes->sub_item_cost_price;

                        // تعديل اسعار الوحدة الاب *************************
                        $this->item_selected_detailes->item_wholesale_price         =  number_format($master_price_Service->get_new_item_wholesale_price($this->item_selected_detailes, $new_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->item_Half_wholesale_price    =  number_format($master_price_Service->get_new_item_Half_wholesale_price($this->item_selected_detailes, $new_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->item_retail_price            =  number_format($master_price_Service->get_new_item_retail_price($this->item_selected_detailes, $new_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->item_cost_price              =  $new_master_cost_price;

                        // تعديل اسعار الوحدة الابن *************************
                        $this->item_selected_detailes->sub_item_wholesale_price     = number_format($master_price_Service->get_new_sub_item_wholesale_price($this->item_selected_detailes, $new_sub_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->sub_item_Half_wholesale_price = number_format($master_price_Service->get_new_sub_item_Half_wholesale_price($this->item_selected_detailes, $new_sub_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->sub_item_retail_price        = number_format($master_price_Service->get_new_sub_item_retail_price($this->item_selected_detailes, $new_sub_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->sub_item_cost_price          = $new_sub_master_cost_price;
                        $this->item_selected_detailes->save();
                    } else           //  لو الوحدة المختارة فرعية
                    {
                        $sub_master_price_Service                                   = new GetAverageSubItemPriceService();
                        $new_sub_master_cost_price                                  = number_format($sub_master_price_Service->calculate_sub_cost($this->item_code, $this->item_units_id), 2, '.', '')  != 0 ? number_format($sub_master_price_Service->calculate_sub_cost($this->item_code, $this->item_units_id), 2, '.', '') : $this->item_selected_detailes->sub_item_cost_price;
                        $new_master_cost_price                                      = number_format($sub_master_price_Service->calculate($new_sub_master_cost_price, $this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') != 0 ? number_format($sub_master_price_Service->calculate($new_sub_master_cost_price, $this->item_selected_detailes->qty_sub_item_unit), 2, '.', '') : $this->item_selected_detailes->item_cost_price;


                        // تعديل اسعار الوحدة الاب *************************
                        $this->item_selected_detailes->item_wholesale_price         =  number_format($sub_master_price_Service->get_new_item_wholesale_price($this->item_selected_detailes, $new_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->item_Half_wholesale_price    =  number_format($sub_master_price_Service->get_new_item_Half_wholesale_price($this->item_selected_detailes, $new_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->item_retail_price            =  number_format($sub_master_price_Service->get_new_item_retail_price($this->item_selected_detailes, $new_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->item_cost_price              =  $new_master_cost_price;



                        // تعديل اسعار الوحدة الابن *************************
                        $this->item_selected_detailes->sub_item_wholesale_price     = number_format($sub_master_price_Service->get_new_sub_item_wholesale_price($this->item_selected_detailes, $new_sub_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->sub_item_Half_wholesale_price = number_format($sub_master_price_Service->get_new_sub_item_Half_wholesale_price($this->item_selected_detailes, $new_sub_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->sub_item_retail_price        = number_format($sub_master_price_Service->get_new_sub_item_retail_price($this->item_selected_detailes, $new_sub_master_cost_price), 2, '.', '');
                        $this->item_selected_detailes->sub_item_cost_price          = $new_sub_master_cost_price;

                        $this->item_selected_detailes->save();
                    }
                }
            }


            // 2 - DELETE ORDER
            $this->order->delete();




            DB::commit();



            // Dispatch events
            $this->dispatch('purchaseOrdersDeleteMS');            // Flash Message
            $this->dispatch('deleteModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            $this->dispatch('purchaseOrdersErrorMS');                   // Flash Message
        }
    }



    public function render()
    {
        return view('back-end.purchase-order-returns.delete');
    }
}

<?php

namespace App\Services;

use App\Models\PurchaseOrderDetailes;



class GetAverageSubItemPriceService
{
     /**
     * حساب متوسط سعر الشراء آخر 10 وحدات بالوحدة الاب (بما فيهم السعر الحالي)
     */

    public function calculate($new_sub_cost_price,$qty_sub_item_unit)   // +++++++++++++++++++++++++++++++++++
    {
        $new_costPrice =  $new_sub_cost_price * $qty_sub_item_unit;

        // حساب سعر التكلفة الجديد للوحدة الاب
        return $new_costPrice;
    }


    /**
     *حساب  سعر الجملة الجديد للوحدة الاب
     */
    public function get_new_item_wholesale_price($item_selected_detailes, float $new_cost_price)
    {
         // فرق السعر بين الجملة والتكلفة القديمة
        $diff = $item_selected_detailes->item_wholesale_price - $item_selected_detailes->item_cost_price;
        // dd($new_cost_price);
        // نضيف الفرق على التكلفة الجديدة
        return $new_cost_price + $diff;
    }


    /**
     *حساب  سعر النص الجملة الجديد للوحدة الاب
     */
    public function get_new_item_Half_wholesale_price($item_selected_detailes, float $new_cost_price)
    {
         // فرق السعر بين النص الجملة والتكلفة القديمة
        $diff = $item_selected_detailes->item_Half_wholesale_price - $item_selected_detailes->item_cost_price;

        // نضيف الفرق على التكلفة الجديدة
        return $new_cost_price + $diff;
    }


    /**
     *حساب  سعر القطاعي الجديد للوحدة الاب
     */
    public function get_new_item_retail_price($item_selected_detailes, float $new_cost_price)
    {
         // فرق السعر بين  القطاعي والتكلفة القديمة
        $diff = $item_selected_detailes->item_retail_price - $item_selected_detailes->item_cost_price;

        // نضيف الفرق على التكلفة الجديدة
        return $new_cost_price + $diff;
    }


        // الوحدات الفرعية ***************************************************

      /**
     * حساب متوسط سعر الشراء آخر 10 وحدات بالوحدة الابن (بما فيهم السعر الحالي)
     */

    public function calculate_sub_cost($item_code, int $item_unit_id): float //+++++++++++++++++++++++++ */
    {
        // آخر 9 أسعار سابقة من قاعدة البيانات
        $lastPrices = PurchaseOrderDetailes::where('item_code', $item_code)
            ->where('item_units_id', $item_unit_id)
            ->whereHas('order',function($q)
            {
                $q->where('order_type','!=','2');
            })
            ->latest()
            ->take(9)
            ->pluck('unit_price')
            ->toArray();

        // dd($lastPrices);

        // حساب المتوسط
        return count($lastPrices) > 0 ? array_sum($lastPrices) / count($lastPrices) : 0;
    }



     /**
     *حساب  سعر الجملة الجديد للوحدة الابن
     */
    public function get_new_sub_item_wholesale_price($item_selected_detailes, float $new_sub_cost_price) //+++++++++++++++++++
    {
         // فرق السعر بين الجملة والتكلفة القديمة
        $diff = $item_selected_detailes->sub_item_wholesale_price - $item_selected_detailes->sub_item_cost_price;

        // dd($diff);
        // نضيف الفرق على التكلفة الجديدة
        return $new_sub_cost_price + $diff;
    }


    /**
     *حساب  سعر النص الجملة الجديد للوحدة الابن
     */
    public function get_new_sub_item_Half_wholesale_price($item_selected_detailes, float $new_sub_cost_price)
    {
         // فرق السعر بين النص الجملة والتكلفة القديمة
        $diff = $item_selected_detailes->sub_item_Half_wholesale_price - $item_selected_detailes->sub_item_cost_price;

        // نضيف الفرق على التكلفة الجديدة
        return $new_sub_cost_price + $diff;
    }


    /**
     *حساب  سعر القطاعي الجديد للوحدة الابن
     */
    public function get_new_sub_item_retail_price($item_selected_detailes, float $new_sub_cost_price)
    {
         // فرق السعر بين  القطاعي والتكلفة القديمة
        $diff = $item_selected_detailes->sub_item_retail_price - $item_selected_detailes->sub_item_cost_price;

        // نضيف الفرق على التكلفة الجديدة
        return $new_sub_cost_price + $diff;
    }
}

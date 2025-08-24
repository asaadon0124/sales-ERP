<?php
namespace App\Services;

use App\Models\Item;
use App\Models\ItemBatch;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StoreAllocator
{
    /**
     * يحسب الكميات، يتحقق إذا الكمية تكفي، ويرتب الباتشات.
     */
    public function allocate(array $item_data): object
    {



             // A - اجيب الباتشات الخاصة بالصنف
                $batch_query = ItemBatch::where('item_code', $item_data['item_code'])
                ->where('store_id', $item_data['store_id'])
                ->where('qty', '>', 0)
                ->where('item_cost_price', $item_data['item_cost_price'])
                ->where('item_code', $item_data['item_code'])
                ->with('item_unit');


                if ($item_data['item_type'] === '1')
                {
                    $batch_query->orderBy('expire_date', 'asc');
                } else
                {
                    $batch_query->orderBy('created_at', 'asc');
                }
                $item_batches           = $batch_query->get();
                $item                   = Item::with('itemUnitChild', 'itemUnit')->where('item_code', $item_data['item_code'])->firstOrFail();
                $required_qty           = $item_data['qty'];
                $total_qty_avliable = $item_batches->sum(function ($batch)
                {
                    return $batch->qty - $batch->deduction;
                });

                // dd($item_data);
                // dd($total_qty_avliable);
                if (($item_data['itemUnit_type'] ?? '') === 'sub_master')
                {
                    // dd('sa');
                    $required_qty /= $item->qty_sub_item_unit;
                }

                // dd($total_qty_avliable);
                // dd($required_qty);
                if ($total_qty_avliable < $required_qty)
                {
                    dd('no');

                    // throw ValidationException::withMessages(
                    //     [
                    //      'qty' => "الكمية المطلوبة من " . $item_data['item_name'] . " غير متوفرة بالمخزن."
                    // ]);
                }
//  dd($item_data['item_name']);
                return (object)
                [
                    'batches'       => $item_batches,
                    'required_qty'  => $required_qty,
                ];
    }
}

<?php

use App\Models\Item;
use App\Models\Shift;
use App\Models\Account;
use App\Models\ItemBatch;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;
use App\Models\TreasuryTransation;
use Illuminate\Support\Collection;
use App\Models\PurchaseOrderDetailes;

function last_update($table)
{
    if ($table->updated_by > 0 && $table->updated_by != null)
    {
        $date = $table->updated_at->format('y-m-d');
        $time = $table->updated_at->format('H:i');
        $newDate = date("A",strToTime($time));
        $convertTime = (($newDate == 'AM') ? 'صباحا' : 'مسائا');
        // return
        // [
        //     'date'          => $date,
        //     'time'          => $time,
        //     'convertTime'   => $convertTime,
        // ];

        return "اخر تحديث بتاريخ: " . $date . " في " . $time . " " . $convertTime;
    } else
    {
        return null;
    }
}


function getStatus($status)
{
    return $status == 'un_active' ? 'غير مفعل' : 'مفعل';
}







function getItemType($type)
{
    if ($type == 0)
    {
        return 'مخزني';
    }elseif($type == 1)
    {
        return 'استهلاكي';
    }else
    {
        return 'عهدة';
    }
}


function getMaster($master)
{
    return $master == 'master' ? 'رئيسية' : 'فرعية';
}

function getItemRetailUnit($master)                    // هل لديه اقسام فرعية
{
    return $master == '0' ? 'لا' : 'نعم';
}


function getItemChangePrice($master)                    // هل سعر الصنف قابل للتغير في فاتورة المبيعات
{
    return $master == '0' ? 'لا' : 'نعم';
}



function getData_With_Master($table,$master_value,$status_value)
{
    return $table::where('is_master',$master_value)->where('status',$status_value)->orderBy('updated_at','DESC');
}


function total_cost_before_all($orderID)
{
    return PurchaseOrderDetailes::where('auto_serial_purchase_orders', $orderID)->sum('total');
}



function total_cost_after_all($order)
{
    $total = PurchaseOrderDetailes::where('auto_serial_purchase_orders', $order->auto_serial)->sum('total');
    $taxAmount = ($order->tax_percent / 100) * $total + $order->tax_value;
    $discountAmount = ($order->discount_percent / 100) * $total + $order->discount_amount;

    return $total + $taxAmount - $discountAmount;
}



 function Active_shift()
 {
    return Shift::where('admin_id',auth()->user()->id)->with('treasury')->where('shift_status','active')->first();
 }


// الخزن الي ليها شفتات مفتوحة
 function treasures_with_Active_shifts($treasury_id)
 {
    // return Shift::where('treasury_id',$treasury_id)->with('treasury')->where('shift_status','active')->where('company_code',auth()->user()->company_code)->first();
    return Shift::where('treasury_id', $treasury_id)
    ->where('company_code', auth()->user()->company_code)
    ->where(function($q) {
        $q->where('shift_status', 'active')
          ->orWhere('is_delevered_review', 'no');
    })
    ->with('treasury')
    ->first();

 }



//  حساب رصيد الخزنة المفعلة في الشيفت الحالي
 function Treasry_balance($activeShift)
 {
    // dd($activeShift);
    //  dd(TreasuryTransation::where('shift_id',$activeShift->auto_serial)->sum('cash_amount'));
    return TreasuryTransation::where('shift_id',$activeShift->auto_serial)->sum('cash_amount');

 }


 //  حساب رصيد كل خزنة
 function Treasries_balances($treasury_id)
 {
    //  dd(TreasuryTransation::where('shift_id',$activeShift->auto_serial)->sum('cash_amount'));
    return TreasuryTransation::where('treasury_id',$treasury_id)->sum('cash_amount');
 }


 //  حساب رصيد كل شيفت
 function shifts_balances($shift_auto_serial)
 {
    // dd($shift_auto_serial);
    $balance = TreasuryTransation::where('shift_id',$shift_auto_serial)->where('cash_source_type','account')->sum('cash_amount');
    return $balance;


 }
 //  حساب رصيد اول  كل شيفت
 function shifts_start_balances($shift_auto_serial)
 {

    $balance = TreasuryTransation::where('shift_id',$shift_auto_serial)->where('cash_source_type','treasury')->sum('cash_amount');
    return $balance;

 }


 // تحديث رقم اخر account_number في الجدول
 function get_last_autoSerial($modelClass, $columnName)
{

    $lastItem = $modelClass::where('company_code', auth()->user()->company_code)->orderBy('account_number', 'DESC')->first();

    // dd($lastItem);
    if ($lastItem && $lastItem->$columnName !== null)
    {
        return $lastItem->$columnName + 1;
    } else
    {
        return 1;
    }
}


// تحديث اخر رقم AUTO SERIAL في اي جدول
function get_last_autoSerial_invoices($modelClass, $columnName)
{

    $lastItem = $modelClass::where('company_code', auth()->user()->company_code)->orderBy('auto_serial', 'DESC')->first();


    if ($lastItem && $lastItem->$columnName !== null)
    {
        return $lastItem->$columnName + 1;
    } else
    {
        return 1;
    }
}



// احسب الرصيد الحالي للمورد
// function getSupplierCurrentBalance($account_number)
// {
//     $supplier_account   = Account::where('account_number', $account_number)->with('account_customer')->first();
//     if (!$supplier_account)
//     {
//         return 'لا يوجد حساب لهذا المورد'; // أو ممكن ترجع 0 أو ترمي استثناء
//     }

//     $cash_transactions  = TreasuryTransation::where('account_id', $supplier_account->account_number)->sum('cash_for_account');
//     $purchase_orders    = PurchaseOrder::where('account_number', $supplier_account->account_number)->where('paid',0)->sum('mony_for_account');
//     $current_balance    = $supplier_account->start_balance + $cash_transactions + $purchase_orders;

//     return $current_balance;
// }



// احسب الرصيد الحالي للعميل
// function getCoustomerCurrentBalance($account_number,$relation_name)
// {
//     $customer_account   = Account::where('account_number', $account_number)->with($relation_name)->first();

//     if (!$customer_account)
//     {
//         return 'لا يوجد حساب لهذا المورد'; // أو ممكن ترجع 0 أو ترمي استثناء
//     }

//     $cash_transactions  = TreasuryTransation::where('account_id', $customer_account->account_number)->sum('cash_for_account');
//     $sales_orders       = SalesOrder::where('customer_account_number', $customer_account->account_number)->where('paid',0)->sum('mony_for_account');
//     $current_balance    = $customer_account->start_balance + $cash_transactions + $sales_orders;

//     // dd($customer_account->account_number);
//     return $current_balance;
// }


// احسب الرصيد الحالي للمندوب
// function getServantCurrentBalance($account_number,$relation_name)
// {
//     $servant_account   = Account::where('account_number', $account_number)->with($relation_name)->first();

//     if (!$servant_account)
//     {
//         return 'لا يوجد حساب لهذا المندوب'; // أو ممكن ترجع 0 أو ترمي استثناء
//     }

//     $cash_transactions  = TreasuryTransation::where('servant_account_id', $servant_account->account_number)->sum('servant_cash_amount');
//     // $sales_orders       = SalesOrder::where('servant_code', $servant_account->$relation_name->servant_code)->latest()->first();
//     $current_balance    = $servant_account->start_balance + $cash_transactions;
//     // dd($cash_transactions);
//     return $current_balance;
// }


// // احسب الرصيد الحالي للموظف
// function getEmployeeCurrentBalance($account_number,$relation_name)
// {
//     $employee_account   = Account::where('account_number', $account_number)->with($relation_name)->first();


//     if (!$employee_account)
//     {
//         return 'لا يوجد حساب لهذا الموظف'; // أو ممكن ترجع 0 أو ترمي استثناء
//     }

//     $cash_transactions  = TreasuryTransation::where('account_id', $employee_account->account_number)->sum('cash_amount');
//     // $sales_orders       = SalesOrder::where('servant_code', $servant_account->$relation_name->servant_code)->latest()->first();
//     $current_balance    = $employee_account->start_balance + $cash_transactions;
//     // dd($current_balance);
//     return $current_balance;
// }




// ************************************************************** الرصيد الحالي للمورد او العميل او المندوب او الموظف ***********************
// function getCurrentBalance($account_number, $type = 'supplier', $relation_name = null)
// {
//     $account = Account::where('account_number', $account_number);

//     if ($relation_name)
//     {
//         $account->with($relation_name);
//     }

//     $account = $account->first();

//     if (!$account)
//     {
//         return 'لا يوجد حساب لهذا ' . $type;
//     }

//     $start_balance = $account->start_balance;
//     $current_balance = $start_balance;

//     switch ($type)
//     {
//         case 'supplier':
//             $cash_transactions = TreasuryTransation::where('account_id', $account->account_number)->sum('cash_for_account');
//             $purchase_orders = PurchaseOrder::where('account_number', $account->account_number)
//                                             ->where('paid', 0)
//                                             ->sum('mony_for_account');
//             $current_balance += $cash_transactions + $purchase_orders;
//             break;

//         case 'customer':
//             $cash_transactions = TreasuryTransation::where('account_id', $account->account_number)->sum('cash_for_account');
//             $sales_orders = SalesOrder::where('customer_account_number', $account->account_number)
//                                       ->where('paid', 0)
//                                       ->sum('mony_for_account');
//             $current_balance += $cash_transactions + $sales_orders;
//             break;

//         case 'servant':
//             $cash_transactions = TreasuryTransation::where('servant_account_id', $account->account_number)->sum('servant_cash_amount');
//             $current_balance += $cash_transactions;
//             break;

//         case 'employee':
//             $cash_transactions = TreasuryTransation::where('account_id', $account->account_number)->sum('cash_amount');
//             $current_balance += $cash_transactions;
//             break;

//          case 'general':
//             $cash_transactions = TreasuryTransation::where('account_id', $account->account_number)->sum('cash_amount');
//             $current_balance += $cash_transactions;
//             break;

//         default:
//             return 'نوع الحساب غير معروف';
//     }

//     return $current_balance;
// }








// حساب سعر الوحدة الفرعية لو كان السعر بالوحدة الاساسية
function sub_item_cost_price_from_parent($itemPrice,$itemQty)
{

    if (!$itemQty || $itemQty == 0)
    {
        return 0; // أو null أو throw حسب منطقك
    }

    $sub_item_unit_price = $itemPrice /$itemQty;
    return $sub_item_unit_price;
    //   dd($itemPrice /$itemQty);
}






// حساب سعر الوحدة الفرعية لو كان السعر بالوحدة الاساسية
function parent_item_cost_price_from_sub_price($itemPrice,$itemQty)
{
    $parent_item_unit_price = $itemPrice * $itemQty;
    return $parent_item_unit_price;
}








function mergeConsumableBatches(Collection $batches, string $unitType, float $subQty = 1): Collection
{
    $reservedItems = collect(session('sales_order_items', []));
    return $batches
        ->map(function ($batch) use ($reservedItems, $subQty)
        {
            // حساب الكمية المحجوزة بالوحدة الرئيسية
            $reservedMaster = (float) $reservedItems
                ->where('store_id', $batch->store_id)
                ->where('batch_id', $batch->id)
                ->where('item_code', $batch->item_code)
                ->where('itemUnit_type', 'master')
                ->sum('qty');

            $reservedSub = (float) $reservedItems
                ->where('store_id', $batch->store_id)
                ->where('batch_id', $batch->id)
                ->where('item_code', $batch->item_code)
                ->where('itemUnit_type', 'sub_master')
                ->sum('qty');

            $convertedSub = $reservedSub / ($subQty ?: 1);

            $batch->qty -= ($reservedMaster + $convertedSub);
            return $batch;
        })
        ->groupBy(function ($batch)
        {
            return $batch->store_id . '|' . $batch->expire_date . '|' . $batch->item_cost_price;
        })
        ->map(function ($grouped)
        {
            $first = $grouped->first();
            $totalQty = $grouped->sum('qty');
            $first->qty = $totalQty;
            return $first;

        })
        ->filter(function ($batch) use ($unitType, $subQty)
        {
            $qty = $unitType === 'master' ? $batch->qty : $batch->qty * $subQty;
            return $qty > 0;
        })
        ->sortBy('expire_date') // ✅ الترتيب حسب تاريخ الانتهاء
        ->values();

}


function mergeNonConsumableBatches(Collection $batches, string $unitType, float $subQty = 1): Collection
{
    $reservedItems = collect(session('sales_order_items', []));

    return $batches
        ->map(function ($batch) use ($reservedItems, $subQty) {
            $reservedMaster = (float) $reservedItems
                ->where('store_id', $batch->store_id)
                ->where('batch_id', $batch->id)
                ->where('item_code', $batch->item_code)
                ->where('itemUnit_type', 'master')
                ->sum('qty');

            $reservedSub = (float) $reservedItems
                ->where('store_id', $batch->store_id)
                ->where('batch_id', $batch->id)
                ->where('item_code', $batch->item_code)
                ->where('itemUnit_type', 'sub_master')
                ->sum('qty');

            $convertedSub = $reservedSub / ($subQty ?: 1);

            $batch->qty -= ($reservedMaster + $convertedSub);

            return $batch;
        })
        ->groupBy(function ($batch) {
            // دمج حسب المخزن + سعر الشراء فقط
            return $batch->store_id . '|' . $batch->item_cost_price;
        })
        ->map(function ($grouped) {
            $first = $grouped->first();
            $first->qty = $grouped->sum('qty');
            return $first;
        })
        ->filter(function ($batch) use ($unitType, $subQty) {
            $qty = $unitType === 'master' ? $batch->qty : $batch->qty * $subQty;
            return $qty > 0;
        })
        ->values();
}


// تحديث تكلفة الصنف  في جدول الاصناف
function update_item_prices($itemCode,$unit_price,$isMAster)
{
    $get_item = Item::where('item_code',$itemCode)->first();
    if ($isMAster == 'master')
    {
        $itemcost = $get_item->item_cost_price;
        dd($itemcost);
    }else
    {
        $itemcost = $get_item->sub_item_cost_price;
        dd($itemcost);
    }

}




// اجيب حالة رصيد اول المدة سواء مدين او دائن او متزن
function start_balanceStatus($start_balance)
{
    if ($start_balance == 0)
    {
        return 'nun';

    }elseif($start_balance > 0)
    {
        return 'credit';

    }else
    {
        return 'debit';
    }
}



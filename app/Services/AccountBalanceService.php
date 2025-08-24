<?php
namespace App\Services;

use App\Models\Account;
use App\Models\SalesOrder;
use App\Models\PurchaseOrder;
use App\Models\TreasuryTransation;

class AccountBalanceService
{
    public function getCurrentBalance($account_number, $type = 'supplier', $relation_name = null)
    {
        $account = Account::where('account_number', $account_number);

        if ($relation_name)
        {
            $account->with($relation_name);
        }

        $account = $account->first();

        if (!$account)
        {
            return 'لا يوجد حساب لهذا ' . $type;
        }

        $start_balance = $account->start_balance;
        $current_balance = $start_balance;

        switch ($type)
        {
            case 'supplier':
                $cash_transactions = TreasuryTransation::where('account_id', $account->account_number)->sum('cash_for_account');
                $purchase_orders = PurchaseOrder::where('account_number', $account->account_number)
                    ->where('paid', 0)->sum('mony_for_account');
                $current_balance += $cash_transactions + $purchase_orders;
                break;

            case 'customer':
                $cash_transactions = TreasuryTransation::where('account_id', $account->account_number)->sum('cash_for_account');
                $sales_orders = SalesOrder::where('customer_account_number', $account->account_number)
                    ->where('paid', 0)->sum('mony_for_account');
                $current_balance += $cash_transactions + $sales_orders;
                break;

            case 'servant':
                $cash_transactions = TreasuryTransation::where('servant_account_id', $account->account_number)->sum('servant_cash_amount');
                $current_balance += $cash_transactions;
                break;

            case 'employee':
            case 'general':
                $cash_transactions = TreasuryTransation::where('account_id', $account->account_number)->sum('cash_amount');
                $current_balance += $cash_transactions;
                break;

            default:
                return 'نوع الحساب غير معروف';
        }
        // dd($current_balance);
        return $current_balance;
    }
}

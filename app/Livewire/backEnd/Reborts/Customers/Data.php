<?php

namespace App\Livewire\BackEnd\Reborts\Customers;

use App\Models\Account;
use Livewire\Component;
use App\Models\Customer;
use App\Models\SalesOrder;
use Livewire\WithPagination;
use App\Models\TreasuryTransation;

class Data extends Component
{
      use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];


    public $account_number;
    public $start_date;
    public $end_date;

    public $search_transactions;
    public $search_invoices;
    public $accounts;
    public $movements;



     // public function updatingSearch()
    // {
    //     $this->resetPage();
    // }


    public function mount()
    {
        $this->accounts = Customer::with('customer_account')->get();
    }



    public function updatedStartDate()
    {
        $this->resetErrorBag('start_date');
    }

    public function updatedEndDate()
    {
        $this->resetErrorBag('start_date');
    }


    public function updatedAccountNumber()
    {
        $this->resetErrorBag('account_number');
    }



     public function submit()
    {
        if ($this->start_date > $this->end_date)
        {
            $this->addError('start_date', 'يجب ان يكون تاريخ البداية قبل تاريخ النهاية.');
            return;
        }

        if ($this->account_number == '')
        {
            $this->addError('account_number', 'يجب اختيار اسم المورد.');
            return;
        }

        $this->search_transactions  = true;
        $this->search_invoices      = true;
    }

    public function render()
    {

        $data           = collect();
        $data_invoices  = collect();
        $customer  ='';



        if ($this->search_transactions)
        {
            $customer   = Account::where('account_number',$this->account_number)->first();
            $data       = TreasuryTransation::where(function ($query)
            {
                if ($this->account_number != null)  // لما اختار عميل
                {
                    $query->where('account_id',$this->account_number);
                }

                if ($this->start_date != null)  // لما اختار تاريخ البداية
                {
                    $query->where('order_date','>=',$this->start_date);
                }

                if ($this->end_date != null)  // لما اختار تاريخ النهاية
                {
                    $query->where('order_date','<=',$this->end_date);
                }

            })
            ->with('account','adminCreate')->latest()->paginate(5);
        }


        if ($this->search_invoices)
        {
            $customer       = Customer::where('account_number',$this->account_number)->first();
            $data_invoices  = SalesOrder::where(function ($query) use($customer)
            {
                if ($this->account_number != null)  // لما اختار مورد
                {
                    $query->where('customer_code',$customer->customer_code);
                }

                if ($this->start_date != null)  // لما اختار تاريخ البداية
                {
                    $query->where('order_date','>=',$this->start_date);
                }

                if ($this->end_date != null)  // لما اختار تاريخ النهاية
                {
                    $query->where('order_date','<=',$this->end_date);
                }

            })
            ->with('customer')->latest()->paginate(5);
            //  dd($customer->customer_code);
        }


        return view('back-end.reborts.customers.data',compact('data','data_invoices','customer'));
    }
}

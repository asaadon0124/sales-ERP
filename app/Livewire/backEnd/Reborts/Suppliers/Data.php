<?php

namespace App\Livewire\BackEnd\Reborts\Suppliers;

use App\Models\Store;
use App\Models\Account;
use App\Models\Servant;
use Livewire\Component;
use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Livewire\WithPagination;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;

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
        $this->accounts = Supplier::with('supplier_account')->get();
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
        $supplier  ='';

       

        if ($this->search_transactions)
        {
            $supplier = Account::where('account_number',$this->account_number)->first();
            $data = TreasuryTransation::where(function ($query)
            {
                if ($this->account_number != null)  // لما اختار مورد
                {
                    $query->where('account_id',$this->account_number);
                }

                if ($this->start_date != null)  // لما اختار تاريخ البداية
                {
                    $query->where('move_date','>=',$this->start_date);
                }

                if ($this->end_date != null)  // لما اختار تاريخ النهاية
                {
                    $query->where('move_date','<=',$this->end_date);
                }

            })
            ->with('account','adminCreate')->latest()->paginate(5);
        }

        if ($this->search_invoices)
        {
            $supplier       = Supplier::where('account_number',$this->account_number)->first();
            $data_invoices  = PurchaseOrder::where(function ($query) use($supplier)
            {
                if ($this->account_number != null)  // لما اختار مورد
                {
                    $query->where('supplier_code',$supplier->supplier_code);
                }

                if ($this->start_date != null)  // لما اختار تاريخ البداية
                {
                    $query->where('move_date','>=',$this->start_date);
                }

                if ($this->end_date != null)  // لما اختار تاريخ النهاية
                {
                    $query->where('move_date','<=',$this->end_date);
                }

            })
            ->with('supplier')->latest()->paginate(5);
            //  dd($supplier->supplier_code);
        }

        // $data  = $this->movements;

        return view('back-end.reborts.suppliers.data',compact('data','supplier','data_invoices'));
    }
}

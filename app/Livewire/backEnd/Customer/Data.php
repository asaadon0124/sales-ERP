<?php

namespace App\Livewire\BackEnd\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\AccountType;
use Livewire\WithPagination;

class Data extends Component
{
    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];
    public $search;
    public $searchAccountBalanceType;

    public function updatingSearch()
    {
        $this->resetPage();
    }




    public function render()
    {
        $acountsTypes =  AccountType::where('status','active')->get();
        $data = Customer::where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('customer_code', 'like', '%' . $this->search . '%')
                ->orWhere('account_number', 'like', '%' . $this->search . '%');

        })->when($this->searchAccountBalanceType, function ($query)
        {
            $query->where('start_balance_status', $this->searchAccountBalanceType);
        })->latest()->with('customer_account')->paginate(5);

        return view('back-end.customer.data',compact('data','acountsTypes'));
    }
}

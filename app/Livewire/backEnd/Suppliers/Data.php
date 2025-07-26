<?php

namespace App\Livewire\BackEnd\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
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
        $data = Supplier::where(function ($query)
        {
            $acountsTypes =  AccountType::where('status','active')->get();

            $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('supplier_code', 'like', '%' . $this->search . '%')
            ->orWhere('account_number', 'like', '%' . $this->search . '%');


        })->when($this->searchAccountBalanceType, function ($query)
        {
            $query->where('start_balance_status', $this->searchAccountBalanceType);

        })->latest()->paginate(5);


        return view('back-end.suppliers.data',compact('data'));
    }
}

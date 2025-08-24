<?php

namespace App\Livewire\BackEnd\PurchaseOrderReturns;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;

class Data extends Component
{
    use WithPagination;
    protected $listeners = ['refreshData' => '$refresh'];
    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $get_active_shift = Active_shift();
        $data = PurchaseOrder::with('supplier','order_detailes')->where('order_type','2')->where(function ($query)
        {
            $query->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('company_code', 'like', '%' . $this->search . '%');
        })->latest()->paginate(5);
        // dd($data);
        return view('back-end.purchase-order-returns.data',compact('get_active_shift','data'));
    }
}

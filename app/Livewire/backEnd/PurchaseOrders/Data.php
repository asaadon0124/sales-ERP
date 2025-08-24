<?php

namespace App\Livewire\BackEnd\PurchaseOrders;

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
        $data = PurchaseOrder::with('supplier','order_detailes')->where(function ($query)
        {
            $query->where('order_number', 'like', '%' . $this->search . '%')
                  ->orWhere('company_code', 'like', '%' . $this->search . '%');
        })->latest()->where('order_type','0')->paginate(5);
        return view('back-end.purchase-orders.data',compact('data','get_active_shift'));
    }
}

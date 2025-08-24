<?php

namespace App\Livewire\BackEnd\SalesOrder;

use Livewire\Component;
use App\Models\SalesOrder;
use Livewire\WithPagination;

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
         $data = SalesOrder::with('customer','order_detailes')->where('order_type','0')->where(function ($query)
        {
            $query->where('auto_serial', 'like', '%' . $this->search . '%');
        })->latest()->paginate(5);
        return view('back-end.sales-order.data',compact('data','get_active_shift'));
    }
}

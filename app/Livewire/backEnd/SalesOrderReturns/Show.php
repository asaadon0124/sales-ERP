<?php

namespace App\Livewire\BackEnd\SalesOrderReturns;

use App\Models\SalesOrder;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SalesOrderDetail;

class Show extends Component
{
     use WithPagination;


    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public $order;
    public $orderId;


    public $subTreasuries;
    public $name;
    public $status;
    public $is_master;
    public $last_recept_pay;
    public $last_recept_recive;
    public $company_code;
    public $updated_by;


    protected $listeners = ['refreshData' => '$refresh'];

    public function mount($id)
    {
        $this->order                = SalesOrder::with('order_detailes','customer','servant')->where('auto_serial',$id)->first();
        $this->orderId              = $id;
    }


    public function render()
    {

         $data = SalesOrderDetail::where('auto_serial_sales_order', $this->orderId)->where(function ($query)
        {
            $query->where('expire_date', 'like', '%' . $this->search . '%')
                ->orWhereHas('item', function ($q)
                {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
        })
        ->with('item')
        ->get();

        return view('back-end.sales-order-returns.show',
         [
            'data'  => $data,
            'order' => $this->order,
        ]);
    }
}

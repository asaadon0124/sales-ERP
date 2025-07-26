<?php

namespace App\Livewire\BackEnd\SalesOrder;

use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use Livewire\Component;
use Livewire\WithPagination;

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
    public $status, $is_master, $last_recept_pay, $last_recept_recive, $company_code, $updated_by;
    protected $listeners = ['refreshData' => '$refresh'];



     public function mount($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->order                = SalesOrder::with('order_detailes','customer','servant')->where('auto_serial',$id)->first();
        $this->orderId              = $id;

        // dd($this->order);
        // $this->name                 = $this->treasuries->name;
        // $this->status               = $this->treasuries->status;
        // $this->is_master            = $this->treasuries->is_master;
        // $this->last_recept_pay      = $this->treasuries->last_recept_pay;
        // $this->last_recept_recive   = $this->treasuries->last_recept_recive;
        // $this->company_code         = $this->treasuries->company_code;
        // $this->updated_by           = $this->treasuries->updated_by;
    }




    public function render()
    {
         $data = SalesOrderDetail::
        where(function ($query)
        {
            $query->where('auto_serial_sales_order', 'like', '%' . $this->search . '%')
            ->orWhere('item_code', 'like', '%' . $this->search . '%');
        })
        ->where('auto_serial_sales_order', $this->orderId)->with('item') // Ensure you are still filtering by ID
        ->get();
        // dd($this->order);


        return view('back-end.sales-order.show',
        [
            'data' => $data,
            'order' => $this->order
        ]);
    }
}

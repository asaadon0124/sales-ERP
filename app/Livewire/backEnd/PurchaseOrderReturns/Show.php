<?php

namespace App\Livewire\BackEnd\PurchaseOrderReturns;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetailes;

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
        $this->order                = PurchaseOrder::with('order_detailes')->where('auto_serial',$id)->first();
        $this->orderId              = $id;
    }

    public function render()
    {
        $data = PurchaseOrderDetailes::where('auto_serial_purchase_orders', $this->orderId)->where(function ($query)
        {
            $query->where('expire_date', 'like', '%' . $this->search . '%')
                ->orWhereHas('item', function ($q)
                {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
        })
        ->with('item')
        ->get();

        return view('back-end.purchase-order-returns.show',
        [
            'data'  => $data,
            'order' => $this->order,
        ]);
    }
}

<?php

namespace App\Livewire\BackEnd\PurchaseOrders;

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
        // fill $slider with the eloquent model of the same id
        $this->order                = PurchaseOrder::with('order_detailes')->where('auto_serial',$id)->first();
        $this->orderId               = $id;

        // dd($this->order);
        // $this->name                 = $this->treasuries->name;
        // $this->status               = $this->treasuries->status;
        // $this->is_master            = $this->treasuries->is_master;
        // $this->last_recept_pay      = $this->treasuries->last_recept_pay;
        // $this->last_recept_recive   = $this->treasuries->last_recept_recive;
        // $this->company_code         = $this->treasuries->company_code;
        // $this->updated_by           = $this->treasuries->updated_by;
    }
    // public function Status()
    // {
    //     return $this->status == 'un_active' ? 'غير مفعل' : ' مفعل';
    // }

    // public function IsMaster()
    // {
    //     return $this->is_master == 'master' ? 'رئيسية' : ' فرعية';
    // }




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

    return view('back-end.purchase-orders.show', [
        'data' => $data,
        'order' => $this->order,
    ]);
}

}

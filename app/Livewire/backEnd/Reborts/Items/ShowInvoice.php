<?php

namespace App\Livewire\BackEnd\Reborts\Items;

use App\Models\AdminSitting;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Livewire\Component;

class ShowInvoice extends Component
{
    protected $listeners = ['ShowInvoice'];

    public $purchase_order;
    public $sales_order;
    public $admin_sittings;
    public $invoiceType;

    public function ShowInvoice($id,$invoiceType)
    {
        $this->invoiceType      = $invoiceType;
        $this->admin_sittings   = AdminSitting::where('company_code',auth()->user()->company_code)->first();

        if ($invoiceType == 'sales')
        {
            $this->sales_order = SalesOrder::where('auto_serial',$id)->with('adminCreate','customer','servant','order_detailes')->first();
        }else
        {
            $this->purchase_order = PurchaseOrder::where('auto_serial',$id)->with('adminCreate','supplier','store','order_detailes')->first();
        }


        $this->dispatch('createModalToggle');
    }




    public function render()
    {


        return view('back-end.reborts.items.show-invoice',
        [
            'admin_sittings'    => $this->admin_sittings,
            'order'             => $this->sales_order != '' ?$this->sales_order : $this->purchase_order,
            'invoiceType'       => $this->invoiceType,
        ]);
    }
}

<?php

namespace App\Livewire\BackEnd\PurchaseOrderDetailesReturns;

use Livewire\Component;
use App\Models\PurchaseOrderDetailes;

class Show extends Component
{
    public $item;
    public $name;
    public $item_code;
    public $order_type;
    public $item_units_id;
    public $is_master;
    public $item_type;
    public $qty;
    public $unit_price;
    public $total;


     protected $listeners = ['purchaseOrderDetailesReturnsShow'];


    public function purchaseOrderDetailesReturnsShow($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->item                     = PurchaseOrderDetailes::with('item')->where('id',$id)->first();
        $this->name                     = $this->item->item->name;
        $this->item_code                = $this->item->item_code;
        $this->order_type               = $this->item->order_type;
        $this->item_units_id            = $this->item->item_unit->name;
        $this->is_master                = $this->item->is_master;
        $this->item_type                = $this->item->item_type;
        $this->qty                      = $this->item->qty;
        $this->unit_price               = $this->item->unit_price;
        $this->total                    = $this->item->total;

        // show modal
        $this->dispatch('showModalToggle');
    }

    public function getOrderTypeLabel()
    {
        if ($this->order_type == '0')
        {
            return 'مشتريات';
        } elseif ($this->order_type == '2')
        {
            return 'مرتجعات';
        }
    }


    public function getItemType()
    {
        if ($this->item_type == '0')
        {
            return 'مخزني';
        } elseif ($this->item_type == '1')
        {
            return 'استهلاكي';
        } else
        {
            return 'عهدة';
        }
    }


    public function getIsMaster()
    {
        if ($this->is_master == 'master')
        {
            return 'اساسية';
        } else
        {
            return 'فرعية';
        }
    }

    public function render()
    {
        return view('back-end.purchase-order-detailes-returns.show',
        [
             'orderTypeLabel'   => $this->getOrderTypeLabel(),
             'isMaster'         => $this->getIsMaster(),
             'itemType'         => $this->getItemType(),
        ]);
    }
}

<?php

namespace App\Livewire\BackEnd\Suppliers;

use App\Models\Supplier;
use Livewire\Component;

class Show extends Component
{
    public $supplier;
    public $name;
   
    protected $listeners = ['supplierShow'];


    public function supplierShow($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->supplier                     = Supplier::find($id);
       
        // show modal
        $this->dispatch('showModalToggle');
    }



    public function render()
    {
        return view('back-end.suppliers.show');
    }
}

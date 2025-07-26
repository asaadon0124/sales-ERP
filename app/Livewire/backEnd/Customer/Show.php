<?php

namespace App\Livewire\BackEnd\Customer;

use App\Models\Customer;
use Livewire\Component;

class Show extends Component
{
    public $customer;
    public $name;
   
    protected $listeners = ['customerShow'];


    public function customerShow($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->customer                     = Customer::find($id);
       
        // show modal
        $this->dispatch('showModalToggle');
    }



    public function render()
    {
        return view('back-end.customer.show');
    }
}

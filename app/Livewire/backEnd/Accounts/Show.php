<?php

namespace App\Livewire\BackEnd\Accounts;

use App\Models\Account;
use Livewire\Component;

class Show extends Component
{
    public $account;
    public $name;
   
    protected $listeners = ['accountsShow'];


    public function accountsShow($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->account                     = Account::find($id);
       
        // show modal
        $this->dispatch('showModalToggle');
    }


    public function render()
    {
        return view('back-end.accounts.show',
        [
            'account' => $this->account
        ]);
    }
}

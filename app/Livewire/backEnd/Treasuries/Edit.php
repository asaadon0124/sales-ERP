<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;

class Edit extends Component
{
    public  $treasuries;

    public function mount(Treasuries $treasuries)
    {
        $this->treasuries = $treasuries;
    }


    public function render()
    {
        return view('livewire.back-end.treasuries.edit');
    }
}

<?php

namespace App\Livewire\backEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;

class Index extends Component
{
    public $perPage         = 2;
    public function render()
    {
        return view('livewire.backEnd.treasuries.index',
        data:
        [
            'treasuries' => Treasuries::orderBy('updated_at','desc')->paginate($this->perPage),
        ]);
    }



}

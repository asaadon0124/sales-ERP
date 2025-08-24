<?php

namespace App\Livewire\BackEnd\MaterialTypes;

use App\Models\MaterialType;
use Livewire\Component;
use Livewire\WithPagination;

class Data extends Component
{

    use WithPagination;
    protected $listeners = ['refreshData' => '$refresh'];
    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $data = MaterialType::where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('company_code', 'like', '%' . $this->search . '%');
        })->latest()->with('adminCreate')->paginate(5);
        // dd($data);

        return view('back-end.material-types.data',compact('data'));
    }
}

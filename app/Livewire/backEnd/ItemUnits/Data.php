<?php

namespace App\Livewire\BackEnd\ItemUnits;

use App\Models\ItemUnit;
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
        if (!auth()->user()->can('عرض وحدات الاصناف'))
        {
            abort(403, 'ليس لديك صلاحية عرض وحدات الاصناف');
        }
        $data = ItemUnit::where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('company_code', 'like', '%' . $this->search . '%');
        })->latest()->paginate(5);

        return view('back-end.item-units.data',compact('data'));
    }
}

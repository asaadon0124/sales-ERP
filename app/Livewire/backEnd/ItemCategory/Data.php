<?php

namespace App\Livewire\BackEnd\ItemCategory;

use App\Models\ItemCategory;
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

        if (!auth()->user()->can('عرض فئات الاصناف'))
        {
            abort(403, 'ليس لديك صلاحية عرض فئات الاصناف ');
        }

        $data = ItemCategory::where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('company_code', 'like', '%' . $this->search . '%')
            ->orWhere('status', 'like', '%' . $this->search . '%');
        })->latest()->paginate(5);
        return view('back-end.item-category.data',compact('data'));
    }
}

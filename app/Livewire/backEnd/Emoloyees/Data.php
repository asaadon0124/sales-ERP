<?php

namespace App\Livewire\BackEnd\Emoloyees;

use App\Models\Admin;
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
        if (!auth()->user()->can('عرض كل الموظفين'))
        {
            abort(403, 'ليس لديك صلاحية عرض كل الموظفين');
        }

        $data = Admin::where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_code', 'like', '%' . $this->search . '%');

        })->latest()->paginate(5);

        return view('back-end.emoloyees.data',compact('data'));
    }
}

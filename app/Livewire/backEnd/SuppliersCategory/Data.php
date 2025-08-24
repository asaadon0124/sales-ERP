<?php

namespace App\Livewire\BackEnd\SuppliersCategory;

use App\Models\SupplierCategory;
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
        $data = SupplierCategory::where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_code', 'like', '%' . $this->search . '%');

            // if ($this->search == 'مفعل')
            // {
            //     $query->orWhere('status', 'active');
            // } elseif (str_contains($this->search, 'غير'))
            // {
            //     $query->orWhere('status', 'un_active');
            // }
        })->latest()->paginate(5);
        return view('back-end.-suppliers-category.data',compact('data'));
    }
}

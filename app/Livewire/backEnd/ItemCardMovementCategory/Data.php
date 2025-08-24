<?php

namespace App\Livewire\BackEnd\ItemCardMovementCategory;

use App\Models\ItemCardMovementCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Data extends Component
{
    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];

    public $search = '';

    // Reset الصفحة عند تغيير البحث
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        if (!auth()->user()->can('عرض فئات حركات الاصناف'))
        {
            abort(403, 'ليس لديك صلاحية عرض فئات حركات الاصناف ');
        }
        $data = ItemCardMovementCategory::query()
        ->when($this->search != '', function ($query)
        {
            $query->where(function ($q)
            {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_code', 'like', '%' . $this->search . '%');
            });
        })
        ->latest()
        ->paginate(5);

        return view('back-end.item-card-movement-category.data',compact('data'));
    }
}

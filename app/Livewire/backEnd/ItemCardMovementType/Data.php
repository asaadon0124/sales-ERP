<?php

namespace App\Livewire\BackEnd\ItemCardMovementType;

use App\Models\ItemCardMovementType;
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
        if (!auth()->user()->can('انواع حركات الاصناف'))
        {
            abort(403, 'ليس لديك صلاحية انواع حركات الاصناف');
        }


        $data = ItemCardMovementType::query()
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

        return view('back-end.item-card-movement-type.data',compact('data'));
    }
}

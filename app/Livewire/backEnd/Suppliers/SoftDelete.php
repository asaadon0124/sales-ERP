<?php

namespace App\Livewire\BackEnd\Suppliers;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;
use App\Models\Scopes\ActiveScope;

class SoftDelete extends Component
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
         if (!auth()->user()->can('عرض كل الموردين المحذوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض كل الموردين المحذوفة');
        }

        $search     = $this->search;
        $data       = Supplier::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })

        ->latest()
        ->paginate(5);


        return view('back-end.suppliers.soft-delete',compact('data'));
    }
}

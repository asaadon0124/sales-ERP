<?php

namespace App\Livewire\BackEnd\SuppliersCategory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SupplierCategory;
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
         if (!auth()->user()->can('عرض اقسام الموردين المحذوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض اقسام الموردين المحذوفة');
        }

        $search     = $this->search;
        $data       = SupplierCategory::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(5);


        return view('back-end.-suppliers-category.soft-delete',compact('data'));
    }
}

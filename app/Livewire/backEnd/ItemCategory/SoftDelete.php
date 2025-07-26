<?php

namespace App\Livewire\BackEnd\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
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
         if (!auth()->user()->can('عرض فئات الاصناف المحزوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض فئات الاصناف المحزوفة المحزوفة');
        }


        $search     = $this->search;
        $data       = ItemCategory::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(10);
        return view('back-end.item-category.soft-delete',compact('data'));
    }
}

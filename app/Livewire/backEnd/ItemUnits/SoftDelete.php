<?php

namespace App\Livewire\BackEnd\ItemUnits;

use Livewire\Component;
use App\Models\ItemUnit;
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
        if (!auth()->user()->can('عرض وحدات الاصناف المحذوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض وحدات الاصناف المحذوفة');
        }

        $search     = $this->search;
        $data       = ItemUnit::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(5);

        // dd($data);

        return view('back-end.item-units.soft-delete',compact('data'));
    }
}

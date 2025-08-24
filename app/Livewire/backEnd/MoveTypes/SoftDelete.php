<?php

namespace App\Livewire\BackEnd\MoveTypes;

use Livewire\Component;
use App\Models\MoveType;
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
         if (!auth()->user()->can('عرض انواع حركات النقدية المحذوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض انواع حركات النقدية المحذوفة');
        }

        $search     = $this->search;
        $data       = MoveType::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(5);
        return view('back-end.move-types.soft-delete',compact('data'));
    }
}

<?php

namespace App\Livewire\BackEnd\Emoloyees;

use App\Models\Admin;
use Livewire\Component;
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
         if (!auth()->user()->can('عرض كل الحسابات المحذوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض كل الحسابات المحذوفة');
        }

        $search     = $this->search;
        $data       = Admin::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })

        ->latest()
        ->paginate(5);


        return view('back-end.emoloyees.soft-delete',compact('data'));
    }
}

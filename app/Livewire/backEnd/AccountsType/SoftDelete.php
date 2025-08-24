<?php

namespace App\Livewire\BackEnd\AccountsType;

use Livewire\Component;
use App\Models\AccountType;
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
         if (!auth()->user()->can('عرض انواع الحسابات المحذوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض انواع الحسابات المحذوفة');
        }

        $search     = $this->search;
        $data     = AccountType::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(5);


        return view('back-end.accounts-type.soft-delete',compact('data'));
    }
}

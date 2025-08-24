<?php

namespace App\Livewire\BackEnd\Servant;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Scopes\ActiveScope;
use App\Models\Servant;

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

          if (!auth()->user()->can('عرض المناديب المحذوفين'))
        {
            abort(403, 'ليس لديك صلاحية عرض المناديب المحذوفين');
        }

        $search     = $this->search;
        $data       = Servant::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })

        ->latest()
        ->paginate(5);
        return view('back-end.servant.soft-delete',compact('data'));
    }
}

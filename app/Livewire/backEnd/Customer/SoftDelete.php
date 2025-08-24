<?php

namespace App\Livewire\BackEnd\Customer;

use Livewire\Component;
use App\Models\Customer;
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
          if (!auth()->user()->can('عرض العملاء المحذوفين'))
        {
            abort(403, 'ليس لديك صلاحية عرض العملاء المحذوفين');
        }

        $search     = $this->search;
        $data       = Customer::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })

        ->latest()
        ->paginate(5);


        return view('back-end.customer.soft-delete',compact('data'));
    }
}

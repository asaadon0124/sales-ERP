<?php

namespace App\Livewire\BackEnd\AccountsType;

use App\Models\AccountType;
use Livewire\Component;
use Livewire\WithPagination;

class Data extends Component
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
        if (!auth()->user()->can('عرض انواع الحسابات'))
        {
            abort(403, 'ليس لديك صلاحية عرض انواع الحسابات');
        }

        $data = AccountType::with('adminCreate')->where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('company_code', 'like', '%' . $this->search . '%');

        })->latest()->paginate(5);
        // $data = AccountType::select('id', 'name', 'created_by')->with('adminCreate')->get();
        // dd($data);
        return view('back-end.accounts-type.data',compact('data'));
    }
}

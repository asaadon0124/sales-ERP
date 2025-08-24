<?php

namespace App\Livewire\BackEnd\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Data extends Component
{
      use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];

    public $search = '';
    public $permtions;

    // Reset الصفحة عند تغيير البحث
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->permtions = Permission::all();
        // dd($this->permtions);
    }


    public function render()
    {
         $data = Role::query()
            ->when($this->search != '', function ($query)
            {
                $query->where(function ($q)
                {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('company_code',auth()->user()->company_code)
            ->latest()
            ->paginate(10);


        return view('back-end.roles.data',compact('data'));
    }
}

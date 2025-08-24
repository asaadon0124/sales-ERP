<?php

namespace App\Livewire\BackEnd\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Data extends Component
{

     use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];

    public $search = '';

    // Reset الصفحة عند تغيير البحث
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $data = Permission::query()
            ->when($this->search != '', function ($query)
            {
                $query->where(function ($q)
                {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);


        return view('back-end.permissions.data',compact('data'));
    }
}

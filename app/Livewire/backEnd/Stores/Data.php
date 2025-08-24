<?php

namespace App\Livewire\BackEnd\Stores;

use App\Models\Store;
use Livewire\Component;
use Livewire\WithPagination;

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
        if (!auth()->user()->can('عرض المخازن'))
        {
            abort(403, 'ليس لديك صلاحية عرض المخازن');
        }

        $data = Store::query()
            ->when($this->search != '', function ($query)
            {
                $query->where(function ($q)
                {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('company_code', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(5);

        return view('back-end.stores.data', compact('data'));
    }
}

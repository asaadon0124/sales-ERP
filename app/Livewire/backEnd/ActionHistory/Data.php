<?php

namespace App\Livewire\BackEnd\ActionHistory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActionHistory;

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
          $data = ActionHistory::query()
            ->when($this->search != '', function ($query)
            {
                $query->where(function ($q)
                {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('desc', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);
        return view('back-end.action-history.data',compact('data'));
    }
}

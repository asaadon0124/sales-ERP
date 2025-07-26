<?php

namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
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

         if (!auth()->user()->can('عرض الاصناف المحزوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض الاصناف المحزوفة');
        }

        $search     = $this->search;
        $data       = Item::withoutGlobalScope(ActiveScope::class)->with(['itemUnit' => function($q)
        {
            $q->withoutGlobalScope(ActiveScope::class);

        },'itemCategory' => function($q2)
        {
            $q2->withoutGlobalScope(ActiveScope::class);
            
        }])->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');
        })
        ->latest()
        ->paginate(5);


        return view('back-end.items.soft-delete',compact('data'));
    }
}

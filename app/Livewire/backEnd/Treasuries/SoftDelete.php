<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;
use Livewire\WithPagination;
use App\Models\Scopes\ActiveScope;
use Illuminate\Support\Str;


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
        if (!auth()->user()->can('عرض الخزن المحزوفة'))
        {
            abort(403, 'ليس لديك صلاحية عرض الخزن المحزوفة');
        }


        $search     = $this->search;
        $data       = Treasuries::withoutGlobalScope(ActiveScope::class)->where('status','un_active')->where(function ($query) use ($search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');

            // ترجمة البحث بالعربي إلى قيم قاعدة البيانات
            if (Str::contains($search, 'رئي'))
            {
                $query->orWhere('is_master', 'like', '%master%');
            } elseif (Str::contains($search, 'فر'))
            {
                $query->orWhere('is_master', 'like', '%user%');
            }
        })
        ->latest()
        ->paginate(10);
        return view('back-end.treasuries.soft-delete',compact('data'));
    }
}

<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;
use Illuminate\Support\Str;
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


// public function render()
// {
//     $data = Treasuries::when($this->search, function($query)
//     {
//         $query->where(function($q)
//         {
//             $q->where('name', 'like', '%'.$this->search.'%')
//             ->orWhere('company_code', 'like', '%'.$this->search.'%');
//         });
//     })
//     ->latest()
//     ->paginate(10);

//     if($this->search)
//     {
//         // تصفية النتائج بناءً على IsMaster مع الحفاظ على Pagination
//         $filtered = $data->getCollection()->filter(function($item)
//         {
//             return stripos($item->IsMaster(), $this->search) !== false;
//         });

//         $data->setCollection($filtered);
//     }

//     return view('back-end.treasuries.data', compact('data'));
// }

    public function render()
    {
        if (!auth()->user()->can('عرض الخزن'))
        {
            abort(403, 'ليس لديك صلاحية عرض تفاصيل خزنة');
        }
        $search     = $this->search;
        $data       = Treasuries::where(function ($query) use ($search)
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

        // $data = Treasuries::where(function ($query)
        // {
        //     $query->where('name', 'like', '%' . $this->search . '%')
        //     ->orWhere('company_code', 'like', '%' . $this->search . '%');
        //     if ($this->search == 'رئيسية')
        //     {
        //         // $this->search = 'master';
        //         $query->orWhere('is_master','master');

        //     } elseif ($this->search == 'فرعية')
        //     {
        //         $query->orWhere('is_master','user');
        //     }

        // })
        // ->latest()
        // ->paginate(10);

        return view('back-end.treasuries.data',compact('data'));
    }
}

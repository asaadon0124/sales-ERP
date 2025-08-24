<?php

namespace App\Livewire\BackEnd\MoveTypes;

use App\Models\MoveType;
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
        if (!auth()->user()->can('عرض انواع حركات النقدية'))
        {
            abort(403, 'ليس لديك صلاحية عرض انواع حركات النقدية ');
        }

         $moveTypeMap =
         [
            'داخ'       => 'private',
            'خلي'       => 'private',
            'عا'        => 'global',
            'عام'       => 'global',
        ];

        $search = $this->search; // نعرفها عشان نستخدمها جوه الفنكشن



        $data = MoveType::where(function ($query) use ($moveTypeMap, $search)
        {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('company_code', 'like', '%' . $search . '%');

            if ($search === 'مفعل')
            {
                $query->orWhere('status', 'active');

            } elseif (str_contains($search, 'غير'))
            {
                $query->orWhere('status', 'inactive'); // عدلتها إلى شكل منطقي
            }


            foreach ($moveTypeMap as $key => $value)
            {
                if (str_contains($search, $key))
                {
                    $query->orWhere('is_private_internal', $value);
                    break;
                }
            }
        })
        ->latest()
        ->with('adminCreate')
        ->withCount('treasuries_transactions') // لحساب عدد الحركات
        ->paginate(5);

        // dd('sd');

        return view('back-end.move-types.data',compact('data'));
    }
}

<?php

namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemBatch;
use Livewire\WithPagination;
use App\Models\Scopes\ActiveScope;

class Data extends Component
{
    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];
    public $search = '';
    public $qty_after_all_stores = 0;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!auth()->user()->can('عرض الاصناف'))
        {
            abort(403, 'ليس لديك صلاحية عرض الاصناف ');
        }


        // $this->qty_after_all_stores = ItemBatch::where('item_code', $this->new_order_detailes->item_code)->sum('qty');
        $statusMap = [
            'مفعل'     => 'active',
            'فعال'     => 'active',
            'غير مفعل' => 'un_active',
            'غير'      => 'un_active',
        ];

        $typeMap = [
            'مخزني'     => 0,
            'مخزنة'     => 0,
            'استهلاكي'  => 1,
            'عهدة'      => 2,
        ];

        $search = $this->search;

        // $data = Item::query()
        //     ->when($search, function ($query) use ($search, $statusMap, $typeMap)
        //     {
        //         $query->where(function ($q) use ($search, $statusMap, $typeMap)
        //         {
        //             $q->where('name', 'like', "%$search%")
        //               ->orWhere('barcode', 'like', "%$search%")
        //               ->orWhere('company_code', 'like', "%$search%");

        //             // بحث في العلاقات
        //             $q->orWhereHas('adminCreate', function ($sub) use ($search)
        //             {
        //                 $sub->where('name', 'like', "%$search%");
        //             });

        //             $q->orWhereHas('adminUpdate', function ($sub) use ($search)
        //             {
        //                 $sub->where('name', 'like', "%$search%");
        //             });

        //             $q->orWhereHas('itemUnit', function ($sub) use ($search)
        //             {
        //                 $sub->withoutGlobalScope(ActiveScope::class)->where('name', 'like', "%$search%");
        //             });

        //             $q->orWhereHas('itemUnitChild', function ($sub) use ($search)
        //             {
        //                 $sub->withoutGlobalScope(ActiveScope::class)->where('name', 'like', "%$search%");
        //             });

        //             $q->orWhereHas('itemCategory', function ($sub) use ($search)
        //             {
        //                 $sub->withoutGlobalScope(ActiveScope::class)->where('name', 'like', "%$search%");
        //             });

        //             // البحث بالحالة
        //             foreach ($statusMap as $key => $value)
        //              {
        //                 if (str_contains($search, $key))
        //                 {
        //                     $q->orWhere('status', $value);
        //                     break;
        //                 }
        //             }

        //             // البحث بنوع الصنف
        //             foreach ($typeMap as $key => $value)
        //             {
        //                 if (str_contains($search, $key))
        //                 {
        //                     $q->orWhere('item_type', $value);
        //                     break;
        //                 }
        //             }
        //         });
        //     })
        //     ->latest()

        //     ->paginate(5);

        $data = Item::query()
            ->with([
                'itemUnit' => function ($q) {
                    $q->withoutGlobalScope(ActiveScope::class);
                },
                'itemUnitChild' => function ($q) {
                    $q->withoutGlobalScope(ActiveScope::class);
                },
                'itemCategory' => function ($q) {
                    $q->withoutGlobalScope(ActiveScope::class);
                },
                'adminCreate',
                'adminUpdate'
            ])
            ->when($search, function ($query) use ($search, $statusMap, $typeMap)
            {
                $query->where(function ($q) use ($search, $statusMap, $typeMap)
                {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('barcode', 'like', "%$search%")
                        ->orWhere('company_code', 'like', "%$search%");

                    $q->orWhereHas('adminCreate', fn($sub) => $sub->where('name', 'like', "%$search%"));
                    $q->orWhereHas('adminUpdate', fn($sub) => $sub->where('name', 'like', "%$search%"));
                    $q->orWhereHas('itemUnit', fn($sub) => $sub->withoutGlobalScope(ActiveScope::class)->where('name', 'like', "%$search%"));
                    $q->orWhereHas('itemUnitChild', fn($sub) => $sub->withoutGlobalScope(ActiveScope::class)->where('name', 'like', "%$search%"));
                    $q->orWhereHas('itemCategory', fn($sub) => $sub->withoutGlobalScope(ActiveScope::class)->where('name', 'like', "%$search%"));

                    foreach ($statusMap as $key => $value)
                    {
                        if (str_contains($search, $key))
                        {
                            $q->orWhere('status', $value);
                            break;
                        }
                    }

                    foreach ($typeMap as $key => $value) {
                        if (str_contains($search, $key)) {
                            $q->orWhere('item_type', $value);
                            break;
                        }
                    }
                });
            })
            ->latest()
            ->paginate(5);


        return view('back-end.items.data', compact('data'));
    }
}

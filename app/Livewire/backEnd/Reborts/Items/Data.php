<?php

namespace App\Livewire\BackEnd\Reborts\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\ItemCategory;
use Livewire\WithPagination;
use App\Models\ItemCardMovement;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class Data extends Component
{
    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];

    public $item_type;
    public $item_category_id;
    public $item_name;
    public $store_name;
    public $start_date;
    public $end_date;
    public $item_sort;
    public $rebort_type;

    public $search_movements;
    public $search_invoices;
    public $items;
    public $item_categories;
    public $stores;
    public $movements;



    // public function updatingSearch()
    // {
    //     $this->resetPage();
    // }


    // لما اختار  نوع الجرد
    public function rebortTypeChange($value)
    {
        $this->item_type            = '';
        $this->item_category_id     = '';
        $this->item_name            = '';
        $this->store_name           = '';
        $this->rebort_type          = $value;

        if ($this->rebort_type == '1')                      // فئة الصنف
        {
            $this->item_categories = ItemCategory::whereHas('items', function ($q) use ($value) {
                $q->where('total_qty_for_parent', '>', 0)->orWhere('total_qty_for_sub_items','>',0);
            })->get();
        } elseif ($this->rebort_type == '2')              // اسم الصنف
        {
            $this->items        = Item::where('total_qty_for_parent', '>', 0)->orWhere('total_qty_for_sub_items','>',0)->with('itemUnit', 'itemCategory', 'item_card_movements')->get();
        } else                                           // نوع الصنف
        {
        }
    }


    // لما اختار اسم الصنف
    public function ItemNameChange($value)
    {
        $this->stores = Store::whereHas('item_batches', function ($q) use ($value) {
            $q->where('item_code', $value);
        })->get();
    }


    public function updatedStartDate()
    {
        $this->resetErrorBag('start_date');
    }

    public function updatedEndDate()
    {
        $this->resetErrorBag('start_date');
    }

    public function updatedItemName()
    {
        $this->resetErrorBag('item_name');
    }


    public function updatedItemSort()
    {
        $this->resetErrorBag('item_sort');
    }



    public function submit()
    {
        $validated = $this->validate(
            [

                'rebort_type'       => 'required',
                'item_type'         => $this->rebort_type == '0' ? 'required' : 'nullable',
                'item_category_id'  => $this->rebort_type == '1' ? 'required' : 'nullable',
                'item_name'         => $this->rebort_type == '2' ? 'required' : 'nullable',

            ],
            [
                'rebort_type.required'               => 'نوع الجرد مطلوب.',
                'item_type.required'                 => 'نوع الصنف مطلوب.',
                'item_category_id.required'          => 'فئة الصنف مطلوب.',
                'item_name.required'                 => 'اسم الصنف مطلوب.',
            ]
        );


        if ($this->start_date > $this->end_date && $this->end_date != '')
        {
            $this->addError('start_date', 'يجب ان يكون تاريخ البداية قبل تاريخ النهاية.');
            return;
        }


        $this->search_movements  = true;
        $this->search_invoices   = true;
    }










    public function render()
    {
        $data           = '';
        $item           = '';
        $data_invoices  = '';

        if ($this->search_movements)
        {
            $query = ItemCardMovement::with(['itemMovementCategory', 'item', 'item_batch', 'itemMovementType', 'adminCreate', 'purchaseOrder', 'salesOrder']);

            if ($this->rebort_type == '0')                      // لو اختارت نوع الصنف
            {
                $query->whereHas('item', function ($q)
                {
                    $q->where('item_type', $this->item_type);
                });
            } elseif ($this->rebort_type  == '1')               // لو اختارت فئة الصنف
            {
                $query->whereHas('item', function ($q)
                {
                    $q->where('item_category_id', $this->item_category_id);
                });
            } else                                               // لو اختارت اسم الصنف
            {
                $query->where('item_code', $this->item_name);
                if ($this->store_name != '')
                {
                    $query->whereHas('item_batch', function ($q)
                    {
                        $q->where('store_id', $this->store_name);
                    });
                }
            }


            // فلترة بالتاريخ من جدول الحركات
            if ($this->start_date || $this->end_date)
            {
                    if ($this->start_date)
                    {
                        $query->where('date', '>=', $this->start_date);
                    }
                    if ($this->end_date)
                    {
                        $query->where('date', '<=', $this->end_date);
                    }
            }





            // ترتيب حسب المطلوب
            if ($this->item_sort === '0')
            {
                $query->whereHas('item', function ($q)
                {
                    $q->orderBy('total_qty_for_parent', 'ASC'); // الكميات الأقل
                });
            } elseif ($this->item_sort === '1')
            {
                $query->whereHas('item', function ($q)
                {
                    $q->orderBy('total_qty_for_parent', 'DESC'); // الكميات الأكثر
                });
            } elseif ($this->item_sort === '2') {
                // أقرب تاريخ انتهاء من جدول الدفعات
                $query->whereHas('item_batch', function ($q) {
                    $q->orderBy('expire_date', 'asc');
                });
            } elseif ($this->item_sort === '3') {
                // أبعد تاريخ انتهاء من جدول الدفعات
                $query->whereHas('item_batch', function ($q) {
                    $q->orderBy('expire_date', 'desc');
                });
            } elseif ($this->item_sort === '4')
            {
               // subquery لحساب الكمية المباعة لكل item_code
                $subQuery = ItemCardMovement::select(
                    'item_code',
                    DB::raw('SUM(qty_before_movement - qty_after_movement) as total_sales_qty')

                )->groupBy('item_code');

                // إعادة تهيئة الاستعلام مع joinSub للربط بالـ subquery
                $query = ItemCardMovement::with(['itemMovementCategory', 'item', 'item_batch', 'itemMovementType', 'adminCreate', 'purchaseOrder', 'salesOrder'])
                    ->joinSub($subQuery, 'sub', function($join)
                    {
                        $join->on('item_card_movements.item_code', '=', 'sub.item_code');
                    })
                    ->select('item_card_movements.*', 'sub.total_sales_qty')
                    ->orderBy('sub.total_sales_qty','asc');
            }

            $data = $query->paginate(5);
            // dd($data);
        }

        return view('back-end.reborts.items.data', compact('data', 'item', 'data_invoices'));
    }
}

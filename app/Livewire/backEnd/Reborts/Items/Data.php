<?php

namespace App\Livewire\BackEnd\Reborts\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemBatch;
use App\Models\ItemCategory;
use Livewire\WithPagination;
use App\Models\ItemCardMovement;
use Illuminate\Support\Facades\DB;

class Data extends Component
{
    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];

    public $item_type;
    public $item_category_id;
    public $item_name;
    public $start_date;
    public $end_date;
    public $item_sort;
    public $rebort_type;

    public $search_movements;
    public $search_invoices;
    public $items;
    public $item_categories;
    public $movements;



    // public function updatingSearch()
    // {
    //     $this->resetPage();
    // }



    public function rebortTypeChange($value)
    {
        $this->item_type            = '';
        $this->item_category_id     = '';
        $this->item_name            = '';
        $this->rebort_type          = $value;

        if ($this->rebort_type == '1')                      // فئة الصنف
        {
            $this->item_categories = ItemCategory::whereHas('items', function ($q) use ($value)
            {
                $q->where('total_qty_for_parent', '>', 0);
            })->get();

        }elseif($this->rebort_type == '2')              // اسم الصنف
        {
            $this->items        = Item::where('total_qty_for_parent','>',0)->with('itemUnit','itemCategory','item_card_movements')->get();

        }else                                           // نوع الصنف
        {

        }
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
        ]);


        if ($this->start_date > $this->end_date)
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
            $query = Item::with(['item_batches', 'adminCreate', 'item_card_movements' => function($q)
            {
                $q->with('itemMovementCategory');
            }]);

            if ($this->rebort_type == '0')                      // لو اختارت نوع الصنف
            {
               $query->where('item_type',$this->item_type);

            } elseif ($this->rebort_type  == '1')               // لو اختارت فئة الصنف
            {
                $query->where('item_category_id', $this->item_category_id);
            }else                                               // لو اختارت اسم الصنف
            {
                $query->where('item_code', $this->item_name);
            }


             // فلترة بالتاريخ من جدول الحركات
            if ($this->start_date || $this->end_date)
            {
                $query->whereHas('item_card_movements', function ($q)
                {
                    if ($this->start_date)
                    {
                        $q->where('created_at', '>=', $this->start_date);
                    }
                    if ($this->end_date)
                    {
                        $q->where('created_at', '<=', $this->end_date);
                    }
                });
            }





            // ترتيب حسب المطلوب
            if ($this->item_sort === '0')
            {
                $query->orderBy('total_qty_for_parent', 'ASC'); // الكميات الأقل
            }
            elseif ($this->item_sort === '1')
            {
                $query->orderBy('total_qty_for_parent', 'DESC'); // الكميات الأكثر
            }
            elseif ($this->item_sort === '2')
            {
                // أقرب تاريخ انتهاء من جدول الدفعات
                $query->with(['item_batches' => function ($q)
                {
                    $q->orderBy('expire_date', 'asc');
                }]);
            }
            elseif ($this->item_sort === '3')
            {
                // أبعد تاريخ انتهاء من جدول الدفعات
                $query->with(['item_batches' => function ($q)
                {
                    $q->orderBy('expire_date', 'desc');
                }]);
            }
            elseif ($this->item_sort === '4')
            {
                // الأكثر مبيعًا من جدول الحركات
                $query->withCount(['sales_movements as total_sales_qty' => function ($q)
                {
                    $q->select(DB::raw("SUM(qty_before_movement - qty_after_movement)"));
                }])->orderBy('total_sales_qty', 'desc');
            }

            $data = $query->paginate(2);
            // dd($data);
        }

        return view('back-end.reborts.items.data', compact('data', 'item', 'data_invoices'));
    }

}

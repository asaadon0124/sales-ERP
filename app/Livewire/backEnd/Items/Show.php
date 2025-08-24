<?php

namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\Scopes\ActiveScope;

class Show extends Component
{



    public $name                            = '';
    public $barcode                         = '';
    public $item_type;                                  // نوع الصنف --- مخزني - استهلاكي- عهدة
    public $item_category_id;                           // الفئة الرئيسية للصنف
    public $item_unit_id;                               // الوحدة الاساسية الصنف
    public $retail_unit                     = 0;        // تعيين القيمة الافتراضية إلى الوحدة الفرعية 0 (لا)
    public $sub_item_unit_id;                           // الوحدات الفرعية للصنف
    public $qty_sub_item_unit               = 0;        // عدد الوحدات الفرعية داخل الوحدة الرئيسية  ------ الطن به 1000 كجرام

    public $item_wholesale_price;                       // سعر الجملة للوحدة الاساسية
    public $item_Half_wholesale_price;                  // سعر النصف جملة للوحدة الاساسية
    public $item_retail_price;                          // سعر التجزئة للوحدة الاساسية
    public $item_cost_price;                            // سعر الشراء للوحدة الاساسية
    public $sub_item_wholesale_price        = 0.00;     // سعر الجملة للوحدة الفرعية
    public $sub_item_Half_wholesale_price   = 0.00;     // سعر النصف جملة للوحدة الفرعية
    public $sub_item_retail_price           = 0.00;     // سعر التجزئة للوحدة الفرعية
    public $sub_item_cost_price             = 0.00;     // سعر الشراء للوحدة الفرعية
    public $is_change;                                  // هل سعر الصنف متغير ولا ثابت في فاتورة البيع

    public $image;                                      // صورة الصنف
    public $status;
    public $item;


     // GET DATA  ******************************************************************************************
     public $itemsUnitsChild     = [];           // الوحدات الفرعية للصنف
     public $itemCategories      = '';
     public $itemsUnitsParent    = '';           // الوحدات الاب للصنف


     protected $listeners = ['unitChanged'];





     public function mount($id)
     {


            $this->item = Item::with(['itemCategory' => function($q)
            {
                $q->withoutGlobalScope(ActiveScope::class);

            },'itemUnit' => function($q2)
            {
                 $q2->withoutGlobalScope(ActiveScope::class);
            }])->where('id',$id)->first();



        // GET DATA
        $this->name                             = $this->item->name;
        $this->barcode                          = $this->item->barcode;
        $this->item_type                        = $this->item->item_type;
        $this->item_category_id                 = $this->item->item_category_id;
        $this->item_unit_id                     = $this->item->item_unit_id;
        $this->retail_unit                      = $this->item->retail_unit;
        $this->sub_item_unit_id                 = $this->item->sub_item_unit_id;
        $this->qty_sub_item_unit                = $this->item->qty_sub_item_unit;
        $this->item_wholesale_price             = $this->item->item_wholesale_price;
        $this->item_Half_wholesale_price        = $this->item->item_Half_wholesale_price;
        $this->item_retail_price                = $this->item->item_retail_price;
        $this->item_cost_price                  = $this->item->item_cost_price;
        $this->sub_item_wholesale_price         = $this->item->sub_item_wholesale_price;
        $this->sub_item_Half_wholesale_price    = $this->item->sub_item_Half_wholesale_price;
        $this->sub_item_retail_price            = $this->item->sub_item_retail_price;
        $this->sub_item_cost_price              = $this->item->sub_item_cost_price;
        $this->is_change                        = $this->item->is_change;
        $this->status                           = $this->item->status;
        $this->image                            = $this->item->image;



     }



    public function render()
    {
        if (!auth()->user()->can('تفاصيل الصنف'))
        {
            abort(403, 'ليس لديك صلاحية عرض  تفاصيل الصنف ');
        }
        return view('back-end.items.show');
    }
}

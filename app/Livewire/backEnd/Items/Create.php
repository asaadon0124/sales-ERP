<?php
namespace App\Livewire\BackEnd\Items;

use App\Models\Item;
use Livewire\Component;
use App\Models\ItemUnit;
use Illuminate\Support\Str;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Items\Data;
use App\Models\Scopes\CreatedByScope;


class Create extends Component
{
    use WithFileUploads;



    public $name                = '';
    public $barcode             = '';
    public $item_type;                          // نوع الصنف --- مخزني - استهلاكي- عهدة
    public $item_category_id;                   // الفئة الرئيسية للصنف
    public $item_unit_id;                       // الوحدة الاساسية الصنف
    public $retail_unit         = "0";            // تعيين القيمة الافتراضية إلى الوحدة الفرعية 0 (لا)
    public $sub_item_unit_id;                  // الوحدات الفرعية للصنف
    public $qty_sub_item_unit   = 0;           // عدد الوحدات الفرعية داخل الوحدة الرئيسية  ------ الطن به 1000 كجرام

    public $item_wholesale_price;               // سعر الجملة للوحدة الاساسية
    public $item_Half_wholesale_price;          // سعر النصف جملة للوحدة الاساسية
    public $item_retail_price;                  // سعر التجزئة للوحدة الاساسية
    public $item_cost_price;                    // سعر الشراء للوحدة الاساسية
    public $sub_item_wholesale_price = 0.00;           // سعر الجملة للوحدة الفرعية
    public $sub_item_Half_wholesale_price = 0.00;      // سعر النصف جملة للوحدة الفرعية
    public $sub_item_retail_price = 0.00;              // سعر التجزئة للوحدة الفرعية
    public $sub_item_cost_price = 0.00;                // سعر الشراء للوحدة الفرعية
    public $is_change;                          // هل سعر الصنف متغير ولا ثابت في فاتورة البيع

    public $image;                              // صورة الصنف
    public $status;                             // حالة الصنف
    public $company_code;                       // كود الشركة


    public $item;
    public $getLastItem;



    // GET DATA  ******************************************************************************************
    public $itemsUnitsChild     = [];           // الوحدات الفرعية للصنف
    public $itemCategories      = '';
    public $itemsUnitsParent    = '';           // الوحدات الاب للصنف


    protected $listeners = ['unitChanged'];

    public function unitChanged($newContent)
    {
        $this->item_unit_id = $newContent;
        // dd($newContent);
    }



    public function submit_retail_unit($value)
    {
        $this->retail_unit = $value;
    }

    public function mount()
    {
        $this->itemCategories   = ItemCategory::all();
        $this->itemsUnitsParent = getData_With_Master(new ItemUnit(),'master','active')->withoutGlobalScope(CreatedByScope::class)->get();
        $this->itemsUnitsChild  = getData_With_Master(new ItemUnit(),'sub_master','active')->withoutGlobalScope(CreatedByScope::class)->get();
        $this->getLastItem      = Item::select('item_code','company_code','id','name')->where('company_code',auth()->user()->company_code)->orderBy('item_code','DESC')->first();
        $this->company_code     = auth()->user()->company_code;
        if ($this->barcode == '')
        {
            $this->barcode = Str::random(32);
        }
    }




    public function rules()
    {
        $rules = [
            'name' =>
            [
                'required',
                Rule::unique('items')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'item_type'                         => 'required|in:0,1,2',
            'item_category_id'                  => 'required',
            'item_unit_id'                      => 'required|exists:item_units,id',

            'item_wholesale_price'              => 'required_with:item_unit_id|numeric|gt:0',
            'item_Half_wholesale_price'         => 'required_with:item_unit_id|numeric|gt:0',
            'item_retail_price'                 => 'required_with:item_unit_id|numeric|gt:0',
            'item_cost_price'                   => 'required_with:item_unit_id|numeric|gt:0',

            'retail_unit'                       => 'required',



            'is_change'                         => 'required|in:0,1',
            'status'                            => 'required|in:active,un_active',
        ];

        // نضيف شرط gt:0 على sub_item_cost_price فقط لو retail_unit == 1
        // if ($this->retail_unit == 1)
        // {
        //     $rules['sub_item_cost_price'] = 'required|numeric|gt:0';
        //     $rules['qty_sub_item_unit'] = 'required|numeric|gt:0';
        //     $rules['sub_item_unit_id'] = 'required|numeric|gt:0';
        //     $rules['sub_item_wholesale_price'] = 'required|numeric|gt:0';
        //     $rules['sub_item_Half_wholesale_price'] = 'required|numeric|gt:0';
        //     $rules['sub_item_retail_price'] = 'required|numeric|gt:0';

        // }


        if ($this->retail_unit == 1)
        {
            $rules['sub_item_cost_price']           = ['required', 'numeric', 'gt:0'];
            $rules['qty_sub_item_unit']             = ['required', 'numeric', 'gt:0'];
            $rules['sub_item_unit_id']              = ['required', 'numeric', 'gt:0'];

            $rules['sub_item_wholesale_price'] = [
                'required', 'numeric', 'gt:0',
                function ($attribute, $value, $fail) {
                    if ($value <= $this->sub_item_cost_price) {
                        $fail('يجب أن يكون سعر الجملة أكبر من سعر التكلفة.');
                    }
                },
            ];

            $rules['sub_item_Half_wholesale_price'] = [
                'required', 'numeric', 'gt:0',
                function ($attribute, $value, $fail) {
                    if ($value <= $this->sub_item_cost_price) {
                        $fail('يجب أن يكون سعر نصف الجملة أكبر من سعر التكلفة.');
                    }
                },
            ];

            $rules['sub_item_retail_price'] = [
                'required', 'numeric', 'gt:0',
                function ($attribute, $value, $fail) {
                    if ($value <= $this->sub_item_cost_price) {
                        $fail('يجب أن يكون سعر البيع أكبر من سعر التكلفة.');
                    }
                },
            ];
        }

        return $rules;
    }

  public function messages()
  {
    return
    [
        'name.required'                                 => 'اسم القسم مطلوب.',
        'name.unique'                                   => 'اسم الصنف موجود بالفعل.',
        'item_type.required'                            => 'نوع الصنف مطلوب .',
        'item_type.in'                                  => 'اختار نوع الصنف من الانواع المتاحة فقط  .',
        'item_category_id.required'                     => 'فئة الصنف مطلوبة .',
        'item_unit_id.required'                         => 'الوحدة الاساسية للصنف مطلوبة .',
        'item_unit_id.exists'                           => 'اختار الوحدة الرئيسية للصنف من الوحدات المتاحة فقط  .',
        'item_wholesale_price.required_with'            => ' سعر الجملة للوحدة الاساسية للصنف مطلوب.',
        'item_Half_wholesale_price.required_with'       => ' سعر النصف جملة للوحدة الاساسية للصنف مطلوب.',
        'item_retail_price.required_with'               => ' سعر التجزئة للوحدة الاساسية للصنف مطلوب.',
        'item_cost_price.required_with'                 => ' سعر التكلفة للوحدة الاساسية للصنف مطلوب.',

        'retail_unit.required'                          => 'هذا الحقل مطلوب.',
        'sub_item_unit_id.required_if'                  => 'برجاء اختيار الوحدة الفرعية للصنف.',
        'qty_sub_item_unit.required_if'                 => 'برجاء تحديد عدد الوحدات الفرعية لكل وحدة اساسية للصنف.',
        'sub_item_wholesale_price.required_if'          => ' سعر الجملة للوحدة الفرعية للصنف مطلوب.',
        'sub_item_Half_wholesale_price.required_if'     => ' سعر النصف جملة للوحدة الفرعية للصنف مطلوب.',
        'sub_item_retail_price.required_if'             => ' سعر التجزئة للوحدة الفرعية للصنف مطلوب.',
        'sub_item_cost_price.required_if'               => ' سعر التكلفة للوحدة الفرعية للصنف مطلوب.',
        'sub_item_cost_price.gt'                        => ' سعر التكلفة للوحدة الفرعية للصنف يجب ان لا = 0.',
        'sub_item_cost_price.numeric'                   => ' سعر التكلفة للوحدة الفرعية للصنف يجب ان يكون ارقام فقط.',
        'qty_sub_item_unit.numeric'                     => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'sub_item_wholesale_price.numeric'              => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'sub_item_Half_wholesale_price.numeric'         => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'sub_item_retail_price.numeric'                 => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'item_wholesale_price.numeric'                  => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'item_Half_wholesale_price.numeric'             => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'item_retail_price.numeric'                     => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'item_cost_price.numeric'                       => 'هذا الحقل يجب ان يحتوي علي ارقام فقط',
        'item_wholesale_price.gt'                       => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',
        'sub_item_Half_wholesale_price.gt'              => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',
        'item_retail_price.gt'                          => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',
        'item_cost_price.gt'                            => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',
        'qty_sub_item_unit.gt'                          => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',
        'sub_item_wholesale_price.gt'                   => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',
        'sub_item_Half_wholesale_price.gt'              => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',
        'sub_item_retail_price.gt'                      => 'هذا الحقل يجب ان يكون قيمته اكبر من 0 ',

        'is_change.required'                            => 'برجاء اختيار قيمة من الحقل.',
        'status.required'                               => '؛حالة الصنف مطلوبة.',
    ];
  }

    public function submit()
    {
        if (!auth()->user()->can('اضافة صنف جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة صنف جديد ');
        }

        $this->validate($this->rules(), $this->messages());

        try
        {
            DB::beginTransaction();

            $this->item                             = new Item();
            $this->item->name                       = $this->name;
            $this->item->item_type                  = $this->item_type;
            $this->item->retail_unit                = $this->retail_unit;
            $this->item->item_category_id           = $this->item_category_id;
            $this->item->item_unit_id               = $this->item_unit_id;
            $this->item->sub_item_unit_id           = $this->sub_item_unit_id;

            if ($this->getLastItem && $this->getLastItem != null)
            {
                $this->item->item_code              = $this->getLastItem->item_code + 1;
            }else
            {
                $this->item->item_code              = 1;
            }

            $this->item->barcode                    = $this->barcode;
            $this->item->item_wholesale_price       = $this->item_wholesale_price;
            $this->item->item_Half_wholesale_price  = $this->item_Half_wholesale_price;
            $this->item->item_retail_price          = $this->item_retail_price;
            $this->item->item_cost_price            = $this->item_cost_price;
            $this->item->sub_item_wholesale_price   = $this->sub_item_wholesale_price;
            $this->item->sub_item_Half_wholesale_price = $this->sub_item_Half_wholesale_price;
            $this->item->sub_item_retail_price      = $this->sub_item_retail_price;
            $this->item->sub_item_cost_price        = $this->sub_item_cost_price;
            $this->item->qty_sub_item_unit          = $this->qty_sub_item_unit;
            $this->item->is_change                  = $this->is_change;
            $this->item->status                     = $this->status;
            $this->item->company_code               = auth()->user()->company_code;
            $this->item->created_by                 = auth()->user()->id;
            $this->item->updated_by                 = auth()->user()->id;
            $this->item->save();

              // 2 - CHECK PHOTO *****************
            if ($this->image)
            {
                $path =  $this->image->store('Items','public');
                $this->item->photo = $path;
                $this->item->save();
            }


             // 3 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'اضافة صنف جديد ';
                $actionHistory->desc        = "اضافة صنف جديد";
                $actionHistory->table_name  = 'Item';
                $actionHistory->row_id      = $this->item->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();
            DB::commit();
            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            toastr()->success('تم اضافة الصنف بنجاح', 'رسالة اضافة'); // ✅
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created
            return redirect()->route('items.index');


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('storesErrorMS');                   // Flash Message
        }

    }

    public function render()
    {
        return view('back-end.items.create',
        [
            'itemCategories' => $this->itemCategories,
            'itemsUnitsParent' => $this->itemsUnitsParent,
            'itemsUnitsChild' => $this->itemsUnitsChild,
        ]);
    }
}

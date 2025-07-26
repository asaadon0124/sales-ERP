<?php

namespace App\Livewire\BackEnd\ItemUnits;

use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\ItemUnits\Data;

class Create extends Component
{
    public $name               = '';
    public $status             = '';
    public $is_master          = '';
    public $date               = '';
    public $company_code;

    protected $listeners = ['itemUnitsCreate'];



    public function itemUnitsCreate()
    {
        // عرض المودال الخاص بالإضافة
        $this->dispatch('createModalToggle');
    }

    public function mount()
    {
        $this->company_code = auth()->user()->company_code;
    }


    public function submit()
    {
        if (!auth()->user()->can('اضافة وحدة صنف جديدة'))
        {
            abort(403, 'ليس لديك صلاحية اضافة وحدة صنف جديدة');
        }

        // إزالة المسافات الزائدة
        $this->name = trim($this->name);


        // التحقق من القيم المدخلة
        $validated = $this->validate(
            [
                'name' => [
                    'required',
                    Rule::unique('item_units')->where('company_code', $this->company_code),
                ],
                'status'    => 'required',
                'is_master' => 'required',
                'date'      => 'required',
            ],
            [
                'name.required'       => 'اسم الوحدة مطلوب.',
                'name.unique'         => 'اسم الوحدة موجود بالفعل.',
                'status.required'     => 'حالة الوحدة مطلوبة.',
                'is_master.required'  => 'نوع الوحدة مطلوب.',
                'date.required'       => 'التاريخ مطلوب.',
            ]
        );

        try
        {

        DB::beginTransaction();

           $create =  ItemUnit::create([
                'name'         => $this->name,
                'status'       => $this->status,
                'is_master'    => $this->is_master,
                'date'         => $this->date,
                'company_code' => $this->company_code,
                'created_by'   => auth()->user()->id,
                'updated_by'   => auth()->user()->id,
            ]);

            // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'اضافة وحدة صنف جديدة ';
                $actionHistory->desc        = "اضافة وحدة صنف جديدة";
                $actionHistory->table_name  = 'ItemUnit';
                $actionHistory->row_id      = $create->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();

        DB::commit();

            // إعادة تعيين الحقول (مع الحفاظ على company_code)
            $this->reset(['name', 'status', 'is_master', 'date']);

            // إرسال الأحداث
            $this->dispatch('ItemUnitsCreateMS');                 // عرض رسالة نجاح
            $this->dispatch('createModalToggle');                 // إغلاق المودال
            $this->dispatch('refreshData')->to(Data::class);     // تحديث البيانات

        } catch (\Throwable $th)
        {
            DB::rollBack();
            logger()->error('خطأ أثناء إنشاء وحدة الصنف: ' . $th->getMessage());
            $this->dispatch('storesErrorMS'); // إرسال رسالة خطأ
        }
    }

    public function render()
    {
        return view('back-end.item-units.create');
    }
}

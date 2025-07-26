<?php

namespace App\Livewire\BackEnd\Suppliers;

use App\Models\Account;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\ActionHistory;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Suppliers\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{

    public $supplier;
    public $supplierId;
    public $account;
    public $typeStatus              = 'active';
    // public $getLastSupplier;
    public $suppliers_Category;
    public $supplierAccount;
    public $adminSitting;

    public $name;
    public $status;
    public $notes;
    public $address;
    public $supplier_Category_id;



    protected $listeners = ['supplierUpdate'];


    public function supplierUpdate($id)
    {
        $this->supplier                 = Supplier::with('supplier_account','transations')->find($id);
        $this->supplierId               = $id;
        $this->name                     = $this->supplier->name;
        $this->supplier_Category_id     = $this->supplier->supplier_Category_id;
        $this->address                  = $this->supplier->address;
        $this->status                   = $this->supplier->status;
        $this->notes                    = $this->supplier->notes;

        $this->dispatch('updateModalToggle');
    }



    public function mount()
    {
        $this->suppliers_Category               = SupplierCategory::where('company_code',auth()->user()->company_code)->get();
    }


    public function rules()
    {
        return
        [
            'name' =>
            [
                'required',
                Rule::unique('suppliers')
                    ->ignore($this->supplier->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->supplier->company_code);
                    }),
            ],
            'status'                        => 'required',
            'start_balance_status'          => 'required',
            'start_balance'                 => 'required',
            'address'                       => 'required',
            'supplier_Category_id'          => 'required||exists:supplier_categories,id',
        ];
    }

    public function messages()
    {
        return
        [
          'name.required'                   => 'اسم المورد مطلوب.',
            'address.required'              => 'عنوان المورد مطلوب.',
            'status.required'               => '؛حالة المورد مطلوبة.',
            'name.unique'                   => 'اسم المورد موجود بالفعل.',
            'start_balance.required'        => 'رصيد المورد بداية المدة مطلوب.',
            'start_balance_status.required' => 'حالة المورد اول المدة مطلوب.',
            'supplier_Category_id.required' => 'اسم قسم المورد مطلوب.',
            'supplier_Category_id.exists'   => 'اسم قسم المورد غير موجود.',
        ];
    }



    public function submit()
    {
         if (!auth()->user()->can('تعديل مورد'))
        {
            abort(403, 'ليس لديك صلاحية تعديل مورد ');
        }

        $this->supplier->load(['supplier_account', 'transations']);


         // لو في اي عمليات نقدية تمت علي الحاسب ده مش حينفع نعدل رصيد اول المدة
        if ($this->supplier->transations->count() > 0 && $this->supplier->start_balance != $this->start_balance)
        {
            $this->addError('start_balance', 'لا يمكن تغير قيمة رصيد اول المدة لان الحساب لديه عمليات صرف و تحصيل تمت عليه .');
            return;
        }


        try
        {

            // dd($this->supplier);
            DB::beginTransaction();
                $this->supplier->name                       = $this->name;
                $this->supplier->address                    = $this->address;
                $this->supplier->notes                      = $this->notes;
                $this->supplier->status                     = $this->status;
                $this->supplier->supplier_Category_id       = $this->supplier_Category_id;
                $this->supplier->updated_by                 = auth()->user()->id;
                $this->supplier->save();

                $this->supplier->supplier_account->name     = $this->name;
                $this->supplier->supplier_account->notes    = $this->notes;
                $this->supplier->supplier_account->status   = $this->status;
                $this->supplier->supplier_account->save();


                 // 3 - تعديل رصيد اول المدة لة الرصيد الحالي = 0 او الرصيد الحالي == رصيد اول المدة
            if ($this->supplier->current_balance == 0 || $this->supplier->current_balance == $this->supplier->start_balance)
            {

                $this->supplier->start_balance_status    = $this->start_balance_status;
                $this->supplier->start_balance           = $this->start_balance;
                $this->supplier->current_balance         = $this->start_balance;
                $this->supplier->save();

                $this->supplier->supplier_account->start_balance_status    = $this->start_balance_status;
                $this->supplier->supplier_account->start_balance           = $this->start_balance;
                $this->supplier->supplier_account->current_balance         = $this->start_balance;
                $this->supplier->supplier_account->save();

            }

                // 4 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'تعديل مورد  ';
                $actionHistory->desc        = "تعديل مورد {$this->supplier->name}";
                $actionHistory->table_name  = 'Supplier';
                $actionHistory->row_id      = $this->supplier->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();

            DB::commit();
        } catch (\Throwable $th)
        {
            //throw $th;
        }

        // Dispatch events
        $this->dispatch('supplierUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }



    public function render()
    {
        return view('back-end.suppliers.update',
        [
            'supplierCategory' => $this->suppliers_Category
        ]);
    }
}

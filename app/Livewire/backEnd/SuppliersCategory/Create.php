<?php

namespace App\Livewire\BackEnd\SuppliersCategory;

use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use App\Models\SupplierCategory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\SuppliersCategory\Data;


class Create extends Component
{


    public $name               = '';
    public $status             = '';
    public $company_code;

    protected $listeners = ['suppliersCategoryCreate'];


    public function suppliersCategoryCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }

    public function mount()
    {
        $this->company_code = auth()->user()->company_code;
    }

    public function submit()
    {
        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('supplier_categories')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'            => 'required',
        ],
        [
            'name.required'             => 'اسم القسم مطلوب.',
            'status.required'           => '؛حالة القسم مطلوبة.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ]);

        if (!auth()->user()->can('اضافة قسم جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة قسم جديد ');
        }



        try
        {
            // dd($this->all());

            DB::beginTransaction();
            $create = SupplierCategory::create(
            [
                'name'                  => $this->name,
                'status'                => $this->status,
                'company_code'          => auth()->user()->company_code,
                'created_by'            => auth()->user()->id,
                'updated_by'            => auth()->user()->id,
            ]);


             // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'اضافة قسم جديد ';
                $actionHistory->desc        = "اضافة قسم جديد";
                $actionHistory->table_name  = 'supplierCategory';
                $actionHistory->row_id      = $create->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();

            DB::commit();

            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('suppliersCategoryCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);        // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('storesErrorMS');                   // Flash Message
        }

    }



    public function render()
    {
        return view('back-end.-suppliers-category.create');
    }
}

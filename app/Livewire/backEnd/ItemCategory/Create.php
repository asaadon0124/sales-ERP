<?php

namespace App\Livewire\BackEnd\ItemCategory;

use Livewire\Component;
use App\Models\ItemCategory;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class Create extends Component
{
    public $name               = '';
    public $status             = '';
    public $date               = '';
    public $company_code;

    protected $listeners = ['itemCategoriesCreate'];


    public function itemCategoriesCreate()
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
        if (!auth()->user()->can('اضافة فئة جديدة للصنف'))
        {
            abort(403, 'ليس لديك صلاحية اضافة فئة جديدة للصنف');
        }
        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('item_categories')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'            => 'required',
            'date'              => 'required',
        ],
        [
            'name.required'             => 'اسم القسم مطلوب.',
            'date.required'             => ' التاريخ مطلوب.',
            'status.required'           => '؛حالة القسم مطلوبة.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ]);


        try
        {
            DB::beginTransaction();
               $create = ItemCategory::create(
                [
                    'name'                  => $this->name,
                    'status'                => $this->status,
                    'date'                  => $this->date,
                    'company_code'          => auth()->user()->company_code,
                    'created_by'            => auth()->user()->id,
                    'updated_by'            => auth()->user()->id,
                ]);

                // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة فئة صنف جديدة ';
                    $actionHistory->desc        = "اضافة فئة صنف جديدة";
                    $actionHistory->table_name  = 'ItemCategory';
                    $actionHistory->row_id      = $create->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
            DB::commit();


            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('itemCategoriesCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
         DB::rollBack();
            throw $th;
            $this->dispatch('itemCategoriesErrorMS');                   // Flash Message
        }

    }



    public function render()
    {

        return view('back-end.item-category.create');
    }
}

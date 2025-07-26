<?php

namespace App\Livewire\BackEnd\ItemCardMovementCategory;

use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementCategory;
use App\Livewire\BackEnd\ItemCardMovementCategory\Data;


class Create extends Component
{

    public $name               = '';
    public $company_code;

    protected $listeners = ['iteCardMovementsCategoryCreate'];


    public function iteCardMovementsCategoryCreate()
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
        if (!auth()->user()->can('اضافة فئة حركة جديدة للصنف'))
        {
            abort(403, 'ليس لديك صلاحية اضافة فئة حركة جديدة للصنف ');
        }
        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('item_card_movement_categories')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
        ],
        [
            'name.required'             => 'اسم القسم مطلوب.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ]);


        try
        {

            DB::beginTransaction();
                $create = ItemCardMovementCategory::create(
                [
                    'name'                  => $this->name,
                    'company_code'          => auth()->user()->company_code,
                    'created_by'            => auth()->user()->id,
                    'updated_by'            => auth()->user()->id,
                ]);

                // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'اضافة فئة جديدة لحركة الصنف ';
                $actionHistory->desc        = "اضافة فئة جديدة لحركة الصنف";
                $actionHistory->table_name  = 'ItemCardMovementCategory';
                $actionHistory->row_id      = $create->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();
            DB::commit();

            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('ItemCardMovementCategoryCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('storesErrorMS');                   // Flash Message
        }

    }
    public function render()
    {
        return view('back-end.item-card-movement-category.create');
    }
}

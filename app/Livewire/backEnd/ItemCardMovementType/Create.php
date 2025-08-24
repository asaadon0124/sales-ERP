<?php

namespace App\Livewire\BackEnd\ItemCardMovementType;

use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\ItemCardMovementType;
use App\Livewire\BackEnd\ItemCardMovementType\Data;


class Create extends Component
{

    public $name               = '';
    public $company_code;

    protected $listeners = ['iteCardMovementsTypeCreate'];


    public function iteCardMovementsTypeCreate()
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
        if (!auth()->user()->can('اضافة حركة جديدة للصنف'))
        {
            abort(403, 'ليس لديك صلاحية اضافة حركة حركة جديدة للصنف');
        }

        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('item_card_movement_types')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
        ],
        [
            'name.required'             => 'نوع حركة الصنف  مطلوب.',
            'name.unique'               => 'نوع حركة الصنف  موجود بالفعل.',
        ]);


        try
        {

            DB::beginTransaction();
               $create =  ItemCardMovementType::create(
                [
                    'name'                  => $this->name,
                    'company_code'          => auth()->user()->company_code,
                    'created_by'            => auth()->user()->id,
                    'updated_by'            => auth()->user()->id,
                ]);

                // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة حركة جديدة للصنف';
                    $actionHistory->desc        = "اضافة حركة جديدة للصنف";
                    $actionHistory->table_name  = 'ItemCardMovementType';
                    $actionHistory->row_id      = $create->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
            DB::commit();
            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('ItemCardMovementTypeCreateMS');                   // Flash Message
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
        return view('back-end.item-card-movement-type.create');
    }
}

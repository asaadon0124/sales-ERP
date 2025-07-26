<?php

namespace App\Livewire\BackEnd\MoveTypes;

use Livewire\Component;
use App\Models\MoveType;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\MoveTypes\Data;


class Create extends Component
{
    public $name;
    public $status;
    public $in_screen;
    public $is_private_internal;
    public $company_code;

    protected $listeners = ['moveTypesCreate'];


    public function moveTypesCreate()
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
         if (!auth()->user()->can('اضافة نوع حركة النقدية'))
        {
            abort(403, 'ليس لديك صلاحية اضافة نوع حركة النقدية');
        }

        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('move_types')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'                => 'required',
            'in_screen'             => 'required',
            'status'                => 'required',
            'is_private_internal'   => 'required',
        ],
        [
            'name.required'                     => 'اسم حركة النقدية مطلوب.',
            'is_private_internal.required'      => ' نوع حركة النقدية مطلوب.',
            'in_screen.required'                => ' التاريخ مطلوب.',
            'status.required'                   => '؛حالة حركة النقدية مطلوبة.',
            'name.unique'                       => 'اسم حركة النقدية موجود بالفعل.',
        ]);


        try
        {
            DB::beginTransaction();

              $create =  MoveType::create(
                [
                    'name'                  => $this->name,
                    'status'                => $this->status,
                    'in_screen'             => $this->in_screen,
                    'is_private_internal'   => $this->is_private_internal,
                    'company_code'          => auth()->user()->company_code,
                    'created_by'            => auth()->user()->id,
                    'updated_by'            => auth()->user()->id,
                ]);

                 // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'اضافة نوع حركة النقدية ';
                $actionHistory->desc        = "اضافة نوع حركة النقدية";
                $actionHistory->table_name  = 'Item';
                $actionHistory->row_id      = $create->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();
            DB::commit();

            $this->reset(['status','in_screen','is_private_internal','name']);                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('typesCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();

            throw $th;
            $this->dispatch('typesErrorMS');                   // Flash Message
        }

    }


    public function render()
    {
        return view('back-end.move-types.create');
    }
}

<?php

namespace App\Livewire\BackEnd\MoveTypes;

use Livewire\Component;
use App\Models\MoveType;


use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\MoveTypes\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح

class Update extends Component
{


    public $name;
    public $status;
    public $in_screen;
    public $is_private_internal;
    public $company_code;

    public $moveType;
    public $moveTypeId;
    protected $listeners = ['moveTypesUpdate'];



    public function moveTypesUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->moveType                 = MoveType::find($id);
        $this->moveTypeId               = $id;
        $this->name                     = $this->moveType->name;
        $this->status                   = $this->moveType->status;
        $this->in_screen                = $this->moveType->in_screen;
        $this->is_private_internal      = $this->moveType->is_private_internal;
        $this->company_code             = $this->moveType->company_code;

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('move_types')
                    ->ignore($this->moveType->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],
            'status'            => 'required',
            'in_screen'              => 'required',
            'status'              => 'required',
            'is_private_internal'              => 'required',
        ];
    }

    public function messages()
    {
        return
        [
            'name.required'                     => 'اسم حركة النقدية مطلوب.',
            'is_private_internal.required'      => ' نوع حركة النقدية مطلوب.',
            'in_screen.required'                => ' التاريخ مطلوب.',
            'status.required'                   => '؛حالة حركة النقدية مطلوبة.',
            'name.unique'                       => 'اسم حركة النقدية موجود بالفعل.',
        ];
    }

    public function submit()
    {
         if (!auth()->user()->can('تعديل انواع حركات النقدية'))
        {
            abort(403, 'ليس لديك صلاحية تعديل انواع حركات النقدية');
        }

        DB::beginTransaction();
            $this->validate($this->rules(), $this->messages());
            $this->moveType->name                  = $this->name;
            $this->moveType->status                = $this->status;
            $this->moveType->in_screen             = $this->in_screen;
            $this->moveType->is_private_internal   = $this->is_private_internal;
            $this->moveType->company_code          = auth()->user()->company_code;
            $this->moveType->updated_by            = auth()->user()->id;
            // Save the changes
            $this->moveType->save();

             // 2 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'تعديل انواع حركات النقدية  ';
            $actionHistory->desc        = "تعديل انواع حركات النقدية {$this->moveType->name}";
            $actionHistory->table_name  = 'MoveType';
            $actionHistory->row_id      = $this->moveType->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();

        DB::commit();
        DB::rollBack();

        // Dispatch events
        $this->dispatch('typesUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }
    public function render()
    {
        return view('back-end.move-types.update');
    }
}

<?php

namespace App\Livewire\BackEnd\MaterialTypes;

use Livewire\Component;
use App\Models\MaterialType;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح
use App\Livewire\BackEnd\MaterialTypes\Data;

class Update extends Component
{
    public $types, $name, $status, $company_code;
    protected $listeners = ['matrialTypeUpdate'];

    public function matrialTypeUpdate($id)
    {

        // fill $slider with the eloquent model of the same id
        $this->types                = MaterialType::find($id);
        $this->name                 = $this->types->name;
        $this->status               = $this->types->status;
        $this->company_code         = $this->types->company_code;

        // show edit modal
        $this->dispatch('updateModalToggle');
    }



    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('material_types')
                    ->ignore($this->types->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query) {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],
            'status' => ['required'],
        ];
    }


    public function messages()
    {
        return
        [
            'name.required'             => 'اسم الخزينة مطلوب.',
            'name.unique'               => 'اسم الخزينة موجود بالفعل.',
            'status.required'           => 'الحالة مطلوبة.',
        ];
    }

    public function submit()
    {
        $this->validate($this->rules(), $this->messages());
        $this->types->name                 = $this->name;
        $this->types->status               = $this->status;
        $this->types->company_code         = auth()->user()->company_code;
        $this->types->updated_by           = auth()->user()->id;
        // Save the changes
        $this->types->save();

        // Dispatch events
        $this->dispatch('material_typesUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }


    public function render()
    {
        return view('back-end.material-types.update');
    }
}

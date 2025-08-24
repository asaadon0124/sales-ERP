<?php

namespace App\Livewire\BackEnd\MaterialTypes;

use Livewire\Component;
use App\Models\MaterialType;
use App\Livewire\BackEnd\MaterialTypes\Data;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public $name = '';
    public $status = '';
    public $company_code;

    protected $listeners = ['matrialTypeCreate'];

    public function matrialTypeCreate()
    {
        // Show Create modal
        $this->dispatch('createModalToggle');
    }

    public function mount()
    {
        $this->company_code = auth()->user()->company_code;
    }

    public function render()
    {
        return view('back-end.material-types.create');
    }

    public function submit()
    {
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('material_types')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status' => 'required',
        ], [
            'name.required' => 'اسم القسم مطلوب.',
            'status.required' => 'حالة القسم مطلوبة.',
            'name.unique' => 'اسم القسم موجود بالفعل.',
        ]);

        try {
            MaterialType::create([
                'name' => $this->name,
                'status' => $this->status,
                'company_code' => $this->company_code,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            // فقط إعادة تعيين الحقول التي يتم تعبئتها من قبل المستخدم
            $this->reset(['name', 'status']);

            // Dispatch events
            $this->dispatch('material_typesCreateMS'); // Flash Message
            $this->dispatch('createModalToggle');      // Close Create Modal
            $this->dispatch('refreshData')->to(Data::class); // Refresh Index Page
        } catch (\Throwable $th) {
            // يمكنك تسجيل الخطأ هنا إن أردت
            // logger()->error($th);
        }
    }
}

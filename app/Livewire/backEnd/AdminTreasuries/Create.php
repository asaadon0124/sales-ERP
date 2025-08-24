<?php

namespace App\Livewire\BackEnd\AdminTreasuries;

use App\Models\Admin;
use Livewire\Component;
use App\Models\Treasuries;
use App\Models\AdminTreasuries;
use Illuminate\Validation\Rule;
use App\Livewire\BackEnd\Emoloyees\Show;

class Create extends Component
{

    public $treasury_id;
    public $admin_id;
    public $status;
    public $company_code;
    public $admin;

    public $treasuries         = '';

    protected $listeners = ['AdminTreasuriesCreate'];


    public function AdminTreasuriesCreate($id)
    {
        // dd($id);
        $this->admin                = Admin::with('treasuries')->find($id);
        $this->admin_id             = $id;
         $this->company_code        = auth()->user()->company_code;
        $this->treasuries           = Treasuries::whereDoesntHave('admins', function ($query) {
            $query->where('admin_id', $this->admin_id);
        })->get();
        // show Create modal
        $this->dispatch('createModalToggle');
    }




    public function submit()
    {
        if (!auth()->user()->can('اضافة خزنة جديدة للموظف'))
        {
            abort(403, 'ليس لديك صلاحية اضافة خزنة');
        }
        // dd($this->all());
        $validated = $this->validate(
        [
            'treasury_id' =>
            [
                'required',
                Rule::unique('admin_treasuries')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code)->where('admin_id',$this->admin_id);
                }),
            ],
            'status'      => 'required',
        ],
        [
            'treasury_id.required'              => 'اسم الخزنة مطلوب.',
            'status.required'                   => '؛حالة الخزنة مطلوبة.',
            'treasury_id.unique'                => 'اسم الخزنة موجود بالفعل.',
        ]);


        try
        {


            $this->admin->treasuries()->attach($this->treasury_id,
            [
                'company_code' => auth()->user()->company_code,
                'created_by'   => auth()->user()->id,
                'updated_by'   => auth()->user()->id,
                'status'       => $this->status,
            ]);

            // dd(auth()->user()->id);

            // Dispatch events
            $this->reset();   
            $this->dispatch('AdminTreasuriesCreateMS');           // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Show::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            throw $th;
            $this->dispatch('AdminTreasuriesErrorMS');                   // Flash Message
        }

    }


    public function render()
    {
        return view('back-end.admin-treasuries.create');
    }
}

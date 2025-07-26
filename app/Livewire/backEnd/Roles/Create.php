<?php

namespace App\Livewire\BackEnd\Roles;

use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class Create extends Component
{
    public $name                   = '';
    public array $permtion_names    = [];
    public $permtions;
    public $company_code;


    protected $listeners = ['roleCreate'];


    public function roleCreate()
    {
        // show Create modal
         $this->dispatch('createModalToggle');
    }


    public function mount()
    {
        $this->permtions    = Permission::all();
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
                Rule::unique('roles')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            // 'name'              => 'required|unique:roles|max:255',
            'permtion_names'    => 'required',

        ],
        [
            'name.required'             => 'اسم الدور مطلوب.',
            'permtion_names.required'   => 'اسم الصلاحية مطلوب.',
            'name.unique'               => 'اسم الدور موجودة بالفعل.',
        ]);


        try
        {
            DB::beginTransaction();
                $role = Role::create(
                    [
                        'name' => $this->name,
                        'company_code'                  => auth()->user()->company_code,
                        'created_by'                    => auth()->user()->id,
                        'updated_by'                    => auth()->user()->id,
                    ]);
                // dd($this->permtion_names);
                $role->syncPermissions($this->permtion_names);

                  // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة دور جديد ';
                    $actionHistory->desc        = "اضافة دور جديد";
                    $actionHistory->table_name  = 'Role';
                    $actionHistory->row_id      = $role->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            DB::commit();

           $this->reset(['name', 'permtion_names']);                                     // Clear Inputs Data

            // Dispatch events
            $this->dispatch('permissionsCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollback();
            throw $th;
            $this->dispatch('permissionsErrorMS');                   // Flash Message
        }

    }

    public function render()
    {

        $permtions = $this->permtions;
        // dd(!empty($permtions));
        return view('back-end.roles.create',compact('permtions'));
    }
}

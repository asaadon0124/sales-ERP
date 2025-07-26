<?php

namespace App\Livewire\BackEnd\Roles;

use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Livewire\BackEnd\Roles\Data;
use Spatie\Permission\Models\Permission;


class Update extends Component
{
    public $name;
    public array $permtion_names;
    public $company_code;

    public $roleId;
    public $role;
    public $permtions;
    public bool $all_permissions         = false;
    public array $all_permission_list    = []; // كل الصلاحيات المتاحة




    protected $listeners = ['roleUpdate'];
    public function roleUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->role                     = Role::find($id);
        $this->roleId                   = $id;
        $this->name                     = $this->role->name;
        $this->company_code             = $this->role->company_code;
        $this->permtion_names           = $this->role->permissions->pluck('name')->toArray();

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function mount()
    {
        $this->permtions    = Permission::all();
        $this->company_code = auth()->user()->company_code;
        $this->all_permission_list   = Permission::pluck('name')->toArray(); // ✅ مهم جدًا
    }





    public function rules()
    {
        return
            [
                'name' => [
                    'required',
                    Rule::unique('roles')
                        ->ignore($this->role->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                        ->where(function ($query) {
                            return $query->where('company_code', $this->company_code);
                        }),
                ],

                'permtion_names'    => 'required',
            ];
    }



    public function messages()
    {
        return
            [
                'name.required'             => 'اسم الدور مطلوب.',
                'name.unique'               => 'اسم الدور موجود بالفعل.',
                'permtion_names.required'   => 'اسم الدور مطلوب.',
            ];
    }

    public function checkAllPermissions()
    {
        if ($this->all_permissions)
        {
            // لو تم اختيار "اختيار الكل" فعلًا
            $this->permtion_names = $this->all_permission_list;
        } else
        {
            // لو تم إلغاء التحديد
            $this->permtion_names = [];
        }
    }


    public function submit()
    {
        $this->validate($this->rules(), $this->messages());
        try {
            DB::beginTransaction();
            $this->role->update(
                [
                    'name'                      => $this->name,
                    'company_code'              => auth()->user()->company_code,
                    'created_by'                => auth()->user()->id,
                    'updated_by'                => auth()->user()->id,
                ]
            );
            // dd($this->permtion_names);
            $this->role->syncPermissions($this->permtion_names);


            // 3 - CREATE ACTION HISTORY TABLE *****************
            $actionHistory              = new ActionHistory();
            $actionHistory->title       = 'تعديل دور  ';
            $actionHistory->desc        = "تعديل دور {$this->role->name}";
            $actionHistory->table_name  = 'Role';
            $actionHistory->row_id      = $this->role->id;
            $actionHistory->created_by  = auth()->user()->id;
            $actionHistory->save();

            DB::commit();

            $this->reset(['name', 'permtion_names']);                                     // Clear Inputs Data

            // Dispatch events
            $this->dispatch('rolesUpdateMS');                   // Flash Message
            $this->dispatch('updateModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            $this->dispatch('rolesErrorMS');                   // Flash Message
        }
    }


    public function render()
    {
        $permtions = $this->permtions;
        return view('back-end.roles.update', compact('permtions'));
    }
}

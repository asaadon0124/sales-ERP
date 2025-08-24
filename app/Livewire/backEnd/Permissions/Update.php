<?php

namespace App\Livewire\BackEnd\Permissions;

use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use App\Livewire\BackEnd\Permissions\Data;


class Update extends Component
{
     public $name;
     public $company_code;
     public $permissionId;
     public $permission;


    protected $listeners = ['permissionUpdate'];
    public function permissionUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->permission               = Permission::find($id);
        $this->permissionId             = $id;
        $this->name                     = $this->permission->name;
        $this->company_code             = $this->permission->company_code;

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('permissions')
                    ->ignore($this->permission->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],

        ];
    }



    public function messages()
    {
        return
        [
            'name.required'             => 'اسم الصلاحية مطلوب.',
            'name.unique'               => 'اسم الصلاحية موجود بالفعل.',
        ];
    }



    public function submit()
    {
        try
        {
            // dd($this->company_code);

           $this->permission->update(
            [
                'name'                          => $this->name,
                'company_code'                  => auth()->user()->company_code,
                'created_by'                    => auth()->user()->id,
                'updated_by'                    => auth()->user()->id,
            ]);

              // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'تعديل صلاحية  ';
                    $actionHistory->desc        = "تعديل صلاحية {$this->permission->name}";
                    $actionHistory->table_name  = 'Permission';
                    $actionHistory->row_id      = $this->permission->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('permissionsUpdateMS');                   // Flash Message
            $this->dispatch('updateModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            throw $th;
            $this->dispatch('permissionsErrorMS');                   // Flash Message
        }
    }

    public function render()
    {
        return view('back-end.permissions.update');
    }
}

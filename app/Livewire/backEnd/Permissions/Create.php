<?php

namespace App\Livewire\BackEnd\Permissions;

use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Livewire\BackEnd\Permissions\Data;

class Create extends Component
{
    public $name                = '';
    public $company_code;

    protected $listeners = ['permissionCreate'];


    public function permissionCreate()
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
        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('permissions')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            // 'name'            => 'required|unique:permissions|max:255',

        ],
        [
            'name.required'             => 'اسم الصلاحية مطلوب.',
            'name.unique'               => 'اسم الصلاحية موجود بالفعل.',
        ]);


        try
        {
            // dd($this->company_code);
            DB::beginTransaction();
                $permission = Permission::create(
                    [
                        'name'                          => $this->name,
                        'company_code'                  => auth()->user()->company_code,
                        'created_by'                    => auth()->user()->id,
                        'updated_by'                    => auth()->user()->id,
                    ]);

                // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة صلاحية جديدة ';
                    $actionHistory->desc        = "اضافة صلاحية جديدة";
                    $actionHistory->table_name  = 'Permission';
                    $actionHistory->row_id      = $permission->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
            DB::commit();

            $this->reset();                                         // Clear Inputs Data

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

        return view('back-end.permissions.create');
    }
}

<?php

namespace App\Livewire\BackEnd\Emoloyees;

use App\Models\Admin;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Livewire\BackEnd\Emoloyees\Data;
use App\Models\Account;

class Update extends Component
{

    public $name;
    public $email;
    public $password;
    public $company_code;
    public $status;

    public array $user_roles;
    public $user;
    public $userId;
    public $roles;
    public $account;
    public $start_balance_status;
    public $start_balance;
    public $current_balance;
    public $notes;




    protected $listeners = ['adminsUpdate'];
    public function adminsUpdate($id)
    {
        $this->user                     = Admin::with('transations','employee_account')->find($id);
        $this->userId                   = $id;
        $this->name                     = $this->user->name;
        $this->start_balance_status     = $this->user->start_balance_status;
        $this->start_balance            = $this->user->start_balance;
        $this->email                    = $this->user->email;
        $this->status                   = $this->user->status;
        $this->company_code             = $this->user->company_code;

        $this->user_roles           = $this->user->roles->pluck('name')->toArray();

        // show edit modal
        $this->dispatch('updateModalToggle');
    }


     public function mount()
    {
        $this->roles    = Role::all();
        $this->company_code = auth()->user()->company_code;
    }


    public function changeStartBalance($value)
    {
      $this->start_balance_status =  start_balanceStatus($value);
    }



     public function rules()
    {
        return
        [
            'email' =>
            [
                'required',
                'email',
                Rule::unique('admins')->ignore($this->user->id)->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],

            'name'          => 'required',
            'password'      => 'required|min:8',
            'status'        => 'required|in:active,un_active',
            'user_roles'    => 'required',
        ];
    }



    public function messages()
    {
        return
        [
            'email.required'            => 'يجب ادخال ايميل المدير ',
            'email.email'               => 'الايميل غير صحيح',
            'email.unique'              => 'اسم الايميل موجود بالفعل.',
            'permtion_names.required'   => 'اسم الدور مطلوب.',
            'name.required'             => 'اسم المستخدم مطلوب.',
            'password.required'         => 'يجب ادخال كلمة السر',
            'password.min'              => 'كلمة السر لا تقل عن 8 عناصر',
            'user_roles.required'       => 'ادوار المستخدم مطلوبة.',
        ];
    }


    public function submit()
    {
        // if (!auth()->user()->can('تعديل الموظف'))
        // {
        //     abort(403, 'ليس لديك صلاحية تعديل الموظف');
        // }


        $this->validate($this->rules(), $this->messages());

        $this->user->load(['transations','employee_account']);

         // لو في اي عمليات نقدية تمت علي الحاسب ده مش حينفع نعدل رصيد اول المدة
        if ($this->user->transations->count() > 0 && $this->user->start_balance != $this->start_balance)
        {
            $this->addError('start_balance', 'لا يمكن تغير قيمة رصيد اول المدة لان الحساب لديه عمليات صرف و تحصيل تمت عليه .');
            return;
        }

        try
        {

            DB::beginTransaction();
            $this->user->name =  $this->name;
            $this->user->email =  $this->email;
            $this->user->password =   bcrypt($this->password);
            $this->user->updated_by =  auth()->user()->id;
            $this->user->save();

            $this->user->employee_account->notes = $this->notes;
            $this->user->employee_account->name = $this->name;
            $this->user->employee_account->updated_by = auth()->user()->id;
            $this->user->employee_account->save();

            // 3 - تعديل رصيد اول المدة لة الرصيد الحالي = 0 او الرصيد الحالي == رصيد اول المدة
            if ($this->user->current_balance == 0 || $this->user->current_balance == $this->user->start_balance)
            {
                $this->user->start_balance_status    = $this->start_balance_status;
                $this->user->start_balance           = $this->start_balance;
                $this->user->current_balance         = $this->start_balance;
                $this->user->save();

                $this->user->employee_account->start_balance_status    = $this->start_balance_status;
                $this->user->employee_account->start_balance           = $this->start_balance;
                $this->user->employee_account->current_balance         = $this->start_balance;
                $this->user->employee_account->save();
            }


                $this->user->syncRoles($this->user_roles);




                // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'تعديل موظف ';
                    $actionHistory->desc = "تعديل موظف {$this->user->name}";
                    $actionHistory->table_name  = 'Admin';
                    $actionHistory->row_id      = $this->user->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            DB::commit();

        //    $this->reset(['name', 'user_roles']);                                     // Clear Inputs Data

            // Dispatch events
            $this->dispatch('adminsUpdateMS');                   // Flash Message
            $this->dispatch('updateModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollback();
            throw $th;
            $this->dispatch('rolesErrorMS');                   // Flash Message
        }
    }


    public function render()
    {
        $roles = $this->roles;
        return view('back-end.emoloyees.update',compact('roles'));
    }
}

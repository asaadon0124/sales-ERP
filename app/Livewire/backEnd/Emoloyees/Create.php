<?php

namespace App\Livewire\BackEnd\Emoloyees;

use App\Models\Admin;
use App\Models\Account;
use Livewire\Component;
use App\Models\AdminSitting;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Livewire\BackEnd\Emoloyees\Data;

class Create extends Component
{
    public $name;
    public $email;
    public $password;
    public $company_code;
    public $status;

    public array $user_roles;
    public $adminSitting;
    public $account;
    public $employee;
    public $roles;
    public $start_balance_status;
    public $start_balance;


    protected $listeners = ['employeeCreate'];


    public function employeeCreate()
    {
        // show Create modal
         $this->dispatch('createModalToggle');
    }


    public function mount()
    {
        $this->roles            = Role::all(); // تحميل الأدوار في المتغير
        $this->user_roles       = [];     // إعداد المصفوفة الفارغة لتحديد الأدوار لاحقًا
        $this->adminSitting     = AdminSitting::where('company_code',auth()->user()->company_code)->with('employeesAccount')->first();

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
                Rule::unique('admins')->where(function ($query)
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
        if (!auth()->user()->can('اضافة موظف جديد'))
        {
            abort(403, 'ليس لديك صلاحية اضافة موظف جديد');
        }


        $this->validate($this->rules(), $this->messages());

            //  dd($this->all());

        try
        {
            DB::beginTransaction();

                // 1 - CREATE NEW ACCOUNT **************************************************
                    $this->account                          = new Account();

                    $this->account->name                    = $this->name;
                    $this->account->account_type_id         = $this->adminSitting->account->account_type_id;
                    $this->account->is_parent               = '1';
                    $this->account->parent_account_number   = $this->adminSitting->account->account_number;
                    $this->account->start_balance_status    = $this->start_balance_status;
                    $this->account->start_balance           = $this->start_balance;
                    $this->account->current_balance         = $this->start_balance;
                    $this->account->account_number          = get_last_autoSerial(Account::class, 'account_number');

                    $this->account->status                  = 'active';
                    $this->account->company_code            = auth()->user()->company_code;
                    $this->account->created_by              = auth()->user()->id;
                    $this->account->updated_by              = auth()->user()->id;
                    $this->account->save();

                // 2 - CREATE NEW ADMIN TABLE
                    $this->employee                         = new Admin();

                    $this->employee->name                   = $this->name;
                    $this->employee->email                  = $this->email;
                    $this->employee->password               = bcrypt($this->password);
                    $this->employee->employee_code          = get_last_autoSerial(Admin::class, 'employee_code');
                    $this->employee->account_number         = $this->account->account_number;
                    $this->employee->start_balance_status   = $this->account->start_balance_status;
                    $this->employee->start_balance          = $this->account->start_balance;
                    $this->employee->current_balance        = $this->account->current_balance;
                    $this->employee->company_code           = auth()->user()->company_code;
                    $this->employee->created_by             = auth()->user()->id;
                    $this->employee->updated_by             = auth()->user()->id;
                    $this->employee->status                 = $this->status;
                    $this->employee->save();





                // 3 - CREATE NEW ROLES AND PERMITIONS RELATION TABLE
                    $this->employee->syncRoles($this->user_roles);


                // 4 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة موظف جديد';
                    $actionHistory->desc        = 'اضافة موظف جديد';
                    $actionHistory->table_name  = 'Admin';
                    $actionHistory->row_id      = $this->employee->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            DB::commit();

           $this->reset(['name', 'user_roles','email','password','status']);                                     // Clear Inputs Data

            // Dispatch events
            $this->dispatch('adminsCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
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
        return view('back-end.emoloyees.create',compact('roles'));
    }
}

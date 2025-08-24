<?php

namespace App\Livewire\BackEnd\Servant;

use App\Models\Account;
use App\Models\Servant;
use Livewire\Component;
use App\Models\AdminSitting;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Servant\Data;


class Create extends Component
{
     public $servant;
    public $account;
    public $typeStatus              = 'active';
    public $getLastServant;
    public $getLastAccount;
    public $adminSitting;

    public $name;
    public $status;
    public $start_balance_status;
    public $current_balance;
    public $start_balance;
    public $notes;
    public $account_number;
    public $commission_type;

    public $customer_code;
    public $address;
    public $company_code;


     protected $listeners = ['servantCreate'];


    public function servantCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }



     public function mount()
    {
        $this->getLastServant                   = Servant::select('account_number','company_code','id','name')->where('company_code',auth()->user()->company_code)->latest()->first();
        $this->getLastAccount                   = Account::select('account_number','company_code','id','name')->where('company_code',auth()->user()->company_code)->latest()->first();
        $this->adminSitting                     = AdminSitting::where('company_code',auth()->user()->company_code)->with('account')->first();
        $this->company_code                     = auth()->user()->company_code;

    }




     public function changeStartBalance($value)
    {
      $this->start_balance_status =  start_balanceStatus($value);
    }

    public function submit()
    {
        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('servants')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'                        => 'required',
            'start_balance_status'          => 'required',
            'commission_type'               => 'required',
            'start_balance'                 => 'required',
            'address'                       => 'required',
        ],
        [
            'name.required'                 => 'اسم المندوب مطلوب.',
            'address.required'              => 'عنوان المندوب مطلوب.',
            'status.required'               => '؛حالة المندوب مطلوبة.',
            'commission_type.required'      => '؛حالة اجر المندوب مطلوبة.',
            'name.unique'                   => 'اسم المندوب موجود بالفعل.',
            'start_balance.required'        => 'رصيد المندوب بداية المدة مطلوب.',
            'start_balance_status.required' => 'حالة المندوب اول المدة مطلوب.',
        ]);


        try
        {
            // dd($this->all());



            if ($this->adminSitting->account && $this->adminSitting->account->count() > 0)
            {
                DB::beginTransaction();
                // 1 - CREATE NEW ACCOUNT **************************************************
                $this->account                          = new Account();

                $this->account->name                    = $this->name;
                $this->account->account_type_id         = $this->adminSitting->servantAccount->account_type_id;
                $this->account->is_parent               = '1';
                $this->account->parent_account_number   = $this->adminSitting->account->account_number;
                $this->account->start_balance_status    = $this->start_balance_status;
                $this->account->notes                   = $this->notes;
                if ($this->start_balance_status == 'credit')
                {
                    $this->account->start_balance           = $this->start_balance * 1;
                    $this->account->current_balance         = $this->start_balance * 1;
                }elseif ($this->start_balance_status == 'debit')
                {
                    $this->account->start_balance           = $this->start_balance * (-1);
                    $this->account->current_balance         = $this->start_balance * (-1);
                }else
                {
                    $this->account->start_balance           = 0;
                    $this->account->current_balance         = 0;
                }

                // if ($this->getLastAccount && $this->getLastAccount != null)
                // {
                //     $this->account->account_number = $this->getLastAccount->account_number + 1;
                // }else
                // {
                //     $this->account->account_number = 1;
                // }
                $this->account->account_number          = get_last_autoSerial(Account::class, 'account_number');

                $this->account->status                  = $this->status;
                $this->account->company_code            = auth()->user()->company_code;
                $this->account->created_by              = auth()->user()->id;
                $this->account->updated_by              = auth()->user()->id;
                $this->account->save();


            // 2 - CREATE NEW CUSTOMER **************************************************
                $this->servant                          = new Servant();

                $this->servant->name                    = $this->name;
                $this->servant->start_balance_status    = $this->start_balance_status;
                $this->servant->notes                   = $this->notes;
                $this->servant->address                 = $this->address;
                $this->servant->start_balance           = $this->account->start_balance;
                $this->servant->current_balance         = $this->account->current_balance;

                // if ($this->getLastServant && $this->getLastServant != null)
                // {
                //     $this->servant->servant_code = $this->getLastServant->servant_code + 1;

                // }else
                // {
                //     $this->servant->servant_code = 1;
                // }

                $this->servant->servant_code = get_last_autoSerial(Servant::class, 'servant_code');

                $this->servant->account_number          = $this->account->account_number;
                $this->servant->status                  = $this->status;
                $this->servant->commission_type         = $this->commission_type;

                $this->servant->company_code            = auth()->user()->company_code;
                $this->servant->created_by              = auth()->user()->id;
                $this->servant->updated_by              = auth()->user()->id;

                $this->servant->save();


                  // 3 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة مندوب جديد';
                    $actionHistory->desc        = 'اضافة مندوب جديد';
                    $actionHistory->table_name  = 'Servant';
                    $actionHistory->row_id      = $this->servant->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                DB::commit();

            } else
            {
                toastr()->success('برجاء اضافة حساب اب للمناديب', 'رسالة خطا'); // ✅
                return redirect()->route('servants.index');
            }


            $this->reset(
            [
                'status',
                'start_balance_status',
                'commission_type',
            ]);
            // Dispatch events
            $this->dispatch('servantsCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('servantsErrorMS');                   // Flash Message
        }

    }


    public function render()
    {
        return view('back-end.servant.create');
    }
}

<?php

namespace App\Livewire\BackEnd\Customer;

use App\Models\Account;
use Livewire\Component;
use App\Models\Customer;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Customer\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{

    public $customer;
    public $customertId;
    public $account;
    public $typeStatus              = 'active';
    public $getLastCustomer;
    public $getLastAccount;
    public $adminSitting;

    public $name;
    public $status;
    public $notes;
    public $address;
    public $start_balance_status;
    public $start_balance;



    protected $listeners = ['customerUpdate'];


    public function customerUpdate($id)
    {
        $this->customer                 = Customer::with('transations','customer_account')->find($id);
        $this->customertId              = $id;
        $this->name                     = $this->customer->name;
        $this->address                  = $this->customer->address;
        $this->status                   = $this->customer->status;
        $this->notes                    = $this->customer->notes;
        $this->start_balance_status     = $this->customer->start_balance_status;
        $this->start_balance            = $this->customer->start_balance;

        $this->dispatch('updateModalToggle');
    }



    public function changeStartBalance($value)
    {
      $this->start_balance_status =  start_balanceStatus($value);
    }


    public function rules()
    {
        return
        [
            'name' => [
                'required',
                Rule::unique('customers')
                    ->ignore($this->customer->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],
            'status'                    => 'required',
            'address'                   => 'required',
            'start_balance'             => 'required',
        ];
    }

    public function messages()
    {
        return
        [
          'name.required'               => 'اسم العميل مطلوب.',
            'status.required'           => '؛حالة العميل مطلوبة.',
            'name.unique'               => 'اسم العميل موجود بالفعل.',
            'address.required'          => 'عنوان العميل مطلوب.',
            'start_balance.required'    => 'رصيد اول المدة مطلوب.',
        ];
    }



    public function submit()
    {
        if (!auth()->user()->can('تعديل العميل'))
        {
            abort(403, 'ليس لديك صلاحية تعديل العميل');
        }


         $this->customer->load(['transations','customer_account']);

         // لو في اي عمليات نقدية تمت علي الحاسب ده مش حينفع نعدل رصيد اول المدة
        if ($this->customer->transations->count() > 0 && $this->customer->start_balance != $this->start_balance)
        {
            $this->addError('start_balance', 'لا يمكن تغير قيمة رصيد اول المدة لان الحساب لديه عمليات صرف و تحصيل تمت عليه .');
            return;
        }


        // dd($this->customer);

        try
        {
            DB::beginTransaction();

                // 1 - تعديل العميل  *********************************************
                $this->customer->name                       = $this->name;
                $this->customer->address                    = $this->address;
                $this->customer->notes                      = $this->notes;
                $this->customer->status                     = $this->status;
                $this->customer->updated_by                 = auth()->user()->id;
                $this->customer->save();



                // 2 - تعديل حساب العميل  *********************************************
                $this->customer->customer_account->name                       = $this->name;
                $this->customer->customer_account->notes                      = $this->notes;
                $this->customer->customer_account->status                     = $this->status;
                $this->customer->customer_account->updated_by                 = auth()->user()->id;
                $this->customer->customer_account->save();


            // 3 - تعديل رصيد اول المدة لة الرصيد الحالي = 0 او الرصيد الحالي == رصيد اول المدة
            if ($this->customer->current_balance == 0 || $this->customer->current_balance == $this->customer->start_balance)
            {

                $this->customer->start_balance_status    = $this->start_balance_status;
                $this->customer->start_balance           = $this->start_balance;
                $this->customer->current_balance         = $this->start_balance;
                $this->customer->save();

                $this->customer->customer_account->start_balance_status    = $this->start_balance_status;
                $this->customer->customer_account->start_balance           = $this->start_balance;
                $this->customer->customer_account->current_balance         = $this->start_balance;
                $this->customer->customer_account->save();

            }


             // 4 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'تعديل العميل ';
                    $actionHistory->desc = "تعديل العميل {$this->customer->name}";
                    $actionHistory->table_name  = 'Customer';
                    $actionHistory->row_id      = $this->customer->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            DB::commit();
            //  dd($this->account);

        } catch (\Throwable $th)
        {
            DB::rollback();
            toastr()->success('برجاء اضافة حساب اب للعملاء', 'رسالة خطا'); // ✅
            return redirect()->route('accounts.index');
        }


        // Dispatch events
        $this->dispatch('customerUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }


    public function render()
    {
        return view('back-end.customer.update');

    }
}

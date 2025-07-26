<?php

namespace App\Livewire\BackEnd\Servant;

use App\Models\Servant;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Servant\Data;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح

class Update extends Component
{
    public $servant;
    public $servanttId;
    public $account;
    public $typeStatus              = 'active';
    public $getLastservant;
    public $getLastAccount;
    public $adminSitting;

    public $name;
    public $status;
    public $notes;
    public $address;
    public $start_balance_status;
    public $start_balance;
    public $commission_type;


     protected $listeners = ['servantUpdate'];


     public function servantUpdate($id)
    {
        $this->servant                 = Servant::with('transations','account_customer')->find($id);
        $this->servanttId              = $id;
        $this->name                     = $this->servant->name;
        $this->address                  = $this->servant->address;
        $this->status                   = $this->servant->status;
        $this->notes                    = $this->servant->notes;
        $this->start_balance_status     = $this->servant->start_balance_status;
        $this->start_balance            = $this->servant->start_balance;
        $this->commission_type          = $this->servant->commission_type;

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
                Rule::unique('servants')
                    ->ignore($this->servant->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query)
                    {
                        return $query->where('company_code', $this->company_code);
                    }),
            ],
            'status'                    => 'required',
            'address'                   => 'required',
            'start_balance'             => 'required',
            'commission_type'            => 'required',
        ];
    }

    public function messages()
    {
        return
        [
          'name.required'               => 'اسم المندوب مطلوب.',
            'status.required'           => '؛حالة المندوب مطلوبة.',
            'name.unique'               => 'اسم المندوب موجود بالفعل.',
            'address.required'          => 'عنوان المندوب مطلوب.',
            'address.required'          => 'نوع الاجر للمندوب مطلوب.',
            'start_balance.required'    => 'رصيد اول المدة مطلوب.',
        ];
    }



     public function submit()
    {

        if (!auth()->user()->can('تعديل المندوب'))
        {
            abort(403, 'ليس لديك صلاحية تعديل المندوب');
        }

         $this->servant->load(['transations','account_customer']);

         // لو في اي عمليات نقدية تمت علي الحاسب ده مش حينفع نعدل رصيد اول المدة
        if ($this->servant->transations->count() > 0 && $this->servant->start_balance != $this->start_balance)
        {
            $this->addError('start_balance', 'لا يمكن تغير قيمة رصيد اول المدة لان الحساب لديه عمليات صرف و تحصيل تمت عليه .');
            return;
        }


        // dd($this->customer);

        try
        {
            DB::beginTransaction();

                // 1 - تعديل المندوب  *********************************************
                $this->servant->name                       = $this->name;
                $this->servant->address                    = $this->address;
                $this->servant->notes                      = $this->notes;
                $this->servant->status                     = $this->status;
                $this->servant->commission_type            = $this->commission_type;
                $this->servant->updated_by                 = auth()->user()->id;
                $this->servant->save();



                // 2 - تعديل حساب العميل  *********************************************
                $this->servant->account_customer->name                       = $this->name;
                $this->servant->account_customer->notes                      = $this->notes;
                $this->servant->account_customer->status                     = $this->status;
                $this->servant->account_customer->updated_by                 = auth()->user()->id;
                $this->servant->account_customer->save();


            // 3 - تعديل رصيد اول المدة لة الرصيد الحالي = 0 او الرصيد الحالي == رصيد اول المدة
            if ($this->servant->current_balance == 0 || $this->servant->current_balance == $this->servant->start_balance)
            {

                $this->servant->start_balance_status    = $this->start_balance_status;
                $this->servant->start_balance           = $this->start_balance;
                $this->servant->current_balance         = $this->start_balance;
                $this->servant->save();

                $this->servant->account_customer->start_balance_status    = $this->start_balance_status;
                $this->servant->account_customer->start_balance           = $this->start_balance;
                $this->servant->account_customer->current_balance         = $this->start_balance;
                $this->servant->account_customer->save();

            }

             // 4 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'تعديل المندوب ';
                    $actionHistory->desc        = "تعديل المندوب {$this->servant->name}";
                    $actionHistory->table_name  = 'Servant';
                    $actionHistory->row_id      = $this->servant->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();

            DB::commit();

        } catch (\Throwable $th)
        {
            DB::rollback();
            toastr()->success('برجاء اضافة حساب اب للمناديب', 'رسالة خطا'); // ✅
            return redirect()->route('servants.index');
        }


        // Dispatch events
        $this->dispatch('servantsUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }

    public function render()
    {
        return view('back-end.servant.update');
    }
}

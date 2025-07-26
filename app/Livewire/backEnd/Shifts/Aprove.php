<?php

namespace App\Livewire\BackEnd\Shifts;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Shift;
use App\Models\Account;
use Livewire\Component;
use App\Models\MoveType;
use App\Models\Treasuries;
use App\Models\ActionHistory;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuriesDetailes;
use App\Models\TreasuryTransation;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Shifts\Data;

class Aprove extends Component
{
     public $is_delevered_review;               // حالة المراجعة
     public $delevered_to_admin_id;             // اسم الشخص الي راجع الاستلام
     public $delevered_to_shift_id;             // رقم الشيفت الي راجع الاستلام
     public $delevered_to_treasury_id;          // رقم الخزنة الي راجع الاستلام
     public $cash_should_delevered;             // النقدية الواجب تسليمها في نهاية الشيفت
     public $cash_actually_delivered = 0;       // النقدية التي تم  تسليمها فعلا في نهاية الشيفت
     public $cash_status;                       // حالة النقدية المسلمة     عجز -زيادة  -متزن
     public $cash_status_value = 0;             // قيمة النقدية المسلمة     عجز -زيادة  -متزن
     public $recive_type;                       // تسليم الشيفت و النقدية علي نفس الخزنة ولا خزنة اخري
     public $Review_recive_date;                // تاريخ المراجعة
     public $treasuries_transaction_id;         // رقم ايصال النقدية
     public $start_balance;                     // رصيد اول الشيفت
     public $current_shift_balance;             // الرصيد الحالي للشيفت


     public $moveType_id;
     public $account_id;


    public $shiftId;
    public $shift;
    public $account_table;
    public $moveTypes;
    public $treasury_transaction_table;
    public $treasury_table;
    public $admin_table;
    public $Active_shift;
    public $shift_balance;
    public $is_same_treasury;
    public $all_master_treasuries;



    protected $listeners = ['shiftAprove'];

    public function shiftAprove($id)
    {
        $this->Active_shift = Active_shift();
        $this->shift                    = Shift::where('auto_serial',$id)->with('treasury')->first();
        $this->moveTypes                = MoveType::where('in_screen','collect')->get();
        $this->current_shift_balance    = Treasry_balance($this->Active_shift);
        $movetype                       = MoveType::where('company_code',auth()->user()->company_code)->where('name','مراجعة واستلام نقدية شفت خزنة مستخدم')->first();
        $this->moveType_id              = $movetype->id;
        $this->account_table            = Account::with('account_employee')->where('account_number',$this->shift->admin->account_number)->first();
        $this->admin_table              = Admin::where('id',$this->shift->admin_id)->first();
        // dd($this->admin_table);


        if (!empty($this->Active_shift))
        {
            $this->shiftId                   = $id;
            $this->is_delevered_review       = $this->shift->is_delevered_review;
            $this->delevered_to_admin_id     = auth()->user()->id;
            $this->delevered_to_shift_id     = $this->Active_shift->auto_serial;
            $this->delevered_to_treasury_id  = $this->Active_shift->treasury_id;
            $this->cash_should_delevered     = shifts_balances($id);
            $this->Review_recive_date        = date('y-m-d');


            if ($this->Active_shift && $this->shift)
            {
                $this->is_same_treasury = $this->Active_shift->treasury_id == $this->shift->treasury_id;

                // لو الخزنة المفتوحة == الخزنة المنتهية او الي تتم مراجعتها
                if ($this->is_same_treasury)
                {
                   $this->recive_type               = 'same';
                   $this->delevered_to_treasury_id  = $this->shift->treasury_id;
                   $this->start_balance             = 0;
                }else
                {
                    $this->recive_type              = 'anther';
                    $this->all_master_treasuries    = Treasuries::where('is_master','master')->get();
                    $this->start_balance            = $this->shift_balance;
                }
            }
        }

        // show aprove modal
        $this->dispatch('aproveModalToggle');
    }


    public function change_delevered_to_treasury_id($value)
    {
        // الخزنة المستلمة
        $this->delevered_to_treasury_id = $value;
        dd($value);
    }

    // public function mount()
    // {


    //     // dd($this->admin_table);
    // }


    // تعديل حالة التحصيل
    public function getCashStatusLabel($status)
    {

        return match($status)
        {
            'nun'  => 'متزن',
            'plus' => 'زيادة',
            'mins' => 'عجز',
            default => 'غير محدد',
        };
    }


    // المبلغ المحصل من المستخدم بالفعل
    public function paid($value)
    {
        $this->cash_status_value = $value - $this->shift_balance;
        if ($this->cash_status_value == 0)
        {
            $this->cash_status = 'nun';
        }elseif($this->cash_status_value > 0)
        {
            $this->cash_status = 'plus';
        }else
        {
            $this->cash_status = 'mins';
        }
    }

    // قيمة العجز و الزيادة
    public function paid_difference($value)
    {
        $this->resetErrorBag('cash_actually_delivered'); // ← تمسح رسالة الخطأ لو موجودة
        $this->cash_actually_delivered = $this->shift_balance - $value;
         if ($this->cash_status_value == 0)
        {
            $this->cash_status = 'nun';
        }elseif($this->cash_status_value > 0)
        {
            $this->cash_status = 'plus';
        }else
        {
            $this->cash_status = 'mins';
        }

        if ($this->cash_actually_delivered < 0)
        {
            $this->addError('cash_actually_delivered', 'لا يمكن دفع قيمة بالسالب .');
            return;
        }
    }





    public function rules()
    {
        return
        [
            // 'moveType_id'               => 'required',

            'cash_status'               => 'required|in:nun,plus,mins',
            'cash_actually_delivered'   => 'required|min:1',
            'cash_status_value'         =>
                [
                    'required', 'numeric', function ($attribute, $value, $fail)
                    {
                        if ($value > $this->shift_balance)
                        {
                            $fail('قيمة الفرق لا يمكن أن تتجاوز رصيد الشيفت.');
                        }
                    }
                ],
        ];
    }



    public function messages()
    {
        return
        [
            // 'moveType_id.required'  => 'نوع الحركة مطلوب.',
            'account_id.required'   => 'اسم الحساب مطلوب.',
            'cash_status.required'   => 'حالة التحصيل مطلوب.',
            'cash_actually_delivered.required'   => 'المبلغ المحصل من المستخدم مطلوب.',
            'cash_status_value.required'   => 'المبلغ الغير محصل من المستخدم مطلوب.',
        ];
    }



    public function submit()
    {

        $this->validate($this->rules(), $this->messages());

        try
        {
            // dd($this->all());
            DB::beginTransaction();
            // 1 - التحقق من الحقول

                // فيمة المدفوع لا يجب ان  = 0
                if ($this->cash_actually_delivered < 0 || $this->cash_actually_delivered == 0)
                {
                    $this->addError('cash_actually_delivered', 'لا يمكن دفع قيمة بالسالب او = 0 .');
                    return;
                }



            // 2 - CREATE TREASURY TRANSATION TABLE انشاء جدول حركة النقدية

                $this->treasury_transaction_table = new TreasuryTransation();

                $this->treasury_transaction_table->treasury_id          = $this->Active_shift->treasury_id;
                $this->treasury_transaction_table->moveType_id          = $this->moveType_id;
                $this->treasury_transaction_table->account_id           = auth()->user()->account_number;
                $this->treasury_transaction_table->shift_id             = $this->Active_shift->auto_serial;
                $this->treasury_transaction_table->cash_source_type     ='treasury';
                $this->treasury_transaction_table->account_type         ='employee';
                $this->treasury_transaction_table->cash_amount          = $this->cash_actually_delivered;
                $this->treasury_transaction_table->cash_for_account     = $this->cash_status_value;
                $this->treasury_transaction_table->auto_serial          = get_last_autoSerial_invoices(TreasuryTransation::class, 'auto_serial');
                $this->treasury_transaction_table->isal_number          = $this->treasury_table->last_recept_recive + 1;
                $this->treasury_transaction_table->move_date            = Carbon::today()->toDateString();
                $this->treasury_transaction_table->company_code         = auth()->user()->company_code;
                $this->treasury_transaction_table->created_by           = auth()->user()->id;
                $this->treasury_transaction_table->updated_by           = auth()->user()->id;
                $this->treasury_transaction_table->save();


                if ($this->treasury_transaction_table->cash_for_account < 0)
                {
                    $this->treasury_transaction_table->invoice_type = '1';

                }else
                {
                    $this->treasury_transaction_table->invoice_type = '0';
                }

                $this->treasury_transaction_table->save();

            // 3 - UPDATE TREASURY TABLE تعديل جدول  الخزن
                $this->treasury_table->last_recept_recive = $this->treasury_transaction_table->isal_number;
                $this->treasury_table->save();

            // 4 - UPDATE ACCOUNTS TABLE تعديل جدول  الحسابات
                $this->account_table->current_balance = $this->account_table->current_balance + $this->cash_status_value;
                $this->account_table->save();
            // 5 - UPDATE aDMINS TABLE تعديل جدول  الادمن
                $this->admin_table->current_balance = $this->admin_table->current_balance + $this->cash_status_value;
                $this->admin_table->save();

            // 6 - UPDATE SHIFTS TABLE تعديل جدول  الشيفتات
                $this->shift->is_delevered_review           = 'yes';
                $this->shift->delevered_to_admin_id         = auth()->user()->id;

                $this->shift->delevered_to_shift_id         = $this->Active_shift->auto_serial;
                $this->shift->delevered_to_treasury_id      = $this->delevered_to_treasury_id;
                $this->shift->cash_should_delevered         = $this->shift_balance;
                $this->shift->cash_actually_delivered       = $this->cash_actually_delivered;
                $this->shift->cash_status                   = $this->cash_status;

                $this->shift->cash_status_value             = $this->cash_status_value;
                $this->shift->recive_type                   = $this->recive_type;
                $this->shift->Review_recive_date            = date('y-m-d');
                $this->shift->treasuries_transaction_id     = $this->treasury_transaction_table->auto_serial;
                 $this->shift->company_code                 = auth()->user()->company_code;
                $this->shift->updated_by                    = auth()->user()->id;


                $this->shift->save();



            // 7 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'مراجعة و استلام شيفت  المستخدم';
                $actionHistory->desc        = "مراجعة و استلام شيفت  المستخدم {$this->shift->admin->name}";
                $actionHistory->table_name  = 'Shift';
                $actionHistory->row_id      = $this->shift->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();

            DB::commit();

        //    $this->reset(['name', 'permtion_names']);                                     // Clear Inputs Data

            // Dispatch events
            $this->dispatch('shiftsUpdateMS');                   // Flash Message
            $this->dispatch('aproveModalToggle');                // Close Create Model
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
        $shift_balance = 0;
        if ($this->shift)
        {

            $this->treasury_table       = Treasuries::where('id',$this->shift->treasury_id)->withoutGlobalScope(ActiveScope::class)->first();
            $this->shift_balance              = Treasries_balances($this->shift->treasury_id);
            // dd($this->shift_balance);

        }


        return view('back-end.shifts.aprove',
        [
            'treasury' => $this->treasury_table,
            'shift_balance' => $this->shift_balance,
            // 'active_shift' => $this->active_shift,
        ]);
    }
}

<?php

namespace App\Livewire\BackEnd\Shifts;

use App\Models\Shift;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TreasuryTransation;

class Show extends Component
{
      use WithPagination;
     public function updatingSearch()
    {
        $this->resetPage();
    }

    public $treasury_name;
    public $shift_number;
    public $start_date;
    public $end_date;
    public $employee_name;
    public $shift_status;
    public $shift_review;
    public $shift_start_balance;
    public $shift_balance;
    public $shift_balance_status;
    public $paid;
    public $unpaid;
    public $delevered_to_admin_id;
    public $delevered_to_shift_id;
    public $delevered_to_treasury_id;
    public $recive_type;
    public $Review_recive_date;
    public $is_master;
    public $admin_current_balance;
    public $last_recept_recive;
    public $company_code;
    public $updated_by;




    public $search = '';
    public $shift;
    public $shiftId;



    protected $listeners = ['shiftsShow','refreshData' => '$refresh'];

     public function mount($id)
    {
        if (!auth()->user()->can('تفاصيل الشيفت'))
        {
            abort(403, 'ليس لديك صلاحية عرض تفاصيل خزنة');
        }

        $this->shift                    = Shift::with('treasury','treasury_transactions','adminCreate')->where('auto_serial',$id)->first();
        $this->shiftId                  = $id;
        $this->treasury_name            = $this->shift->treasury->name;
        $this->shift_number             = $this->shift->auto_serial;
        $this->start_date               = $this->shift->start_date;
        $this->end_date                 = $this->shift->end_date;
        $this->employee_name            = $this->shift->adminCreate->name;
        $this->shift_status             = $this->shift->status();
        $this->shift_review             = $this->shift->isReview();
        $this->shift_start_balance      = $this->shift->start_balance;
        $this->shift_balance            = $this->shift->cash_should_delevered;
        $this->shift_balance_status     = $this->shift->cash_amount_status();
        $this->paid                     = $this->shift->cash_actually_delivered;
        $this->unpaid                   = $this->shift->cash_status_value;
        $this->delevered_to_admin_id    = $this->shift->adminCreate->name;
        $this->delevered_to_shift_id    = $this->shift->auto_serial;
        $this->delevered_to_treasury_id = $this->shift->treasury->name;
        $this->recive_type              = $this->shift->Recive_type();
        $this->Review_recive_date       = $this->shift->Review_recive_date;
        $this->admin_current_balance    = $this->shift->adminCreate->current_balance;
        $this->company_code             = $this->shift->company_code;
    }




    public function IsMaster()
    {
        return $this->is_master == 'master' ? 'رئيسية' : ' فرعية';
    }


    public function render()
    {

       $data = TreasuryTransation::with('adminCreate', 'account', 'move_type')
    ->where('shift_id', $this->shiftId)
    ->where(function ($query) {
        $query->where('auto_serial', 'like', '%' . $this->search . '%')
              ->orWhere('company_code', 'like', '%' . $this->search . '%')
              ->orWhereHas('account', function ($q) {
                  $q->where('name', 'like', '%' . $this->search . '%'); // البحث باسم الحساب
              });
    })
    ->latest()
    ->paginate(5);

        return view('back-end.shifts.show',compact('data'));
    }
}

<?php

namespace App\Livewire\BackEnd\Treasuries;

use App\Models\Shift;
use Livewire\Component;
use App\Models\Treasuries;
use App\Models\ActionHistory;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // ✅ تأكد من استخدام Rule من namespace الصحيح


class Update extends Component
{
    use WithFileUploads;

    public $treasuries, $name, $status, $is_master, $last_recept_pay, $last_recept_recive, $company_code, $updated_by,$total =0,$active_shift;
    protected $listeners = ['treasuriesUpdate'];

    public function treasuriesUpdate($id)
    {
        // fill $slider with the eloquent model of the same id
        $this->treasuries           = Treasuries::with('treasuriesDetailes')->where('id',$id)->first();
        // $this->active_shift         = Shift::where('treasury_id',$id)->with('treasury')->where('shift_status','active')->orWhere('is_delevered_review','no')->count();
        $this->name                 = $this->treasuries->name;
        $this->status               = $this->treasuries->status;
        $this->is_master            = $this->treasuries->is_master;
        $this->last_recept_pay      = $this->treasuries->last_recept_pay;
        $this->last_recept_recive   = $this->treasuries->last_recept_recive;
        $this->company_code         = $this->treasuries->company_code;
        $this->updated_by           = $this->treasuries->updated_by;
        $this->total                = Treasries_balances($id);

        // show edit modal
        $this->dispatch('updateModalToggle');
    }



    public function rules()
    {
        return
        [
             'name' => [
                'required',
                Rule::unique('treasuries')
                    ->ignore($this->treasuries->id) // ✅ هذا هو المعرف الصحيح للسجل الحالي
                    ->where(function ($query) {
                        return $query->where('company_code', $this->treasuries->company_code);
                    }),
            ],
            'status'                => ['required'],
            'is_master'             => ['required'],
            'last_recept_pay'       => ['required'],
            'last_recept_recive'    => ['required'],
            // 'starting_price' => ['nullable'],
            // 'btn_url'        => ['required', 'url'],
            // 'serial'         => ['required', 'unique:sliders,serial,'. $this->serial],
            // 'status'         => ['required'],
        ];
    }

    public function messages()
    {
        return
        [
            'name.required'             => 'اسم الخزينة مطلوب.',
            'name.unique'               => 'اسم الخزينة موجود بالفعل.',
            'status.required'           => 'الحالة مطلوبة.',
            // 'status.exists'             => 'الحالة المختارة غير صحيحة.',
            'is_master.required'        => 'يجب تحديد ما إذا كانت خزينة رئيسية.',
            // 'is_master.exists'          => 'القيمة المختارة غير صحيحة.',
            'last_recept_pay.required'  => 'آخر ايصال صرف مطلوب.',
            'last_recept_recive.required'=> 'آخر ايصال استلام مطلوب.',
        ];
    }

    public function submit()
    {
        if (!auth()->user()->can('تعديل الخزن'))
        {
            abort(403, 'ليس لديك صلاحية تعديل خزنة');
        }

        $this->validate($this->rules(), $this->messages());

        DB::beginTransaction();
            $this->treasuries->name                 = $this->name;
            $this->treasuries->status               = $this->status;
            $this->treasuries->is_master            = $this->is_master;
            $this->treasuries->last_recept_pay      = $this->last_recept_pay;
            $this->treasuries->last_recept_recive   = $this->last_recept_recive;
            $this->treasuries->company_code         = auth()->user()->company_code;
            $this->treasuries->updated_by           = auth()->user()->id;
            // Save the changes
            $this->treasuries->save();

            // 2 - CREATE ACTION HISTORY TABLE *****************
                $actionHistory              = new ActionHistory();
                $actionHistory->title       = 'تعديل الخزنة';
                $actionHistory->desc        = "تعديل الخزنة {$this->treasuries->name}";
                $actionHistory->table_name  = 'Treasuries';
                $actionHistory->row_id      = $this->treasuries->id;
                $actionHistory->created_by  = auth()->user()->id;
                $actionHistory->save();
        DB::commit();
        DB::rollback();

        // Dispatch events
        $this->dispatch('treasuriesUpdateMS');
        $this->dispatch('updateModalToggle');
        $this->dispatch('refreshData')->to(Data::class);
    }

    public function render()
    {
        return view('back-end.treasuries.update');
    }
}

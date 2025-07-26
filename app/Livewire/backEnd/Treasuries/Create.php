<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class Create extends Component
{

    public $treasuriesCount     = '';
    public $name                = '';
    public $status              = '';
    public $is_master           = '';
    public $last_recept_pay     = '';
    public $last_recept_recive  = '';
    public $company_code;





    protected $listeners = ['treasuriesCreate'];





    public function treasuriesCreate()
    {

        // show edit modal
        $this->dispatch('createModalToggle');
    }

    public function render()
    {
        return view('back-end.treasuries.create');
    }

    public function mount()
    {

        $this->company_code = auth()->user()->company_code;
    }

    public function submit()
    {
        if (!auth()->user()->can('اضافة خزنة'))
        {
            abort(403, 'ليس لديك صلاحية اضافة خزنة');
        }


        // dd($this->all());
        $validated = $this->validate(
        [
            'name'              => 'required|unique:treasuries,name',
            'name' =>
            [
                'required',
                Rule::unique('treasuries')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'            => 'required',
            'is_master'         => 'required',
            'last_recept_pay'   => 'required',
            'last_recept_recive'=> 'required',
        ],
        [
            'name.required'             => 'اسم الخزينة مطلوب.',
            'name.unique'               => 'اسم الخزينة موجود بالفعل.',
            'status.required'           => 'الحالة مطلوبة.',
            // 'status.exists'             => 'الحالة المختارة غير صحيحة.',
            'is_master.required'        => 'يجب تحديد ما إذا كانت خزينة رئيسية.',
            // 'is_master.exists'          => 'القيمة المختارة غير صحيحة.',
            'last_recept_pay.required'  => 'آخر ايصال صرف مطلوب.',
            'last_recept_recive.required'=> 'آخر ايصال استلام مطلوب.',
        ]);


        try
        {
            $treasuriesCount = Treasuries::where('is_master', 'master')->count();

            if ($treasuriesCount > 0 && $this->is_master == 'master')
            {
                 $this->addError('is_master', 'لا يمكن اضافة اكثر من خزنة رئيسية.');
                return;
            }else
            {
                DB::beginTransaction();
                    $create = Treasuries::create(
                        [
                            'name'                  => $this->name,
                            'status'                => $this->status,
                            'is_master'             => $this->is_master,
                            'last_recept_pay'       => $this->last_recept_pay,
                            'last_recept_recive'    => $this->last_recept_recive,
                            'company_code'          => auth()->user()->company_code,
                            'created_by'            => auth()->user()->id,
                        ]);

                        // 2 - CREATE ACTION HISTORY TABLE *****************
                        $actionHistory              = new ActionHistory();
                        $actionHistory->title       = 'اضافة خزنة جديدة';
                        $actionHistory->desc        = 'اضافة خزنة جديدة';
                        $actionHistory->table_name  = 'Treasuries';
                        $actionHistory->row_id      = $create->id;
                        $actionHistory->created_by  = auth()->user()->id;
                        $actionHistory->save();

                    DB::commit();
                    $this->reset();                                         // Clear Inputs Data

                    // Dispatch events
                    $this->dispatch('treasuriesCreateMS');                // Flash Message
                    $this->dispatch('createModalToggle');                // Close Create Model
                    $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created
            }


        } catch (\Throwable $th)
        {
            DB::rollBack();
                // Dispatch events
                    $this->dispatch('treasuriesErrorMS');                // Flash Message
                    $this->dispatch('createModalToggle');                // Close Create Model
                    $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created
        }




    }
}

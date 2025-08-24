<?php

namespace App\Livewire\BackEnd\Stores;

use App\Models\Store;
use Livewire\Component;
use App\Models\ActionHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Livewire\BackEnd\Stores\Data;
use App\Services\ActionHistoryService;



class Create extends Component
{
    public $name               = '';
    public $status             = '';
    public $phone              = '';
    public $address            = '';
    public $date               = '';
    public $company_code;

    protected $listeners = ['storesCreate'];


    public function storesCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }

    public function mount()
    {
        $this->company_code = auth()->user()->company_code;
    }


    public function submit(ActionHistoryService $action_history)
    {
        // dd($this->all());
        $validated = $this->validate(
        [
            'name' =>
            [
                'required',
                Rule::unique('stores')->where(function ($query)
                {
                    return $query->where('company_code', $this->company_code);
                }),
            ],
            'status'            => 'required',
            'date'              => 'required',
        ],
        [
            'name.required'             => 'اسم القسم مطلوب.',
            'date.required'             => ' التاريخ مطلوب.',
            'status.required'           => '؛حالة القسم مطلوبة.',
            'name.unique'               => 'اسم القسم موجود بالفعل.',
        ]);


        try
        {
             DB::beginTransaction();

            $create = Store::create(
            [
                'name'                  => $this->name,
                'status'                => $this->status,
                'phone'                 => $this->phone,
                'address'               => $this->address,
                'date'                  => $this->date,
                'company_code'          => auth()->user()->company_code,
                'created_by'            => auth()->user()->id,
                'updated_by'            => auth()->user()->id,
            ]);

            // 2 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('اضافة مخزن جديد', 'اضافة مخزن جديد', 'Store', $create->id,auth()->user()->id);

            DB::commit();
            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('storesCreateMS');                   // Flash Message
            $this->dispatch('createModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            DB::rollBack();
            throw $th;
            $this->dispatch('storesErrorMS');                   // Flash Message
        }

    }


    public function render()
    {
        return view('back-end.stores.create');
    }
}

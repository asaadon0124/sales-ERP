<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;
use Livewire\WithPagination;

class Show extends Component
{

    use WithPagination;


    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public $treasuries,$subTreasuries, $name, $status, $is_master, $last_recept_pay, $last_recept_recive, $company_code, $updated_by,$itemId;
    protected $listeners = ['treasuriesShow','refreshData' => '$refresh'];





    public function mount($id)
    {
        if (!auth()->user()->can('تفاصيل الخزن'))
        {
            abort(403, 'ليس لديك صلاحية عرض تفاصيل خزنة');
        }
        // fill $slider with the eloquent model of the same id
        $this->treasuries           = Treasuries::with('treasuriesDetailes')->where('id',$id)->first();
        // dd($this->treasuries->treasuriesDetailes);
        $this->itemId               = $id;
        $this->name                 = $this->treasuries->name;
        $this->status               = $this->treasuries->status;
        $this->is_master            = $this->treasuries->is_master;
        $this->last_recept_pay      = $this->treasuries->last_recept_pay;
        $this->last_recept_recive   = $this->treasuries->last_recept_recive;
        $this->company_code         = $this->treasuries->company_code;
        $this->updated_by           = $this->treasuries->updated_by;
    }


    public function Status()
    {
        return $this->status == 'un_active' ? 'غير مفعل' : ' مفعل';
    }

    public function IsMaster()
    {
        return $this->is_master == 'master' ? 'رئيسية' : ' فرعية';
    }

    public function render()
    {
        $data = Treasuries::with('treasuriesDetailes')
        ->where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('company_code', 'like', '%' . $this->search . '%');
        })
        ->where('id', $this->itemId) // Ensure you are still filtering by ID
        ->first();
        return view('back-end.treasuries.show',compact('data'));
    }
}

<?php

namespace App\Livewire\BackEnd\Emoloyees;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AdminTreasuries;
use Spatie\Permission\Models\Role;

class Show extends Component
{
    use WithPagination;


    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public $admin;
    public $adminID;
    public $subTreasuries;
    public $name;
    public $activeShift;
    public $roles;
    protected $listeners = ['refreshData' => '$refresh'];



    public function mount($id)
    {
        $this->admin                = Admin::with('treasuries')->where('id',$id)->first();
        $this->adminID              = $id;
        $activeShift                = Active_shift();
        $this->roles                = $this->admin->getRoleNames();


        foreach ($this->admin->treasuries as $treasury)
        {
            if ($activeShift)
            {
                $treasury->activeShift = $treasury->id == $activeShift->treasury_id ? 'نعم' : 'لا';
            }else
            {
                $treasury->activeShift = 'لا';
            }
        }




    }





    public function render()
    {


        return view('back-end.emoloyees.show',
        [
            'data' => $this->admin,

        ]);
    }
}

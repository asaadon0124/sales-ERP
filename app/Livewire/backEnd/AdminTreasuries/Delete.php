<?php

namespace App\Livewire\BackEnd\AdminTreasuries;

use Livewire\Component;

class Delete extends Component
{

    public function submit()
    {
        if (!auth()->user()->can('حذف خزنة الموظف'))
        {
            abort(403, 'ليس لديك صلاحية حذف خزنة');
        }
    }
    public function render()
    {
        return view('back-end.admin-treasuries.delete');
    }
}

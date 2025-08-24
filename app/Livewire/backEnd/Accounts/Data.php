<?php

namespace App\Livewire\BackEnd\Accounts;

use App\Models\Account;
use Livewire\Component;
use App\Models\AccountType;
use Livewire\WithPagination;

class Data extends Component
{
    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh'];
    public $search;
    public $searchAccountType;
    public $searchAccountBalanceType;


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchAccountType()
    {
        $this->resetPage();  // عشان لما تغير النوع ترجع الصفحة 1
    }




    public function render()
    {
         if (!auth()->user()->can('عرض كل الحسابات'))
        {
            abort(403, 'ليس لديك صلاحية عرض كل الحسابات ');
        }

        
        $acountsTypes =  AccountType::where('status','active')->get();
        $data = Account::where(function ($query)
        {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('account_number', 'like', '%' . $this->search . '%');

            if ($this->search == 'نعم')
            {
                $query->orWhere('is_parent', '1');
            } elseif (str_contains($this->search, 'لا'))
            {
                $query->orWhere('is_parent', '0');
            }
        }) ->when($this->searchAccountType, function ($query)
        {
            $query->where('account_type_id', $this->searchAccountType);
        })->when($this->searchAccountBalanceType, function ($query)
        {
            $query->where('start_balance_status', $this->searchAccountBalanceType);
        })->latest()->paginate(5);
        // dd($data);
        return view('back-end.accounts.data',compact('data','acountsTypes'));
    }
}

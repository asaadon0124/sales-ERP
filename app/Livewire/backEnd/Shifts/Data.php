<?php

namespace App\Livewire\BackEnd\Shifts;

use App\Models\Admin;
use App\Models\Shift;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Scopes\ActiveScope;

class Data extends Component
{

    use WithPagination;

    protected $listeners = ['refreshData' => '$refresh', 'shiftCreated' => 'handleShiftCreated', 'shiftFinshed' => 'handleShiftFinshed'];
    public $search = '';
    public $shift_created = false;
    public $check_admin_active_Treasury_Shift;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function handleShiftCreated()
    {
        $this->shift_created = true;
    }

    public function handleShiftFinshed()
    {
        $this->shift_created = false;
    }

    public function mount()
    {
        $this->check_admin_active_Treasury_Shift = Admin::where('id', auth()->user()->id)->whereDoesntHave('shifts', function ($qu) {
            $qu->where('shift_status', 'active');
        })->get();

        // dd($this->check_admin_active_Treasury_Shift);
    }



    public function render()
    {
        if (!auth()->user()->can('الشيفتات'))
        {
            abort(403, 'ليس لديك صلاحية عرض الشيفتات');
        }

        $data = Shift::when(!auth()->user()->can('عرض شيفتات كل المستخدمين'), function ($query)
        {
            // لو ما عندوش صلاحية، شوف شيفتاته بس
            $query->where('admin_id', auth()->id());

        })->when($this->search != '', function ($query)
        {
                $query->whereHas('treasury', function ($q)
                {
                    $q->withoutGlobalScope(ActiveScope::class)
                        ->where('name', 'like', '%' . $this->search . '%');
                });

            })->with(
            [
                'treasury' => function ($q) {
                    $q->withoutGlobalScope(ActiveScope::class);
                },
                'admin'
            ])
            ->latest()
            ->paginate(5);

        return view('back-end.shifts.data', compact('data'));
    }
}

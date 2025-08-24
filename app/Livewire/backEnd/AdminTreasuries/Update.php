<?php

namespace App\Livewire\BackEnd\AdminTreasuries;

use App\Models\Admin;
use Livewire\Component;
use App\Models\Treasuries;
use App\Livewire\BackEnd\Emoloyees\Show;
use Illuminate\Validation\Rule;


class Update extends Component
{
    public $treasury_id;
    public $admin_id;
    public $status;
    public $company_code;

    public $treasuries         = [];
    public $treasury;

    protected $listeners = ['AdminTreasuriesUpdate'];


    public function AdminTreasuriesUpdate($id)
    {
        $adminTreasuries    = Admin::with('treasuries')->find(auth()->user()->id);
        $this->treasury     = $adminTreasuries->treasuries->where('id',$id)->first();
        $this->status       = $this->treasury->pivot->status;
        $this->treasury_id  = (int) $this->treasury->id;


          $this->treasuries = Treasuries::where(function ($query)
        {
            $query->whereDoesntHave('admins', function ($q)
            {
                $q->where('admin_id', auth()->user()->id);
            })
            ->orWhere('id', $this->treasury_id); // تضمين الخزنة الحالية
        })->get();

        $this->company_code         = auth()->user()->company_code;

// dd($this->treasury->id);
        // dd($this->treasury_id, $this->treasuries->pluck('id')->toArray());
        // show Create modal
        $this->dispatch('updateModalToggle');
    }





     public function submit()
    {
        // dd($this->all());
        $validated = $this->validate(
        [
            'treasury_id' => 'required',
            'status'      => 'required',
        ],
        [
            'treasury_id.required'              => 'اسم الخزنة مطلوب.',
            'status.required'                   => '؛حالة الخزنة مطلوبة.',
            'treasury_id.unique'                => 'اسم الخزنة موجود بالفعل.',
        ]);


        try
        {
           // احصل على treasury_id القديم
            $oldTreasuryId = $this->treasury->id;

            // افصل القديمة
            auth()->user()->treasuries()->detach($oldTreasuryId);

            // أضف الجديدة
            auth()->user()->treasuries()->attach($this->treasury_id,
            [
                'company_code' => $this->company_code,
                'created_by'   => auth()->user()->id,
                'updated_by'   => auth()->user()->id,
                'status'       => $this->status,
            ]);

            // dd(auth()->user()->id);

            // Dispatch events
            $this->dispatch('AdminTreasuriesUpdateMS');           // Flash Message
            $this->dispatch('updateModalToggle');                // Close Create Model
            $this->dispatch('refreshData')->to(Show::class);     // Refresh Index Page To Show New Record Created


        } catch (\Throwable $th)
        {
            throw $th;
            $this->dispatch('AdminTreasuriesErrorMS');                   // Flash Message
        }

    }


    public function render()
    {
        return view('back-end.admin-treasuries.update');
    }
}

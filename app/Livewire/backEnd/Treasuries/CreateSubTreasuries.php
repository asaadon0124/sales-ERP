<?php

namespace App\Livewire\BackEnd\Treasuries;

use Livewire\Component;
use App\Models\Treasuries;
use App\Models\ActionHistory;
use App\Models\TreasuriesDetailes;
use Illuminate\Support\Facades\DB;

class CreateSubTreasuries extends Component
{
    public $treasuries, $sub_treasuries_id, $status, $treasuriesCount,$id;
    protected $listeners = ['treasuriesCreateٍSub'];



    public function treasuriesCreateٍSub($id)
    {
        $this->id = $id;
        $this->treasuries = Treasuries::select('name','status','is_master','id')->where('status','active')->get();

        // show Create modal
        $this->dispatch('createModalToggle');
    }




    public function render()
    {
        return view('back-end.treasuries.create-sub-treasuries');
    }



    public function submit()
    {


        if (!auth()->user()->can('اضافة خزنة فرعية للخزنة الرئيسية'))
        {
            abort(403, 'ليس لديك صلاحية اضافة خزنة فرعية' );
        }

        $validated = $this->validate(
        [
            'sub_treasuries_id' => 'required',
            'status'            => 'required',
        ],
        [
            'sub_treasuries_id.required'  => 'اسم الخزينة مطلوب.',
            'status.required'             => 'الحالة مطلوبة.',
        ]);



        try
        {

            $this->treasuriesCount = TreasuriesDetailes::where('treasuries_id', $this->id)->where('sub_treasuries_id',$this->sub_treasuries_id)->count();
            // dd($this->treasuriesCount);
            if ($this->treasuriesCount > 0)
            {
                 // Dispatch events
                 $this->dispatch('SubTreasuresErrorMS');                // Flash Message
                 $this->dispatch('createModalToggle');                // Close Create Model
                 $this->dispatch('refreshData')->to(Data::class);     // Refresh Index Page To Show New Record Created
            }else
            {
                DB::beginTransaction();


                $create = TreasuriesDetailes::create(
                    [
                        'sub_treasuries_id'      => $this->sub_treasuries_id,
                        'treasuries_id'          => $this->id,
                        'created_by'             => auth()->user()->id,
                        'updated_by'             => auth()->user()->id,
                    ]);


                    // 2 - CREATE ACTION HISTORY TABLE *****************
                    $actionHistory              = new ActionHistory();
                    $actionHistory->title       = 'اضافة خزنة تستلم من الخزنة الرئيسية ';
                    $actionHistory->desc        = "اضافة خزنة تستلم من  الرئيسية الخزنة";
                    $actionHistory->table_name  = 'TreasuriesDetailes';
                    $actionHistory->row_id      = $this->id;
                    $actionHistory->created_by  = auth()->user()->id;
                    $actionHistory->save();
                    DB::commit();

                    $this->reset();                                         // Clear Inputs Data

                    // Dispatch events
                    $this->dispatch('SubTreasuresCreateMS');               // Flash Message
                    $this->dispatch('createModalToggle');                 // Close Create Model
                    $this->dispatch('refreshData')->to(Show::class);     // Refresh Index Page To Show New Record Created
            }


        } catch (\Throwable $th)
        {
            DB::rollBack();
            //throw $th;
        }

    }
}

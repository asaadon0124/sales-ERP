<?php

namespace App\Livewire\BackEnd\Stores;

use App\Models\Store;
use Livewire\Component;
use App\Models\ItemBatch;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Show extends Component
{

        use WithPagination;


    public $store;
    public $subTreasuries;
    public $name;
    public $status;
    public $phone;
    public $address;
    public $company_code;
    public $created_by;
    public $updated_by;

    public $storeId;


    protected $listeners = ['storesShow','refreshData' => '$refresh'];


    public $search;

    public function updatingSearch()
    {
        $this->resetPage();
    }


    // public function storesShow($id)
    // {
    //     dd('ds');
    //      // fill $slider with the eloquent model of the same id
    //     $this->store           = Store::with('item_batches')->where('id',$id)->first();
    //     dd($this->store);
    //     $this->storeId              = $id;
    //     $this->name                 = $this->store->name;
    //     $this->status               = $this->store->status;
    //     $this->phone                = $this->store->phone;
    //     $this->address              = $this->store->address;

    //     $this->company_code         = $this->store->company_code;
    //     $this->created_by           = $this->store->created_by;
    // }




    public function mount($id)
    {
        if (!auth()->user()->can('تفاصيل الخزن'))
        {
            abort(403, 'ليس لديك صلاحية عرض تفاصيل خزنة');
        }


        $this->store           = Store::with('item_batches')->where('id',$id)->first();
        // dd($this->store);
        $this->storeId              = $id;
        $this->name                 = $this->store->name;
        $this->status               = $this->store->status();
        // dd($this->status);
        $this->phone                = $this->store->phone;
        $this->address              = $this->store->address;

        $this->company_code         = $this->store->company_code;
        $this->created_by           = $this->store->created_by;

    }




    public function render()
    {

         if (!auth()->user()->can('عرض المخازن'))
        {
            abort(403, 'ليس لديك صلاحية عرض المخازن');
        }

        $search = $this->search;

        // باتشات بدون تاريخ انتهاء
        $noExpire = ItemBatch::select('item_code','deduction','item_unit_id','item_cost_price',DB::raw('NULL as expire_date'),DB::raw('SUM(qty) as total_qty'))
            ->where('store_id', $this->storeId)
            ->where('status', 'active')
            ->whereNull('expire_date')
            ->whereHas('item', function ($q) use ($search)
            {
                $q->where('status', 'active');
                if ($search)
                {
                    $q->where('name', 'like', "%$search%");
                }
            })
            ->groupBy('item_code','item_unit_id','deduction', 'item_cost_price');

        // باتشات فيها تاريخ انتهاء
        $withExpire = ItemBatch::select('item_code','deduction','item_unit_id','item_cost_price','expire_date',DB::raw('SUM(qty) as total_qty'))
            ->where('store_id', $this->storeId)
            ->where('status', 'active')
            ->whereNotNull('expire_date')
            ->whereHas('item', function ($q) use ($search)
            {
                $q->where('status', 'active');
                if ($search)
                {
                    $q->where('name', 'like', "%$search%");
                }
            })
            ->groupBy('item_code','item_unit_id','deduction', 'item_cost_price','expire_date');

        // دمج النتائج
        $data = $withExpire->unionAll($noExpire)->get();



            // dd($data);
        return view('back-end.stores.show',compact('data'));
    }
}

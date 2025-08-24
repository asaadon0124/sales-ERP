<?php

namespace App\Livewire\BackEnd\PurchaseOrderReturns;

use App\Models\Store;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Livewire\BackEnd\PurchaseOrderReturns\Data;


class Create extends Component
{
    public $order_date;
    public $invoice_type;
    public $auto_serial;
    public $order_number;
    public $supplier_code;
    public $store_id;
    public $notes;

    public $suppliers;
    public $stores;
    public $order;
    public $lastOrder;
    public $get_Supplier_Account_number;


     protected $listeners = ['purchaseOrderReturnsCreate','supplierChanged'];


    public function purchaseOrderReturnsCreate()
    {
        // show Create modal
        $this->dispatch('createModalToggle');
    }

    public function supplierChanged($newContent)
    {
        $this->supplier_code                = $newContent;
        $this->get_Supplier_Account_number  = Supplier::where('supplier_code',$newContent)->first();
    }

    public function mount()
    {
        $this->suppliers    = Supplier::select('name','supplier_code','id')->whereHas('purchase_orders')->get();
        $this->stores       = Store::select('name','id')->get();
        $this->lastOrder    = PurchaseOrder::select('order_number','company_code','id','auto_serial')->where('company_code',auth()->user()->company_code)->latest()->first();
    }


    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('اضافة فاتورة مرتجع المشتريات'))
        {
            abort(403, 'ليس لديك صلاحية اضافة فاتورة مرتجع المشتريات ');
        }

        // dd($this->all());
        $validated = $this->validate(
        [
            'order_date'                        => 'required|date',
            // 'invoice_type'                      => 'required',
            'order_number'                      => 'required',
            'supplier_code'                     => 'required|exists:suppliers,supplier_code',
            'store_id'                          => 'required|exists:stores,id',
        ],
        [
            'order_date.required'               => 'تاريخ شراء الفاتورة مطلوب.',
            'order_date.date'                   => 'هذا الحقل يج ان يكون من نوع تاريخ.',
            'order_number.required'             => 'الرقم المدون علي الفاتورة مطلوب.',
            'order_number.unique'               => 'الرقم المسجل علي الفانورة موجود بالفعل.',
            'supplier_code.required'            => 'اسم المورد مطلوب.',
            'supplier_code.exists'              => 'اسم المورد غير موجود.',
            'store_id.required'                 => 'اسم المخزن مطلوب.',
            'store_id.exists'                   => 'اسم المخزن غير موجود.',
            // 'invoice_type.required'             => 'نوع الفاتورة مطلوب.',
        ]);


        try
        {
            DB::beginTransaction();

            $this->order = new PurchaseOrder();


            $this->order->auto_serial               = get_last_autoSerial_invoices(PurchaseOrder::class,'auto_serial');
            $this->order->order_date                = $this->order_date;
            $this->order->invoice_type              = '0';
            $this->order->order_number              = $this->order_number;
            $this->order->store_id                  = $this->store_id;
            $this->order->supplier_code             = $this->supplier_code;
            $this->order->notes                     = $this->notes;
            $this->order->order_type                = '2';
            $this->order->account_number            = $this->get_Supplier_Account_number->account_number;

            $this->order->company_code              = auth()->user()->company_code;
            $this->order->created_by                = auth()->user()->id;
            $this->order->updated_by                = auth()->user()->id;
            $this->order->save();

            // 2 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('اضافة فاتورة مرتجع المشتريات ', "اضافة فاتورة مرتجع المشتريات {$this->order->supplier->name}", 'PurchaseOrder', $this->order->auto_serial,auth()->user()->id);
                DB::commit();
            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('purchaseOrdersCreateMS');                   // Flash Message
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
        return view('back-end.purchase-order-returns.create',
        [
            'supplieres'    => $this->suppliers,
            'stores'        => $this->stores,
        ]);
    }
}

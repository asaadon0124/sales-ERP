<?php

namespace App\Livewire\BackEnd\PurchaseOrders;

use App\Models\Store;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Validation\Rule;
use App\Livewire\BackEnd\PurchaseOrders\Data;


class Update extends Component
{
    public $order_date;     // ******
    public $invoice_type;   // ******
    public $auto_serial;
    public $order_number;
    public $supplier_code;  // ******
    public $store_id;
    public $notes;

    public $suppliers;
    public $stores;
    public $order;
    public $get_Supplier_Account_number;

    protected $listeners = ['purchaseOrderUpdate'];


    public function purchaseOrderUpdate($id)
    {

        // fill $slider with the eloquent model of the same id
        $this->order                = PurchaseOrder::where('auto_serial',$id)->first();
        $this->order_date           = $this->order->order_date;
        $this->invoice_type         = $this->order->invoice_type;
        $this->supplier_code        = $this->order->supplier_code;
        $this->store_id             = $this->order->store_id;
        $this->notes                = $this->order->notes;
        $this->auto_serial          = $this->order->auto_serial;
        $this->order_number         = $this->order->order_number;
        $this->get_Supplier_Account_number       = $this->order->account_number;


        // show edit modal
        $this->dispatch('updateModalToggle');
    }


    public function mount()
    {
        $this->suppliers    = Supplier::select('name','supplier_code','id')->get();
        $this->stores       = Store::select('name','id')->get();
        // $this->lastOrder    = PurchaseOrder::select('order_number','company_code','id','auto_serial')->where('company_code',auth()->user()->company_code)->latest()->first();
    }

    public function supplierChanged($newContent)
    {
        $this->supplier_code = $newContent;
        $this->get_Supplier_Account_number = Supplier::where('supplier_code',$newContent)->first()->account_number;
    }

    public function rules()
    {
        return
        [
            'order_date'                        => 'required|date',
            'invoice_type'                      => 'required',
            'order_number'                      =>
            [
                'required',
                Rule::unique('purchase_orders')->where(function ($query)
                {
                    return $query->where('company_code', request('company_code'));
                }),
            ],
            'supplier_code'                     => 'required|exists:suppliers,supplier_code',
            'store_id'                          => 'required|exists:stores,id',
        ];
    }


    public function messages()
    {
        return
        [
            'order_date.required'               => 'تاريخ شراء الفاتورة مطلوب.',
            'order_date.date'                   => 'هذا الحقل يج ان يكون من نوع تاريخ.',
            'order_number.required'             => 'الرقم المدون علي الفاتورة مطلوب.',
            'order_number.unique'               => 'الرقم المسجل علي الفانورة موجود بالفعل.',
            'supplier_code.required'            => 'اسم المورد مطلوب.',
            'supplier_code.exists'              => 'اسم المورد غير موجود.',
            'store_id.required'                 => 'اسم المخزن مطلوب.',
            'store_id.exists'                   => 'اسم المخزن غير موجود.',
            'invoice_type.required'             => 'نوع الفاتورة مطلوب.',
        ];
    }



    public function submit()
    {
      try
      {
        // dd($this->get_Supplier_Account_number);
        // $this->order = new PurchaseOrder();



        $this->order->order_date                = $this->order_date;
        $this->order->invoice_type              = $this->invoice_type;
        $this->order->order_number              = $this->order_number;
        $this->order->store_id                  = $this->store_id;
        $this->order->supplier_code             = $this->supplier_code;
        $this->order->auto_serial               = $this->auto_serial;
        $this->order->notes                     = $this->notes;
        $this->order->account_number            = $this->get_Supplier_Account_number;

        $this->order->company_code              = auth()->user()->company_code;
        $this->order->created_by                = auth()->user()->id;
        $this->order->updated_by                = auth()->user()->id;
        $this->order->save();


        // $this->reset();                                         // Clear Inputs Data

       // Dispatch events
       $this->dispatch('purchaseOrdersUpdateMS');
       $this->dispatch('updateModalToggle');
       $this->dispatch('refreshData')->to(Data::class);

      } catch (\Throwable $th)
      {
        throw $th;
        $this->dispatch('storesErrorMS');                   // Flash Message
      }


    }




    public function render()
    {
        return view('back-end.purchase-orders.update');
    }
}

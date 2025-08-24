<?php

namespace App\Livewire\BackEnd\SalesOrderReturns;

use App\Models\Servant;
use Livewire\Component;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\MaterialType;
use Illuminate\Support\Facades\DB;
use App\Services\ActionHistoryService;
use App\Livewire\BackEnd\SalesOrderReturns\Data;

class Update extends Component
{
     public $order_date;
    public $invoice_type;
    public $auto_serial;
    public $customer_code;
    public $servant_code;
    public $matrial_types_id;
    public $notes;

    public $customers;
    public $servants;
    public $stores;
    public $matrial_types;
    public $order;
    public $lastOrder;
    public $get_Customer_Account_number;
    public $get_Servant_Account_number;
    public $customer_balance;
    public $servant_balance;
    public $customer;
    public $servant;



   protected $listeners = ['salesOrdeReturnsrUpdate','customerChanged','servantChanged'];


    public function salesOrdeReturnsrUpdate($id)
    {
        $this->order                = SalesOrder::where('auto_serial',$id)->with('customer','servant')->first();
        $this->get_Customer_Account_number  = Customer::where('customer_code',$this->order->customer->customer_code)->first();
        $this->get_Servant_Account_number   = Servant::where('servant_code',$this->order->servant->servant_code)->first();

        // dd($this->get_Servant_Account_number);

        $this->order_date           = $this->order->order_date;
        $this->customer_code        = $this->order->customer_code;
        $this->customer_balance     = $this->order->customer->current_balance;
        $this->servant_code         = $this->order->servant_code;
        $this->servant_balance      = $this->order->servant->current_balance;
        $this->matrial_types_id     = $this->order->matrial_types_id;
        $this->notes                = $this->order->notes;
        $this->invoice_type         = $this->order->invoice_type;

        // show Create modal
        $this->dispatch('updateModalToggle');
    }


    public function mount()
    {
        $this->customers        = Customer::select('name','customer_code','id')->whereHas('sales_orders')->get();
        $this->servants         = Servant::select('name','servant_code','id')->whereHas('sales_orders')->get();
        $this->matrial_types    = MaterialType::all();
        $this->lastOrder        = SalesOrder::select('company_code','id','auto_serial')->where('company_code',auth()->user()->company_code)->latest()->first();
    }


    public function customerChanged($newContent)
    {
        $this->customer_code                = $newContent;
        $this->get_Customer_Account_number  = Customer::where('customer_code',$newContent)->first();
        $this->customer_balance             = $this->get_Customer_Account_number?->current_balance ?? 0;
    }


    public function servantChanged($newContent)
    {
        $this->servant_code                 = $newContent;
        $this->get_Servant_Account_number   = Servant::where('servant_code',$newContent)->first();
        $this->servant_balance              = $this->get_Servant_Account_number?->current_balance ?? 0;
    }



    public function submit(ActionHistoryService $action_history)
    {
        if (!auth()->user()->can('تعديل فاتورة مرتجع المبيعات'))
        {
            abort(403, 'ليس لديك صلاحية تعديل فاتورة مرتجع المبيعات ');
        }

        // dd($this->all());
        $validated = $this->validate(
        [
            'order_date'                       => 'required|date',
            'customer_code'                    => 'required|exists:customers,customer_code',
            'servant_code'                     => 'required|exists:servants,servant_code',
            'matrial_types_id'                 => 'required|exists:material_types,id',
        ],
        [
            'order_date.required'               => 'تاريخ شراء الفاتورة مطلوب.',
            'order_date.date'                   => 'هذا الحقل يج ان يكون من نوع تاريخ.',

            'customer_code.required'            => 'اسم العميل مطلوب.',
            'customer_code.exists'             => 'اسم العميل غير موجود.',
            'servant_code.required'            => 'اسم المندوب مطلوب.',
            'servant_code.exists'              => 'اسم المندوب غير موجود.',
            'matrial_types_id.required'         => 'اسم فئة الفاتورة غير موجود.',
        ]);


        try
        {
            DB::beginTransaction();

            // dd($this->get_Customer_Account_number);
             // لو رصيد العميل مش بيساوي رصيده الحالي
                if ($this->customer_balance == null || $this->customer_balance == '' ||$this->customer_balance != $this->get_Customer_Account_number?->current_balance)
                {
                    $this->addError('customer_balance', 'رصيد العميل غير صحيح');
                    return;
                }


                 // لو رصيد المندوب مش بيساوي رصيده الحالي
                if ($this->servant_balance == null || $this->servant_balance == '' ||$this->servant_balance != $this->get_Servant_Account_number?->current_balance)
                {
                    $this->addError('servant_balance', 'رصيد المندوب غير صحيح');
                    return;
                }

            // $this->order->auto_serial               = get_last_autoSerial_invoices(SalesOrder::class,'auto_serial');
            $this->order->order_date                = $this->order_date;
            $this->order->invoice_type              = $this->invoice_type;
            // $this->order->order_type                = '2';
            // $this->order->sales_item_type           = '1';
            $this->order->customer_code             = $this->customer_code;
            $this->order->customer_account_number   = $this->get_Customer_Account_number->account_number;
            $this->order->servant_code              = $this->servant_code;
            $this->order->matrial_types_id          = $this->matrial_types_id;
            $this->order->notes                     = $this->notes;


            $this->order->company_code              = auth()->user()->company_code;
            $this->order->created_by                = auth()->user()->id;
            $this->order->updated_by                = auth()->user()->id;
            $this->order->save();
            // 2 - CREATE ACTION HISTORY TABLE *****************
                $action_history->action('تعديل فاتورة مرتجع المبيعات ', "تعديل فاتورة مرتجع المبيعات {$this->order->customer->name}", 'SalesOrder', $this->order->auto_serial,auth()->user()->id);
                DB::commit();
            $this->reset();                                         // Clear Inputs Data

            // Dispatch events
            $this->dispatch('salesOrdersUpdateMS');                // Flash Message
            $this->dispatch('updateModalToggle');                // Close Create Model
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
        return view('back-end.sales-order-returns.update',
         [
            'customers'    => $this->customers,
            'stores'        => $this->stores,
        ]);
    }
}

<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\ItemBatch;
use App\Models\SalesOrderDetail;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table    = 'sales_orders';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function item_batches()
    {
        return $this->hasMany(ItemBatch::class,'store_id');
    }

    public function treasury_tranaction()
    {
        return $this->hasOne(TreasuryTransation::class,'auto_serial','treasures_transactions_id');
    }

     public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_code','customer_code')->where('company_code',auth()->user()->company_code);
    }

     public function servant()
    {
        return $this->belongsTo(Servant::class,'servant_code','servant_code')->where('company_code',auth()->user()->company_code);
    }




     public function OrderType()
     {
        if ($this->order_type == '0')
        {
            return 'مبيعات';

        } elseif($this->order_type == '1')
        {
            return 'مرتجع مبيعات علي نف الفاتورة';
        }elseif($this->order_type == '2')
        {
            return 'مرتجع مبيعات';
        }
     }


       // OTHER METHODS
     public function InvoiceType()
     {
         return $this->invoice_type == '1' ? 'اجل' : 'كاش';
     }


     public function DiscountType()
     {
         return $this->discount_type == '0' ? 'قيمة' : ' نسبة';
     }

     public function items_type()
     {
         return $this->discount_type == '0' ? 'متغير' : ' ثابت';
     }

     public function sales_item_type()
     {
        if ($this->sales_item_type == '0')
        {
            return 'قطاعي';
        } elseif($this->sales_item_type == '1')
        {
            return 'نصف جملة';
        }else
        {
            return 'جملة';
        }

     }


      public function order_detailes()
     {
         return $this->hasMany(SalesOrderDetail::class,'auto_serial_sales_order','auto_serial');
     }



     public function Approval()
     {
         return $this->approve == '1' ? 'اعتمدت' : ' لم تعتمد';
     }


     public function is_fixed_customer()
     {
         return $this->is_fixed_customer == '0' ? 'عميل ثابت' : 'عميل طياري';
     }




    protected static function booted()
    {
    //    static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

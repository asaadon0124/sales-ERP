<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Scopes\ActiveScope;
use App\Models\PurchaseOrderDetailes;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table    = 'purchase_orders';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }



    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_code','supplier_code')->where('company_code',auth()->user()->company_code);
    }


    public function store()
    {
        return $this->belongsTo(Store::class,'store_id');
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


     public function OrderType()
     {
        if ($this->order_type == '0')
        {
            return 'مشتريات';

        } elseif($this->order_type == '1')
        {
            return 'مرتجع مشتريات علي نف الفاتورة';
        }else
        {
            return 'مرتجع مشتريات';
        }
     }


     public function Approval()
     {

        return $this->approve == '1' ? 'اعتمدت' : ' لم تعتمد';
     }





     public function order_detailes()
     {
         return $this->hasMany(PurchaseOrderDetailes::class,'auto_serial_purchase_orders','auto_serial');
     }


     protected static function booted()
    {
       static::addGlobalScope(new CompanyCodeScope);
    }

}

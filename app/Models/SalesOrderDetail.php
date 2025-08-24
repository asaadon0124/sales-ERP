<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Admin;
use App\Models\ItemUnit;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesOrderDetail extends Model
{
    use HasFactory;

    public function SalesItemType()
     {
        if ($this->sales_item_type_detailes == '0')
        {
            return 'قطاعي';
        }elseif ($this->sales_item_type_detailes == '1')
        {
           return 'نص جملة';
        }else
        {
             return 'جملة';
        }

     }

    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }


     public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'auto_serial_sales_order', 'auto_serial');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_code','item_code');
    }

    public function store()
    {
        return $this->belongsTo(Store::class,'store_id','id');
    }


    public function item_unit()
    {
        return $this->hasOne(ItemUnit::class,'id','item_units_id');
    }

    protected static function booted()
    {
       static::addGlobalScope(new CompanyCodeScope);
    }
}

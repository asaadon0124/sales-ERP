<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Admin;
use App\Models\ItemUnit;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderDetailes extends Model
{
    use HasFactory;
    protected $table    = 'purchase_order_detailes';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }


    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }



    public function item()
    {
        return $this->hasOne(Item::class,'item_code','item_code');
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

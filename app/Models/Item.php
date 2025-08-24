<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\ItemUnit;
use App\Models\ItemBatch;
use App\Models\ItemCategory;
use App\Models\ItemCardMovement;
use App\Models\SalesOrderDetail;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CreatedByScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table    = 'items';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class,'item_unit_id');
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class,'item_category_id','id');
    }

    public function itemUnitChild()
    {
        return $this->belongsTo(ItemUnit::class,'sub_item_unit_id');
    }

    public function item_batches()
    {
        return $this->hasMany(ItemBatch::class,'item_code','item_code');
    }


    public function sales_order_detailes()
    {
        return $this->hasMany(SalesOrderDetail::class,'item_code','item_code');
    }


    public function item_card_movements()
    {
        return $this->hasMany(ItemCardMovement::class,'item_code','item_code');
    }

    public function sales_movements()
    {
        return $this->hasMany(ItemCardMovement::class, 'item_code', 'item_code')->where('item_card_movements_category_id', 10); // 10 = بيع
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }



    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

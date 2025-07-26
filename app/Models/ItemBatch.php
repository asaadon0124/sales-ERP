<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Admin;
use App\Models\Store;
use App\Models\ItemUnit;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemBatch extends Model
{
    use HasFactory;

    protected $table    = 'item_batches';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function store()
    {
        return $this->belongsTo(Store::class,'store_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_code','item_code');
    }

    public function item_unit()
    {
        return $this->belongsTo(ItemUnit::class,'item_unit_id');
    }



    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

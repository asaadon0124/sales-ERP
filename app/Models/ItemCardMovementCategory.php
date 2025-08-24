<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemCardMovementCategory extends Model
{
    use HasFactory;

    protected $table    = 'item_card_movement_categories';
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

    protected static function booted()
    {
       static::addGlobalScope(new CompanyCodeScope);
    }
}

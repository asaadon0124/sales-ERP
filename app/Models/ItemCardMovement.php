<?php

namespace App\Models;

use App\Models\Item;
use App\Models\ItemCardMovementType;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemCardMovementCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemCardMovement extends Model
{
    use HasFactory;

    protected $table    = 'item_card_movements';
    protected $guarded  = [];


    public function itemMovementCategory()
    {
        return $this->belongsTo(ItemCardMovementCategory::class,'item_card_movements_category_id','id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_code','item_code');
    }

    public function itemMovementType()
    {
        return $this->belongsTo(ItemCardMovementType::class,'item_card_movements_type_id','id');
    }

    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }
}

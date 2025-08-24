<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CompanyCodeScope;
use App\Models\Scopes\CreatedByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table    = 'item_categories';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function items()
    {
        return $this->hasMany(Item::class,'item_category_id');
    }


    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

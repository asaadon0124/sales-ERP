<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CompanyCodeScope;
use App\Models\Scopes\CreatedByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;


    protected $table    = 'stores';
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
        return $this->hasMany(ItemBatch::class,'store_id','id');
    }


     public function status()
     {
         return $this->status == 'un_active' ? 'غير مفعل' : ' مفعل';
     }

    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

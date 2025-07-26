<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CreatedByScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialType extends Model
{
    use HasFactory;

    protected $table    = 'material_types';
    protected $guarded  = [];

    public function adminCreate()
     {
         return $this->belongsTo(Admin::class,'created_by');
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

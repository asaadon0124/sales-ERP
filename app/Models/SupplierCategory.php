<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CreatedByScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierCategory extends Model
{
    use HasFactory;

    protected $table    = 'supplier_categories';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }


    public function suppliers()
    {
        return $this->hasMany(Supplier::class,'supplier_Category_id');
    }





    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);

    }
}

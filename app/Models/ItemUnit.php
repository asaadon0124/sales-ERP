<?php

namespace App\Models;

use App\Models\Item;
use App\Models\Admin;
use App\Models\ItemBatch;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CreatedByScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemUnit extends Model
{
    use HasFactory;

    protected $table    = 'item_units';
    protected $guarded  = [];

    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function IsMaster()
    {
        return $this->is_master == 'master' ? 'اساسية' : ' فرعية';
    }


    public function item_batches()
    {
        return $this->hasMany(ItemBatch::class,'item_unit_id');
    }

    public function master_items()
    {
        return $this->hasMany(Item::class, 'item_unit_id', 'id'); // تأكد من اسم العمود
    }


    public function sub_master_items()
    {
        return $this->hasMany(Item::class, 'sub_item_unit_id', 'id'); // تأكد من اسم العمود
    }



    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Shift;
use App\Models\AdminTreasuries;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuriesDetailes;
use Illuminate\Support\Facades\Schema;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treasuries extends Model
{
    use HasFactory;

    protected $table    = 'treasuries';
    protected $guarded  = [];


     // OTHER METHODS
     public function Status()
     {
         return $this->status == 'un_active' ? 'غير مفعل' : ' مفعل';
     }


     public function IsMaster()
     {
         return $this->is_master == 'master' ? 'رئيسية' : ' فرعية';
     }


     public function canBeDeleted()
     {
         // لو الخزنة نفسها فيها أي شيفتات (قديمة أو مفتوحة)
         if ($this->shifts()->exists())
         {
             return false;
         }

         // لو الخزنة رئيسية
         if ($this->is_master === 'master')
         {
             // لو أي خزنة فرعية ليها شيفتات
            //  $subHasShifts = $this->subTreasuries()->whereHas('shifts')->exists();
            //  if ($subHasShifts) {
            //      return false;
            //  }

            if ($this->subTreasuries()->exists())
            {
                return false;
            }

         }

         // لو خزنة فرعية: الشرط الوحيد انها ما يكونش ليها شيفتات
         return true;
     }



     public function treasuriesDetailes()
     {
        return $this->hasMany(TreasuriesDetailes::class,'treasuries_id');
     }




     public function admins()
{
    return $this->belongsToMany(Admin::class, 'admin_treasuries', 'treasury_id', 'admin_id')
                ->withTimestamps()
                ->withPivot(['company_code', 'created_by', 'updated_by', 'status']);
}

    //  public function adminTreasuries()
    //  {
    //      return $this->hasMany(AdminTreasuries::class,'treasury_id');
    //  }



     public function shifts()
     {
         return $this->hasMany(Shift::class,'treasury_id','id');
     }


     protected static function booted()
     {
        static::addGlobalScope(new ActiveScope);
        static::addGlobalScope(new CompanyCodeScope);
     }
}

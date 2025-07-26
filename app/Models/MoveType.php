<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Account;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoveType extends Model
{
    use HasFactory;

    protected $table    = 'move_types';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function treasuries_transactions()
    {
        return $this->hasMany(TreasuryTransation::class,'moveType_id');
    }





    public function isPrivate()
    {
        if ($this->is_private_internal == 'global') {
            return 'عامة';
        }else
        {
            return 'داخلية';
        }
    }


    public function inScrean()
    {
        if ($this->in_screen == 'pay') {
            return 'صرف';
        }else
        {
            return 'تحصيل';
        }
    }

    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }




    public function accounts()
    {
        return $this->hasMany(Account::class,'moveType_id');
    }
}

<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Account;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CreatedByScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountType extends Model
{
    use HasFactory;

    protected $table    = 'account_types';
    protected $guarded  = [];


public function accounts()
{
    return $this->hasMany(Account::class,'account_type_id','id');
}

public function adminCreate()
{
    return $this->belongsTo(Admin::class, 'created_by', 'id');
}

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function CreatedBy_account_type()
    {
        if ($this->related_internal_accounts == 0)
        {
            return 'مدير';
        }elseif ($this->related_internal_accounts == 1)
        {
            return 'مورد';
        }else
        {
            return 'عميل';
        }
    }

    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

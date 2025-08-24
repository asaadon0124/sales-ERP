<?php

namespace App\Models;

// use App\Models\Admin;
use App\Models\Shift;

use App\Models\Account;
use App\Models\Treasuries;
use App\Models\AdminTreasuries;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    protected $table    = 'admins';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function treasuries()
    {
        return $this->belongsToMany(Treasuries::class, 'admin_treasuries', 'admin_id', 'treasury_id')
                    ->withTimestamps()
                    ->withPivot(['company_code', 'created_by', 'updated_by', 'status']);
    }

    // public function adminTreasuries()
    // {
    //     return $this->hasMany(AdminTreasuries::class,'admin_id');
    // }






    public function shifts()
    {
        return $this->hasMany(Shift::class,'admin_id','id')->with('treasury');
    }





    public function employee_account()
    {
        return $this->belongsTo(Account::class, 'account_number', 'account_number');
    }


      public function transations()
    {
        return $this->hasMany(TreasuryTransation::class, 'account_id', 'account_number');
    }


    public function started_balance_status()
    {
        if ($this->start_balance_status == 'credit')
        {
            return 'مدين';
        }elseif ($this->start_balance_status == 'debit')
        {
            return 'دائن';
        }else
        {
            return 'متزن';
        }
    }










    public function Status()
    {
        return $this->status == 'active' ? 'مفعل' : ' غير مفعل';
    }

    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }

}

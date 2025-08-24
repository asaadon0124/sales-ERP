<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Servant;
use App\Models\Customer;
use App\Models\MoveType;
use App\Models\Supplier;
use App\Models\AccountType;
use App\Models\AdminSitting;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use App\Models\Scopes\CreatedByScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $table    = 'accounts';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class,'account_type_id');
    }

    public function adminSitting()
    {
        return $this->hasOne(AdminSitting::class, 'customer_parent_account_number', 'account_number');
    }



    public function parent_account()
    {
        return $this->belongsTo(Account::class, 'parent_account_number', 'account_number');
    }

    public function chiled_accounts()
    {
        return $this->hasMany(Account::class, 'parent_account_number', 'account_number');
    }




    public function account_customer()
    {
        return $this->hasOne(Supplier::class,'account_number','account_number');
    }

    public function supplier_account()
    {
        return $this->hasOne(Supplier::class,'account_number','account_number');
    }


     public function account_customer2()
    {
        return $this->hasOne(Customer::class,'account_number','account_number');
    }


     public function account_servant()
    {
        return $this->hasOne(Servant::class,'account_number','account_number');
    }

    public function account_employee()
    {
        return $this->hasOne(Admin::class,'account_number','account_number');
    }


    public function transations()
    {
        return $this->hasMany(TreasuryTransation::class,'account_id','account_number');
    }

















    public function scopeParent_accounts($q)
    {
        return $q->where('is_parent','1');
    }
    public function isParent()
    {
       return $this->is_parent == 1 ? 'نعم' : 'لا';
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

    protected static function booted()
    {
    //    static::addGlobalScope(new CreatedByScope);
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }



    public function moveType()
    {
        return $this->belongsTo(MoveType::class, 'moveType_id');
    }
}

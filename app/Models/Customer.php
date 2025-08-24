<?php

namespace App\Models;

use App\Models\Account;
use App\Models\AdminSitting;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table    = 'customers';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function sales_orders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_code', 'customer_code');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function admin_sitting()
    {
        return $this->belongsTo(AdminSitting::class,'company_code');
    }


    public function transations()
    {
        return $this->hasMany(TreasuryTransation::class, 'account_id', 'account_number');
    }

    public function customer_account()
    {
        return $this->belongsTo(Account::class, 'account_number', 'account_number');
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

<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Account;
use App\Models\SalesOrder;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servant extends Model
{
    use HasFactory;


     protected $table    = 'servants';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
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


     public function salary_status()
    {
        if ($this->commission_type == 'fixed')
        {
            return 'ثابت';
        }else
        {
            return 'متغير';
        }
    }


    public function account_customer()
    {
        return $this->belongsTo(Account::class, 'account_number', 'account_number');
    }

     public function transations()
    {
        return $this->hasMany(TreasuryTransation::class, 'account_id', 'account_number');
    }

    public function sales_orders()
    {
        return $this->hasMany(SalesOrder::class, 'servant_code', 'servant_code');
    }

    protected static function booted()
    {
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);

    }

}

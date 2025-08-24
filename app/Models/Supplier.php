<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\AdminSitting;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;


    protected $table    = 'suppliers';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function admin_sitting()
    {
        return $this->belongsTo(AdminSitting::class,'company_code');
    }


    public function supplierCategory()
    {
        return $this->belongsTo(SupplierCategory::class,'supplier_Category_id');
    }



    public function supplier_account()
    {
        return $this->belongsTo(Account::class, 'account_number', 'account_number')->with('parent_account');
    }

    public function transations()
    {
        return $this->hasMany(TreasuryTransation::class, 'account_id', 'account_number');
    }

    public function purchase_orders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_code', 'supplier_code');
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
       static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);

    }

}

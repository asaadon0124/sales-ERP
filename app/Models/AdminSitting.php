<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Treasuries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminSitting extends Model
{
    use HasFactory;

    protected $table    = 'admin_sittings';
    protected $guarded  = [];


    // SCOPES ********************************************************************************
    public function scopeStatus($qry)
    {
        return $qry->where('status','active');
    }


    public function account()
    {
        return $this->belongsTo(Account::class, 'customer_parent_account_number', 'account_number');
    }

    public function supplierAccount()
    {
        return $this->belongsTo(Account::class, 'supplier_parent_account_number', 'account_number');
    }


    public function employeesAccount()
    {
        return $this->belongsTo(Account::class, 'employee_parent_account_number', 'account_number');
    }


    public function servantAccount()
    {
        return $this->belongsTo(Account::class, 'servant_parent_account_number', 'account_number');
    }




}

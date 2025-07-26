<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Account;
use App\Models\MoveType;
use App\Models\Treasuries;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreasuryTransation extends Model
{
    use HasFactory;


    protected $table    = 'treasury_transations';
    protected $guarded  = [];


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function treasurie()
    {
        return $this->belongsTo(Treasuries::class,'treasury_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','account_number');
    }


      public function move_type()
    {
        return $this->belongsTo(MoveType::class,'moveType_id','id');
    }


    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }



    public function Invoice_type_accounts()
    {
       if ($this->invoice_type_accounts == 'purchases')
        {
            return 'فاتورة مشتريات';

        }elseif ($this->invoice_type_accounts == 'sales')
        {
            return 'فاتورة مبيعات';
        }elseif($this->cash_amount > 0 && $this->invoice_type_accounts == '')
        {
            return 'تحصيل';

        }elseif($this->cash_amount < 0 && $this->invoice_type_accounts == '')
        {
            return 'ًصرف';
        }
    }


    public function Cash_source_type()
    {
       return $this->cash_source_type == 'account' ? 'حسابات' : 'خزن';
    }


     public function Invoice_type()
    {
       return $this->invoice_type == 0 ? 'كاش' : 'اجل';
    }



      public function Account_type()
    {
        if ($this->account_type == 'suppliers')
        {
            return 'حساب موردين';
        }elseif ($this->account_type == 'customers')
        {
            return 'حساب عملاء';
        }elseif ($this->account_type == 'servants')
        {
            return 'حساب مناديب';
        }elseif ($this->account_type == 'employee')
        {
            return 'حساب موظفين';
        }else
        {
             return 'حساب عام';
        }
    }


    protected static function booted()
    {
    //    static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }
}

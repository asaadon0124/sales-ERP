<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\Treasuries;
use App\Models\Scopes\ActiveScope;
use App\Models\TreasuryTransation;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{

    protected $table    = 'shifts';
    protected $guarded  = [];


    public function Status()
    {
       return $this->shift_status == 'active' ? 'مفعل' : 'مغلق';
    }


    public function isReview()
    {
       return $this->is_delevered_review == 'yes' ? 'تمت المراجعة' : 'لم تتم المراجعة';
    }


     public function Recive_type()
    {
       return $this->is_delevered_review == 'same' ? 'خزنة اخري' : 'نفس الخزنة ';
    }


     public function cash_amount_status()
    {
        if ($this->cash_status == 'plus')
        {
            return 'زيادة';
        }elseif ($this->cash_status == 'mins')
        {
            return 'عجز';
        }else
        {
            return 'متزن';
        }
    }


    public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }

    public function adminUpdate()
    {
        return $this->belongsTo(Admin::class,'updated_by');
    }


    public function treasury()
    {
        return $this->belongsTo(Treasuries::class,'treasury_id');
    }

    public function treasury_transactions()
    {
        return $this->hasMany(TreasuryTransation::class,'shift_id','auto_serial');
    }


    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id','id');
    }

    protected static function booted()
    {
    //    static::addGlobalScope(new ActiveScope);
       static::addGlobalScope(new CompanyCodeScope);
    }


    use HasFactory;
}

<?php

namespace App\Models;

use App\Models\Treasuries;
use App\Models\Scopes\ActiveScope;
use App\Models\Scopes\CompanyCodeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreasuriesDetailes extends Model
{
    use HasFactory;

    protected $table    = 'treasuries_detailes';
    protected $guarded  = [];


  public function treasurie()
{
    return $this->belongsTo(Treasuries::class, 'sub_treasuries_id')->withoutGlobalScope(ActiveScope::class);
}



    // OTHER METHODS
    public function Status()
    {
        return $this->status == 'un_active' ? 'غير مفعل' : ' مفعل';
    }






}

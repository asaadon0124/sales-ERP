<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActionHistory extends Model
{
    use HasFactory;

    protected $table    = 'action_histories';
    protected $guarded  = [];


     public function adminCreate()
    {
        return $this->belongsTo(Admin::class,'created_by');
    }
}

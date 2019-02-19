<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class token extends Model
{
    protected $fillable = array('user_id','token','revoke');

    public function User(){
        $this->belongsTo(User::class);
    }
}

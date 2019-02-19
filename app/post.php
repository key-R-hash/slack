<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    protected $fillable = array('has_unread','seen','user_id','topic_name','topic','body','logged_at','has_tag','tags','has_file','files_name','files_url','revoke');

    public function User(){
        $this->belongsTo(User::class);
    }
}

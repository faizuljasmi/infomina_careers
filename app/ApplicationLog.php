<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    public function application(){
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

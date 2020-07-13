<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class ApplicationMeta extends Model
{
    use HasTrixRichText;
    protected $guarded = [];

    public function application(){
        return $this->belongsTo(Application::class, 'application_id');
    }
}

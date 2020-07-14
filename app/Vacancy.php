<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;

class Vacancy extends Model
{
    use HasTrixRichText;
    protected $guarded = [];
    //
    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function logs(){
        return $this->hasMany(VacancyLog::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VacancyLog extends Model
{
    public function application(){
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}

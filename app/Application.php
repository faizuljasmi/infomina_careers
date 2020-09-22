<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Te7aHoudini\LaravelTrix\Traits\HasTrixRichText;


class Application extends Model
{
    use HasTrixRichText;
    protected $guarded = [];

    public function metas(){
        return $this->hasMany(ApplicationMeta::class);
    }

    public function logs(){
        return $this->hasMany(ApplicationLog::class);
    }

    public function vacancy(){
        return $this->belongsTo(Vacancy::class);
    }

    public function getOldResumeUrlAttribute(){
        return $this->metas[26]->meta_value ? url('/storage/'.$this->metas[26]->meta_value) : 'https://placehold.it/900x300';
    }
    public function getNewResumeUrlAttribute(){
        return $this->metas[28]->meta_value ? url('/storage/'.$this->metas[28]->meta_value) : 'https://placehold.it/900x300';
    }
}

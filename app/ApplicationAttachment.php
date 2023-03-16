<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationAttachment extends Model
{
    public function application(){
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function getAttachmentUrlAttribute(){
        return $this->file_path ? url('/storage/'.$this->file_path) : 'https://placehold.it/900x300';
    }
}

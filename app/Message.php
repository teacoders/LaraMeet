<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
    public function receiver()
    {
        return $this->belongsTo(User::class);
    }
    public function getFileAttribute($file)
    {
        if($file)
        return $file = Storage::url('files/'.$file);
    }
    public function getCreatedAtAttribute($time)
    {
        return $time = Carbon::parse($time)->diffForHumans();
    }
}

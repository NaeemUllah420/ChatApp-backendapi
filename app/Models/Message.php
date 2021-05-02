<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Foundation\Auth\User as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Message extends Model implements Authenticatable
{
    use HasFactory, Notifiable, AuthAuthenticatable,SoftDeletes;

    protected $collection="messages";
    protected $fillable=['text','sender_id','receiver_id','group_id'];

    public function attachmentable()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function attachments()
    {
        // return $this->belongs
    }
}

<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Contracts\Auth\Authenticatable;
// use Illuminate\Foundation\Auth\User as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Group extends Model implements Authenticatable
{
    use HasFactory, Notifiable, AuthAuthenticatable, SoftDeletes;
    protected $fillable = ['name', 'type', 'owner_id', 'role'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

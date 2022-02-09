<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Provincia;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nombre',
        'email',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function provincia(){
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    public function proyecto(){
    return $this->belongsTo(Proyecto::class);
    }

    public function potencial(){
    return $this->belongsTo(Proyecto::class);
    }

    public function setPasswordAttribute($password){
       $this->attributes['password'] = $password;
    }

    public function chat(){
      return $this->hasOne(Chat::class);
    }

    public function facturas(){
      return $this->hasManyThrough(Factura::class, Proyecto::class, 'usuario_id', 'id_proyecto', 'id', 'id')
        ->orderBy('created_at', 'DESC');
    }

}

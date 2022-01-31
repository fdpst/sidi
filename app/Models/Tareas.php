<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tareas extends Model
{
    use HasFactory, SoftDeletes; 

    protected $table = 'tareas';

    protected $fillable = [
      'id_proyecto',
      'descripcion',
      'tiempo',
      'fecha',
      'id_usuario'
    ];

    protected $dates = ['created_at'];

    protected $casts = [
      'created_at' => 'date:d-m-Y'
    ];
}
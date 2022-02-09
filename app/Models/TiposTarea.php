<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TiposTarea extends Model
{
    use HasFactory, SoftDeletes; 

    protected $table = 'tipos_tarea';

    protected $fillable = [
      'nombre',
    ];

    protected $dates = ['created_at'];

    protected $casts = [
      'created_at' => 'date:d-m-Y'
    ];
}
<?php

namespace App\Models;

use App\Mail\Presupesto;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use SoftDeletes;

    protected $table = 'proyecto';

    protected $fillable = [
      'nombre',
    ];
}

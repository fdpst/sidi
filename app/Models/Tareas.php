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
      'id_usuario',
      'id_tipo_tarea'
    ];

    protected $dates = ['created_at'];

    protected $casts = [
      'created_at' => 'date:d-m-Y'
    ];

    public function empleado(){
      return $this->belongsTo(User::class, 'id_usuario');
    }

    public function tipoTarea(){
      return $this->belongsTo(TiposTarea::class, 'id_tipo_tarea');
    }

    public function proyecto(){
      return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}
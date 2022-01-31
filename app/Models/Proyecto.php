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
      'usuario_id',
      'servicio_id',
      'fecha_alta',
      'detalle_servicio',
      'estado_id',
      'pvp',
      'pvp_gasto',
      'porc_realizado',
      'detalles_gasto',
      'nombre'
    ];

    protected $casts = [
      'potencial' => 'boolean',
      'proyecto' => 'boolean',
    ];

    public function servicio(){
      return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function estado(){
      return $this->belongsTo(Estado::class, 'estado_id');
    }

    public function usuario(){
      return $this->belongsTo(User::class, 'usuario_id');
    }

    public function presupuesto(){
      return $this->hasOne(Presupuesto::class, 'id_proyecto');
    }

    public function archivos(){
      return $this->morphMany(Archivo::class, 'archivos');
    }

    public function estadosProyecto(){
      return $this->hasMany(EstadoProyecto::class, 'id_proyecto');
    }
}

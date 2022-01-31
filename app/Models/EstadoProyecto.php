<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoProyecto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'estados_proyecto';

    protected $fillable = [
        'id_proyecto',
        'descripcion',
        'fecha',
        'finalizado',
    ];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}

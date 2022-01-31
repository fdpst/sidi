<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presupuesto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'presupuestos';

    protected $fillable = [
        'id_proyecto',
        'descripcion',
        'file_name',
        'url',
    ];

    protected $casts = [
      'created_at' => 'datetime:Y-m-d H:m:i',
      'updated_at' => 'datetime:Y-m-d H:m:i'
    ];

    protected $appends = ['path'];

    public function getPathAttribute(){
      return asset("storage/presupuesto/{$this->file_name}");
    }

    public function proyecto(){
      return $this->belongsTo(Proyecto::class, 'id_proyecto');
    }
}

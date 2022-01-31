<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'facturas';

    protected $fillable = [
        'id_proyecto',
        'nro_factura',
        'file_name',
        'url',
        'total',
        'descuento',
        'fecha',
        'status_iva'
      ];

      public function items_factura(){
        return $this->hasMany(ItemsFactura::class, 'id_factura');
      }

      public function proyecto(){
        return $this->belongsTo(Proyecto::class, 'id_proyecto');
      }
}

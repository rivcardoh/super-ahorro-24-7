<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_venta';

    protected $primaryKey= 'id_detalle_venta';

    public $timestamps= false;

    protected $fillable= [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio_venta',
        'descuento'
    ];

    protected $guarded = [
        
    ];
}

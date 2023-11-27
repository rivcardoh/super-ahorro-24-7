<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;

    protected $table = 'persona';

    protected $primaryKey= 'id_persona';

    public $timestamps= false;

    protected $fillable= [
        'tipo_persona',
        'nombre',
        'ci',
        'direccion',
        'telefono',
        'email',
        'estado'
    ];

    protected $guarded = [
        
    ];
}

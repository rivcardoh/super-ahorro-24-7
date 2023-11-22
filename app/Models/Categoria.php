<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categoria';

    protected $primaryKey= 'id_categoria';

    public $timestamps= false;

    protected $fillable= [
        'categoria',
        'descripcion',
        'estado'
    ];

    protected $guarded = [
        
    ];
}

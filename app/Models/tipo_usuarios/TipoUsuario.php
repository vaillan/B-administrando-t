<?php

namespace App\Models\tipo_usuarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoUsuario extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipo_usuarios';

    protected $fillable = [
        'tipo_usuario',
        'nombre_tipo_usuario',
    ];
}

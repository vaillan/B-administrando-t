<?php

namespace App\Models\lista_articulos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\lista_articulos\Etiqueta;
use App\Models\lista_articulos\Categoria;

class ListaArticulo extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lista_articulos';

    protected $fillable = [
        'nombre_articulo',
        'categoria_id',
        'etiqueta_id',
        'usuario_id',
        'default',
    ];

    public function etiqueta()
    {
        return $this->hasOne(Etiqueta::class, 'id', 'etiqueta_id');
    }

    public function categoria()
    {
        return $this->hasOne(Categoria::class, 'id', 'categoria_id');
    }
}

<?php

namespace App\Models\egresos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gastos';

    protected $fillable = [
        'total',
        'lista_articulo_id',
        'created_by',
        'updated_by',
    ];
}

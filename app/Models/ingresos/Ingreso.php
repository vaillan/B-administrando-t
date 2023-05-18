<?php

namespace App\Models\ingresos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingreso extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ingresos';

    protected $fillable = [
        'ingreso',
        'tipo_ingreso_id',
        'plazo_id',
        'created_by',
        'updated_by',
    ];
}

<?php

namespace App\Models\ingresos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\plazos\plazo;
use App\Models\ingresos\TipoIngreso;
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

    public function plazo()
    {
        return $this->hasOne(Plazo::class, 'id', 'plazo_id');
    }

    public function tipoIngreso()
    {
        return $this->hasOne(TipoIngreso::class, 'id', 'tipo_ingreso_id');
    }
}

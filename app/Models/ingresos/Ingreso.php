<?php

namespace App\Models\ingresos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ingresos\TipoIngreso;
use App\Models\periodos\Periodo;

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
        'created_by',
        'updated_by',
    ];

    public function tipoIngreso()
    {
        return $this->hasOne(TipoIngreso::class, 'id', 'tipo_ingreso_id');
    }

    public function periodo()
    {
        return $this->hasOne(Periodo::class, 'ingreso_id');
    }
}

<?php

namespace App\Models\egresos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\egresos\Gasto;
use App\Models\reglas\ReglaAplicadaPresupuesto;
use Illuminate\Database\Eloquent\SoftDeletes;

class GastoReporte extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gastos_reporte';

    protected $fillable = [
        'total',
        'regla_aplicada_presupuesto_id',
        'gasto_id',
        'created_by',
        'updated_by',
    ];

    public function gasto() {
        return $this->hasOne(Gasto::class, 'id','gasto_id');
    }

    public function reglaAplicadaPresupuesto()
    {
        return $this->hasOne(ReglaAplicadaPresupuesto::class, 'id', 'regla_aplicada_presupuesto_id');
    }
}

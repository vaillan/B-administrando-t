<?php

namespace App\Models\reglas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\reglas\Regla;
use App\Models\egresos\GastoReporte;
class ReglaAplicadaPresupuesto extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'regla_aplicada_presupuesto';

    protected $fillable = [
        'regla_id',
        'presupuesto_id',
        'total',
        'created_by',
        'updated_by',
    ];

    public function regla()
    {
        return $this->hasOne(Regla::class, 'id', 'regla_id');
    }

    public function gastosReporte()
    {
        return $this->hasMany(GastoReporte::class, 'regla_aplicada_presupuesto_id');
    }
}

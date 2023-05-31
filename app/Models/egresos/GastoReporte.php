<?php

namespace App\Models\egresos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}

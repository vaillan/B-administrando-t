<?php

namespace App\Models\reglas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}

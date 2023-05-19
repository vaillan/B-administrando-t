<?php

namespace App\Models\presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ingresos\Ingreso;
class Presupuesto extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'presupuesto';

    protected $fillable = [
        'total',
        'usuario_id',
        'ingreso_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total' => 'float',
    ];

    public function ingreso()
    {
        return $this->hasOne(Ingreso::class, 'id', 'ingreso_id');
    }
}

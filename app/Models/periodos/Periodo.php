<?php

namespace App\Models\periodos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Periodo extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'periodos';

    protected $fillable = [
        'ingreso_id',
        'periodo',
        'gasto_id',
        'created_by',
        'updated_by',
    ];

    /**
     * Set the peridos in periodo
     *
     * @param string  $value
     * @return void
     */
    public function setPeriodoAttribute($value)
    {
        $this->attributes['periodo'] = Carbon::parse($value)->format('Y-m');
    }
}

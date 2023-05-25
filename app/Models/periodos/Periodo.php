<?php

namespace App\Models\periodos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'created_by',
        'updated_by',
    ];
}

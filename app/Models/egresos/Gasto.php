<?php

namespace App\Models\egresos;

use App\Models\lista_articulos\ListaArticulo;
use App\Models\periodos\Periodo;
use App\Models\reglas\ReglaAplicadaPresupuesto;
use App\Models\egresos\GastoReporte;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gasto extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gastos';

    protected $fillable = [
        'total',
        'lista_articulo_id',
        'created_by',
        'updated_by',
    ];

    public function periodo()
    {
        return $this->hasOne(Periodo::class, 'gasto_id');
    }

    public function articulo()
    {
        return $this->hasOne(ListaArticulo::class, 'id' ,'lista_articulo_id');
    }


    public function gastoReporte()
    {
        return $this->hasOne(GastoReporte::class, 'gasto_id');
    }

    public function reglaAplicadaPresupuesto()
    {
        return $this->hasOneThrough(
            ReglaAplicadaPresupuesto::class, 
            GastoReporte::class, 
            'gasto_id',
            'id',
            'id',
            'regla_aplicada_presupuesto_id'
        );
    }

}

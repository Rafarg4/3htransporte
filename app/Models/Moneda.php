<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Moneda
 * @package App\Models
 * @version September 24, 2026, 4:59 pm UTC
 *
 * @property string $tipo_moneda
 * @property string $monto
 */
class Moneda extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'monedas';

    const NOMBRES = [
        'PYG' => 'Guaraníes',
        'USD' => 'Dólares',
        'EUR' => 'Euros',
        'ARS' => 'Pesos Argentinos',
        'BRL' => 'Reales Brasileros',
    ];


    protected $dates = ['deleted_at'];



    public $fillable = [
        'tipo_moneda',
        'monto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tipo_moneda' => 'string',
        'monto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipo_moneda' => 'required|in:PYG,USD,EUR,ARS,BRL',
        'monto' => 'required'
    ];

    /**
     * Ultima cotizacion cargada de cada moneda extranjera (PYG queda afuera: es la moneda base
     * y ya aparece fija como "Guaranies" en los selects que usan esta lista).
     */
    public static function vigentes()
    {
        return static::where('tipo_moneda', '!=', 'PYG')->orderByDesc('id')->get()->unique('tipo_moneda')->values();
    }

    /**
     * Una sola fila por moneda (la ultima cargada), que es la que usa el resto del sistema.
     */
    public static function actuales()
    {
        return static::orderByDesc('id')->get()->unique('tipo_moneda')->sortBy('tipo_moneda')->values();
    }

    public function getNombreAttribute()
    {
        return self::NOMBRES[$this->tipo_moneda] ?? $this->tipo_moneda;
    }

    /**
     * Monto como numero: acepta "5780", "5.780", "5780,50" o "5.5" tal como se haya cargado.
     */
    public function getCotizacionAttribute()
    {
        $texto = trim((string) $this->monto);

        if (strpos($texto, ',') !== false) {
            $texto = str_replace(',', '.', str_replace('.', '', $texto));
        } elseif (preg_match('/^\d{1,3}(\.\d{3})+$/', $texto)) {
            $texto = str_replace('.', '', $texto);
        }

        return (float) $texto;
    }
}

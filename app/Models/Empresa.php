<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * Class Empresa
 *
 * @property int $id
 * @property string $nombre
 * @property string $correo
 * @property string $contra
 * @property string $ubicacion
 * @property string $telefono
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @property Candidatura[] $candidaturas
 * @property Vacante[] $vacantes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Empresa extends Model
{
    // Reglas de validación
    static $rules = [
        'nombre' => 'required',
        'correo' => 'required',
        'contra' => 'required',
    ];

    // Número de elementos por página en paginación
    protected $perPage = 20;

    // Atributos asignables en masa (opcional, pero recomendable si usas fill())
    protected $fillable = [
        'nombre',
        'correo',
        'contra',
        'ubicacion',
        'telefono',
    ];

    // Mutador para encriptar la contraseña automáticamente
    public function setContraAttribute($value)
    {
        // Solo encripta si la contraseña es distinta a la que ya está almacenada
        if (!empty($value) && Hash::needsRehash($value)) {
            $this->attributes['contra'] = Hash::make($value);
        } else {
            $this->attributes['contra'] = $value;
        }
    }

    // Relaciones (opcional si ya están en otro lado)
    public function candidaturas()
    {
        return $this->hasMany('App\Models\Candidatura', 'empresa_id', 'id');
    }

    public function vacantes()
    {
        return $this->hasMany(Vacante::class);
    }
}

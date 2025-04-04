<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Empresa
 *
 * @property $id
 * @property $nombre
 * @property $correo
 * @property $ubicacion
 * @property $telefono
 * @property $created_at
 * @property $updated_at
 *
 * @property Candidatura[] $candidaturas
 * @property Vacante[] $vacantes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Empresa extends Model
{
    
    static $rules = [
		'nombre' => 'required',
		'correo' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','correo','ubicacion','telefono'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidaturas()
    {
        return $this->hasMany('App\Models\Candidatura', 'empresa_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vacantes()
    {
        return $this->hasMany('App\Models\Vacante', 'empresa_id', 'id');
    }
    

}

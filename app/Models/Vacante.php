<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vacante
 *
 * @property $id
 * @property $titulo
 * @property $descripcion
 * @property $sueldo
 * @property $modalidad
 * @property $empresa_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Candidatura[] $candidaturas
 * @property Empresa $empresa
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Vacante extends Model
{
    
    static $rules = [
		'empresa_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['titulo','descripcion','sueldo','modalidad','empresa_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidaturas()
    {
        return $this->hasMany('App\Models\Candidatura', 'vacante_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empresa()
    {
        return $this->hasOne('App\Models\Empresa', 'id', 'empresa_id');
    }
    

}

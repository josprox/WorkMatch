<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Candidatura
 *
 * @property $id
 * @property $token_user
 * @property $empresa_id
 * @property $vacante_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Empresa $empresa
 * @property Usuario $usuario
 * @property Vacante $vacante
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Candidatura extends Model
{
    
    static $rules = [
		'token_user' => 'required',
		'empresa_id' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['token_user','empresa_id','vacante_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function empresa()
    {
        return $this->hasOne('App\Models\Empresa', 'id', 'empresa_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usuario()
    {
        return $this->hasOne('App\Models\Usuario', 'token_user', 'token_user');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vacante()
    {
        return $this->hasOne('App\Models\Vacante', 'id', 'vacante_id');
    }
    

}

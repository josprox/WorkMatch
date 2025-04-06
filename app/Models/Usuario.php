<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 *
 * @property $id
 * @property $token_user
 * @property $especialidades
 * @property $curriculum
 * @property $created_at
 * @property $updated_at
 *
 * @property Candidatura[] $candidaturas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Usuario extends Model
{
    
    static $rules = [
		'token_user' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['token_user','especialidades','curriculum'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function candidaturas()
    {
        return $this->hasMany('App\Models\Candidatura', 'token_user', 'token_user');
    }
    

}

<?php

namespace App;

use App\Model\DimFichaFuncionario;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table         = 'DIM_USUARIOS';
    protected $primaryKey    = 'matricula';
    protected $numTentativas = 4;

    public $incrementing     = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricula', 'nome', 'sobrenome', 'email', 'senha', 'cpf', 'data_nascimento', 'nome_mae', 'ativo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'senha', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->attributes['senha'];
    }

    public function getNumTentativas(){
        return $this->numTentativas;
    }

    public function getFuncionAttribute()
    {
        $FichaFunc = new DimFichaFuncionario();
        $vFuncion  = $FichaFunc->where('matricula', Auth()->user()->matricula)->select('funcion')->first();

        return $vFuncion->funcion;
    }

}
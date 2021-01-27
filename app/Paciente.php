<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Paciente extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'paciente';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rut',
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'email',
        'telefono',
        'celular',
        'isapre',
        'diff',
        'ciudad'
    ];

}

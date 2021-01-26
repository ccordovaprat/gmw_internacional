<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hora extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'hora';
    protected $primaryKey = 'idhora';
    protected $fillable = [
        'paciente',
        'hora_atencion',
        'prestador',
        'prestacion'
    ];

}

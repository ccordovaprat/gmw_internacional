<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ZonaHoraria extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'zona_horaria';
    protected $fillable = [
        'idzona',
        'codigoZona',
        'nombreZona'
    ];

}

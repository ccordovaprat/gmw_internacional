<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Ciudad extends Model
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'ciudad';
    protected $fillable = [
        'idciudad',
        'cod_pais',
        'ciudad'

    ];
}

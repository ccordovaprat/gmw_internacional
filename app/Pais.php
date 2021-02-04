<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pais extends Model
{
    use Notifiable;

    public $timestamps = false;
    protected $table = 'pais';
    protected $fillable = [
        'codigo',
        'pais'

    ];
}

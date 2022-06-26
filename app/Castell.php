<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Castell extends Model
{
    protected $table = 'castells';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $attributes = [
        'punts_carregat' => 0,
        'punts_descarregat' => 0,
        'estrena_intent' => NULL,
    ];
}

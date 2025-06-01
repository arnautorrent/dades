<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colla extends Model
{
    protected $table = 'colles';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $attributes = [
        'sobrenom' => NULL,
    ];

    public function diades(){
        return $this->belongsToMany(Diada::class, 'diades_colles','id_colla','id_diada');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diada extends Model
{
    protected $table = 'diades';
    protected $primaryKey = 'id';
    public $timestamps = false;

    //Valors per defecte:
    protected $attributes = [
        'millor_actuacio' => 0,
    ];

    //Altres camps:
    protected $fillable = ['data', 'diada', 'poblacio'];

    //funcions:
    public function colles(){
        return $this->belongsToMany(Diada::class,'diades_colles','id_diada','id_colla');
    }
}

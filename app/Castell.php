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
        'estrena_descarregat' => null,
        'estrena_placa' => null,
    ];

    protected $fillable = ['abreviatura','nom','punts_carregat','punts_descarregat','estrena_descarregat','estrena_placa'];

    public function diadesCastells(){
        return $this->hasMany(DiadesCastell::class,'castell','abreviatura');
    }
}

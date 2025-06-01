<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiadesCastell extends Model
{
    protected $table = 'diades_castells';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id_diada', 'castell', 'resultat', 'ronda'];

    /**
     * Relació amb la diada (cada castell pertany a una diada).
     */
    public function diada()
    {
        return $this->belongsTo(Diada::class, 'id_diada');
    }

    /**
     * Relació amb el castell (cada registre apunta a un castell per abreviatura).
     */
    public function castell()
    {
        return $this->belongsTo(Castell::class, 'castell', 'abreviatura');
    }
}

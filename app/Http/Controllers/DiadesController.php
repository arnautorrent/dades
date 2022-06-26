<?php

namespace App\Http\Controllers;
use App\Castell;
use App\Colla;
use App\Diada;
use Illuminate\Support\Facades\DB;


class DiadesController extends Controller
{
    public function index()
    {
        //agafar totes les dades
        $colles = Colla::get(['id', 'nom'])->sortBy('nom')->toArray();
        $castells = Castell::get()->sortBy('abreviatura')->pluck('abreviatura')->toArray();
        $diades = Diada::get()->toArray();
        foreach($diades as $key => $value){
            $diades[$key]['resultats'] = DB::table('diades_castells')->where('id_diada', $value['id'])->get()->toArray();
            $diades[$key]['colles'] = DB::table('diades_colles')->where('id_diada',$value['id'])->get()->toArray();
        }

        if (!empty($_REQUEST)) {
            //Mirar: Input::all()
            //Utilitzar try/catch
            //$.ajax amb jquery
            //FAIG UNA CERCA AMB ELS VALORS PASSATS A REQUEST I ELS AFEGEIXO A LA VISTA
            $id = $_REQUEST['colla'];
            $diades = Diada::leftJoin('diades_colles', 'diades.id', '=', 'diades_colles.id_diada')->where('id_colla', $id)->get()->toArray();
            //$diades_i_resultats = Diada::leftJoin('diades_castells','diades.id', '=', 'diades_castells.id_diada')->get()->toArray();
            return view('cercador')->with('castells', $castells)->with('colles', $colles)->with('diades', $diades);
        }
        else {
            return view('cercador')->with('castells', $castells)->with('colles', $colles);
        }
    }

    public function create()
    {
        $castells = Castell::get()->sortBy('abreviatura')->pluck('abreviatura');
        $colles = Colla::get(['id', 'nom'])->sortBy('nom')->toArray();

        return view('entrada')->with('castells', $castells)->with('colles', $colles);
    }

    public function store()
    {
        // 1. Guardo la diada
        $diada = Diada::create(
            [   'diada' => $_REQUEST['diada'],
                'data' => $_REQUEST['data'],
                'poblacio' => $_REQUEST['poblacio']
            ]);

        // 2. Guardo a diades_colles un registre per cada colla
        for ($i = 1; $i<9; $i++) {
            if (!empty($_REQUEST['colla' . $i])){
                DB::table('diades_colles')->insert(
                    [   'id_diada' => $diada['id'],
                        'id_colla' => $_REQUEST['colla' . $i]
                    ]);
            }
        }

        // 3. Guardo a diades_castells un registre per cada parella castell-resultat.
        for ($j = 1; $j<9; $j++){
            if(!empty($_REQUEST['castell' . $j])){
                DB::table('diades_castells')->insert(
                    [   'id_diada' => $diada['id'],
                        'castell' => $_REQUEST['castell' . $j],
                        'resultat' => $_REQUEST['resultat' . $j],
                        'ronda' => $j
                    ]);
            }
        }

        // Retorno missatge d'èxit
        //TODO: Retorno vista correcta
        return view('index');
    }

    public function cercaTotalCastell($any,$resultat,$castell,$data){
        $nombre = DB::table('diades')
            ->leftJoin('diades_castells','diades.id','=','diades_castells.id_diada')
            ->where('castell','=',$castell)
            ->where('resultat','=',$resultat)
            ->where('data','>',$any.'-01-01')
            ->where('data','<',$any.$data)
            ->count();
        return $nombre;
    }

    public function compTemp(){

        //1. Defineixo paràmetres de comparació
        if (!empty($_REQUEST)) {
            $any_comparar = $_REQUEST['temporada_comparar'];
            $any_referencia = $_REQUEST['temporada_referencia'];
            if(!empty($_REQUEST['castells'])){
                $castells = $_REQUEST['castells'];
            } else{
                $castells = array('3d7','4d7','pd5','2d6','3d6ps','5d6','7d6','4d6a','3d6a','3d6','4d6','pd4ps','pd4cam','pd4bal','pd4'); //Per defecte tots els castells
            }

            if($_REQUEST['data'] == "avui"){
                $data = date("-m-d");
            } else{
                $data = "-12-31";
            }

        } else{
            $castells = array('3d7','4d7','pd5','2d6','3d6ps','5d6','7d6','4d6a','3d6a','3d6','4d6','pd4ps','pd4cam','pd4bal','pd4'); //Per defecte tots els castells
            $any_comparar = date("Y"); //Per defecte compraro la temporada actual
            $any_referencia = date("Y") - 1; //Per defecte comparo amb la temporada anterior a la actual
            $data = date("-m-d"); //Per defecte comparo a dia d'avui
        }

        //2. Busco de cada castell els d, c, id, i de les dues temporades.
        foreach($castells as $castell){
            $temporada_comparar[$castell][0] = $this->cercaTotalCastell($any_comparar,'d',$castell,$data);
            $temporada_comparar[$castell][1] = $this->cercaTotalCastell($any_comparar,'c',$castell,$data);
            $temporada_comparar[$castell][2] = $this->cercaTotalCastell($any_comparar,'id',$castell,$data);
            $temporada_comparar[$castell][3] = $this->cercaTotalCastell($any_comparar,'i',$castell,$data);

            $temporada_referencia[$castell][0] = $this->cercaTotalCastell($any_referencia,'d',$castell,$data);
            $temporada_referencia[$castell][1] = $this->cercaTotalCastell($any_referencia,'c',$castell,$data);
            $temporada_referencia[$castell][2] = $this->cercaTotalCastell($any_referencia,'id',$castell,$data);
            $temporada_referencia[$castell][3] = $this->cercaTotalCastell($any_referencia,'i',$castell,$data);
        }

        //Assegurar-se que tinguin les mateixes files les dues variables.

        return view('comparador')->with('temporada_comparar', $temporada_comparar)->with('temporada_referencia', $temporada_referencia);
    }
}

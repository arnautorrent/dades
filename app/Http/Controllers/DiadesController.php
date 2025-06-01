<?php

namespace App\Http\Controllers;
use App\Castell;
use App\Colla;
use App\Diada;
use App\Http\Middleware\TrimStrings;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class DiadesController extends Controller
{
    public function index(Request $request)
    {
        $colles = Colla::get(['id', 'nom'])->sortBy('nom')->values()->all();

        // PAS 1: Crear la subconsulta interna (el 'sub' de SQL)
        $sub = DB::table('diades_castells')
            ->select(
                'id_diada',
                'ronda',
                'castell',
                'resultat',
                DB::raw('COUNT(*) as compta'),
                DB::raw("
                CASE
                    WHEN resultat = 'd' THEN castell
                    WHEN resultat = 'c' THEN CONCAT(castell, '(c)')
                    WHEN resultat = 'id' THEN CONCAT(castell, '(id)')
                    WHEN resultat = 'i' THEN CONCAT(castell, '(i)')
                    ELSE CONCAT(castell, '(', resultat, ')')
                END AS castell_sufix
            ")
            )
            ->groupBy('id_diada', 'ronda', 'castell', 'resultat');

        //TODO: AQUÍ PODRÍA APLICAR FILTRES DE CASTELL I RESULTAT


        // PAS 2: Crear la subconsulta que agrupa per diada i ronda, amb el GROUP_CONCAT
        $r = DB::table(DB::raw("({$sub->toSql()}) as sub"))
            ->mergeBindings($sub) // Important per passar els bindings
            ->select(
                'id_diada',
                'ronda',
                DB::raw("
                GROUP_CONCAT(
                    CASE
                        WHEN compta = 1 THEN castell_sufix
                        ELSE CONCAT(compta, castell_sufix)
                    END
                    SEPARATOR ' + '
                ) AS resultat_ronda
            ")
            )
            ->groupBy('id_diada', 'ronda');


        // PAS 3: Fer el join amb diades i agrupar per diada
        $result = DB::table('diades as d')
            ->leftJoin(DB::raw("({$r->toSql()}) as r"), 'd.id', '=', 'r.id_diada')
            ->mergeBindings($r) // Passar bindings també
            ->select(
                'd.id',
                'd.data',
                'd.diada',
                'd.poblacio',
                DB::raw("GROUP_CONCAT(r.resultat_ronda ORDER BY r.ronda SEPARATOR ' , ') AS resultats")
            )
            ->groupBy('d.id', 'd.data', 'd.diada', 'd.poblacio');
        //TODO: AQUÍ PODRÍA APLICAR FILTRES DE POBLACIÓ, DATA INICI, DATA FI I COLLA.

        //FILTRE DE COLLA:
        if($request->filled('colla')){
            $result->join('diades_colles as dc', 'dc.id_diada', '=', 'd.id')
                ->join('colles as c', 'c.id', '=', 'dc.id_colla')
                ->where('c.id', $request->colla);
        }

        $diades = $result->orderBy('d.data', 'desc')->get()->toArray();
        return view('cercador')->with('colles', $colles)->with('diades', $diades);//->with('castells', $castells)
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
        if (isset($_REQUEST["temporada_comparar"])) {
            $any_comparar = $_REQUEST['temporada_comparar'];
            $any_referencia = $_REQUEST['temporada_referencia'];
            if(!empty($_REQUEST['castells'])){
                $castells = $_REQUEST['castells'];
            } else{
                $castells = array('5d7','4d7a','3d7','4d7','9d6','pd5','2d6','3d6ps','5d6','7d6','4d6a','3d6a','3d6','4d6','pd4ps','pd4cam','pd4bal','pd4'); //Per defecte tots els castells
            }

            if($_REQUEST['data'] == "avui"){
                $data = date("-m-d");
            } else{
                $data = "-12-31";
            }

        } else{
            $castells = array('5d7','4d7a','3d7','4d7','9d6','pd5','2d6','3d6ps','5d6','7d6','4d6a','3d6a','3d6','4d6','pd4ps','pd4cam','pd4bal','pd4'); //Per defecte tots els castells
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

@extends('layouts.base')

@section('title', 'Comparador de temporades')

@section('content')

    <?//TODO: Posar bonic el formulari (possibilitat de canviar els select, radio i checkboxes a les dades actuals)?>
    <form action="{{ action('DiadesController@compTemp') }}" method="post">
    {{ csrf_field() }}
        <div class="columns">
            <div class="column field">
                <label class="label">Temporada a comparar</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="temporada_comparar">
                            <?php
                            for ($i = 2012; $i < 2024; $i++){
                                echo '<option>' + $i + '</option>'
                            }
                            ?>
                            <option>2012</option>
                            <option>2013</option>
                            <option>2014</option>
                            <option>2015</option>
                            <option>2016</option>
                            <option>2017</option>
                            <option>2018</option>
                            <option>2019</option>
                            <option>2020</option>
                            <option>2021</option>
                            <option>2022</option>
                            <option selected>2023</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="column field">
                <label class="label">Temporada de referència</label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="temporada_referencia">
                            <option>2012</option>
                            <option>2013</option>
                            <option>2014</option>
                            <option>2015</option>
                            <option>2016</option>
                            <option>2017</option>
                            <option>2018</option>
                            <option>2019</option>
                            <option>2020</option>
                            <option>2021</option>
                            <option selected>2022</option>
                            <option>2023</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="field column">
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="data" value="avui" checked> A dia d'avui
                    </label>
                    <label class="radio">
                        <input type="radio" name="data" value="final_any"> A final d'any
                    </label>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="field column">
                <div class="control">
                    <label class="checkbox">
                        <input type="checkbox" name="castells[]" value="3d7">3d7
                        <input type="checkbox" name="castells[]" value="4d7">4d7
                        <input type="checkbox" name="castells[]" value="pd5">pd5
                        <input type="checkbox" name="castells[]" value="2d6">2d6
                        <input type="checkbox" name="castells[]" value="3d6ps">3d6ps
                        <input type="checkbox" name="castells[]" value="5d6">5d6
                        <input type="checkbox" name="castells[]" value="7d6">7d6
                        <input type="checkbox" name="castells[]" value="4d6a">4d6a
                        <input type="checkbox" name="castells[]" value="3d6a">3d6a
                        <input type="checkbox" name="castells[]" value="3d6">3d6
                        <input type="checkbox" name="castells[]" value="4d6">4d6
                        <input type="checkbox" name="castells[]" value="pd4ps">pd4ps
                        <input type="checkbox" name="castells[]" value="pd4cam">pd4cam
                        <input type="checkbox" name="castells[]" value="pd4bal">pd4bal
                        <input type="checkbox" name="castells[]" value="pd4">pd4
                    </label>
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="field column">
                <div class="control">
                    <button type="submit" class="button is-fullwidth is-info">COMPARA</button>
                </div>
            </div>
        </div>
    </form>

    <!-- PINTO LES TAULES AMB ELS RESULTATS -->
    <div class="columns">
       <div class="column">
           <table class="table is-bordered is-hoverable is-fullwidth">
               <thead>
               <tr>
                   <th>Castell</th>
                   <th><abbr title="Descarregat">d</th>
                   <th><abbr title="Carregat">c</th>
                   <th><abbr title="Intent Desmuntat">id</th>
                   <th><abbr title="Intent">i</th>
               </tr>
               </thead>
               <tbody>
               <?php
                foreach($temporada_comparar as $key => $value){
                    echo    '<tr>';
                    echo        '<th>' . $key . '</th>';

                    //Descarregats Temporada Actual:
                    if($temporada_comparar[$key][0] > $temporada_referencia[$key][0]){
                        echo '<td class="millor">' . $value[0] . '</td>';
                    } elseif ($temporada_comparar[$key][0] < $temporada_referencia[$key][0]){
                        echo '<td class="pitjor">' . $value[0] . '</td>';
                    } else{
                        echo '<td class="base">' . $value[0] . '</td>';
                    }

                    //Carregats Temporada Actual:
                    if($temporada_comparar[$key][1] < $temporada_referencia[$key][1]){
                        echo '<td class="millor">' . $value[1] . '</td>';
                    } elseif ($temporada_comparar[$key][1] > $temporada_referencia[$key][1] && $temporada_referencia[$key][1] == 0){
                        echo '<td class="millor-pero">' . $value[1] . '</td>';
                    } elseif ($temporada_comparar[$key][1] > $temporada_referencia[$key][1] && $temporada_referencia[$key][1] !== 0){
                        echo '<td class="pitjor">' . $value[1] . '</td>';
                    }  else{
                        echo '<td class="base">' . $value[1] . '</td>';
                    }

                    //Intents Desmuntats Temporada Actual:
                    if($temporada_comparar[$key][2] < $temporada_referencia[$key][2]){
                        echo '<td class="millor">' . $value[2] . '</td>';
                    } elseif ($temporada_comparar[$key][2] > $temporada_referencia[$key][2]){
                        echo '<td class="pitjor">' . $value[2] . '</td>';
                    }  else{
                        echo '<td class="base">' . $value[2] . '</td>';
                    }

                    //Intents Temporada Actual:
                    if($temporada_comparar[$key][3] < $temporada_referencia[$key][3]){
                        echo '<td class="millor">' . $value[3] . '</td>';
                    } elseif ($temporada_comparar[$key][3] > $temporada_referencia[$key][3]){
                        echo '<td class="pitjor">' . $value[3] . '</td>';
                    }  else{
                        echo '<td class="base">' . $value[3] . '</td>';
                    }

                    echo    '</tr>';
                }
                ?>
               </tbody>
           </table>
       </div>

       <div class="column">
           <table class="table is-bordered is-hoverable is-fullwidth comparacio-castells">
               <thead>
               <tr>
                   <th>Castell</th>
                   <th><abbr title="Descarregat"/>d</th>
                   <th><abbr title="Carregat"/>c</th>
                   <th><abbr title="Intent Desmuntat"/>id</th>
                   <th><abbr title="Intent"/>i</th>
               </tr>
               </thead>
               <tbody>
               <?php
               foreach($temporada_referencia as $key => $value){
                   echo    '<tr>';
                   echo        '<th>' . $key . '</th>';
                   echo        '<td class="">' . $value[0] . '</td>';
                   echo        '<td class="">' . $value[1] . '</td>';
                   echo        '<td class="">' . $value[2] . '</td>';
                   echo        '<td class="">' . $value[3] . '</td>';
                   echo    '</tr>';
               }
               ?>
               </tbody>
           </table>
       </div>
    </div>
@endsection

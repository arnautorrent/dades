@extends('layouts.base')

@section('title', 'Nova Diada')

@section('content')

    <h1 class="title">ENTRADA RESULTATS DIADA</h1>

        <form action="{{ action('DiadesController@store') }}" method="post">
            {{ csrf_field() }}

            {{--NOM DIADA--}}
            <div class="field">
                <label for="diada" class="label">Diada:</label>
                <div class="control">
                    <input name="diada" type="text" class="input">
                </div>
            </div>

            {{--POBLACIÓ--}}
            <div class="field">
                <label for="poblacio" class="label">Població:</label>
                <div class="control">
                    <input name="poblacio" type="text" class="input">
                </div>
            </div>

            {{--DATA--}}
            <div class="field">
                <label for="data" class="label">Data:</label>
                <div class="control">
                    <input name="data" type="text" class="input" placeholder="aaaa-mm-dd">
                </div>
            </div>

            {{--MILLOR ACTUACIÓ--}}
            <div class="field">
                <label class="label">Millor actuació històrica?</label>
                <div class="control">
                    <label class="radio">
                        <input type="radio" name="millor_actuacio" value="1">
                        Sí
                    </label>
                    <label class="radio">
                        <input type="radio" name="millor_actuacio" value="0">
                        No
                    </label>
                </div>
            </div>

            {{--COLLES--}}
            <?php
            for ($i = 1; $i<9; $i++){
                echo '<fieldset id="colla' . $i . '" class="field is-hidden">';
                echo    '<div class="field">';
                echo        '<label for="Colla ' . $i . '" class="label">Colla ' . $i . ':</label>';
                echo        '<div class="control">';
                echo            '<div class="select is-fullwidth">';
                echo                '<select name="colla' . $i .'" onchange="showNewSelect()">';
                echo                            '<option value=""></option>';
                foreach ($colles as $colla)
                {
                    echo                            '<option value="' . $colla['id'] . '">' . $colla['nom'] . '</option>';
                }
                echo                 '</select>';
                echo            '</div>';
                echo        '</div>';
                echo    '</div>';
                echo '</fieldset>';
            }
            ?>

            {{--CASTELLS--}}
            <?php
            for ($j = 1; $j<9; $j++){
                echo '<fieldset id="ronda' . $j . '" class="field is-hidden">';
                echo    '<div class ="columns">';
                echo        '<div class="column">';
                echo            '<div class="field">';
                echo                '<label for="Castell ' . $j . '" class="label">Castell ' . $j . ':</label>';
                echo                '<div class="control">';
                echo                    '<div class="select is-fullwidth">';
                echo                        '<select name="castell' . $j . '">';
                echo                            '<option value=""></option>';
                foreach ($castells as $castell)
                {
                echo                            '<option value="' . $castell . '">' . $castell . '</option>';
                }
                echo                        '</select>';
                echo                    '</div>';
                echo                '</div>';
                echo            '</div>';
                echo        '</div>';
                echo        '<div class="column">';
                echo            '<div class="field">';
                echo                '<label for="Resultat ' . $j . '" class="label">Resultat ' . $j . ':</label>';
                echo                '<div class="control">';
                echo                    '<div class="select is-fullwidth">';
                echo                        '<select name="resultat' . $j . '" onchange="showNewSelect()">';
                echo                            '<option value=""></option>';
                echo                            '<option value="d">d</option>';
                echo                            '<option value="c">c</option>';
                echo                            '<option value="i">i</option>';
                echo                            '<option value="id">id</option>';
                echo                        '</select>';
                echo                    '</div>';
                echo                '</div>';
                echo            '</div>';
                echo        '</div>';
                echo    '</div>';
                echo '</fieldset>';
            }
            ?>

            {{--ENVIAR--}}
            <div class="field">
                <div class="control">
                    <button type="submit" class="button is-info">Afegeix</button>
                </div>
            </div>
        </form>

        <script>
            document.getElementById('ronda1').classList.remove('is-hidden');
            document.getElementById('colla1').classList.remove('is-hidden');
            function showNewSelect(){
                var num = this.event.target.closest('fieldset').id.slice(-1);
                var item = this.event.target.closest('fieldset').id.slice(0,-1);
                num = parseInt(num) + 1;
                new_element = item + num;
                document.getElementById(new_element).classList.remove('is-hidden');
            }
            //TODO: Afegir una funció per eliminar colles i castells?
            //TODO: Afegir una funció per escriure i autocompletar colles i castells.
        </script>
@endsection

@extends('layouts.base')

@section('title', 'Menú')

@section('content')

    <h1 class="title">FILTRE</h1>

    <?//TODO: Data, castell, resultat, lloc, ...?>
    <form action="{{ action('DiadesController@index') }}" method="post">
    {{ csrf_field() }}

    {{--COLLA--}}
        <fieldset id="colla" class="field">
            <div class="field">
                <label for="Colla" class="label"></label>
                <div class="control">
                    <div class="select is-fullwidth">
                        <select name="colla">
                            <option value=""></option>
                            <?php
                            foreach ($colles as $colla)
                                {
                                    echo '<option value="' . $colla['id'] . '">' . $colla['nom'] . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        {{--ENVIAR--}}
        <div class="field">
            <div class="control">
                <button type="submit" class="button is-info">Cerca</button>
            </div>
        </div>
    </form>

    <?php
    if (isset($resultats))
    { ?>
    <h1 class="title">RESULTATS</h1>
    <table class="table">
        <tr>
            <th class="th">Data</th>
            <th class="th">Diada</th>
            <th class="th">Població</th>
            <th class="th">Resultats</th>
        </tr>
        <?php
        foreach ($resultats as $resultat)
        {
            echo '<tr>';
            echo    '<td>' . $resultat['data'] . '</td>';
            echo    '<td>' . $resultat['diada'] . '</td>';
            echo    '<td>' . $resultat['poblacio'] . '</td>';
            echo    '<td>';
            foreach($resultat['resultats'] as $ronda){ echo $ronda->castell . '(' . $ronda->resultat . '), ';}
            echo '</td>';
            echo '</tr>';
        }
        ?>
    </table>
    <?php
    }
    else
    {

    }
    ?>
@endsection

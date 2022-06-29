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
                            <?
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

    <?
    if (isset($diades))
    { ?>
    <h1 class="title">RESULTATS</h1>
    <table class="table">
        <tr>
            <th class="th">Data</th>
            <th class="th">Diada</th>
            <th class="th">Població</th>
            <th class="th">Resultats</th>
        </tr>
        <?
        foreach ($diades as $diada)
        {
            echo '<tr>';
            echo    '<td>' . $diada['data'] . '</td>';
            echo    '<td>' . $diada['diada'] . '</td>';
            echo    '<td>' . $diada['poblacio'] . '</td>';
            echo    '<td>' . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
    <?
    }
    else
    {

    }
    ?>
@endsection

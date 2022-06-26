<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.css">
    <link rel="stylesheet" href="{{URL::asset('css/custom.css')}}">

</head>
<body>

@include('layouts.menu')

<section class="section">
    <div class="container">
        @yield('content')
    </div>
</section>

</body>
</html>

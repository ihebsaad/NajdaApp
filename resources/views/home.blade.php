@extends('layouts.mainlayout')

@section('content')

    <div class="row">
        <br><br>
        <h1> Bienvenue</h1>
<?php
setlocale(LC_ALL, 'nl_NL');

/* Affiche : vrijdag 22 december 1978 */
echo strftime("%A %e %B %Y", mktime(0, 0, 0, 12, 22, 1978));

?>

    </div>

@endsection


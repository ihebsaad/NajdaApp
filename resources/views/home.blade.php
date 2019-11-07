@extends('layouts.mainlayout')

@section('content')

    <div class="row">
        <br><br>
        <h1>Bienvenue.</h1>

        <p style="">Commencez par traiter une notification, sélectionner un dossier, effectuer une recherche ou consulter votre boite email ...
         <?php   $actual_link = "http://$_SERVER[HTTP_HOST]/najdaapp"; ?>
            <?php echo 'URL :'.$actual_link; ?>
        </p>

    </div>

@endsection
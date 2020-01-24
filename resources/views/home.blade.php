@extends('layouts.mainlayout')

@section('content')

    <div class="row">
        <br><br>
        <h1>Bienvenue</h1>

        <?php
        use App\Dossier;
        use App\Http\Controllers\Controller;
        use App\Seance;
        use App\User;
        use App\Notif;
        use App\Mission;
        use App\ActionEC;
        use Illuminate\Foundation\Auth\AuthenticatesUsers;

        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Log;


            $user = auth()->user();
            $iduser = $user->id;

            $seance = Seance::first();
            $medic = $seance->superviseurmedic;
            $tech = $seance->superviseurtech;
            $charge = $seance->chargetransport;
            $dispatcheur = $seance->dispatcheur;
            $veilleur = $seance->veilleur;
            $debut = $seance->debut;
            $fin = $seance->fin;
            $date_actu = date("H:i");

            if ($date_actu < $debut || ($date_actu > $fin)) {
                 echo 'heure de nuit (test)';
            }else{
                echo 'heure de jour (test) ';

        }

            ?>


    </div>

@endsection
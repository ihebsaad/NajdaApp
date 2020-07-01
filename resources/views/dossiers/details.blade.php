@extends('layouts.adminlayout')
<?php 
use App\User ; 
use App\Prestataire ;
use App\Template_doc ; 
use App\Document ; 
use App\Client;
use App\ClientGroupe;
use App\Adresse;
use App\Mission;
use App\Facture;

?>
<?php use \App\Http\Controllers\PrestationsController;
     use  \App\Http\Controllers\PrestatairesController;
     use  \App\Http\Controllers\ClientsController;
use  \App\Http\Controllers\DossiersController ;
use  \App\Http\Controllers\EnvoyesController ;
use  \App\Http\Controllers\EntreesController ;
use \App\Http\Controllers\UsersController;



 $missions=UsersController::countmissionsDossier($id);
 $actions=UsersController::countactionsDossier($id);
 $notifications=UsersController::countnotifsDossier($id);

?>							 
@section('content')

@endsection

<style>
 
  .actived {
  background-color: #ffd051!important;color:red;
}
</style>
<?php if (isset( $dossier)){
  $currentdoss=$dossier->id ;
}else{$currentdoss=0;}

  use \App\Http\Controllers\DossiersController;
$dossiers= DossiersController:: ListeDossiers();


?>
@isset ($dossiers)
<div class="row folders" style="margin-top:20px;">
        <div class="carousel-wrap">
          <div class="owl-carousel">
            @foreach ($dossiers as $i) 
            <div class="item">
                <a class="dossieritem" href="{{url('dossiers/view/'.$i->id )}}" id="{{ $i->id }}" >
                    <div class="dossiercr well well-gc well-sm <?php if($i->id ==$currentdoss){echo 'actived';}?>  " >
                        <h3 class="cutlongtext" style="font-size:20px!important">{{ $i->reference_medic }}</h3>
                        <p class="cutlongtext" style="font-size:70%"> {!!$i->subscriber_name!!} <br>
                        {!!$i->subscriber_lastname!!}</p>
                    </div>
                </a>
            </div>
            @endforeach
          </div>
        </div>
    </div>
@endisset
<style>
 
  .actived {
  background-color: #ffd051!important;color:red;
}
</style>
<?php if (isset( $dossier)){
  $currentdoss=$dossier->id ;
}else{$currentdoss=0;}

 if (isset( $doss)){
    $currentdoss=$doss ;}

  use \App\Http\Controllers\DossiersController;
$dossiersaff= DossiersController:: ListeDossiersAffecte();
//print_r($dossiers);

?>
@isset ($dossiersaff)
<div class="row folders" style="margin-top:20px;min-height:120px">
        <div class="carousel-wrap">
          <div class="owl-carousel">
            @foreach ($dossiersaff as $i) 
            <div class="item">
                <a class="dossieritem" href="{{url('dossiers/view/'.$i->id )}}" id="{{ $i->id }}" >
                    <div style="padding-left:5px!important;padding-top:5px!important; " class="dossiercr well well-gc well-md <?php if($i->id ==$currentdoss){echo 'actived';}?>  " >
                        <p class="cutlongtext" style="font-size:17px"> {!!$i->subscriber_name!!} <br>
                        {!!$i->subscriber_lastname!!}</p>
                        <h3 class="cutlongtext" style="font-size:20px!important">{{ $i->reference_medic }}</h3>
                    </div>
                </a>
            </div>
            @endforeach
          </div>
        </div>
    </div>
@endisset
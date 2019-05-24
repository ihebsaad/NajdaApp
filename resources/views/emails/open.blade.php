@extends('layouts.mainlayout')

@section('content')
    <div class="form-group">
        {{ csrf_field() }}
        <label for="emetteur">emetteur:</label>
        {{$oMessage->getSender()}}

</div>
<div class="form-group">
  <label for="sujet">sujet :</label>
  <?php $sujet= $oMessage->getSubject() ;
  echo utf8_encode($sujet) ;?>
    </div>
    <div class="form-group">
        <label for="contenu">contenu:</label>
        <?php $contenu=  $oMessage->getHTMLBody(true);
        echo utf8_encode($contenu) ;
        ?>

        <style>
            .invoice{background-color: khaki;padding:5px 5px 5px 5px;}
            label{font-weight:bold;}
        </style>

    </div>
    <div class="form-group">

        <label for="contenu">Attachements:</label>
        <?php

        $aAttachment = $oMessage->getAttachments();
        $aAttachment->each(function ($oAttachment) {
            echo $oAttachment->getContent();
        });

        ?>
    </div>
    <div class="form-group">
        <label for="date">date:</label>
        <?php echo  date('d/m/Y', strtotime($oMessage->getDate())) ; ?>

    </div>
@endsection
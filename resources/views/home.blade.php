@extends('layouts.mainlayout')

@section('content')

    <div class="row">
        <br><br>
        <h1> Bienvenue</h1>
    </div>
     <?php   if ($type == 'financier' || $type == 'bureau' || $type == 'admin' ) {   ?>
            <div class="row  pull-right">
             <div class="col-sm-2">
            <a href="{{ route('factures') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span  class="fas fa-lg fa-file-invoice"></span>
                <br>
            Factures
            </a>
              </div>
            </div>

    <div class="row ">



    <div class="col-md-12">
        <div class="card  ">
            <div class="card-header bg-danger " >
                <h3 class="card-title" style="color:white;padding:10px 10px 10px 10px">
                    <i class="fas    fa-warning"></i> Notifications financiers     </h3>

            </div>

                    <div class="form-group">
                        <table class="table table-striped">
                         <thead>
                         <tr id="headtable">
                             <th style="width:15%">Date</th><th style="width:35%">Dossier</th><th style="width:15%">Statut</th><th style="width:20%">Facturé</th><th style="width:10%">Suppression</th>
                         </tr>
                         </thead>
                            <tbody>
                       <?php foreach ($alertes as $alerte){
                       $dossier=  \App\Dossier::where('id',$alerte->id_dossier)->first();
                       $abn= $dossier->subscriber_name. ' '.$dossier->subscriber_lastname ;
                       $statut= $alerte->statut;
                       $datea= date('d/m/Y H:i', strtotime($alerte->created_at)) ;
                       $facture= $alerte->facture; //if($facture!=1){$fact='<label style="color:white;padding:10px 10px 10px 10px" class="bg-danger">Non Facturé';}else{$fact='<label style="color:white;padding:10px 10px 10px 10px" class="bg-success">Facturé</label>';}
                       if($statut=='reouverture'){$stat='Re-Ouverture';}
                       if($statut=='ferme'){$stat='Fermeture';}
                       if($statut=='sanssuite'){$stat='Fermeture <small>Sans Suite</small>';}
                        ?>
                       <tr><td style="width:15%"><?php   echo $datea; ?></td> <td style="width:35%"><?php echo $alerte->ref_dossier . ' '.$abn; ?> </td><td style="width:15%"><?php echo $stat; ?> </td><td style="width:20%">

                                     <div class="radio" id="uniform-actif">
                           <label><span class="checked">
                            <input  class="actus-<?php echo $alerte->id;?>"  type="checkbox"    id="actus-<?php echo $alerte->id;?>"    <?php if ($facture ==1){echo 'checked'; }  ?>  onclick="changing(this,'<?php echo $alerte->id; ?>' );"      >
                        </span> Facturé</label>
                                     </div>
                            </td><td style="width:15%;text-align: center">
                                <a onclick="return confirm('Êtes-vous sûrs de voulir supprimer cette notification ?')"  href="{{action('HomeController@destroy', $alerte['id'])}}" class="btn btn-sm btn-danger btn-responsive" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  data-original-title="Supprimer">
                                    <i class="fa fa-lg fa-fw fa-trash-alt"></i>
                                </a>

                            </td></tr>
                        <?php } ?>
                            </tbody> </table>
                    </div>



    </div>

    </div>


    </div>
    <?php   }
         ?>


    <script>

        function changing(elm,actus) {
            var champ=elm.id;

            var val =document.getElementById('actus-'+actus).checked==1;

            if (val==true){val=1;}
            else{val=0;}
            //if ( (val != '')) {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('home.updating') }}",
                method: "POST",
                data: {actus:actus , champ:champ ,val:val, _token: _token},
                success: function (data) {
                    $('.actus-'+actus).animate({
                        opacity: '0.3',
                    });
                    $('.actus-'+actus).animate({
                        opacity: '1',
                    });

                }
            });
            // } else {

            // }
        }


    </script>

@endsection


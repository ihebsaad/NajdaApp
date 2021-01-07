 <?php 
use App\Http\Controllers\DossiersController;


use App\Dossier ;
use App\Attachement ;
 $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;

?>
 <?php use \App\Http\Controllers\ClientsController;     ?>

 <!--select css-->
    <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title">Email</h4>
                        <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>
                    </div>
 


  <div class="panel-body scrollable-panel" style="display: block;height:2050px">

  				<?php 


              function custom_echo($x, $length)
              {
                  if(strlen($x)<=$length)
                  {
                      return $x;
                  }
                  else
                  {
                      $y=substr($x,0,$length) . '..';
                      return $y;
                  }
              }
			  
				  function convertToHoursMins($time, $format = '%02d:%02d') {
                  if ($time < 1) {
                      return;
                  }
                  $hours = floor($time / 60);
                  $minutes = ($time % 60);
                  return sprintf($format, $hours, $minutes);
              }

			  function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'année',
        'm' => 'mois',
        'w' => 'semaine',
        'd' => 'jour',
        'h' => 'heure',
        'i' => 'minute',
     //   's' => 'seconde',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ' : 'maintenant';
}

				$type=$entree['type'];
									$time=$entree['created_at'];$heure= "<small>Il y'a ".time_elapsed_string($time, false).'</small>';
								//	$emetteur= $entree['emetteur'] ;
									$emetteur=custom_echo($entree['emetteur'],'18');
								//	$sujet= $entree['sujet'] ;
									$sujet=custom_echo($entree['sujet'],'20');  
 									$attachs=$entree['nb_attach'];
				 
									?>
  <div class="agent" id="agent-<?php echo $entree->id;?>"   >
    <div class="form-group pull-left">
        <?php if ($type=='email'){echo '<img width="15" src="'. $urlapp .'/public/img/email.png" />';} ?><?php if ($type=='fax'){echo '<img width="15" src="'. $urlapp .'/public/img/faxx.png" />';} ?><?php if ($type=='sms'){echo '<img width="15" src="'. $urlapp .'/public/img/smss.png" />';} ?> <?php if ($type=='phone'){echo '<img width="15" src="'. $urlapp .'/public/img/tel.png" />';} ?> <?php // echo $entree['type']; ?>
 	   </div>

                            <div class="form-group pull-right">
                                <label for="date">Date:</label>
                                <label> <?php echo  date('d/m/Y H:i', strtotime($entree->reception)) ; ?></label>
                            </div><br>

                        <div class="form-group">
                        <label for="emetteur">Emetteur:</label>
                       <input id="emetteur" type="text" class="form-control" name="emetteur"  value="<?php echo $entree->emetteur ?>" />
                   </div>
                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <input style="overflow:scroll;" id="sujet" type="text" class="form-control" name="sujet"  value="<?php echo  ($entree->sujet);?>"  />

                </div>
                <div class="form-group">
                    <label for="contenu" id="contenulabel" style="cursor:pointer">Contenu:</label>
                    <section><div    id="lecontenu" class="form-control" style=" <?php if($entree->type=='fax'){echo 'display:none';}?>;  overflow:scroll;min-height:400px">

                        <?php

                        if($entree['contenu']!= null)
                        {$content= nl2br($entree['contenu']) ;}else{
                        $content= nl2br($entree['contenutxt']);
                        }
                        echo ($content);  ?>
                    </div>
                    </section>
                    @if ($entree['nb_attach']  > 0)
                        <?php
                        // get attachements info from DB
                        $attachs = Attachement::get()->where('parent', '=', $entree['id'] )->where('entree_id', '=', $entree['id'] );

                        ?>
                        @if (!empty($attachs) )
                            <?php $i=1; ?>
                            @foreach ($attachs as $att)

                              <?php if ( ($att->type =="pdf") ||($att->type =="png") ||($att->type =="jpg") || ($att->type =="jpeg") || ($att->type =="gif")||($att->type =="bmp")        )
                                            { ?>
                                <div class="tab-pane fade in <?php  if ( ($entree['type']=='fax')&&($i==1)) {echo 'active';}?>" id="pj<?php echo $i; ?>">
                                    Pièce jointe N°: <?php echo $i; ?>
                                    <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a style="font-size: 13px;" href="<?php if($att->type =="pdf"){if($att->path_org){ echo URL::asset('storage'.$att->path_org);}else{echo URL::asset('storage'.$att->path);} }else{ echo URL::asset('storage'.$att->path); }?>" download>Télécharger</a>)</h4>

                                    @switch($att->type)
                                    @case('docx')
                                    @case('doc')
                                    @case('dot')
                                    @case('dotx')
                                    @case('docm')
                                    @case('odt')
                                    @case('pot')
                                    @case('potm')
                                    @case('pps')
                                    @case('ppsm')
                                    @case('ppt')
                                    @case('pptm')
                                    @case('pptx')
                                    @case('ppsx')
                                    @case('odp')
                                    @case('xls')
                                    @case('xlsx')
                                    @case('xlsm')
                                    @case('xlsb')
                                    @case('ods')
                                    @case('wri')
                                    @case('602')
                                    @case('txt')
                                    @case('sdw')
                                    @case('sgl')
                                    @case('wpd')
                                    @case('vor')
                                    @case('wps')
                                    @case('html')
                                    @case('htm')
                                    @case('jdt')
                                    @case('jtt')
                                    @case('hwp')
                                    @case('pdb')
                                    @case('pages')
                                    @case('cwk')
                                    @case('rtf')
                                    @case('gnumeric')
                                    @case('numbers')
                                    @case('dif')
                                    @case('gnm')
                                    @case('wk1')
                                    @case('wks')
                                    @case('123')
                                    @case('wk3')
                                    @case('wk4')
                                    @case('xlw')
                                    @case('xlt')
                                    @case('wk3')
                                    @case('pxl')
                                    @case('wb2')
                                    @case('wq1')
                                    @case('wq2')
                                    @case('sdc')
                                    @case('vor')
                                    @case('slk')
                                    @case('wk3')
                                    @case('xlts')
                                    @case('svg')
                                    @case('odg')
                                    @case('odp')
                                    @case('kth')
                                    @case('key')
                                    @case('pcd')
                                    @case('sda')
                                    @case('sdd')
                                    @case('sdp')
                                    @case('potx')


                                    
                                    @break

                                    @case('pdf')
                                    <?php

                                    $fact=$att->facturation;
                                    if ($fact!='')
                                    {
                                        echo '<span class="pdfnotice"> Ce document contient le(s) mots important(s) suivant(s) : <b>'.$fact.'</b></span>';
                                    }

                                    ?>

                                    <iframe src="{{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                    @break

                                    @case('jpg')
                                    @case('jpeg')
                                    @case('gif')
                                    @case('png')
                                    @case('bmp')
                                    <img src="{{ URL::asset('storage'.$att->path) }}" class="mx-auto d-block" style="max-width: 100%!important;">
                                    @break

                                    @default
                                    <span>Type de fichier non reconnu ... </span>
                                    @endswitch

                                </div>
                                <?php $i++; }?>
                            @endforeach

                        @endif

                    @endif
                </div>


      </div>


  </div>
					
       </div>

 <script>


     $('#contenulabel').on('click',   function() {

         var   div=document.getElementById('lecontenu');
          if(div.style.display==='none')
         {
             div.style.display='block';
          }
         else
         {
             div.style.display='none';
          }

     });
  
</script>

 <style>

 
     </style>

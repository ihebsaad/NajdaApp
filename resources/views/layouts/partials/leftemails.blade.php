
<?php
use App\Entree ;
?>
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify.css') }}">
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify-bootstrap.css') }}">
<style>
    .dropbtn {
        background-color: #4CAF50;
        color: white;
        padding: 12px;
        font-size: 12px;
        border: none;
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #f1f1f1}

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>
<?php  $urlapp=env('APP_URL');

if (App::environment('local')) {
    // The environment is local
    $urlapp='http://localhost/najdaapp';
}
?>

<div class="panel panel-default"  >
    <div class="panel-heading" >
        <h4 class="panel-title">Emails non dispatchés</h4>
        <span class="pull-right">
         <i class="fa fa-fw clickable fa-chevron-up"></i>
            </span>
    </div>
    <div  class="panel-body" style="display: block;">
<ul>
         <?php $entrees = Entree::orderBy('id', 'desc')
            ->where('statut','<','2')
            ->where('dossier','=','')
            ->paginate(10000000);   
			$c=0;
			foreach($entrees as $entree)
			{$c++;
			if(($c % 2 )==0){$bg='background-color:#EDEDE9';}else{$bg='background-color:#F9F9F8';}
			 $type=$entree['type']; 
			 echo '<li  class="overme" style=";padding-left:6px;margin-bottom:25px;'.$bg.'" >';

			  if ($type=='email'){echo '<img width="15" src="'. $urlapp .'/public/img/email.png" />';} ?><?php if ($type=='fax'){echo '<img width="15" src="'. $urlapp .'/public/img/faxx.png" />';} ?><?php if ($type=='sms'){echo '<img width="15" src="'. $urlapp .'/public/img/smss.png" />';} ?> <?php if ($type=='phone'){echo '<img width="15" src="'. $urlapp .'/public/img/tel.png" />';} ?> <?php // echo $entree['type']; ?>
			<label style="font-size:11px"><?php  echo $entree['emetteur'] . '</label><br>';  ?>
    <a <?php if($entree['viewed']==false) {echo 'style="color:#337085!important;font-weight:800;font-size:16px;"' ;} ?>  href="{{action('EntreesController@show', $entree['id'])}}" ><small style="font-size:9px"><?php echo $entree['sujet'] ; ?></small></a>
			<?php echo '</li>';

			}
			
		 ?>
	</ul>
    </div>
</div><!--fin tab -->
 
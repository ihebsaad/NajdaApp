 <?php 
use App\Http\Controllers\DossiersController;


use App\Dossier ;
?>
<!--select css-->
    <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title">Dossiers ouverts</h4>
                        <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>
                    </div>
 
        <div class="panel-body scrollable-panel" style="display: block;">
                    
                    <div class="panel-body" style="display: block;">
    
	   <?php $dossiers = Dossier::all();
	/*   Dossier::orderBy('id', 'desc')
            ->where('statut','=','');
		*/	
 			$c=0;
			foreach($dossiers as $dossier)
			{$c++;
			if(($c % 2 )==0){$bg='background-color:#EDEDE9';}else{$bg='background-color:#F9F9F8';}
			 echo '<li  class="overme" style=";padding-left:6px;margin-bottom:25px;'.$bg.'" >';
			echo $dossier['reference_medic'];
			echo '</li>';			
			}
			?>
                    </div>
					
       </div>
</div>

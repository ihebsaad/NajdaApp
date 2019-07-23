 <?php 
use App\Http\Controllers\DossiersController;


use App\Dossier ;
?>
 <?php use \App\Http\Controllers\ClientsController;     ?>

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
 
        <div class="panel-body " style="display: block;padding-top:10px;padding-left:10px;padding-right:10px;">
            <div class="row">
                <div class="col-sm-4"> <input class="search" type="text" id="myInput" onkeyup="Searchf()" placeholder="N° Dossier.." title="Taper"></div>
                <div class="col-sm-4"><input  class="search" type="text" id="myInput2" onkeyup="Searchf2()" placeholder="Abonné.." title="Taper"></div>
                <div class="col-sm-4"> <input class="search" type="text" id="myInput3" onkeyup="Searchf3()" placeholder="Client.." title="Taper"></div>
            </div>

            <div class="panel-body scrollable-panel" style="display: block;">


                        <ul style="list-style: none" id="myUL">
	   <?php $dossiers = Dossier::all();
	/*   Dossier::orderBy('id', 'desc')
            ->where('statut','=','');
		*/	
 			$c=0;
			foreach($dossiers as $dossier)
			{$c++; if($dossier->affecte==''){$styled=';color:red;';}else{$styled=';color:black;';}
			if(($c % 2 )==0){$bg='background-color:#EDEDE9';}else{$bg='background-color:#F9F9F8';} $idd=$dossier['id'];$ref=$dossier['reference_medic'];$abn=$dossier['subscriber_lastname'].' '.$dossier['subscriber_name'];$idclient=$dossier['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;
        echo '<li  class="overme" style=";padding-left:6px;margin-bottom:15px;'.$bg.'" >';
        echo '<label   title="sélectionner ce dossier" onclick="selectFolder(this)" id="folder-'.$ref.'" style="width:80px;font-size:14px;cursor:pointer;font-weight:bold;'.$styled.'">'.$ref .'</label>'; ?><a style="margin-left:30px;margin-right:20px" title="fiche de dossier" href="{{action('DossiersController@fiche', $idd)}}" ><span class="fa fa-file"/></a><a title="détails de dossier" href="{{action('DossiersController@view', $idd)}}" ><span class="fa fa-folder-open"/></a>
               <br><small style="font-size:11px"><?php echo $abn;?></small>
               <br><small style="font-size:10px"><?php echo $client;?></small>

			<?php echo '</li>';
			}
			?>
    </ul>
                    </div>
					
       </div>
</div>
<script>
    function selectFolder(elm)
    {
        var idelm=elm.id;
        var ref=idelm.slice(7);

       var dossier=document.getElementById('affdoss').value=ref;
    }


    function Searchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function Searchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function Searchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

</script>

 <style>
     .search  {
         font-size: 13px;
         padding: 4px 4px 4px 10px;
         border: 1px solid #ddd;
         margin-bottom: 20px;
         margin-right:5px;
         width:100px;
     }


     </style>
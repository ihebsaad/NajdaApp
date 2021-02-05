<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__remor'])) {$prest__remor=$_GET['prest__remor'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname']; $subscriber_lastname2=$_GET['subscriber_lastname'];  }
if (isset($_GET['CL_date_heure_debut'])) {$CL_date_heure_debut=$_GET['CL_date_heure_debut'];}
if (isset($_GET['CL_date_heure_fin'])) {$CL_date_heure_fin=$_GET['CL_date_heure_fin'];}
if (isset($_GET['CL_montant_numerique'])) {$CL_montant_numerique=$_GET['CL_montant_numerique'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['agent__lastname'])) {$agent__lastname=$_GET['agent__lastname']; }
if (isset($_GET['agent__signature'])) {$agent__signature=$_GET['agent__signature']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['montantgop'])) {$montantgop=$_GET['montantgop'];}
if (isset($_GET['idtaggop'])) 
    {
        $idtaggop=$_GET['idtaggop']; 
    }
// Create connection
// read info from env file
$lines_array = file("../../.env");

foreach($lines_array as $line) {
    // username
    if(strpos($line, 'DB_USERNAME') !== false) {
        list(, $user) = explode("=", $line);
        $user = trim(preg_replace('/\s+/', ' ', $user));
        $user = str_replace(' ', '', $user);
    }
    // password
    if(strpos($line, 'DB_PASSWORD') !== false) {
        list(, $mdp) = explode("=", $line);
        $mdp = trim(preg_replace('/\s+/', ' ', $mdp));
        $mdp = str_replace(' ', '', $mdp);
    }
    // database
    if(strpos($line, 'DB_DATABASE') !== false) {
        list(, $dbname) = explode("=", $line);
        $dbname = trim(preg_replace('/\s+/', ' ', $dbname));
        $dbname = str_replace(' ', '', $dbname);
    }
    // hostname
    if(strpos($line, 'DB_HOST') !== false) {
        list(, $hostname) = explode("=", $line);
        $hostname = trim(preg_replace('/\s+/', ' ', $hostname));
        $hostname = str_replace(' ', '', $hostname);
    }
}
//echo $hostname.",".$user.",".$mdp.",".$dbname."<br>";
// Create connection
$conn = mysqli_connect($hostname, $user, $mdp,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn,"set names 'utf8'");

// recuperation des prestataires HOTEL ayant prestations dans dossier

$sqlvh = "SELECT id,name,civilite,prenom,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1 ) AND id IN (SELECT prestataire_id FROM specialites_prestataires WHERE specialite = 116)";

$resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"],"prenom" => $rowvh["prenom"],"civilite" => $rowvh["civilite"]  );
        }
// infos agent
	    $sqlagt = "SELECT name,lastname,signature FROM users WHERE id=".$iduser."";
		$resultagt = $conn->query($sqlagt);
		if ($resultagt->num_rows > 0) {
	    // output data of each row
	    $detailagt = $resultagt->fetch_assoc();
	    
		} else {
	    echo "0 results agent";
		}
$sqldos = "SELECT id,benefdiff,subscriber_name,subscriber_lastname,type_affectation FROM dossiers WHERE id=".$iddossier."";
        $resultdos = $conn->query($sqldos);
        if ($resultdos->num_rows > 0) {
        // output data of each row
        $detaildos = $resultdos->fetch_assoc();
        
        } else {
        echo "0 results agent";
        }
        $sqlbenef = "SELECT subscriber_name,beneficiaire,beneficiaire2,beneficiaire3,prenom_benef,prenom_benef2,prenom_benef3,subscriber_lastname FROM dossiers WHERE id=".$iddossier."";

    $resultbenef = $conn->query($sqlbenef);
    if ($resultbenef->num_rows > 0) {

        $array_benef = array();
        while($rowbenef = $resultbenef->fetch_assoc()) {
            $array_benef[] = array('beneficiaire' => $rowbenef["beneficiaire"] ,'prenom_benef' => $rowbenef["prenom_benef"] );
            if($rowbenef["beneficiaire2"]!==null)
            {
            $array_benef[] = array('beneficiaire' => $rowbenef["beneficiaire2"] ,'prenom_benef' => $rowbenef["prenom_benef2"]  );}
             if($rowbenef["beneficiaire3"]!==null)
            {
            $array_benef[] = array('beneficiaire' => $rowbenef["beneficiaire3"] ,'prenom_benef' => $rowbenef["prenom_benef3"]  );}
              $array_benef[] = array('beneficiaire' => $rowbenef["subscriber_name"]  ,'prenom_benef' => $rowbenef["subscriber_lastname"] );
            }}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_gardiennage</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <style type="text/css"><!--
        body {
            margin: 95px 95px 95px 95px;
            background-color: #ffffff;
        }
        /* ========== Text Styles ========== */
        hr { color: #000000}
        body, table, span.rvts0 /* Font Style */
        {
            font-size: 10pt;
            font-family: 'Times New Roman', 'Times', serif;
            font-style: normal;
            font-weight: normal;
            color: #000000;
            text-decoration: none;
        }
        span.rvts1
        {
            font-size: 12pt;
        }
        span.rvts2
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts3
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts4
        {
            font-size: 16pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts6
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts7
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts8
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts13
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        /* ========== Para Styles ========== */
        p,ul,ol /* Paragraph Style */
        {
            text-align: left;
            text-indent: 0px;
            widows: 2;
            orphans: 2;
            padding: 0px 0px 0px 0px;
            margin: 0px 0px 0px 0px;
        }
        .rvps1
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
        }
        .rvps2
        {
            text-align: justify;
            text-align-last: auto;
            text-indent: 0px;
            page-break-after: avoid;
            widows: 2;
            orphans: 2;
            padding: 0px 0px 0px 0px;
            margin: 0px 0px 0px 0px;
        }
        .rvps3
        {
            text-align: center;
            widows: 2;
            orphans: 2;
        }
        .rvps4
        {
            text-align: right;
            widows: 2;
            orphans: 2;
        }
        .rvps5
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.15;
            widows: 2;
            orphans: 2;
        }
        .rvps6
        {
            page-break-after: avoid;
            widows: 2;
            orphans: 2;
        }
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
    <input name="idtaggop" type="hidden" value="<?php if(isset ($idtaggop)) echo $idtaggop; ?>"></input>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2>
<select id="prest__remor" name="prest__remor" autocomplete="off" onchange="prestchange();" >
<?php
if(!empty($array_prest))
{$prest="prestselectfaux";
$prestid=0;
 echo '<option value="'.$prest.'" id="'.$prestid.'" >Veuillez sélectionner le prestataire</option>';
foreach ($array_prest as $prest) {
if(($prest['id'] === $id__prestataire)) {
    
    echo '<option value="'.$prest["civilite"].' '.$prest["prenom"].' '.$prest["name"].'" id="'.$prest["id"].'" selected >'.$prest["civilite"].' '.$prest["prenom"].' '.$prest["name"].'</option>';}
else {
    
      echo '<option value="'.$prest["civilite"].' '.$prest["prenom"].' '.$prest["name"].'" id="'.$prest["id"].'" >'.$prest["civilite"].' '.$prest["prenom"].' '.$prest["name"].'</option>';}
}}
?>
</select>

</span></p>
<p class=rvps1><span class=rvts2><input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps3><span class=rvts4>Prise en charge gardiennage</span></p>
<p class=rvps1><span class=rvts5><br></span></p>
<p class=rvps4><span class=rvts6>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6>Nous soussignés, </span><span class=rvts5>Najda Assistance</span><span class=rvts6>, nous engageons à prendre en charge les frais relatifs au gardiennage dans vos locaux du véhicule </span><span class=rvts5> </input> <input name="vehicule_marque" placeholder="marque du véhicule" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>">, </span><span class=rvts6>immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input> appartenant à notre client(e) Mr/Mme 

   <?php
if($detaildos['benefdiff']==='1')
{
?>
<select id="subscriber__lastname" name="subscriber__lastname" autocomplete="off"  >
            <?php

foreach ($array_benef as $benef) {
    if($benef['prenom_benef'] === $subscriber__lastname) {
    
    
    echo "<option value='".$benef['prenom_benef']."' selected >".$benef['prenom_benef']."</option>";}
    else{
       echo "<option value='".$benef['prenom_benef']."' >".$benef['prenom_benef']."</option>";  
    }
}
?>
</select> <select id="subscriber__name" name="subscriber__name" autocomplete="off"  >
            <?php
foreach ($array_benef as $benef) {
    if($benef['beneficiaire'] === $subscriber__name) {
    
    echo "<option value='".$benef['beneficiaire']."'  selected>".$benef['beneficiaire']."</option>";}
       else {
    
    echo "<option value='".$benef['beneficiaire']."' >".$benef['beneficiaire']."</option>";}
}
?>
</select>
<?php
}else
{
?>
<input id="subscriber__lastname"  name="subscriber__lastname" placeholder="nom du l'abonnée"  value="<?php  if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"/><input name="subscriber__name" id="subscriber__name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" /> 
<?php
}
?>


pour la période du </span><span class=rvts5><input name="CL_date_heure_debut" placeholder="Date Heure Debut" value="<?php if(isset ($CL_date_heure_debut)) echo $CL_date_heure_debut; ?>"></input></span><span class=rvts6> au  <input name="CL_date_heure_fin" placeholder="Date Heure fin" value="<?php if(isset ($CL_date_heure_fin)) echo $CL_date_heure_fin; ?>"></input> et pour le coût total de </span><span style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label><input name="CL_montant_numerique" placeholder="Montant"  value="<?php if(isset ($CL_montant_numerique)) echo $CL_montant_numerique; ?>" onKeyUp=" keyUpHandler(this)"></input></span><span class=rvts6>TND, (soit</span><span class=rvts5> </span><span class=rvts6><input name="CL_montant_toutes_lettres"  id="CL_montant_toutes_lettres" placeholder="Montant toutes lettres" value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input> dinars)</span><span class=rvts5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>
<p class=rvps5><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6>Merci de nous adresser votre facture originale dès que possible par courrier postal au nom de </span><span class=rvts5>Najda Assistance</span><span class=rvts6> à l</span><span class=rvts7>’</span><span class=rvts6>adresse ci-dessus en mentionnant notre référence de dossier <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"></input/> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" />   </span></p>
<p class=rvps1><span class=rvts5><br></span></p>
<p class=rvps1><span class=rvts5><br></span></p>
<p class=rvps1><span class=rvts8>ATTENTION IMPORTANT</span><span class=rvts9> </span></p>
<p class=rvps1><span class=rvts10><br></span></p>
<p class=rvps1><span class=rvts11>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts11>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts12>’</span><span class=rvts11>accompagnerait.</span></p>
<p class=rvps5><span class=rvts6><br></span></p>
<p class=rvps5><span class=rvts6>Merci pour votre collaboration.</span></p>
<p class=rvps5><span class=rvts6><br></span></p>
<p class=rvps5><span class=rvts6>Salutations Cordiales</span><span class=rvts5>.</span><span class=rvts6> </span></p>
<p class=rvps5><span class=rvts6><br></span></p>
<p class=rvps5><span class=rvts6>Pour la gérante</span></p>
<p class=rvps1><span class=rvts9> <input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /></span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p class=rvps1><span class=rvts6>Plateau d</span><span class=rvts7>’</span><span class=rvts6>assistance technique</span></p>
<p class=rvps1><span class=rvts6>« courrier électronique, sans signature »;</span></p>
<p class=rvps6><span class=rvts13><br></span></p>
</form>
<script language="javascript" src="nombre_en_lettre.js"></script>
<script type="text/javascript">
   function keyUpHandler(obj){
	<?php if (intval($montantgop) > 0) { ?>
            if (obj.value > <?php echo $montantgop; ?>) {document.getElementById("alertGOP").style.display="block"; obj.value="";document.getElementById("CL_montant_toutes_lettres").value  = "";}
            else {document.getElementById("alertGOP").style.display="none";}
	<?php } ?>
if (obj.value > 0)
            {document.getElementById("CL_montant_toutes_lettres").value  = NumberToLetter(obj.value) }

        }//fin de keypressHandler
// initialisation id prestataires
    
        var e = document.getElementById("prest__remor");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire").value = idpres;
 
    //changement de id prestataire lors changement select
    function prestchange() {
        //var optionSelected = $("option:selected", this);
        var e = document.getElementById("prest__remor");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire").value = idpres;
     }
</script>
</body></html>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>


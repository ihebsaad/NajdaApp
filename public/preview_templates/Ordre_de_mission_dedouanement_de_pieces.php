<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__transitaire'])) {$prest__transitaire=$_GET['prest__transitaire'];}
if (isset($_GET['id__prestataire1'])) {$id__prestataire1=$_GET['id__prestataire1'];}
if (isset($_GET['inter__garage'])) {$inter__garage=$_GET['inter__garage'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['CL_name'])) {$CL_name=$_GET['CL_name'];}
if (isset($_GET['CL_nlta'])) {$CL_nlta=$_GET['CL_nlta'];}
if (isset($_GET['CL_date_heure_ariv'])) {$CL_date_heure_ariv=$_GET['CL_date_heure_ariv'];}
if (isset($_GET['CL_cordonnes_vol'])) {$CL_cordonnes_vol=$_GET['CL_cordonnes_vol'];}
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name']; }
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['CL_cordonnes_gar'])) {$CL_cordonnes_gar=$_GET['CL_cordonnes_gar'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['agent__lastname'])) {$agent__lastname=$_GET['agent__lastname']; }
if (isset($_GET['agent__signature'])) {$agent__signature=$_GET['agent__signature']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
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

$sqlvh = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue=1) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 40)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],'name' => $rowvh["name"]  );
        } }
$sqlvha = "SELECT id,name,phone_home,ville,ville_id,id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue=1)  AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 22)";

    $resultvha = $conn->query($sqlvha);
    if ($resultvha->num_rows > 0) {

        $array_presta = array();
        while($rowvha = $resultvha->fetch_assoc()) {
            $array_presta[] = array('id' => $rowvha["id"],"name" => $rowvha["name"]  );
        } }
$sqlclient = "SELECT id,groupe,name FROM clients";
	$resultclient = $conn->query($sqlclient);
	if ($resultclient->num_rows > 0) {
	    // output data of each row
	    $array_client = array();
	    while($rowclient = $resultclient->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_client[] = array('id' => $rowclient["id"],'groupe' => $rowclient["groupe"],'name' => $rowclient["name"]);
	    }
	    //print_r($array_prest);
		}

$IMA="non";
foreach ($array_client as $client) {
	if ($client['name'] == $customer_id__name && $client['groupe'] == 3)
		{
	$IMA="oui";
}
}
// infos agent
	    $sqlagt = "SELECT name,lastname,signature FROM users WHERE id=".$iduser;
		$resultagt = $conn->query($sqlagt);
		if ($resultagt->num_rows > 0) {
	    // output data of each row
	    $detailagt = $resultagt->fetch_assoc();
	    
		} else {
	    echo "0 results agent";
		}
        $sqldos = "SELECT id,benefdiff,subscriber_name,subscriber_lastname FROM dossiers WHERE id=".$iddossier."";
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
<html><head><title>w5mvim5sot8rvi4n6hbsfpa25ahs9suk_Ordre_de_mission_d%C3%A9douanement_de_pi%C3%A8ces_R%C3%A9v</title>
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
            font-size: 18pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 18pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts6
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts7
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts8
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #ff0000;
        }
        span.rvts9
        {
            font-size: 12pt;
            font-family: 'Century Gothic';
        }
        span.rvts10
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #0070c0;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts12
        {
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
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.50;
            widows: 2;
            orphans: 2;
        }
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1> <input name="date_heure" type="hidden" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps3><span class=rvts4>Ordre de Mission Transitaire</span></p>
<p class=rvps3><span class=rvts4><br></span></p>
<p class=rvps3><span class=rvts5><br></span></p>
<p class=rvps4><span class=rvts2>Nous soussignés, </span><span class=rvts6>Najda Assistance</span><span class=rvts2>, société d</span><span class=rvts7>’</span><span class=rvts2>assistance correspondante de la compagnie 

<?php 
{if( $IMA==='oui' )
 {
?>

<input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" />

<?php 
}
 else
 {
?><input type=hidden name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="" /> 
<?php 
}
 }
?>
attestons par la présente que la société </span><span class=rvts8>
<select id="prest__transitaire" name="prest__transitaire" autocomplete="off" onchange="prestchange();" >
<?php
if(!empty($array_prest))
{
$prest="prestselectfaux";
$prestid=0;
 echo '<option value="'.$prest.'" id="'.$prestid.'" >Veuillez sélectionner le prestataire</option>';
foreach ($array_prest as $prest) {

if(($prest['id'] === $id__prestataire)) {
    
    echo '<option value="'.$prest["name"].'" id="'.$prest["id"].'" selected >'.$prest["name"].'</option>';}
else {
    
    echo '<option value="'.$prest["name"].'" id="'.$prest["id"].'">'.$prest["name"].'</option>';}
}}
?>
</select>
</span><span class=rvts2><input type="hidden" name="id__prestataire1" id="id__prestataire1"  value="<?php if(isset ($id__prestataire1)) echo $id__prestataire1; ?>"></input>&nbsp; représentée par son gérant Mr <input name="CL_name"  placeholder="name" value="<?php if(isset ($CL_name)) echo $CL_name; ?>" /> est chargée par nos soins de procéder au dédouanement de pièces sous le numéro de LTA  <input name="CL_nlta"  placeholder="NLTA" value="<?php if(isset ($CL_nlta)) echo $CL_nlta; ?>" /> et qui arrivera le <input name="CL_date_heure_ariv" placeholder="Date et heure" value="<?php if(isset ($CL_date_heure_ariv)) echo $CL_date_heure_ariv; ?>"></input> sur le vol <input name="CL_cordonnes_vol" placeholder="Cordonnes Vol" value="<?php if(isset ($CL_cordonnes_vol)) echo $CL_cordonnes_vol; ?>"></input> au nom de notre client(e) </span><span class=rvts9>


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
</select><select id="subscriber__name" name="subscriber__name" autocomplete="off"  >
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

<input id="subscriber__lastname"  name="subscriber__lastname" placeholder="nom du l'abonnée"  value="<?php  if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"/> <input name="subscriber__name" id="subscriber__name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" /> 

    <?php
}
?>




</span><span class=rvts2> </span><br><span class=rvts2>A noter que la pièce de rechange en question sera montée sur le véhicule de notre client(e) de marque  <input name="vehicule_marque" placeholder="marque du véhicule
" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input>  <input name="vehicule_type" placeholder="Type du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input>  immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input> en circulation temporaire en Tunisie et nécessitant la réparation avant son retour vers l</span><span class=rvts7>’</span><span class=rvts2>étranger. Le véhicule sera réparé au garage </span><span class=rvts8>
<select id="inter__garage" name="inter__garage" autocomplete="off" onchange="intgaragechange();" >
<?php
if(!empty($array_presta))
{$prest="prestselectfaux";
$prestid=0;
 echo '<option value="'.$prest.'" id="'.$prestid.'" >Veuillez sélectionner le prestataire</option>';
foreach ($array_presta as $presta) {

if(($presta['id'] === $id__prestataire1)) {
    
  echo '<option value="'.$presta["name"].'" id="'.$presta["id"].'" selected >'.$presta["name"].'</option>';}
else {
    
     echo '<option value="'.$presta["name"].'" id="'.$presta["id"].'">'.$presta["name"].'</option>';}
}
    }
   
?>
</select>


</span><span class=rvts2> <input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><input name="CL_cordonnes_gar" placeholder="Cordonnes Garage" value="<?php if(isset ($CL_cordonnes_gar)) echo $CL_cordonnes_gar; ?>"></input></span><span class=rvts10>.</span><span class=rvts2>&nbsp;&nbsp; </span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2>Cette procuration est délivrée pour servir et valoir ce que de droit.</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2>P/ Le Directeur des Opérations</span></p>
<p class=rvps1><span class=rvts9> <input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /></span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>

<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
</form>
<script type="text/javascript">
   var e = document.getElementById("prest__transitaire");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire").value = idpres;
 
    //changement de id prestataire lors changement select
    function prestchange() {
        //var optionSelected = $("option:selected", this);
        var e = document.getElementById("prest__transitaire");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire").value = idpres;
     }
   // initialisation id garage intervenant
 
    var e = document.getElementById("inter__garage");
    var idpres = e.options[e.selectedIndex].id;
    document.getElementById("id__prestataire1").value = idpres;

    //changement de id remorquage intervenant lors changement select
    function intgaragechange() {
        var e = document.getElementById("inter__garage");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire1").value = idpres;
     }



</script>
</body></html>



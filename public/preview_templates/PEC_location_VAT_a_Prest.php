<?php
if (isset($_GET['CL_text_client'])) {$CL_text_client=$_GET['CL_text_client'];}
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__location_voiture'])) {$prest__location_voiture=$_GET['prest__location_voiture'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name']; $subscriber_name2=$_GET['subscriber_name'];  }
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname'];$subscriber_lastname2=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell'];}
if (isset($_GET['CL_date_debut_location'])) {$CL_date_debut_location=$_GET['CL_date_debut_location'];}
if (isset($_GET['CL_heure'])) {$CL_heure=$_GET['CL_heure'];}
if (isset($_GET['CL_date_fin_location'])) {$CL_date_fin_location=$_GET['CL_date_fin_location'];}
if (isset($_GET['CL_duree_location'])) {$CL_duree_location=$_GET['CL_duree_location'];}
if (isset($_GET['CL_adresse_livraison'])) {$CL_adresse_livraison=$_GET['CL_adresse_livraison'];}
if (isset($_GET['CL_categorie'])) {$CL_categorie=$_GET['CL_categorie'];}
if (isset($_GET['CL_cout_location'])) {$CL_cout_location=$_GET['CL_cout_location'];}
if (isset($_GET['CL_cout_total'])) {$CL_cout_total=$_GET['CL_cout_total'];}
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

$sqlvh = "SELECT id,name,prenom,civilite,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 17)"; 

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"],"prenom" => $rowvh["prenom"],"civilite" => $rowvh["civilite"]   );
        } 
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
$sqltel = "SELECT champ,nom,prenom FROM adresses WHERE parent =".$iddossier." AND nature ='teldoss'";
    $resulttel = $conn->query($sqltel);
    if ($resulttel->num_rows > 0) {

        $array_tel = array();
        while($rowtel = $resulttel->fetch_assoc()) {
            $array_tel[] = array('id' => $rowtel["id"],'nom' => $rowtel["nom"] ,'prenom' => $rowtel["prenom"],'champ' => $rowtel["champ"]  );
        } }
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
<html><head><title>0w4wsrxpm0zuxc4sv6kwvhx97h9dw2n5_PEC_location_VAT_a_Prest</title>
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
        }
        span.rvts2
        {
            font-size: 12pt;
        }
        span.rvts3
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts4
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
        }
        span.rvts6
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts7
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts8
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #2e74b5;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #ff0000;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts18
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #0070c0;
        }
        span.rvts19
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts20
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts21
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts22
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts23
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts24
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
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
            widows: 2;
            orphans: 2;
        }
        .rvps3
        {
            text-align: right;
            widows: 2;
            orphans: 2;
        }
        .rvps4
        {
            text-align: center;
            widows: 2;
            orphans: 2;
        }
        .rvps5
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 4px 0px 0px 0px;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.15;
            widows: 2;
            orphans: 2;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 0px;
        }
        .rvps9
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 26px;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 0px;
        }
        .rvps10
        {
            text-align: center;
            widows: 2;
            orphans: 2;
        }
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<p><span class=rvts1><br></span></p>
<p><span class=rvts1><br></span></p>
<p><span class=rvts1><br></span></p>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2>
<select id="prest__location_voiture" name="prest__location_voiture" autocomplete="off" onchange="prestchange();" >
<?php
if(!empty($array_prest))
{$prest="prestselectfaux";
$prestid=0;
 echo '<option value="'.$prest.'" id="'.$prestid.'" >Veuillez sélectionner le prestataire</option>';
foreach ($array_prest as $prest) {
if(($prest['id'] === $id__prestataire)) {
    
     echo '<option value="'.$prest["civilite"].' '.$prest["name"].' '.$prest["prenom"].'" id="'.$prest["id"].'" selected >'.$prest["civilite"].' '.$prest["name"].' '.$prest["prenom"].'</option>';}
else {
    
   echo '<option value="'.$prest["civilite"].' '.$prest["name"].' '.$prest["prenom"].'" id="'.$prest["id"].'">'.$prest["civilite"].' '.$prest["name"].' '.$prest["prenom"].'</option>';}
}}
?>
</select>
</span></p>
<p class=rvps1><span class=rvts2><input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps3><span class=rvts3>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps4><span class=rvts4>PRISE EN CHARGE LOCATION DE VOITURE</span></p>
<p class=rvps4><span class=rvts5><br></span></p>
<?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text_client"  type="hidden" value="CLient : " />
<p><span class=rvts6>Client </span><span class=rvts7>: </span><span class=rvts6><input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>

<?php 
}
 else
 {
?>
<input name="CL_text_client"  type="hidden" value="" />
<p class=rvts6><span class=rvts7> <input  type="hidden" name="customer_id__name" id="customer_id__name" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?>
<?php
if($detaildos['benefdiff']==='1')
{
?>
<p class=rvps5><span class=rvts8>Nom Assuré : <select id="subscriber__name" name="subscriber__name" autocomplete="off"  >
            <?php
foreach ($array_benef as $benef) {
    if($benef['beneficiaire'] === $subscriber__name) {
    
    echo "<option value='".$benef['beneficiaire']."'  selected>".$benef['beneficiaire']."</option>";}
       else {
    
    echo "<option value='".$benef['beneficiaire']."' >".$benef['beneficiaire']."</option>";}
}
?>
</select> </span></p>
<p><span class=rvts6>Prénom : <select id="subscriber__lastname" name="subscriber__lastname" autocomplete="off"  >
            <?php

foreach ($array_benef as $benef) {
    if($benef['prenom_benef'] === $subscriber__lastname) {
    
    
    echo "<option value='".$benef['prenom_benef']."' selected >".$benef['prenom_benef']."</option>";}
    else{
       echo "<option value='".$benef['prenom_benef']."' >".$benef['prenom_benef']."</option>";  
    }
}
?>
</select> </span></p>
<?php
}else
{
?>
<p class=rvps5><span class=rvts8>Nom Assuré : <input name="subscriber__name" id="subscriber__name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" />  </span></p>
<p><span class=rvts6>Prénom : <input id="subscriber__lastname"  name="subscriber__lastname" placeholder="nom du l'abonnée"  value="<?php  if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"/> </span></p>
<?php
}
?>

<p><span class=rvts6>Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> |  <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"></input/> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" /> </span></p>
<p class=rvps6><span class=rvts9>Téléphone assuré :</span><span class=rvts8><input name="subscriber_phone_cell"list="subscriber_phone_cell" placeholder="num de téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell; ?>">
<datalist id="subscriber_phone_cell">
<?php

foreach ($array_tel as $tel) {
	
		echo "<option value='".$tel['champ']."'  >".$tel['prenom']." ".$tel['nom']." </option>";
 }

?>
</datalist>
</input></span></p>
<p class=rvps6><span class=rvts9>Date début de location:<input type="datetime-local" name="CL_date_debut_location" placeholder="Date Debut Location" value="<?php if(isset ($CL_date_debut_location)) echo $CL_date_debut_location; ?>" onKeyUp=" calculduree()"></input></span></p>RDV avec l</span><span class=rvts10>’</span><span class=rvts9>assuré: <input name="CL_heure" placeholder="Heure" value="<?php if(isset ($CL_heure)) echo $CL_heure; ?>"></input></span></p>
<p class=rvps6><span class=rvts9>Date fin de location: <input type="datetime-local" name="CL_date_fin_location"  id="CL_date_fin_location" placeholder="Date Fin Location" value="<?php if(isset ($CL_date_fin_location)) echo $CL_date_fin_location; ?>" onKeyUp=" calculduree()"></input></span></p>
<p class=rvps6><span class=rvts9>Durée de la location :</span><span class=rvts10> </span><span class=rvts11><input name="CL_duree_location"  id="CL_duree_location" placeholder="Duree Location" value="<?php if(isset ($CL_duree_location)) echo $CL_duree_location; ?>"  onchange=" keyUpHandler(this)"></input></span></p>
<p class=rvps6><span class=rvts9>Adresse de livraison :  <input name="CL_adresse_livraison" placeholder="Adresse Livraison" value="<?php if(isset ($CL_adresse_livraison)) echo $CL_adresse_livraison; ?>"></input> </span></p>
<p class=rvps6><span class=rvts9>Type véhicule : <input name="CL_categorie" placeholder="Categorie" value="<?php if(isset ($CL_categorie)) echo $CL_categorie; ?>"></input></span></p>
<p class=rvps6><span class=rvts9>Coût de location journalier : <input name="CL_cout_location" id="CL_cout_location" placeholder="Cout Location" value="<?php if(isset ($CL_cout_location)) echo $CL_cout_location; ?>"  onchange=" keyUpHandler(this)" onKeyUp=" keyUpHandler(this) " ></input></span></p>
<p class=rvps6><span class=rvts9>Coût total : </span><span  style="display:inline-block; "><input name="CL_cout_total" id="CL_cout_total" placeholder="Cout total Location" value="<?php if(isset ($CL_cout_total)) echo $CL_cout_total; ?>" onKeyUp=" montantverif(this) " ></input></span></p>
<p class=rvps5><span class=rvts13><br></span></p>
<p class=rvps7><span class=rvts13>Suite</span><span class=rvts14> </span><span class=rvts13>à</span><span class=rvts14> </span><span class=rvts13>notre</span><span class=rvts14> </span><span class=rvts13>entretien</span><span class=rvts14> </span><span class=rvts13>téléphonique,</span><span class=rvts14> </span><span class=rvts13>nous</span><span class=rvts14> </span><span class=rvts13>vous</span><span class=rvts14> </span><span class=rvts13>confirmons</span><span class=rvts14> </span><span class=rvts13>la</span><span class=rvts14> </span><span class=rvts13>prise</span><span class=rvts14> </span><span class=rvts13>en</span><span class=rvts14> </span><span class=rvts13>charge</span><span class=rvts14> </span><span class=rvts13>des</span><span class=rvts14> </span><span class=rvts13>frais</span><span class=rvts14> </span><span class=rvts13>de</span><span class=rvts14> </span><span class=rvts13>location</span><span class=rvts14> </span><span class=rvts13>d</span><span class=rvts15>’</span><span class=rvts13>une</span><span class=rvts14> </span><span class=rvts13>voiture</span><span class=rvts14> </span><span class=rvts13>tel</span><span class=rvts14> </span><span class=rvts13>que</span><span class=rvts14> </span><span class=rvts13>spécifié</span><span class=rvts14> </span><span class=rvts13>ci-dessus. </span></p>
<p class=rvps8><span class=rvts13><br></span></p>
<p class=rvps5><span class=rvts13>Merci de nous adresser votre facture originale dès que possible (dans un délai max de 30 jours) à l</span><span class=rvts15>’</span><span class=rvts13>adresse ci-dessus, en mentionnant notre référence de dossier.</span></p>
<p class=rvps5><span class=rvts13><br></span></p>
<p class=rvps9><span class=rvts16>Observations</span><span class=rvts17> : </span><span class=rvts13> <input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
<p class=rvps9><span class=rvts18><br></span></p>
<p><span class=rvts19>ATTENTION IMPORTANT</span><span class=rvts20> </span></p>
<p><span class=rvts21><br></span></p>
<p class=rvps1><span class=rvts22>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts22>Toute facture devra être envoyée accompagnée de la présente prise en charge</span><span class=rvts23>.</span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7>Merci de votre collaboration.</span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7>P/ la Gérante</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<span class=rvts7>Plateau d</span><span class=rvts24>’</span><span class=rvts7>assistance technique</span></p>
<p class=rvps1><span class=rvts7>« courrier électronique, sans signature »</span></p>
<p class=rvps10><span class=rvts3><br></span></p>
<script type="text/javascript">
    function keyUpHandler(obj){
        var coutloc=document.getElementById("CL_cout_location").value;
        var dureeloc=document.getElementById("CL_duree_location").value;
        document.getElementById("CL_cout_total").value = (parseInt(coutloc) * parseInt(dureeloc));
        document.getElementById("CL_cout_total").onchange();
        }//fin de keypressHandler

    function calculduree() {
        var inputdate1 = document.getElementsByName("CL_date_debut_location")[0].value;
        var inputdate2 = document.getElementById("CL_date_fin_location").value;
        var date1 = inputdate1.substring(0, 10);
        var date2 = inputdate2.substring(0, 10);

        /*if (date1.indexOf('/') > -1)
        {
            var date1Parts = date1.split("/"); }
        if (date1.indexOf('-') > -1)
        {
            var date1Parts = date1.split("-"); }
        if (date2.indexOf('/') > -1)
        {
            var date2Parts = date2.split("/"); }
        if (date2.indexOf('-') > -1)
        {
            var date2Parts = date2.split("-"); }

        // month is 0-based, that's why we need dataParts[1] - 1
        var datedeb = new Date(+date1Parts[2], date1Parts[1] - 1, +date1Parts[0]); 
        var datefin = new Date(+date2Parts[2], date2Parts[1] - 1, +date2Parts[0]); */
        var datedeb = new Date(date1);
        var datefin = new Date(date2);



        diffTime = Math.abs(datefin.getTime() - datedeb.getTime());
        diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
       // alert(diffDays);
        document.getElementById("CL_duree_location").value = diffDays;
        document.getElementById("CL_duree_location").onchange();
    }
// initialisation id prestataires
    
        var e = document.getElementById("prest__location_voiture");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire").value = idpres;
 
    //changement de id prestataire lors changement select
    function prestchange() {
        //var optionSelected = $("option:selected", this);
        var e = document.getElementById("prest__location_voiture");
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


<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__expertise'])) {$prest__expertise=$_GET['prest__expertise'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['CL_date_heure_debut'])) {$CL_date_heure_debut=$_GET['CL_date_heure_debut'];}
if (isset($_GET['CL_date_heure_fin'])) {$CL_date_heure_fin=$_GET['CL_date_heure_fin'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name'];$subscriber_name2=$_GET['subscriber_name'];$subscriber_name3=$_GET['subscriber_name'];   }
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname']; $subscriber_lastname2=$_GET['subscriber_lastname']; $subscriber_lastname3=$_GET['subscriber_lastname'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell']; }
if (isset($_GET['CL_lieu_localisation'])) {$CL_lieu_localisation=$_GET['CL_lieu_localisation'];}
if (isset($_GET['CL_type_expertise'])) {$CL_type_expertise=$_GET['CL_type_expertise'];}
if (isset($_GET['reference_customer'])) {$reference_customer=$_GET['reference_customer']; }
if (isset($_GET['CL_coordonner'])) {$CL_coordonner=$_GET['CL_coordonner'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['agent__lastname'])) {$agent__lastname=$_GET['agent__lastname']; }
if (isset($_GET['agent__signature'])) {$agent__signature=$_GET['agent__signature']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['montantgop'])) {$montantgop=$_GET['montantgop'];}
if (isset($_GET['idtaggop'])) 
    {
        $idtaggop=$_GET['idtaggop']; 
    }
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
$sqlvh = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1 ) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 23)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"]  );
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
<html><head><title>taml0bzv01qwdlqbdo97kjxixaeohm1q_PEC_expertise</title>
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
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #538135;
            background-color: #ffff00;
        }
        span.rvts5
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #538135;
            background-color: #ffff00;
        }
        span.rvts6
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #538135;
        }
        span.rvts7
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts8
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            letter-spacing: -2px;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts18
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts19
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts20
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            letter-spacing: -1px;
        }
        span.rvts21
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts22
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts23
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts24
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts25
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts26
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts27
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts28
        {
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts29
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts30
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
            margin: 5px 0px 0px 8px;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 0px;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 4px 0px 0px 8px;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 8px;
        }
        .rvps9
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 0px 0px 0px 8px;
        }
        .rvps10
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
        }
        .rvps11
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 8px 0px 0px 8px;
        }
        .rvps12
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.17;
            widows: 2;
            orphans: 2;
            margin: 0px 4px 0px 8px;
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
<select id="prest__expertise" name="prest__expertise" autocomplete="off" onchange="prestchange();" >
<?php
if(!empty($array_prest))
{$prest="prestselectfaux";
$prestid=0;
 echo '<option value="'.$prest.'" id="'.$prestid.'" >Veuillez sélectionner le prestataire</option>';
foreach ($array_prest as $prest) {
if(($prest['id'] === $id__prestataire)) {
    
    echo '<option value="'.$prest["name"].'" id="'.$prest["id"].'" selected >'.$prest["name"].'</option>';}
else {
    
    echo '<option value="'.$prest["name"].'" id="'.$prest["id"].'" >'.$prest["name"].'</option>';}
}}
?>
</select>
</span></p>
<p class=rvps1><span class=rvts2><input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<span class=rvts2>Sousse Le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts7>PRISE EN CHARGE EXPERTISE</span></p>
<p class=rvps4><span class=rvts8><br></span></p>
<p class=rvps5><span class=rvts9>Bonjour de Sousse,</span></p>
<p class=rvps6><span class=rvts9><br></span></p>
<p class=rvps7><span class=rvts9>Suite</span><span class=rvts10> </span><span class=rvts9>à</span><span class=rvts10> </span><span class=rvts9>notre</span><span class=rvts10> </span><span class=rvts9>conversation</span><span class=rvts10> </span><span class=rvts9>téléphonique,</span><span class=rvts11> </span><span class=rvts9>nous</span><span class=rvts10> </span><span class=rvts9>vous</span><span class=rvts10> </span><span class=rvts9>confirmons</span><span class=rvts10> </span><span class=rvts9>notre</span><span class=rvts10> </span><span class=rvts9>demande</span><span class=rvts10> </span><span class=rvts9>d</span><span class=rvts12>’</span><span class=rvts9>expertise</span><span class=rvts10> </span><span class=rvts9>du</span><span class=rvts10> </span><span class=rvts9>véhicule</span><span class=rvts10> </span><span class=rvts9>en</span><span class=rvts10> </span><span class=rvts9>date du</span><span class=rvts10> <input name="CL_date_heure_debut" placeholder="Date Heure Debut" value="<?php if(isset ($CL_date_heure_debut)) echo $CL_date_heure_debut; ?>"></input> à <input name="CL_date_heure_fin" placeholder="Date Heure fin" value="<?php if(isset ($CL_date_heure_fin)) echo $CL_date_heure_fin; ?>"></input></span><span class=rvts13>, selon </span><span class=rvts9>les informations ci-dessous relatives à ce dossier :</span></p>
<p class=rvps8><span class=rvts9><br></span></p>
 <?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text_client"  type="hidden" value="CLient : " />
<p class=rvps9><span class=rvts14>Client</span><span class=rvts9> : <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name;  ?>" /></span></p>
<?php 
}
 else
 {
?>
<input name="CL_text_client"  type="hidden" value="" />
<p class=rvps9><span class=rvts14> <input  type="hidden" name="customer_id__name" id="customer_id__name" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?>

<?php
if($detaildos['benefdiff']==='1')
{
?>
<p class=rvps9><span class=rvts15>Nom</span><span class=rvts16> </span><span class=rvts15>assuré</span><span class=rvts17> </span><span class=rvts13>:</span><span class=rvts17><select id="subscriber__name" name="subscriber__name" autocomplete="off"  >
            <?php
foreach ($array_benef as $benef) {
    if($benef['beneficiaire'] === $subscriber__name) {
    
    echo "<option value='".$benef['beneficiaire']."'  selected>".$benef['beneficiaire']."</option>";}
       else {
    
    echo "<option value='".$benef['beneficiaire']."' >".$benef['beneficiaire']."</option>";}
}
?>
</select> </span></p>
<p class=rvps9><span class=rvts15>Prénom</span><span class=rvts16> </span><span class=rvts15>assuré</span><span class=rvts17> </span><span class=rvts13>:</span><span class=rvts17> <select id="subscriber__lastname" name="subscriber__lastname" autocomplete="off"  >
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
<p class=rvps9><span class=rvts15>Nom</span><span class=rvts16> </span><span class=rvts15>assuré</span><span class=rvts17> </span><span class=rvts13>:</span><span class=rvts17><input name="subscriber__name" id="subscriber__name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" /></span></p>
<p class=rvps9><span class=rvts15>Prénom</span><span class=rvts16> </span><span class=rvts15>assuré</span><span class=rvts17> </span><span class=rvts13>:</span><span class=rvts17><input id="subscriber__lastname"  name="subscriber__lastname" placeholder="nom du l'abonnée"  value="<?php  if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"/></span></p>
<?php
}
?>
<p class=rvps9><span class=rvts15>Notre référence</span><span class=rvts13>: <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"></input/> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" />  </span></p>
<p class=rvps9><span class=rvts15>Type du véhicule</span><span class=rvts17> </span><span class=rvts13>: <input name="vehicule_marque" placeholder="marque du véhicule" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input> </span></p>
<p class=rvps9><span class=rvts15>Immatriculation</span><span class=rvts17> </span><span class=rvts13>: <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input></span></p>
<p class=rvps9><span class=rvts15>Contact abonné</span><span class=rvts17> </span><span class=rvts13>:<input name="subscriber_phone_cell" list="subscriber_phone_cell" placeholder="téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell;?>"/>
<datalist id="subscriber_phone_cell">
<?php

foreach ($array_tel as $tel) {
	
		echo "<option value='".$tel['champ']."'  >".$tel['prenom']." ".$tel['nom']." </option>";
 }

?>
</datalist>
</span></p>
<p class=rvps9><span class=rvts15>Localisation du véhicule</span><span class=rvts13> :<input name="CL_lieu_localisation" placeholder="Lieu Localisation" value="<?php if(isset ($CL_lieu_localisation)) echo $CL_lieu_localisation; ?>"></input></span></p>
<p class=rvps9><span class=rvts15>Type d</span><span class=rvts18>’</span><span class=rvts15>expertise</span><span class=rvts13> :<input name="CL_type_expertise" placeholder="Type Expertise" value="<?php if(isset ($CL_type_expertise)) echo $CL_type_expertise; ?>"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>

 <?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text_reference"  type="hidden" value="Reference client : " />
<span class=rvts15>Référence client</span><span class=rvts13>:  <input name="reference_customer" id="reference_customer"placeholder="reference client" value="<?php if(isset ($reference_customer)) echo $reference_customer; ?>"></input>  <input name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value="<?php if(isset ($customer_id__name2)) echo $customer_id__name2; ?>" /> </span></p>

<?php 
}
 else
 {
?>
<input name="CL_text_reference"  type="hidden" value="" />
<p class=rvps9><span class=rvts14> <input  type="hidden" name="reference_customer" id="reference_customer" placeholder="compagnie" value=" " /></span></p>
<p class=rvps9><span class=rvts14> <input  type="hidden" name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?>
<p class=rvps10><span class=rvts9><br></span></p>
<p class=rvps11><span class=rvts9>Merci de coordonner avec </span><span class=rvts13><input name="CL_coordonner" placeholder="Coordonner" value="<?php if(isset ($CL_coordonner)) echo $CL_coordonner; ?>"></input> pour convenir de l</span><span class=rvts19>’</span><span class=rvts13>heure du rendez-vous.</span></p>
<p class=rvps11><span class=rvts9><br></span></p>
<p class=rvps12><span class=rvts9>Nous,</span><span class=rvts10> </span><span class=rvts14>Najda</span><span class=rvts20> </span><span class=rvts14>Assistance</span><span class=rvts9>,</span><span class=rvts10> </span><span class=rvts9>nous</span><span class=rvts10> </span><span class=rvts9>engageons</span><span class=rvts10> </span><span class=rvts9>à</span><span class=rvts10> </span><span class=rvts9>prendre</span><span class=rvts10> </span><span class=rvts9>en</span><span class=rvts10> </span><span class=rvts9>charge</span><span class=rvts10> </span><span class=rvts9>les</span><span class=rvts10> </span><span class=rvts9>frais</span><span class=rvts10> </span><span class=rvts9>de</span><span class=rvts10> </span><span class=rvts9>cette expertise</span><span class=rvts10> </span><span class=rvts9>tel</span><span class=rvts10> </span><span class=rvts9>que</span><span class=rvts10> </span><span class=rvts9>spécifié</span><span class=rvts10> </span><span class=rvts9>ci-dessus.</span><span class=rvts10> </span><span class=rvts9>Merci</span><span class=rvts10> </span><span class=rvts9>de</span><span class=rvts10> </span><span class=rvts9>nous adresser</span><span class=rvts11> </span><span class=rvts9>votre</span><span class=rvts11> </span><span class=rvts9>facture</span><span class=rvts11> </span><span class=rvts9>originale</span><span class=rvts11> </span><span class=rvts9>accompagnée</span><span class=rvts11> </span><span class=rvts9>de</span><span class=rvts11> </span><span class=rvts9>l'original</span><span class=rvts11> </span><span class=rvts9>du</span><span class=rvts11> </span><span class=rvts9>rapport</span><span class=rvts11> </span><span class=rvts9>d'expertise</span><span class=rvts11> </span><span class=rvts9>(dans</span><span class=rvts11> </span><span class=rvts9>un</span><span class=rvts11> </span><span class=rvts9>délai</span><span class=rvts11> </span><span class=rvts9>max</span><span class=rvts11> </span><span class=rvts9>de</span><span class=rvts11> </span><span class=rvts9>15</span><span class=rvts11> </span><span class=rvts9>jours)</span><span class=rvts11> </span><span class=rvts9>à</span><span class=rvts11> </span><span class=rvts9>l'adresse</span><span class=rvts11> </span><span class=rvts9>ci-dessus, en mentionnant notre référence</span><span class=rvts10> </span><span class=rvts9>de dossier.</span></p>
<p><span class=rvts21><br></span></p>
<p><span class=rvts22>ATTENTION IMPORTANT</span><span class=rvts23> </span></p>
<p><span class=rvts24><br></span></p>
<p class=rvps1><span class=rvts25>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts25>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts26>’</span><span class=rvts25>accompagnerait</span><span class=rvts27>.</span></p>
<p><span class=rvts28><br></span></p>
<p><span class=rvts29>Merci de votre collaboration.</span></p>
<p><span class=rvts29><br></span></p>
<p><span class=rvts29>P/la Gérante</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p><span class=rvts29>Plateau d</span><span class=rvts30>’</span><span class=rvts29>assistance technique</span></p>
<p class=rvps1><span class=rvts29>« courrier électronique, sans signature »</span></p>
</form>
<script type="text/javascript">
// initialisation id prestataires
    
        var e = document.getElementById("prest__expertise");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire").value = idpres;
 
    //changement de id prestataire lors changement select
    function prestchange() {
        //var optionSelected = $("option:selected", this);
        var e = document.getElementById("prest__expertise");
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

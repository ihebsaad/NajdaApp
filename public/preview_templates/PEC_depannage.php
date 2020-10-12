<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['inter__remor'])) {$inter__remor=$_GET['inter__remor'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['CL_text_client'])) {$CL_text_client=$_GET['CL_text_client'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; $subscriber_lastname2=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['CL_date_heure_depannage'])) {$CL_date_heure_depannage=$_GET['CL_date_heure_depannage'];}
if (isset($_GET['CL_lieu_depannage'])) {$CL_lieu_depannage=$_GET['CL_lieu_depannage'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['agent__lastname'])) {$agent__lastname=$_GET['agent__lastname']; }
if (isset($_GET['agent__signature'])) {$agent__signature=$_GET['agent__signature']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['idtaggop'])) 
    {
        $idtaggop=$_GET['idtaggop']; } 
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
$sqlvh = "SELECT id,name,phone_home,prenom,civilite,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 1)";
    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
           $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"] ,"prenom" => $rowvh["prenom"],"civilite" => $rowvh["civilite"]  );
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
// infos agent
	    $sqlagt = "SELECT name,lastname,signature FROM users WHERE id=".$iduser."";
		$resultagt = $conn->query($sqlagt);
		if ($resultagt->num_rows > 0) {
	    // output data of each row
	    $detailagt = $resultagt->fetch_assoc();
	    
		} else {
	    echo "0 results agent";
		}
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>9zpxmcfurivwdy9m9da12ps9mhktyd7z_PEC_depannage</title>
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
            font-size: 9.5pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts8
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts9
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts10
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts11
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts13
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts18
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts19
        {
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
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
            margin: 5px 0px 0px 0px;
        }
        .rvps6
        {
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
<select id="inter__remor" name="inter__remor" autocomplete="off" onchange="intremorqchange();" >
<?php

foreach ($array_prest as $prest) {
if(($prest['id'] === $id__prestataire)) {
 echo '<option value="'.$prest["civilte"].''.$prest["prenom"].''.$prest["name"].'" id="'.$prest["id"].'" selected >'.$prest["civilite"].''.$prest["prenom"].''.$prest["name"].'</option>';   
  }
else {
    
    echo '<option value="'.$prest["civilte"].''.$prest["prenom"].''.$prest["name"].'" id="'.$prest["id"].'" >'.$prest["civilite"].''.$prest["prenom"].''.$prest["name"].'</option>';}
}
    
   
?>
</select>
</span></p>
<p class=rvps1><span class=rvts2><input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps3><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse le  <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input> </span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts4>PRISE EN CHARGE DEPANNAGE</span></p>
<p class=rvps4><span class=rvts5><br></span></p>
<?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text_client"  type="hidden" value="CLient : " />
 <p><span class=rvts6>Client </span><span class=rvts7>: <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>

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
<p class=rvps5><span class=rvts8>Nom Assuré</span><span class=rvts9> : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> </span></p>
<p><span class=rvts6>Prénom : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input>  </span></p>
<p><span class=rvts6>Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /> </span></p>
<p><span class=rvts6>Véhicule: <input name="vehicule_marque" placeholder="marque du véhicule" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input> </span></p>
<p><span class=rvts6>Immatriculation : </span><span class=rvts10><input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input></span></p>
<p><span class=rvts6>Date et heure du dépannage: </span><span class=rvts10><input name="CL_date_heure_depannage" placeholder="Date Heure Depannage" value="<?php if(isset ($CL_date_heure_depannage)) echo $CL_date_heure_depannage; ?>"></input></span><span class=rvts11> </span></p>
<p><span class=rvts6>Lieu du dépannage : </span><span class=rvts10><input name="CL_lieu_depannage" placeholder="Lieu Depannage" value="<?php if(isset ($CL_lieu_depannage)) echo $CL_lieu_depannage; ?>"></input></span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts2>Suite à notre entretien téléphonique, nous vous confirmons la prise en charge du dépannage du véhicule tel que spécifié ci- dessus.</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2>Merci de nous adresser votre facture originale dès que possible </span><span class=rvts10>(dans un délai de 15 jours) </span><span class=rvts2>à l</span><span class=rvts13>’</span><span class=rvts2>adresse ci-dessus, en mentionnant </span><span class=rvts10>notre référence de dossier ci-dessus.</span></p>
<p><span class=rvts6><br></span></p>
<p><span class=rvts14>ATTENTION IMPORTANT</span><span class=rvts15> </span></p>
<p><span class=rvts16><br></span></p>
<p><span class=rvts17>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p><span class=rvts17>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts18>’</span><span class=rvts17>accompagnerait</span><span class=rvts6>.</span></p>
<p><span class=rvts19><br></span></p>
<p><span class=rvts12>Merci de votre collaboration.</span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts2>P/ la Gérante</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname'] ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p><span class=rvts2>Plateau d</span><span class=rvts13>’</span><span class=rvts2>assistance technique</span></p>
<p class=rvps1><span class=rvts2>« courrier électronique, sans signature »</span></p>
<p><span class=rvts2><br></span></p>
<p class=rvps6><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
</body></html>
</form>
<script type="text/javascript">
// initialisation id remorquage intervenant
 
    var e = document.getElementById("inter__remor");
    var idpres = e.options[e.selectedIndex].id;
    document.getElementById("id__prestataire").value = idpres;

    //changement de id remorquage intervenant lors changement select
    function intremorqchange() {
        var e = document.getElementById("inter__remor");
        var idpres = e.options[e.selectedIndex].id;
        document.getElementById("id__prestataire").value = idpres;
     }
</script>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>


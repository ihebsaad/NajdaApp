<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['prest__transit'])) {$prest__transit=$_GET['prest__transit'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; $subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['CL_lieu_immobilisation'])) {$CL_lieu_immobilisation=$_GET['CL_lieu_immobilisation'];}
if (isset($_GET['CL_date_heure_prestation'])) {$CL_date_heure_prestation=$_GET['CL_date_heure_prestation'];}
if (isset($_GET['CL_lieu_dedouanement'])) {$CL_lieu_dedouanement=$_GET['CL_lieu_dedouanement'];}
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell'];}
if (isset($_GET['CL_cordonnes_vol'])) {$CL_cordonnes_vol=$_GET['CL_cordonnes_vol'];}
if (isset($_GET['CL_nlta'])) {$CL_nlta=$_GET['CL_nlta'];}
if (isset($_GET['CL_text'])) {$CL_text=$_GET['CL_text'];}
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

// recuperation des prestataires transitaire ayant prestations dans dossier

$sqlvh = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." ) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 40)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],'name' => $rowvh["name"]  );
        }

    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>e656tm04wv2pqh4fhyw85xsr6byto589_PEC_dedouanement_pieces</title>
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
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts4
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
        }
        span.rvts5
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts6
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts7
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #ff0000;
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
            color: #ff0000;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #0070c0;
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
        }
        span.rvts16
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #0070c0;
        }
        span.rvts17
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts18
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts19
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts20
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts21
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts22
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts23
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #ff0000;
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
            text-align: right;
            widows: 2;
            orphans: 2;
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
            margin: 1px 0px 0px 0px;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 26px;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 0px;
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
    <p class=rvps1><span class=rvts2>
<input type="text" list="prest__transit" name="prest__transit"  value="<?php if(isset ($prest__transit)) echo $prest__transit; ?>" />
        <datalist id="prest__transit">
            <?php
foreach ($array_prest as $prest) {
    
    echo "<option value='".$prest['name']."' >".$prest['name']."</option>";
}
?>
</span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps2><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input> </span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps3><span class=rvts3>PRISE EN CHARGE DEDOUANEMENT</span></p>
    <p class=rvps3><span class=rvts4><br></span></p>
    <p><span class=rvts5>Client </span><span class=rvts6>: [</span><span class=rvts7><input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
    <p class=rvps4><span class=rvts8>Nom Assuré : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> </span></p>
    <p><span class=rvts5>Prénom : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> </span></p>
    <p><span class=rvts5>Notre réf. dossier : </span><span class=rvts7><input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /> <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input></span></p>
    <p><span class=rvts5>Véhicule: <input name="vehicule_marque" placeholder="marque du véhicule" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input></span></p>
    <p><span class=rvts5>Immatriculation : <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input></span></p>
    <p class=rvps4><span class=rvts8>Lieu d</span><span class=rvts9>’</span><span class=rvts8>immobilisation du véhicule :</span><span class=rvts10> </span><span class=rvts11><input name="CL_lieu_immobilisation" placeholder="Lieu IMMObilisation" value="<?php if(isset ($CL_lieu_immobilisation)) echo $CL_lieu_immobilisation; ?>"></input></span></p>
    <p class=rvps4><span class=rvts8>Date de la prestation :</span><span class=rvts10> </span><span class=rvts11><input name="CL_date_heure_prestation" placeholder="Date Heure Prestation" value="<?php if(isset ($CL_date_heure_prestation)) echo $CL_date_heure_prestation; ?>"></input></span><span class=rvts12> (noter qu</span><span class=rvts13>’</span><span class=rvts12>elle servira pour les rappels)</span></p>
    <p class=rvps4><span class=rvts8>Lieu de dédouanement : <input name="CL_lieu_dedouanement" placeholder="Lieu Dedouanement" value="<?php if(isset ($CL_lieu_dedouanement)) echo $CL_lieu_dedouanement; ?>"></input></span></p>
    <p class=rvps4><span class=rvts8>Téléphone propriétaire : <input name="subscriber_phone_cell"id="subscriber_phone_cell" placeholder="num de téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell; ?>"></input></span></p>
    <p class=rvps4><span class=rvts8>Coordonnées de vol : </span><span class=rvts11><input name="CL_cordonnes_vol" placeholder="Cordonnes Vol" value="<?php if(isset ($CL_cordonnes_vol)) echo $CL_cordonnes_vol; ?>"></input></span></p>
    <p class=rvps4><span class=rvts8>N° LTA:</span><span class=rvts10> </span><span class=rvts11><input name="CL_nlta"  placeholder="NLTA" value="<?php if(isset ($CL_nlta)) echo $CL_nlta; ?>" /></span></p>
    <p class=rvps4><span class=rvts12><br></span></p>
    <p class=rvps4><span class=rvts12>Suite à notre entretien téléphonique, nous vous confirmons la prise en charge du dédouanement des pièces tel que spécifié ci-dessus.</span></p>
    <p class=rvps5><span class=rvts12><br></span></p>
    <p class=rvps4><span class=rvts12>Merci de nous adresser votre facture originale dès que possible (dans un délai max de 30 jours) à l</span><span class=rvts13>’</span><span class=rvts12>adresse ci-dessus, en mentionnant notre référence de dossier.</span></p>
    <p class=rvps4><span class=rvts12><br></span></p>
    <p class=rvps6><span class=rvts14>Observations</span><span class=rvts15> : <input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
    <p class=rvps6><span class=rvts16><br></span></p>
    <p><span class=rvts17>ATTENTION IMPORTANT</span><span class=rvts18> </span></p>
    <p><span class=rvts19><br></span></p>
    <p><span class=rvts20>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
    <p><span class=rvts20>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts21>’</span><span class=rvts20>accompagnerait</span><span class=rvts22>.</span></p>
    <p><span class=rvts6><br></span></p>
    <p><span class=rvts6>Merci de votre collaboration.</span></p>
    <p><span class=rvts6><br></span></p>
    <p><span class=rvts6><br></span></p>
    <p><span class=rvts6>P/ la Gérante</span></p>
	<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($agent__lastname)) echo $agent__lastname; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($agent__signature)) echo $agent__signature; ?>" /></span></p>
    <p><span class=rvts6>Plateau d</span><span class=rvts24>’</span><span class=rvts6>assistance technique</span></p>
    <p class=rvps1><span class=rvts6>« courrier électronique, sans signature »</span></p>
    <p><span class=rvts6><br></span></p>
    <p><span class=rvts6><br></span></p>
    <p><span class=rvts6><br></span></p>
    <p><span class=rvts2><br></span></p>
    <p><span class=rvts2><br></span></p>
    </body></html>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>

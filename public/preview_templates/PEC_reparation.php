<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__garage'])) {$prest__garage=$_GET['prest__garage'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['CL_facture_devis'])) {$CL_facture_devis=$_GET['CL_facture_devis'];}
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; $subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
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
//mysqli_query($conn,"set names 'utf8'");

// recuperation des prestataires HOTEL ayant prestations dans dossier

$sqlvh = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." ) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 22)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"]  );
        }
mysqli_query($conn,"set names 'utf8'");
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
<html xmlns="http://www.w3.org/1999/html"><head><title>PEC_reparation+</title>
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
}
span.rvts6
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 letter-spacing: -1px;
}
span.rvts7
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts8
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
 letter-spacing: -1px;
}
span.rvts9
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 letter-spacing: -2px;
}
span.rvts10
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts11
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 text-decoration: underline;
}
span.rvts12
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 font-weight: bold;
 text-decoration: underline;
}
span.rvts13
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 text-decoration: underline;
}
span.rvts14
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
}
span.rvts15
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 font-weight: bold;
}
span.rvts16
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 font-weight: bold;
}
span.rvts17
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts18
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts19
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 letter-spacing: -1px;
}
span.rvts20
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts21
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
 text-decoration: underline;
}
span.rvts22
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts23
{
    font-family: 'Tahoma', 'Geneva', sans-serif;
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
 margin: 0px 0px 8px 0px;
}
.rvps5
{
    text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 1.25;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 8px;
}
.rvps6
{
    text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 8px;
}
.rvps7
{
    text-indent: -8px;
 widows: 1;
 orphans: 1;
 margin: 0px 0px 0px 39px;
}
.rvps8
{
    widows: 1;
    orphans: 1;
    margin: 0px 0px 0px 39px;
}
.rvps9
{
    widows: 1;
    orphans: 1;
    margin: 0px 21px 0px 31px;
}
/* ========== Lists ========== */
.list0 {text-indent: 0px; padding: 0; margin: 0 0 0 24px; list-style-position: outside; list-style-type: disc;}
.list1 {text-indent: 0px; padding: 0; margin: 0 0 0 118px; list-style-position: outside;}
.list2 {text-indent: 0px; padding: 0; margin: 0 0 0 8px; list-style-position: outside; list-style-type: upper-alpha;}
.list3 {text-indent: 0px; padding: 0; margin: 0 0 0 31px; list-style-position: outside; list-style-type: disc;}
.list4 {text-indent: 0px; padding: 0; margin: 0 0 0 11px; list-style-position: outside; list-style-type: disc;}
.list5 {text-indent: 0px; padding: 0; margin: 0 0 0 20px; list-style-position: outside; list-style-type: disc;}
.list6 {text-indent: 0px; padding: 0; margin: 0 0 0 19px; list-style-position: outside; list-style-type: disc;}
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
<p class=rvps1><span class=rvts2>  <!--<input name="prest__garage" style="width:300px" placeholder="Prestataire Garage" value="<?php if(isset ($prest__garage)) echo $prest__garage; ?>"></input>-->
<input type="text" list="prest__garage" name="prest__garage" value="<?php if(isset ($prest__garage)) echo $prest__garage; ?>" />
        <datalist id="prest__garage">
            <?php
foreach ($array_prest as $prest) {
    
   echo '<option value="'.$prest["name"].'" >'.$prest["name"].'</option>';
}
?>
</span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts2>Sousse <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>

<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps3><span class=rvts3>ORDRE DE REPARATION/PRISE EN CHARGE </span></p>
<p class=rvps3><span class=rvts4><br></span></p>
<p class=rvps4><span class=rvts5>Nous</span><span class=rvts6> </span><span class=rvts5>soussignés,</span><span class=rvts6> </span><span class=rvts7>Najda</span><span class=rvts8> </span><span class=rvts7>Assistance</span><span class=rvts5>,</span><span class=rvts6> </span><span class=rvts5>nous</span><span class=rvts6> </span><span class=rvts5>engageons</span><span class=rvts6> </span><span class=rvts5>à</span><span class=rvts6> </span><span class=rvts5>prendre</span><span class=rvts6> </span><span class=rvts5>en</span><span class=rvts6> </span><span class=rvts5>charge</span><span class=rvts6> </span><span class=rvts5>les</span><span class=rvts6> </span><span class=rvts5>frais</span><span class=rvts6> </span><span class=rvts5>de</span><span class=rvts6> </span><span class=rvts5>réparation</span><span class=rvts6> </span><span class=rvts5>et</span><span class=rvts6> </span><span class=rvts5>éventuellement</span><span class=rvts6> </span><span class=rvts5>des</span><span class=rvts6> </span><span class=rvts5>pièces</span><span class=rvts6> </span><span class=rvts5>de rechange</span><span class=rvts9> </span><span class=rvts5>telles</span><span class=rvts9> </span><span class=rvts5>que</span><span class=rvts9> </span><span class=rvts5>détaillées</span><span class=rvts6> </span><span class=rvts5>dans</span><span class=rvts9> </span><span class=rvts5>votre</span><span class=rvts9> <input name="CL_facture_devis" placeholder="Facture Devis" type="text" value="<?php if(isset ($CL_facture_devis)) echo $CL_facture_devis; ?>"></input></span><span class=rvts5>relatif</span><span class=rvts9> </span><span class=rvts5>au</span><span class=rvts6> </span><span class=rvts5>véhicule  <input name="vehicule_marque" placeholder="marque du véhicule" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input>  immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input> et</span><span class=rvts6> </span><span class=rvts5>appartenant</span><span class=rvts9> </span><span class=rvts5>à</span><span class=rvts9> </span><span class=rvts5>notre</span><span class=rvts9> </span><span class=rvts5>client(e)</span><span class=rvts5> Mr/Mme <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" />.</span></p>
<p class=rvps4><span class=rvts5>Nous</span><span class=rvts6> </span><span class=rvts5>vous</span><span class=rvts6> </span><span class=rvts5>rappelons</span><span class=rvts6> </span><span class=rvts5>que</span><span class=rvts6> </span><span class=rvts5>les</span><span class=rvts6> </span><span class=rvts5>réparations</span><span class=rvts6> </span><span class=rvts5>doivent</span><span class=rvts6> </span><span class=rvts5>êtes</span><span class=rvts6> </span><span class=rvts5>effectuées</span><span class=rvts6> </span><span class=rvts5>selon</span><span class=rvts6> </span><span class=rvts5>les</span><span class=rvts6> </span><span class=rvts5>normes</span><span class=rvts6> </span><span class=rvts5>de</span><span class=rvts6> </span><span class=rvts5>sécurité</span><span class=rvts6> </span><span class=rvts5>et</span><span class=rvts6> </span><span class=rvts5>standards</span><span class=rvts6> </span><span class=rvts5>exigés</span><span class=rvts6> </span><span class=rvts5>par</span><span class=rvts6> </span><span class=rvts5>le</span><span class=rvts6> </span><span class=rvts5>constructeur</span><span class=rvts6> </span><span class=rvts5>du véhicule</span><span class=rvts6> </span><span class=rvts5>et</span><span class=rvts6> </span><span class=rvts5>que</span><span class=rvts6> </span><span class=rvts5>les</span><span class=rvts6> </span><span class=rvts5>pièces</span><span class=rvts6> </span><span class=rvts5>de</span><span class=rvts6> </span><span class=rvts5>rechange</span><span class=rvts6> </span><span class=rvts5>éventuelles</span><span class=rvts6> </span><span class=rvts5>doivent</span><span class=rvts6> </span> <span class=rvts5>être</span><span class=rvts6> </span><span class=rvts5>d</span><span class=rvts10>’</span><span class=rvts5>origine</span><span class=rvts6> </span><span class=rvts5>reconnues</span><span class=rvts6> </span><span class=rvts5>par</span><span class=rvts6> </span><span class=rvts5>la</span><span class=rvts6> </span><span class=rvts5>maison</span><span class=rvts6> </span><span class=rvts5>de</span><span class=rvts6> </span><span class=rvts5>fabrication</span><span class=rvts6> </span><span class=rvts5>du</span><span class=rvts6> </span><span class=rvts5>véhicule.</span></p>
<p class=rvps4><span class=rvts5>Merci de nous adresser votre facture originale au nom de Najda Assistance</span><span class=rvts6> </span><span class=rvts5>dès que possible (et au plus tard 15 jours après la restitution du véhicule) </span><span class=rvts11>accompagnée des pièces justificatives notamment la liste des pièces de rechange éventuelles</span><span class=rvts5> par courrier postal à</span><span class=rvts6> </span><span class=rvts5>l</span><span class=rvts10>’</span><span class=rvts5>adresse</span><span class=rvts6> </span><span class=rvts5>ci-dessus</span><span class=rvts6> </span><span class=rvts5>en</span><span class=rvts6> </span><span class=rvts5>mentionnant</span><span class=rvts6> </span><span class=rvts5>notre</span><span class=rvts6> </span><span class=rvts5>référence</span><span class=rvts6> </span><span class=rvts5>de</span><span class=rvts6> </span><span class=rvts5>dossier </span> <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /><span class=rvts6> </span><span class=rvts5></span></p>
<p><span class=rvts12>ATTENTION IMPORTANT</span><span class=rvts13> </span></p>
<p><span class=rvts14><br></span></p>
<p class=rvps1><span class=rvts15>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts15>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts16>’</span><span class=rvts15>accompagnerait</span><span class=rvts17>.</span></p>
<p class=rvps5><span class=rvts5><br></span></p>
<p class=rvps6><span class=rvts5>Merci</span><span class=rvts6> </span><span class=rvts5>de</span><span class=rvts6> </span><span class=rvts5>nous</span><span class=rvts6> </span><span class=rvts5>informer</span><span class=rvts6> </span><span class=rvts5>sans délai de l</span><span class=rvts10>’</span><span class=rvts5>achèvement des travaux de réparation ou de tout événement relatif à son déroulement et notamment :</span></p>
<ol class=list2><ul class=list3>
<li style="margin-left: 0px" class=rvps8><span class=rvts18>Demande de modification des réparations de la part du client</span></li>
<li style="margin-left: 0px" class=rvps8><span class=rvts18>Arrêt des</span><span class=rvts19> </span><span class=rvts18>réparations</span></li>
<li style="margin-left: 0px" class=rvps9><span class=rvts18>Evénement</span><span class=rvts19> </span><span class=rvts18>risquant</span><span class=rvts19> </span><span class=rvts18>de</span><span class=rvts19> </span><span class=rvts18>reporter</span><span class=rvts19> </span><span class=rvts18>la</span><span class=rvts19> </span><span class=rvts18>date</span><span class=rvts19> </span><span class=rvts18>de</span><span class=rvts19> </span><span class=rvts18>remise</span><span class=rvts19> </span><span class=rvts18>du</span><span class=rvts19> </span><span class=rvts18>véhicule</span><span class=rvts19> </span><span class=rvts18>au-delà</span><span class=rvts19> </span><span class=rvts18>des</span><span class=rvts19> </span><span class=rvts18>délais</span><span class=rvts19> </span><span class=rvts18>convenus</span><span class=rvts19> </span><span class=rvts18>dans</span><span class=rvts19> </span><span class=rvts18>le</span><span class=rvts19> </span><span class=rvts18>contact</span><span class=rvts19> </span><span class=rvts18>technique</span><span class=rvts19> </span><span class=rvts18>ou</span><span class=rvts19> </span><span class=rvts18>dans</span><span class=rvts19> </span><span class=rvts18>le rapport d</span><span class=rvts20>’</span><span class=rvts18>expertise.</span></li>
<li style="margin-left: 0px" class=rvps8><span class=rvts18>Remise du véhicule au</span><span class=rvts19> </span><span class=rvts18>client</span></li>
</ul></ol>
<p><span class=rvts17><br></span></p>
<p><span class=rvts21>Observations</span><span class=rvts22><input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
<p><span class=rvts23><br></span></p>
<p><span class=rvts23>Merci de votre collaboration.</span></p>
<p><span class=rvts23><br></span></p>
<p><span class=rvts23>P/la Gérante</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p><span class=rvts23>Plateau d</span><span class=rvts24>’</span><span class=rvts23>assistance technique</span></p>
<p class=rvps1><span class=rvts23>« Courrier électronique, sans signature »</span></p>
<p><span class=rvts23><br></span></p>
<p><span class=rvts23><br></span></p>
<p><span class=rvts23><br></span></p>
<p><span class=rvts23><br></span></p>
</body></html>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>


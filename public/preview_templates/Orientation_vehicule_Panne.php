<?php 
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['inter__garage'])) {$inter__garage=$_GET['inter__garage'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; $subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic'];}
if (isset($_GET['CL_dateheure'])) {$CL_dateheure=$_GET['CL_dateheure'];}
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
$sqlvh = "SELECT id,name,phone_home,prenom,civilite,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 22)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
             $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"] ,"prenom" => $rowvh["prenom"],"civilite" => $rowvh["civilite"]  );
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>Orientation_vehicule_Panne</title>
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
 font-family: 'Century Gothic';
 font-weight: bold;
}
span.rvts5
{
 font-size: 12pt;
 font-family: 'Century Gothic';
 font-weight: bold;
}
span.rvts6
{
 font-size: 8pt;
 font-family: 'Century Gothic';
}
span.rvts7
{
 font-size: 12pt;
 font-family: 'Century Gothic';
}
span.rvts8
{
 font-size: 12pt;
 font-family: 'Century Gothic';
}
span.rvts9
{
 font-size: 12pt;
 font-family: 'Century Gothic';
 color: #ff0000;
}
span.rvts10
{
 font-size: 12pt;
 font-family: 'Century Gothic';
 font-weight: bold;
 text-decoration: underline;
}
span.rvts11
{
 font-size: 12pt;
 font-family: 'Century Gothic';
 font-weight: bold;
 text-decoration: underline;
}
span.rvts12
{
 font-family: 'Century Gothic';
 font-weight: bold;
}
span.rvts13
{
 font-family: 'Century Gothic';
}
span.rvts14
{
 font-family: 'Century Gothic';
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
 color: #ff0000;
}
span.rvts17
{
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 font-weight: bold;
}
span.rvts18
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
 text-align: right;
 widows: 2;
 orphans: 2;
}
.rvps5
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 text-indent: -24px;
 line-height: 1.15;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 72px;
}
.rvps6
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 1.15;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 72px;
}
.rvps7
{
 line-height: 1.15;
 widows: 2;
 orphans: 2;
}
.rvps8
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 1.15;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 48px;
}
.rvps9
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 1.15;
 widows: 2;
 orphans: 2;
}
.rvps10
{
 line-height: 1.15;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 48px;
}
.rvps11
{
 widows: 2;
 orphans: 2;
}
/* ========== Lists ========== */
.list0 {text-indent: 0px; padding: 0; margin: 0 0 0 24px; list-style-position: outside; list-style-type: disc;}
.list1 {text-indent: 0px; padding: 0; margin: 0 0 0 118px; list-style-position: outside;}
.list2 {text-indent: 0px; padding: 0; margin: 0 0 0 72px; list-style-position: outside; list-style-type: disc;}
.list3 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: circle;}
.list4 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: square;}
.list5 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: disc;}
--></style>
</head>
<body>
<form id="formchamps">
<input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"></input>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2>
<input type="text" list="inter__garage" name="inter__garage"  value="<?php  if(isset ($inter__garage)) echo $inter__garage; ?>"  />
        <datalist id="inter__garage">
            <?php
foreach ($array_prest as $prest) {
    
        echo '<option value="'.$prest["civilte"].''.$prest["prenom"].''.$prest["name"].'" id="'.$prest["id"].'" >'.$prest["civilite"].''.$prest["prenom"].''.$prest["name"].'</option>';
 }
?>
</span></p>
<p class=rvps1><span class=rvts2><input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps3><span class=rvts4>Orientation de véhicule en panne</span></p>
<p class=rvps1><span class=rvts5><br></span></p>
<p class=rvps4><span class=rvts1>Sousse le <input name="date_heure" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts7>Nous soussignés, </span><span class=rvts5>Najda Assistance</span><span class=rvts7>, vous confirmons par la présente l</span><span class=rvts8>’</span><span class=rvts7>orientation vers votre garage du véhicule  <input name="vehicule_marque" placeholder="marque du véhicule
" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input> &nbsp; immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input> et appartenant à notre client(e) Mr/Mme <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> (tél : <input name="subscriber_phone_cell" list="subscriber_phone_cell" placeholder="téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell; ?>"></input>
<datalist id="subscriber_phone_cell">
<?php

foreach ($array_tel as $tel) {
	
		echo "<option value='".$tel['champ']."'>".$tel['prenom']." ".$tel['nom']." </option>";
 }

?>
</datalist>)</span></p>
<p class=rvps1><span class=rvts9><br></span></p>
<p class=rvps1><span class=rvts7>Merci de nous adresser dès la réception du véhicule et dans un délai maximal de </span><span class=rvts5>24h</span><span class=rvts7> un contact technique détaillant les éléments suivants :</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<ul class=list2>
<li style="margin-left: 0px" class=rvps6><span class=rvts7>Le </span><span class=rvts5>D</span><span class=rvts7>iagnostic technique</span></li>
<li style="margin-left: 0px" class=rvps6><span class=rvts7>La </span><span class=rvts5>D</span><span class=rvts7>urée estimée des réparations</span></li>
<li style="margin-left: 0px" class=rvps6><span class=rvts7>Le </span><span class=rvts5>D</span><span class=rvts7>evis maximal approximatif des réparations</span></li>
</ul>
<p class=rvps7><span class=rvts7><br></span></p>
<p class=rvps7><span class=rvts10>Notre référence dossier :</span><span class=rvts9>&nbsp; </span><span class=rvts7><input name="reference_medic" placeholder="Notre Reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /> </span></p>
<p class=rvps8><span class=rvts10><br></span></p>
<p class=rvps9><span class=rvts10>Date et heure de l</span><span class=rvts11>’</span><span class=rvts10>orientation du véhicule :</span><span class=rvts7> <input name="CL_dateheure" placeholder="Date et heure" value="<?php if(isset ($CL_dateheure)) echo $CL_dateheure; ?>"></input></span></p>
<p class=rvps10><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts12>ATTENTION</span><span class=rvts13> : Merci de noter que cette orientation de véhicule ne représente pas un ordre pour entamer les réparations ni un engagement de la part de </span><span class=rvts12>Najda Assistance</span><span class=rvts13> pour prendre en charge les frais des réparations. Notre éventuel accord de prise en charge des frais de réparation ne pourrait éventuellement être notifié explicitement et par écrit qu</span><span class=rvts14>’</span><span class=rvts13>après réception du contact technique ou du rapport d</span><span class=rvts14>’</span><span class=rvts13>expertise ainsi que du devis des réparations, et </span><span class=rvts12>obligatoirement par écrit</span><span class=rvts13> (fax ou mail).&nbsp; Il demeure bien entendu que nous ne prenons pas systématiquement en charge ces frais de réparation, même après réception du contact technique ou du rapport d</span><span class=rvts14>’</span><span class=rvts13>expertise.</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>Merci d</span><span class=rvts8>’</span><span class=rvts7>avance pour votre collaboration.</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>Avec nos salutations Cordiales </span></p>
<p><span class=rvts15><br></span></p>
<p><span class=rvts15>P/la Gérante</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p><span class=rvts15>Plateau d</span><span class=rvts17>’</span><span class=rvts15>assistance technique </span></p>
<p><span class=rvts18><br></span></p>
<p class=rvps11><span class=rvts18> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
</form>
<script type="text/javascript">
document.querySelector('input[list="inter__garage"]').addEventListener('input', onInput);

	function onInput(e) {
	   var input = e.target,
	       val = input.value;
	       list = input.getAttribute('list'),
	       options = document.getElementById(list).childNodes;

	  for(var i = 0; i < options.length; i++) {
	    if(options[i].innerText === val) {
	      // An item was selected from the list
	      document.getElementById("id__prestataire").value = options[i].getAttribute("id");
	      break;
	    }
	  }
	}

</script>
</body></html>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>

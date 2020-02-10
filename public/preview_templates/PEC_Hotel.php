<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__hotel'])) {$prest__hotel=$_GET['prest__hotel'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name'];$subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname'];$subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['reference_customer'])) {$reference_customer=$_GET['reference_customer']; }
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; }
if (isset($_GET['CL_debut_sejour'])) {$CL_debut_sejour=$_GET['CL_debut_sejour'];}
if (isset($_GET['CL_fin_sejour'])) {$CL_fin_sejour=$_GET['CL_fin_sejour'];}
if (isset($_GET['CL_arrangement'])) {$CL_arrangement=$_GET['CL_arrangement'];}
if (isset($_GET['CL_tarif_convention'])) {$CL_tarif_convention=$_GET['CL_tarif_convention'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
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

// recuperation des prestataires HOTEL ayant prestations dans dossier

$sqlvh = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier.")AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 18)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"]  );
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
<html><head><title>PEC_Hotel</title>
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
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts6
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
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
            font-weight: bold;
        }
        span.rvts9
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts10
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            letter-spacing: -1px;
        }
        span.rvts11
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts12
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts13
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts14
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts15
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts16
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
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
            margin: 5px 0px 0px 8px;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 4px 0px 0px 8px;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 0px 0px 8px 8px;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 35px;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 0px;
        }
        .rvps9
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 2px 0px 0px 0px;
        }
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
    <input name="idtaggop" type="hidden" value="<?php if(isset ($idtaggop)) echo $idtaggop; ?>"></input>
<p><span class=rvts1><br></span></p>
<p><span class=rvts1><br></span></p>
<p><span class=rvts1><br></span></p>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2>

    <!--<input name="prest__hotel" style="width:300px" placeholder="Prestataire hotel" value="<?php //if(isset ($prest__hotel)) echo $prest__hotel; ?>"></input>-->
<input type="text" list="prest__hotel" name="prest__hotel" value="<?php  if(isset ($prest__hotel)) echo $prest__hotel; ?>" />
        <datalist id="prest__hotel">
            <?php
foreach ($array_prest as $prest) {
    
    echo '<option value="'.$prest["name"].'" >'.$prest["name"].'</option>';
}
?>

</span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps3><span class=rvts3>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps4><span class=rvts4>CONFIRMATION DE RESERVATION PRISE EN CHARGE</span></p>
<p class=rvps4><span class=rvts4><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps5><span class=rvts5>Nom client</span><span class=rvts6> : </span><span class=rvts7><input name="subscriber_name" placeholder="Prenom Client" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>"></input> </span><span class=rvts6> </span><span class=rvts6> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts8>Prénom</span><span class=rvts7> : <input name="subscriber_lastname" placeholder="Nom Client" value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> </span></p>
<p class=rvps5><span class=rvts8>N/ref</span><span class=rvts7> : </span><span class=rvts6><input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="Nom Client" value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" placeholder="Prenom Client" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>"></input></span></p>
<p class=rvps5><span class=rvts8>Référence client : </span><span class=rvts7></span><span class=rvts6><input name="reference_customer" placeholder="reference client" value="<?php if(isset ($reference_customer)) echo $reference_customer; ?>"></input> | <input name="customer_id__name" placeholder="Nom Client" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>"></input> </span></p>
<p class=rvps6><span class=rvts8>Dates de séjour</span><span class=rvts7> : de <input type="datetime-local" name="CL_debut_sejour" placeholder="Debut Sejour" value="<?php if(isset ($CL_debut_sejour)) echo $CL_debut_sejour; ?>"></input> à <input  type="datetime-local" name="CL_fin_sejour" placeholder="Fin Sejour" value="<?php if(isset ($CL_fin_sejour)) echo $CL_fin_sejour; ?>"></input></span></p>
<p class=rvps6><span class=rvts8>Arrangement</span><span class=rvts7> :<input name="CL_arrangement" placeholder="Arrangement" value="<?php if(isset ($CL_arrangement)) echo $CL_arrangement; ?>"></input></span></p>
<p class=rvps6><span class=rvts8>Tarif de convention : </span><span style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label><input name="CL_tarif_convention" placeholder="Tarif Convention" value="<?php if(isset ($CL_tarif_convention)) echo $CL_tarif_convention; ?>"  onKeyUp=" keyUpHandler(this)"></input></span><span class=rvts8>Toutes lettres </span> <span class=rvts8> : <input name="CL_montant_toutes_lettres"  id="CL_montant_toutes_lettres" placeholder="Montant toutes lettres" value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input></span> dinars </p>
<p class=rvps6><span class=rvts7><br></span></p>
<p class=rvps6><span class=rvts7><br></span></p>
<p class=rvps7><span class=rvts7>Messieurs,</span></p>
<p class=rvps7><span class=rvts6>Nous</span><span class=rvts9> </span><span class=rvts6>soussignés,</span><span class=rvts9> </span><span class=rvts5>Voyages</span><span class=rvts10> </span><span class=rvts5>Assistance</span><span class=rvts10> </span><span class=rvts5>Tunisie</span><span class=rvts6>,</span><span class=rvts9> </span><span class=rvts6>nous</span><span class=rvts9> </span><span class=rvts6>engageons</span><span class=rvts9> </span><span class=rvts6>à</span><span class=rvts9> </span><span class=rvts6>prendre</span><span class=rvts9> </span><span class=rvts6>en</span><span class=rvts9> </span><span class=rvts6>charge</span><span class=rvts9> </span><span class=rvts6>les</span><span class=rvts9> </span><span class=rvts6>frais</span><span class=rvts9> </span><span class=rvts6>d</span><span class=rvts11>’</span><span class=rvts6>hébergement</span><span class=rvts9> </span><span class=rvts6>de</span><span class=rvts9> </span><span class=rvts6>notre</span><span class=rvts9> </span><span class=rvts6>client ci-dessus</span><span class=rvts9> </span><span class=rvts6>selon l</span><span class=rvts11>’</span><span class=rvts6>arrangement énoncé.</span></p>
<p class=rvps7><span class=rvts6>Merci</span><span class=rvts9> </span><span class=rvts6>de</span><span class=rvts9> </span><span class=rvts6>nous</span><span class=rvts9> </span><span class=rvts6>adresser</span><span class=rvts9> </span><span class=rvts6>votre</span><span class=rvts9> </span><span class=rvts6>facture</span><span class=rvts9> </span><span class=rvts6>originale</span><span class=rvts9> </span><span class=rvts6>dès</span><span class=rvts9> </span><span class=rvts6>que</span><span class=rvts9> </span><span class=rvts6>possible</span><span class=rvts9> </span><span class=rvts6>(et</span><span class=rvts9> </span><span class=rvts6>au</span><span class=rvts9> </span><span class=rvts6>plus</span><span class=rvts9> </span><span class=rvts6>tard</span><span class=rvts9> </span><span class=rvts6>30</span><span class=rvts9> </span><span class=rvts6>jours</span><span class=rvts9> </span><span class=rvts6>après</span><span class=rvts9> </span><span class=rvts6>le check-out du client),</span><span class=rvts9> </span><span class=rvts6>à</span><span class=rvts9> </span><span class=rvts6>l</span><span class=rvts11>’</span><span class=rvts6>adresse</span><span class=rvts9> </span><span class=rvts6>ci-dessus,</span><span class=rvts9> </span><span class=rvts6>en mentionnant notre référence</span><span class=rvts9> </span><span class=rvts6>ci-dessus.</span></p>
<p class=rvps7><span class=rvts6><br></span></p>
<p class=rvps12><span class=rvts12>Observations:</span><span class=rvts11> <input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
<p><span class=rvts12>ATTENTION IMPORTANT</span><span class=rvts13> </span></p>
<p><span class=rvts14><br></span></p>
<p class=rvps1><span class=rvts15>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts15>Toute facture devra être envoyée accompagnée de la présente prise en charge.</span></p>
<p><span class=rvts1><br></span></p>
<p class=rvps8><span class=rvts6>Avec</span><span class=rvts16> </span><span class=rvts6>nos</span><span class=rvts16> </span><span class=rvts6>remerciements</span><span class=rvts16> </span><span class=rvts6>pour</span><span class=rvts16> </span><span class=rvts6>votre</span><span class=rvts16> </span><span class=rvts6>collaboration. </span></p>
<p class=rvps8><span class=rvts6>P/la Gérante</span></p>
<p class=rvps1><span class=rvts9> <input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /></span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p class=rvps9><span class=rvts6>Service réservations</span></p>
<p class=rvps1><span class=rvts3>« courrier électronique, sans signature »</span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<script language="javascript" src="nombre_en_lettre.js"></script>
<script type="text/javascript">
    function keyUpHandler(obj){
<?php if (intval($montantgop) > 0) { ?>
            //document.getElementById("CL_montant_toutes_lettres").firstChild.nodeValue =   NumberToLetter(obj.value)
            if (obj.value > <?php echo $montantgop  ?>) {document.getElementById("alertGOP").style.display="block";}
            else {document.getElementById("alertGOP").style.display="none";}
<?php } ?>
 document.getElementById("CL_montant_toutes_lettres").value  = NumberToLetter(obj.value)
        }//fin de keypressHandler
</script>
</body></html>
<?php
        }
else
{ echo "<h3>Il n'y a pas un prestataire valide pour ce type de document sous le dossier!</h3>";}
?>


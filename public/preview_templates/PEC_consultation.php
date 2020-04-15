<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['inter__medecin'])) {$inter__medecin=$_GET['inter__medecin'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['CL_text_client'])) {$CL_text_client=$_GET['CL_text_client'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name'];$subscriber_name2=$_GET['subscriber_name'];  }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname'];$subscriber_lastname2=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_montant_numerique'])) {$CL_montant_numerique=$_GET['CL_montant_numerique'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
if (isset($_GET['CL_date_heure_visite'])) {$CL_date_heure_visite=$_GET['CL_date_heure_visite'];}
if (isset($_GET['CL_adresse'])) {$CL_adresse=$_GET['CL_adresse'];}
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
$sqlvh = "SELECT id,name,prenom,civilite,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 36)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],"name" => $rowvh["name"],"prenom" => $rowvh["prenom"],"civilite" => $rowvh["civilite"]);
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
<html><head><title>isq2qntiwti7l6gjvbvp2ptrlkw2og25_PEC_consultation+</title>
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
            font-size: 18pt;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts4
        {
            font-size: 16pt;
            font-weight: bold;
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
            font-weight: bold;
        }
        span.rvts8
        {
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts9
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts16
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
            text-justify: inter-word;
            text-align-last: auto;
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
            margin: 5px 0px 0px 0px;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 8px 0px 0px 0px;
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
<input type="text" list="inter__medecin" name="inter__medecin"  value="<?php  if(isset ($inter__medecin)) echo $inter__medecin; ?>" />
        <datalist id="inter__medecin">
            <?php
foreach ($array_prest as $prest) {
    //echo "<option value='".$prest['civilite']." ".$prest['prenom']." ".$prest['name']."' >".$prest['civilite']." ".$prest['prenom']." ".$prest['name']."</option>";
       echo '<option value="'.$prest["civilte"].''.$prest["prenom"].''.$prest["name"].'" id="'.$prest["id"].'" >'.$prest["civilite"].''.$prest["prenom"].''.$prest["name"].'</option>';
}
?>
</span></p>
<p class=rvps1><span class=rvts2><input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><br></span></p>
<p class=rvps2><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
<p class=rvps3><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts3>Prise en charge de consultation/visite médicale</span></p>
<p class=rvps4><span class=rvts4> </span></p>
<?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text_client"  type="hidden" value="*CLient : " />
<p class=rvps5><span class=rvts5>*Client :</span><span class=rvts6> </span><span class=rvts5><input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>

<?php 
}
 else
 {
?>
<input name="CL_text_client"  type="hidden" value="" />
<p class=rvps5><span class=rvts5> <input  type="hidden" name="customer_id__name" id="customer_id__name" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?>
<p class=rvps5><span class=rvts5>*Nom patient :</span><span class=rvts6> </span><span class=rvts5><input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> </span></p>
<p class=rvps5><span class=rvts5>*Prénom :</span><span class=rvts6> </span><span class=rvts5><input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> </span></p>
<p><span class=rvts2>* </span><span class=rvts7>Notre réf. </span><span class=rvts2>: <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /> </span></p></span></p>
<p class=rvps5><span class=rvts5>Montant maximal de prise en charge (TND)</span><span class=rvts6>:&nbsp;</span><span class=rvts9> </span><span style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label><input name="CL_montant_numerique" placeholder="Montant maximal"  value="<?php if(isset ($CL_montant_numerique)) echo $CL_montant_numerique; ?>" onKeyUp=" keyUpHandler(this)"></input></span><span class=rvts9>&nbsp;&nbsp;&nbsp; </span><span class=rvts5>Toutes lettres</span><span class=rvts6> : <input name="CL_montant_toutes_lettres" id="CL_montant_toutes_lettres" placeholder="Montant en toutes lettres"  value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input> dinars</span></p>
<p><span class=rvts8><br></span></p>
<p><span class=rvts2>Nous soussignés, Najda Assistance, nous engageons à prendre en charge, pour le compte de notre client société d</span><span class=rvts9>’</span><span class=rvts2>assistance</span><span class=rvts7>, </span><span class=rvts10>les frais relatifs à la consultation/visite du </span><span class=rvts7><input name="CL_date_heure_visite" placeholder="Date Heure Visite" value="<?php if(isset ($CL_date_heure_visite)) echo $CL_date_heure_visite; ?>"></input></span><span class=rvts2> à votre cabinet (si visite, <input name="CL_adresse" placeholder="Adresse" value="<?php if(isset ($CL_adresse)) echo $CL_adresse ?>"></input>) pour le patient nommé ci-dessus et pour le montant susmentionné.</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2>Merci de bien vouloir nous adresser votre note d</span><span class=rvts9>’</span><span class=rvts2>honoraires par voie postale dans les 15 jours max après réalisation de la consultation/visite, accompagnée d</span><span class=rvts9>’</span><span class=rvts2>une copie de la présente prise en charge à notre adresse ci-dessus en entête.</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts11>ATTENTION IMPORTANT</span><span class=rvts12> </span></p>
<p><span class=rvts13><br></span></p>
<p><span class=rvts14>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p><span class=rvts14>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout éventuel document à signer qui l</span><span class=rvts15>’</span><span class=rvts14>accompagnerait.</span></p>
<p><span class=rvts16><br></span></p>
<p class=rvps6><span class=rvts6>Merci pour votre collaboration</span></p>
<p class=rvps6><span class=rvts6>P/La Gérante</span></p>
<p class=rvps1><span class=rvts9> <input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /></span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p class=rvps1><span class=rvts2>Plateau d</span><span class=rvts9>’</span><span class=rvts2>assistance médicale</span></p>
<p class=rvps1><span class=rvts2>« courrier électronique, sans signature »</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2><br></span></p>
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
document.querySelector('input[list="inter__medecin"]').addEventListener('input', onInput);

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


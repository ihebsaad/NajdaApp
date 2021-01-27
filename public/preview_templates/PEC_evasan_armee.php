<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['CL_avion_l410_helicoptere'])) {$CL_avion_l410_helicoptere=$_GET['CL_avion_l410_helicoptere'];}
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name'];$subscriber_name2=$_GET['subscriber_name']; }
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname'];$subscriber_lastname2=$_GET['subscriber_lastname']; }
if (isset($_GET['CL_date_debut'])) {$CL_date_debut=$_GET['CL_date_debut'];}
if (isset($_GET['CL_date_fin'])) {$CL_date_fin=$_GET['CL_date_fin'];}
if (isset($_GET['CL_base_laouina_aeroport'])) {$CL_base_laouina_aeroport=$_GET['CL_base_laouina_aeroport'];}
if (isset($_GET['CL_name_directeur'])) {$CL_name_directeur=$_GET['CL_name_directeur'];}
if (isset($_GET['CL_cin_directeur'])) {$CL_cin_directeur=$_GET['CL_cin_directeur'];}
if (isset($_GET['CL_name_medecin'])) {$CL_name_medecin=$_GET['CL_name_medecin'];}
if (isset($_GET['CL_cin_medecin'])) {$CL_cin_medecin=$_GET['CL_cin_medecin'];}
if (isset($_GET['CL_name_chauffeur'])) {$CL_name_chauffeur=$_GET['CL_name_chauffeur'];}
if (isset($_GET['CL_cin_chauffeur'])) {$CL_cin_chauffeur=$_GET['CL_cin_chauffeur'];}
if (isset($_GET['CL_immatriculation'])) {$CL_immatriculation=$_GET['CL_immatriculation']; }
if (isset($_GET['CL_date_heure_entree_base'])) {$CL_date_heure_entree_base=$_GET['CL_date_heure_entree_base'];}
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

$sqlvh = "SELECT id,name,immarticulation,type,fonction FROM voitures";
	$resultvh = $conn->query($sqlvh);
	if ($resultvh->num_rows > 0) {
	    // output data of each row
	    $array_vehic = array();
	    while($rowvh = $resultvh->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_vehic[] = array('id' => $rowvh["id"],'name' => $rowvh["name"],'immarticulation' => $rowvh["immarticulation"],'type' => $rowvh["type"],'fonction' => $rowvh["fonction"]  );
	    }
	    //print_r($array_prest);
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
<html><head><title>PEC_evasan_armee</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <style type="text/css"><!--
        body {
            margin: 47px 95px 95px 95px;
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
            font-size: 14pt;
            font-weight: bold;
        }
        span.rvts4
        {
            font-size: 14pt;
        }
        span.rvts5
        {
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts6
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts7
        {
            font-size: 18pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
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
        }
        span.rvts10
        {
            font-size: 11pt;
            font-weight: bold;
        }
        span.rvts11
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts12
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts13
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts14
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts15
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts16
        {
            font-size: 12pt;
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
            margin: 8px 0px 0px 0px;
        }
        .rvps5
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.50;
            widows: 2;
            orphans: 2;
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
<p class=rvps1><span class=rvts1><br></span></p>

<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p><span class=rvts3><br></span></p>
<p><span class=rvts4><br></span></p>
<p class=rvps2><span class=rvts5> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts5> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts5> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts5> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
<p class=rvps2><span class=rvts5><br></span></p>
<p class=rvps2><span class=rvts6>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps3><span class=rvts7>PRISE EN CHARGE EVASAN NATIONALE</span></p>
<p class=rvps1><span class=rvts8><br></span></p>
<p class=rvps1><span class=rvts8><br></span></p>
<p class=rvps1><span class=rvts6>Monsieur le Ministre,</span></p>
<p class=rvps1><span class=rvts8><br></span></p>
<p class=rvps1><span class=rvts6>Nous avons l</span><span class=rvts9>’</span><span class=rvts6>honneur de vous confirmer notre prise en charge de l</span><span class=rvts9>’</span><span class=rvts6>évacuation sanitaire par (<input name="CL_avion_l410_helicoptere" placeholder="Avion L410 Helicoptere" value="<?php if(isset ($CL_avion_l410_helicoptere)) echo $CL_avion_l410_helicoptere; ?>"></input>) </span><span class=rvts8>du (de la) patient(e) Mr/Mme </span><span class=rvts10>


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

  </span><span class=rvts6>de </span><span class=rvts11><input name="CL_date_debut" placeholder="Date Debut" value="<?php if(isset ($CL_date_debut)) echo $CL_date_debut; ?>"></input> </span><span class=rvts6>vers <input name="CL_date_fin" placeholder="Date Fin"value="<?php if(isset ($CL_date_fin)) echo $CL_date_fin; ?>"></input> </span></p>
<p class=rvps1><span class=rvts6> </span></p>
<p class=rvps1><span class=rvts6>Notre équipe médicale, qui se rendra à (<input name="CL_base_laouina_aeroport" placeholder="Base Laouina Aeroport" value="<?php if(isset ($CL_base_laouina_aeroport)) echo $CL_base_laouina_aeroport; ?>"></input> </span><span class=rvts11>) </span><span class=rvts6>…… sera composée du </span><span class=rvts8>Dr </span><span class=rvts11><input name="CL_name_directeur" placeholder="Name Directeur" value="<?php if(isset ($CL_name_directeur)) echo $CL_name_directeur; ?>"></input> )</span><span class=rvts6>, </span><span class=rvts8>CIN # </span><span class=rvts11><input name="CL_cin_directeur" placeholder="Cin Directeur" value="<?php if(isset ($CL_cin_directeur)) echo $CL_cin_directeur; ?>"></input> </span><span class=rvts8>, </span><span class=rvts6>médecin, du paramédical </span><span class=rvts8>Mr </span><span class=rvts11><input name="CL_name_medecin" placeholder="Name Medecin" value="<?php if(isset ($CL_name_medecin)) echo $CL_name_medecin; ?>"></input></span><span class=rvts6>, </span><span class=rvts8>CIN # </span><span class=rvts11><input name="CL_cin_medecin" placeholder="Cin Medecin" value="<?php if(isset ($CL_cin_medecin)) echo $CL_cin_medecin; ?>"></input></span><span class=rvts6>et de </span><span class=rvts8>Mr <input name="CL_name_chauffeur" placeholder="Name Chauffeur" value="<?php if(isset ($CL_name_chauffeur)) echo $CL_name_chauffeur; ?>"></input> ,</span><span class=rvts6> </span><span class=rvts8>CIN # </span><span class=rvts11><input name="CL_cin_chauffeur" placeholder="Cin Chauffeur" value="<?php if(isset ($CL_cin_chauffeur)) echo $CL_cin_chauffeur; ?>"></input> </span><span class=rvts8>, </span><span class=rvts6>chauffeur ambulancier.</span></p>
<p class=rvps1><span class=rvts6>Cette équipe se présentera à bord de </span><span class=rvts8>l</span><span class=rvts12>’</span><span class=rvts8>ambulance</span><span class=rvts6> de Medic</span><span class=rvts9>’</span><span class=rvts6> Multiservices, immatriculée</span><span class=rvts11>  <input name="CL_immatriculation" list="CL_immatriculation" placeholder="immatriculation"  value="<?php if(isset ($CL_immatriculation)) echo $CL_immatriculation; ?>">
<datalist id="CL_immatriculation">
<?php

foreach ($array_vehic as $vehic) {
	if (strstr($vehic['name'], 'ambulance' ) || strstr($vehic['type'], 'ambulance' )|| strstr($vehic['fonction'], 'ambulance' ) )
		{
	echo "<option value='".$vehic['immarticulation']."'  >".$vehic['name']."</option>";
} }

?>
</datalist></input>
</span><span class=rvts8>.</span><span class=rvts6> Elle devrait se présenter à l</span><span class=rvts9>’</span><span class=rvts6>entrée de la base le </span><span class=rvts11><input name="CL_date_heure_entree_base" placeholder="Date Heure Entree Base" value="<?php if(isset ($CL_date_heure_entree_base)) echo $CL_date_heure_entree_base; ?>"></input></span></p>
<p class=rvps4><span class=rvts13>Merci de nous faire adresser la facture relative par vos services dans les meilleurs délais à l</span><span class=rvts14>’</span><span class=rvts13>adresse ci-dessus en mentionnant notre </span><span class=rvts15>référence de dossier </span><span class=rvts16><input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"></input/> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" /> </span></p>
<p class=rvps1><span class=rvts6>Veuillez agréer, Monsieur le Ministre, nos profonds respects.</span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6>P/La Gérante</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /> </span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p class=rvps1><span class=rvts6>Plateau d</span><span class=rvts9>’</span><span class=rvts6>assistance Médicale</span></p>
<p class=rvps5><span class=rvts1><br></span></p>
<p><span class=rvts6><br></span></p>
<p class=rvps6><span class=rvts6> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
</body></html>

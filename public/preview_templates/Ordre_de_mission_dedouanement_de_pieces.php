<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['prest__transitaire'])) {$prest__transitaire=$_GET['prest__transitaire'];}
if (isset($_GET['inter__garage'])) {$inter__garage=$_GET['inter__garage'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['CL_name'])) {$CL_name=$_GET['CL_name'];}
if (isset($_GET['CL_nlta'])) {$CL_nlta=$_GET['CL_nlta'];}
if (isset($_GET['CL_date_heure_ariv'])) {$CL_date_heure_ariv=$_GET['CL_date_heure_ariv'];}
if (isset($_GET['CL_cordonnes_vol'])) {$CL_cordonnes_vol=$_GET['CL_cordonnes_vol'];}
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
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

$sqlvh = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier.")AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 40)";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            $array_prest[] = array('id' => $rowvh["id"],'name' => $rowvh["name"]  );
        } }
$sqlvha = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM intervenants WHERE dossier=".$iddossier." ) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id = 22)";

    $resultvha = $conn->query($sqlvha);
    if ($resultvha->num_rows > 0) {

        $array_presta = array();
        while($rowvha = $resultvha->fetch_assoc()) {
            $array_presta[] = array('id' => $rowvha["id"],'name' => $rowvha["name"]  );
        } }
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
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps3><span class=rvts4>Ordre de Mission Transitaire</span></p>
<p class=rvps3><span class=rvts4><br></span></p>
<p class=rvps3><span class=rvts5><br></span></p>
<p class=rvps4><span class=rvts2>Nous soussignés, </span><span class=rvts6>Najda Assistance</span><span class=rvts2>, société d</span><span class=rvts7>’</span><span class=rvts2>assistance correspondante de la compagnie <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /> attestons par la présente que la société </span><span class=rvts8>
<input type="text" list="prest__transitaire" name="prest__transitaire"  value="<?php  if(isset ($prest__transitaire)) echo $prest__transitaire; ?>"/>
        <datalist id="prest__transitaire">
            <?php
foreach ($array_prest as $prest) {
    
    echo "<option value='".$prest['name']."' >".$prest['name']."</option>";
}
?>
</span><span class=rvts2>&nbsp; représentée par son gérant Mr <input name="CL_name"  placeholder="name" value="<?php if(isset ($CL_name)) echo $CL_name; ?>" /> est chargée par nos soins de procéder au dédouanement de pièces sous le numéro de LTA  <input name="CL_nlta"  placeholder="NLTA" value="<?php if(isset ($CL_nlta)) echo $CL_nlta; ?>" /> et qui arrivera le <input name="CL_date_heure_ariv" placeholder="Date et heure" value="<?php if(isset ($CL_date_heure_ariv)) echo $CL_date_heure_ariv; ?>"></input> sur le vol <input name="CL_cordonnes_vol" placeholder="Cordonnes Vol" value="<?php if(isset ($CL_cordonnes_vol)) echo $CL_cordonnes_vol; ?>"></input> au nom de notre client(e) </span><span class=rvts9><input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /><input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span><span class=rvts2> </span><br><span class=rvts2>A noter que la pièce de rechange en question sera montée sur le véhicule de notre client(e) de marque  <input name="vehicule_marque" placeholder="marque du véhicule
" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input>  <input name="vehicule_type" placeholder="Type du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input>  immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input> en circulation temporaire en Tunisie et nécessitant la réparation avant son retour vers l</span><span class=rvts7>’</span><span class=rvts2>étranger. Le véhicule sera réparé au garage </span><span class=rvts8>
<input type="text" list="inter__garage" name="inter__garage" value="<?php  if(isset ($inter__garage)) echo $inter__garage; ?>" />
        <datalist id="inter__garage">
            <?php
foreach ($array_presta as $presta) {
    
    echo "<option value='".$presta['name']."' >".$presta['name']."</option>";

}
?>
</span><span class=rvts2> <input name="CL_cordonnes_gar" placeholder="Cordonnes Garage" value="<?php if(isset ($CL_cordonnes_gar)) echo $CL_cordonnes_gar; ?>"></input></span><span class=rvts10>.</span><span class=rvts2>&nbsp;&nbsp; </span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2>Cette procuration est délivrée pour servir et valoir ce que de droit.</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2>P/ Le Directeur des Opérations</span></p>
<p class=rvps1><span class=rvts9> <input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($agent__lastname)) echo $agent__lastname; ?>" /></span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($agent__signature)) echo $agent__signature; ?>" /></span></p>

<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
<p><span class=rvts12><br></span></p>
</body></html>


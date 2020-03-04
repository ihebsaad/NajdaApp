<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}
if (isset($_GET['prest__structure'])) {$prest__structure=$_GET['prest__structure'];}
if (isset($_GET['id__prestataire'])) {$id__prestataire=$_GET['id__prestataire'];}
if (isset($_GET['CL_text_client'])) {$CL_text_client=$_GET['CL_text_client'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; $subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_periode_hospitalisation'])) {$CL_periode_hospitalisation=$_GET['CL_periode_hospitalisation']; }
if (isset($_GET['franchise'])) {$franchise=$_GET['franchise']; }
if (isset($_GET['CL_text_montant'])) {$CL_text_montant=$_GET['CL_text_montant']; }
if (isset($_GET['montant_franchise'])) {$montant_franchise=$_GET['montant_franchise']; }
if (isset($_GET['doc_dossier'])) {$doc_dossier=$_GET['doc_dossier']; }
if (isset($_GET['CL_montant_numerique'])) {$CL_montant_numerique=$_GET['CL_montant_numerique'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
if (isset($_GET['CL_attention'])) {$CL_attention=$_GET['CL_attention'];}
//if (isset($_GET['CL_doc_sig'])) {$CL_doc_sig=$_GET['CL_doc_sig']; }
//if (isset($_GET['CL_nom_doc_sig'])) {$CL_nom_doc_sig=$_GET['CL_nom_doc_sig']; }
//if (isset($_GET['CL_doc_sign'])) {$CL_doc_sign=$_GET['CL_doc_sign']; }
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

  $sqlvh = "SELECT dossier,doc FROM dossiers_docs ";
	$resultvh = $conn->query($sqlvh);
	if ($resultvh->num_rows > 0) {
	    // output data of each row
	    $array_docs = array();
	    while($rowvh = $resultvh->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_docs[] = array('dossier' => $rowvh["dossier"],'doc' => $rowvh["doc"]);
	    }
	    //print_r($array_prest);
		}
		 $sqlvhq = "SELECT id,reference_medic,documents FROM dossiers";
	$resultvhq = $conn->query($sqlvhq);
	if ($resultvhq->num_rows > 0) {
	    // output data of each row
	    $array_dossier = array();
	    while($rowvhq = $resultvhq->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_dossier[] = array('id' => $rowvhq["id"],'reference_medic' => trim($rowvhq["reference_medic"]),'documents' => $rowvhq["documents"]);
			
			
	    }
	
	    //print_r($array_prest);
		}
		 $sqlvhqa = "SELECT id,nom FROM docs";
	$resultvhqa = $conn->query($sqlvhqa);
	if ($resultvhqa->num_rows > 0) {
	    // output data of each row
	    $array_docssignes = array();
	    while($rowvhqa = $resultvhqa->fetch_assoc()) {
	        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
	        $array_docssignes[] = array('id' => $rowvhqa["id"],'nom' => $rowvhqa["nom"]);
			
			
	    }
	
	    //print_r($array_prest);
		}
	foreach ($array_dossier as $dossier) 
	{if (trim($dossier['reference_medic'])==$reference_medic ){
		$id=$dossier['id'];

	}	}
$sign = 'non';
	foreach ($array_docs as $doc) 
{     if ( $id==$doc['dossier'] )
		{ $sign = 'oui'; 
	    } 
	
	} 
	foreach ($array_docs as $doc) 
{     if ( $id==$doc['dossier'] )
		{  $iddoc=$doc['doc'];} } 
	
$sqlstruc = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1) AND id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id  IN (8,9))";

    $resultstruc = $conn->query($sqlstruc);
    if ($resultstruc->num_rows > 0) {

        $array_struc = array();
        while($rowstruc = $resultstruc->fetch_assoc()) {
            $array_struc[] = array('id' => $rowstruc["id"],'name' => $rowstruc["name"]  );
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
$sqlsig = "SELECT id,nom FROM docs WHERE id IN (SELECT doc FROM dossiers_docs WHERE dossier=".$iddossier.")";
    $resultsig = $conn->query($sqlsig);
    if ($resultsig->num_rows > 0) {

        $array_sig = array();
        while($rowsig = $resultsig->fetch_assoc()) {
            $array_sig[] = array('id' => $rowsig["id"],'nom' => $rowsig["nom"]  );
        }}
 $sqldos = "SELECT id,documents FROM dossiers WHERE id=".$iddossier."";
		$resultdos = $conn->query($sqldos);
		if ($resultdos->num_rows > 0) {
	    // output data of each row
	    $detaildos = $resultdos->fetch_assoc();
	    
		} else {
	    echo "0 results agent";
		}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_frais_medicaux</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
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
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts4
        {
            font-size: 14pt;
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
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts7
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #00b050;
        }
        span.rvts8
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #ff0000;
        }
        span.rvts9
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
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
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            text-decoration: underline;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
            text-decoration: underline;
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
        }
        span.rvts22
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts23
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts24
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts25
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts26
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts27
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #00b050;
        }
        span.rvts28
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #00b050;
        }
        span.rvts29
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts30
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts31
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            text-decoration: underline;
        }
        span.rvts32
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts33
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts34
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #00b050;
        }
        span.rvts35
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
            text-decoration: underline;
        }
        span.rvts36
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts37
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts38
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts39
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts40
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts41
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
            margin: 5px 0px 0px 8px;
        }
        .rvps5
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 2px 0px 0px 8px;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.18;
            widows: 2;
            orphans: 2;
            margin: 2px 0px 0px 8px;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 8px;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.18;
            widows: 2;
            orphans: 2;
            margin: 0px 0px 0px 8px;
        }
        .rvps9
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
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
<input type="text" list="prest__structure" name="prest__structure"  value="<?php  if(isset ($prest__structure)) echo $prest__structure; ?>" />
        <datalist id="prest__structure">
            <?php
foreach ($array_struc as $struc) {
    
   // echo "<option value='".$struc['name']."' >".$struc['name']."</option>";
echo '<option value="'.$struc["name"].'" id="'.$struc["id"].'" >'.$struc["name"].'</option>';
}
?>
</span></p>
<p class=rvps1><span class=rvts2><input type="hidden" name="id__prestataire" id="id__prestataire"  value="<?php if(isset ($id__prestataire)) echo $id__prestataire; ?>"></input><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps2><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input> </span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps3><span class=rvts4>PRISE EN CHARGE DE FRAIS MEDICAUX</span></p>
<p class=rvps3><span class=rvts5><br></span></p>
<?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text_client"  type="hidden" value="CLient : " />
<p class=rvps4><span class=rvts6>Client : <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>

<?php 
}
 else
 {
?>
<input name="CL_text_client"  type="hidden" value="" />
<p class=rvps4><span class=rvts6> <input  type="hidden" name="customer_id__name" id="customer_id__name" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?>
<p class=rvps5><span class=rvts6>Nom patient : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" />  Prénom : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> </span></p>
<p class=rvps6><span class=rvts6>Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input/> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /> </span></p>
<p class=rvps5><span class=rvts5>Montant maximal de prise en charge (TND)</span><span class=rvts6>:&nbsp;</span><span class=rvts9> </span><span style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label><input name="CL_montant_numerique" placeholder="Montant maximal"  value="<?php if(isset ($CL_montant_numerique)) echo $CL_montant_numerique; ?>" onKeyUp=" keyUpHandler(this)"></input></span><span class=rvts9>&nbsp;&nbsp;&nbsp; </span><span class=rvts5>Toutes lettres</span><span class=rvts6> : <input name="CL_montant_toutes_lettres" id="CL_montant_toutes_lettres" placeholder="Montant en toutes lettres"  value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input> dinars</span></p>
<p class=rvps5><span class=rvts6>Periode d'hospitalisation: <input name="CL_periode_hospitalisation" placeholder="periode de hospitalisation"  value="<?php if(isset ($CL_periode_hospitalisation)) echo $CL_periode_hospitalisation; ?>"></input></span></p>

<p class=rvps6><span class=rvts6>Franchise: <input name="franchise" placeholder="franchise"  value="<?php if($franchise==="1"||$franchise==="oui" )echo "oui"; else echo "non" ?>"></input> </span><span class=rvts7></span></p>
<?php
{
	if ($franchise === "1"||$franchise==="oui") { ?>
<input name="CL_text_montant" type="hidden" placeholder="" value="<?php if(isset($CL_text_montant)) {echo $CL_text_montant;}  if (empty($CL_text_montant)){ echo "Montant de la franchise:" ;}?>"></input>
<p class=rvps7><span class=rvts6>Montant de la franchise: </span><span class=rvts8><input name="montant_franchise" placeholder="montant franchise"  value="<?php if(isset ($montant_franchise)) echo $montant_franchise; ?>"></input></span><span class=rvts6></span><span class=rvts9></span><span class=rvts6></span><span class=rvts9></span><span class=rvts6></span></p>
<?php

	}else { ?>
<input name="CL_text_montant" type="hidden" placeholder="" value="<?php if(isset($CL_text_montant)) {echo $CL_text_montant;}  if (empty($CL_text_montant)){ echo "" ;}?>">
<p class=rvps7><span class=rvts6></span><span class=rvts8><input name="montant_franchise" placeholder="montant franchise" type="hidden" value=""></input></span><span class=rvts6></span><span class=rvts9></span><span class=rvts6></span><span class=rvts9></span><span class=rvts6></span></p>
</input>

<?php } }?>

<p class=rvps5><span class=rvts6>Document à signer: </span><span class=rvts7> <input name="doc_dossier" id="doc_dossier" placeholder=""  value="
<?php  { if ($detaildos['documents']==='1')  {echo 'oui'; } else  {echo  'non';}}  ?> "></input></span></p>
<p><span class=rvts10>&nbsp; </span></p>
<p class=rvps5><span class=rvts11>Nous</span><span class=rvts12> </span><span class=rvts11>soussignés,</span><span class=rvts12> </span><span class=rvts11>Najda</span><span class=rvts12> </span><span class=rvts11>Assistance,</span><span class=rvts12> </span><span class=rvts11>nous</span><span class=rvts12> </span><span class=rvts11>engageons</span><span class=rvts12> </span><span class=rvts11>à</span><span class=rvts12> </span><span class=rvts11>prendre</span><span class=rvts12> </span><span class=rvts11>en</span><span class=rvts12> </span><span class=rvts11>charge,</span><span class=rvts12> </span><span class=rvts11>pour</span><span class=rvts12> </span><span class=rvts11>le</span><span class=rvts12> </span><span class=rvts11>compte</span><span class=rvts12> </span><span class=rvts11>de</span><span class=rvts12> </span><span class=rvts11>notre</span><span class=rvts12> </span><span class=rvts11>client</span><span class=rvts13>,</span><span class=rvts12> </span><span class=rvts11>les</span><span class=rvts12> </span><span class=rvts11>frais</span><span class=rvts12> </span><span class=rvts11>médicaux</span><span class=rvts12> </span><span class=rvts11>et</span><span class=rvts12> </span><span class=rvts11>d</span><span class=rvts14>’</span><span class=rvts11>hospitalisation</span><span class=rvts12> </span><span class=rvts11>du</span><span class=rvts12> </span><span class=rvts11>(de</span><span class=rvts12> </span><span class=rvts11>la) patient(e) ci-dessus mentionné(e)</span><span class=rvts15> </span><span class=rvts11>pour le</span><span class=rvts15> </span><span class=rvts11>montant maximal ci-dessus.</span></p>
<p class=rvps8><span class=rvts11>Merci</span><span class=rvts15> </span><span class=rvts11>de</span><span class=rvts15> </span><span class=rvts11>nous</span><span class=rvts15> </span><span class=rvts11>adresser</span><span class=rvts15> </span><span class=rvts11>votre</span><span class=rvts15> </span><span class=rvts11>facture</span><span class=rvts15> </span><span class=rvts11>originale</span><span class=rvts15> </span><span class=rvts11>dès</span><span class=rvts15> </span><span class=rvts11>que</span><span class=rvts15> </span><span class=rvts11>possible</span><span class=rvts15> </span><span class=rvts11>(et</span><span class=rvts15> </span><span class=rvts11>au</span><span class=rvts15> </span><span class=rvts11>plus</span><span class=rvts15> </span><span class=rvts11>tard</span><span class=rvts15> </span><span class=rvts11>30</span><span class=rvts15> </span><span class=rvts11>jours</span><span class=rvts15> </span><span class=rvts11>après</span><span class=rvts15> </span><span class=rvts11>la</span><span class=rvts15> </span><span class=rvts11>sortie),</span><span class=rvts15> </span><span class=rvts16>accompagnée</span><span class=rvts17> </span><span class=rvts16>de</span><span class=rvts17> </span><span class=rvts16>tous</span><span class=rvts15> </span><span class=rvts16>les</span><span class=rvts15> </span><span class=rvts16>justificatifs</span><span class=rvts15> </span><span class=rvts11>(notamment</span><span class=rvts15> </span><span class=rvts11>articles</span><span class=rvts15> </span><span class=rvts11>de </span><span class=rvts18>pharmacie,</span><span class=rvts19> </span><span class=rvts18>laboratoire,</span><span class=rvts19> </span><span class=rvts18>notes</span><span class=rvts19> </span><span class=rvts18>d</span><span class=rvts20>’</span><span class=rvts18>honoraires,</span><span class=rvts19> </span><span class=rvts18>rapport</span><span class=rvts19> </span><span class=rvts18>médical</span><span class=rvts19> </span><span class=rvts18>avec</span><span class=rvts19> </span><span class=rvts18>codification</span><span class=rvts19> </span><span class=rvts18>précise</span><span class=rvts19> </span><span class=rvts18>des</span><span class=rvts19> </span><span class=rvts18>actes</span><span class=rvts19> </span><span class=rvts18>pratiqués…),</span><span class=rvts19> </span><span class=rvts18>à</span><span class=rvts19> </span><span class=rvts18>notre adresse</span><span class=rvts19> </span><span class=rvts18>ci-dessus,</span><span class=rvts19> </span><span class=rvts18>en</span><span class=rvts19> </span><span class=rvts18>mentionnant</span><span class=rvts19> </span><span class=rvts18>notre</span><span class=rvts19> </span><span class=rvts18>référence</span><span class=rvts19> </span><span class=rvts21>de dossier</span><span class=rvts18>.</span></p>
<p class=rvps9><span class=rvts27><br></span></p>
<?php
{ if (isset($CL_attention))
	{ if (($CL_attention === "Attention : Cette prise en charge s'entend hors extra (y compris surclassement de chambre) et conformément à la nomenclature officielle des actes médicaux et à votre liste de prix")) { 
     $test='oui';
	 ?>	
		<input type="checkbox" name="CL_attention" id="CL_attention" checked value="oui">
		
		<?php } else { ?>
		<input type="checkbox" name="CL_attention" id="CL_attention" checked value="">
		
<?php }} else { ?>
<input type="checkbox" name="CL_attention" id="CL_attention" checked value="oui">

<?php }
} ?>

<p class="rvps9" id ="pattention"><span class=rvts22>Attention</span><span class=rvts23> </span><span class=rvts24>:</span><span class=rvts23> </span><span class=rvts24>Cette</span><span class=rvts25> </span><span class=rvts24>prise</span><span class=rvts25> </span><span class=rvts24>en</span><span class=rvts23> </span><span class=rvts24>charge</span><span class=rvts25> </span><span class=rvts24>s</span><span class=rvts26>’</span><span class=rvts24>entend</span><span class=rvts25> </span><span class=rvts24>hors</span><span class=rvts25> </span><span class=rvts24>extra</span><span class=rvts25> </span><span class=rvts24>(y</span><span class=rvts25> </span><span class=rvts24>compris</span><span class=rvts25> </span><span class=rvts24>surclassement</span><span class=rvts23> </span><span class=rvts24>de</span><span class=rvts25> </span><span class=rvts24>chambre)</span><span class=rvts23> </span><span class=rvts24>et</span><span class=rvts23> </span><span class=rvts24>conformément</span><span class=rvts23> </span><span class=rvts24>à</span><span class=rvts25> </span><span class=rvts24>la</span><span class=rvts25> </span><span class=rvts24>nomenclature</span><span class=rvts25> </span><span class=rvts24>officielle</span><span class=rvts25> </span><span class=rvts24>des</span><span class=rvts25> </span><span class=rvts24>actes médicaux</span><span class=rvts25> </span><span class=rvts24>et</span><span class=rvts23> </span><span class=rvts24>à</span><span class=rvts25> </span><span class=rvts24>votre</span><span class=rvts25> </span><span class=rvts24>liste</span><span class=rvts25> </span><span class=rvts24>de</span><span class=rvts25> </span><span class=rvts24>prix</span></p>
<p class=rvps9><span class=rvts27><br></span></p>

<?php
{ 
	if ($detaildos['documents']==='1') { ?>

<p><span class=rvts29>Attention</span><span class=rvts30> </span><span class=rvts31>:</span><span class=rvts30> </span><span class=rvts32>Cette</span><span class=rvts30> </span><span class=rvts32>prise</span><span class=rvts30> </span><span class=rvts32>en</span><span class=rvts30> </span><span class=rvts32>charge</span><span class=rvts30> </span><span class=rvts32>ne</span><span class=rvts30> </span><span class=rvts32>sera</span><span class=rvts30> </span><span class=rvts32>valable</span><span class=rvts30> </span><span class=rvts32>que</span><span class=rvts30> </span><span class=rvts32>si</span><span class=rvts30> </span><span class=rvts32>la</span><span class=rvts30> </span><span class=rvts32>facture</span><span class=rvts30> </span><span class=rvts32>nous</span><span class=rvts30> </span><span class=rvts32>parvient</span><span class=rvts30> </span><span class=rvts32>accompagnée</span><span class=rvts30> </span><span class=rvts32>de</span><span class=rvts30> </span><span class=rvts29>l</span><span class=rvts33>’</span><span class=rvts29>original</span><span class=rvts30> </span><span class=rvts32>de</span><span class=rvts30>&nbsp;</span><span class=rvts32> </span><span class=rvts34><input name="CL_nom_doc_sig" id="CL_nom_doc_sig" placeholder=""  value="
<?php  
if(isset($CL_nom_doc_sig)) {echo $CL_nom_doc_sig;} 
foreach ($array_sig as $docsigne) 
{     
		 echo $docsigne['nom'].' '; 
	      }    ?> " ></input></span></span><span class=rvts32> </span><span class=rvts31>dûment</span><span class=rvts35> </span><span class=rvts31>complétée</span><span class=rvts30> </span><span class=rvts32>et</span><span class=rvts30> </span><span class=rvts32>signée</span><span class=rvts30> </span><span class=rvts32>par</span><span class=rvts30> </span><span class=rvts32>le</span><span class=rvts30> </span><span class=rvts32>(la) patient(e)</span></p>
<p><span class=rvts32><br></span></p>
<?php

	} else { 
?>
<input name="CL_nom_doc_sig" type="hidden" placeholder="" value="<?php if(isset($CL_nom_doc_sig)) {echo $CL_nom_doc_sig;}  ?>"></input>
<?php

	} } 
?>

<input name="CL_doc_sig" type="hidden" placeholder="" value="<?php if(isset($CL_doc_sig)) {echo $CL_doc_sig;} if ($detaildos['documents']==='1'){ echo "Attention : Cette prise en charge ne sera valable que si la facture nous parvient accompagnée de l'original de "   ;} else { echo '' ;}?>"></input>
<input name="CL_doc_sign" type="hidden" placeholder="" value="<?php if(isset($CL_doc_sign)) {echo $CL_doc_sign;} if ($detaildos['documents']==='1'){ echo 'dûment  complétée  et  signée  par  le  (la) patient(e)'   ;} else { echo '' ;}?>"></input>
<p class=rvps9><span class=rvts16>Observations:</span><span class=rvts11> <input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
<p><span class=rvts32><br></span></p>
<p><span class=rvts36><br></span></p>
<p class=rvps1><span class=rvts37>ATTENTION IMPORTANT</span><span class=rvts38> </span></p>
<p class=rvps1><span class=rvts39>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts39>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts40>’</span><span class=rvts39>accompagnerait</span><span class=rvts36>.</span></p>
<p><span class=rvts3><br></span></p>
<p><span class=rvts3>Merci de votre collaboration.</span></p>
<p><span class=rvts3><br></span></p>
<p><span class=rvts3><br></span></p>
<p><span class=rvts3>P/ la Gérante</span></p>
<p class=rvps1><span class=rvts9> <input name="agent__name" id="agent__name" placeholder="prenom du lagent" value="<?php if(isset ($detailagt['name'])) echo $detailagt['name']; ?>" /> <input name="agent__lastname" id="agent__lastname" placeholder="nom du lagent" value="<?php if(isset ($detailagt['lastname'])) echo $detailagt['lastname']; ?>" /></span></p>
<p class=rvps1><span class=rvts9> <input name="agent__signature" id="agent__signature" placeholder="signature" value="<?php if(isset ($detailagt['signature'])) echo $detailagt['signature']; ?>" /></span></p>
<p><span class=rvts3>Plateau d</span><span class=rvts41>’</span><span class=rvts3>assistance médicale</span></p>
<p class=rvps1><span class=rvts3>« courrier électronique, sans signature »</span></p>
</form>
<script language="javascript" src="nombre_en_lettre.js"></script>
<script type="text/javascript">
    function keyUpHandler(obj){
	<?php if (intval($montantgop) > 0) { ?>
            if (obj.value > <?php echo $montantgop; ?>) {document.getElementById("alertGOP").style.display="block";}
            else {document.getElementById("alertGOP").style.display="none";}
	<?php } ?>
            document.getElementById("CL_montant_toutes_lettres").value  = NumberToLetter(obj.value)
        }//fin de keypressHandler
	// statut malade checkbox
    $("#CL_attention").change(function() {
        if(this.checked) {
            $("#pattention").show();
        }
        else
        {
            $("#pattention").hide();
        }
    });	
document.querySelector('input[list="prest__structure"]').addEventListener('input', onInput);

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


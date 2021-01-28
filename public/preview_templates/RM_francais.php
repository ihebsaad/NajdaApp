<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['ville'])) {$ville=$_GET['ville'];}
if (isset($_GET['inter__structure'])) {$inter__structure=$_GET['inter__structure'];}
if (isset($_GET['inter__medecin'])) {$inter__medecin=$_GET['inter__medecin'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname'];$subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['CL_age'])) {$CL_age=$_GET['CL_age'];}
if (isset($_GET['reference_customer'])) {$reference_customer=$_GET['reference_customer']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_rapport'])) {$CL_rapport=$_GET['CL_rapport']; }
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

$sqlvh = "SELECT medecin_traitant3,medecin_traitant, medecin_traitant2 FROM dossiers WHERE id=".$iddossier."";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
           $array_prest[] = array('id' => 1,'medecin_traitant' => $rowvh["medecin_traitant"]  );
$medecin=$rowvh["medecin_traitant"];
$medecin1=$rowvh["medecin_traitant2"];
$medecin2=$rowvh["medecin_traitant3"];
            $array_prest[] = array('id' => 2,'medecin_traitant' => $rowvh["medecin_traitant2"]  );
            $array_prest[] = array('id' => 3,'medecin_traitant' => $rowvh["medecin_traitant3"]  );
        }}
$sqlstruc = "SELECT hospital_address3,hospital_address,hospital_address2 FROM dossiers WHERE id=".$iddossier."";

    $resultstruc = $conn->query($sqlstruc);
    if ($resultstruc->num_rows > 0) {

        $array_struc = array();
        while($rowstruc = $resultstruc->fetch_assoc()) {
            $array_struc[] = array('hospital_address' => $rowstruc["hospital_address3"]  );
            $array_struc[] = array('hospital_address' => $rowstruc["hospital_address2"]  );
            $array_struc[] = array('hospital_address' => $rowstruc["hospital_address"]  );
            }}
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
 $sqlagt = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM specialites_typeprestations WHERE type_prestation=15) AND id IN (SELECT specialite FROM specialites_prestataires WHERE prestataire_id  IN (SELECT id FROM prestataires WHERE name='{$medecin}'))";
		$resultagt = $conn->query($sqlagt);
		if ($resultagt->num_rows > 0) {
	    // output data of each row
	    $detailagt = $resultagt->fetch_assoc();
	    
		} else {
	    echo "";
		}
 $sqlagt1 = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM specialites_typeprestations WHERE type_prestation=15) AND id IN (SELECT specialite FROM specialites_prestataires WHERE prestataire_id  IN (SELECT id FROM prestataires WHERE name='{$medecin1}'))";
		$resultagt1 = $conn->query($sqlagt1);
		if ($resultagt1->num_rows > 0) {
	    // output data of each row
	    $detailagt1 = $resultagt1->fetch_assoc();
	    
		} else {
	    echo "";
		}
$sqlagt2 = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM specialites_typeprestations WHERE type_prestation=15) AND id IN (SELECT specialite FROM specialites_prestataires WHERE prestataire_id  IN (SELECT id FROM prestataires WHERE name='{$medecin2}'))";
		$resultagt2 = $conn->query($sqlagt2);
		if ($resultagt2->num_rows > 0) {
	    // output data of each row
	    $detailagt2 = $resultagt2->fetch_assoc();
	    
		} else {
	    echo "";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//FR">
<html><head><title>v0v3vdqk8khqpdac0lr3ipig4xm8mf3m_RM_francais</title>
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
            font-size: 16pt;
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts4
        {
            font-size: 16pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 11pt;
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-weight: bold;
        }
        span.rvts6
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts7
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #000000;
        }
        span.rvts8
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #000000;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #000000;
        }
        span.rvts11
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
            widows: 2;
            orphans: 2;
            border-color: #000000;
            border-style: solid;
            border-width: 2px;
            border-top: none;
            border-right: none;
            border-left: none;
            padding: 0px 0px 1px 0px;
        }
        .rvps5
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
		 .rvps6
        {
            text-align: right;
            widows: 2;
            orphans: 2;
        }
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<p class=rvps6><span class=rvts1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
<input name="CL_text"  type="hidden" value="Att: " />
<span class=rvts2>Att: <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps6><span class=rvts5>Sousse<input name="date_heure" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps3><span class=rvts3>Rapport Médical</span></p>
<p><span class=rvts6><br></span></p>
<p><span class=rvts6><br></span></p>

<?php
if($detaildos['benefdiff']==='1')
{
?>

<p class=rvps7><span class=rvts7>Nom patient : <select id="subscriber__name" name="subscriber__name" autocomplete="off"  >
            <?php
foreach ($array_benef as $benef) {
    if($benef['beneficiaire'] === $subscriber__name) {
    
    echo "<option value='".$benef['beneficiaire']."'  selected>".$benef['beneficiaire']."</option>";}
       else {
    
    echo "<option value='".$benef['beneficiaire']."' >".$benef['beneficiaire']."</option>";}
}
?>
</select> </span></p>
<p><span class=rvts7>Prénom : <select id="subscriber__lastname" name="subscriber__lastname" autocomplete="off"  >
            <?php

foreach ($array_benef as $benef) {
    if($benef['prenom_benef'] === $subscriber__lastname) {
    
    
    echo "<option value='".$benef['prenom_benef']."' selected >".$benef['prenom_benef']."</option>";}
    else{
       echo "<option value='".$benef['prenom_benef']."' >".$benef['prenom_benef']."</option>";  
    }
}
?>
</select></span></p>

<?php
}else
{
?>
<p class=rvps7><span class=rvts7>Nom patient : <input name="subscriber__name" id="subscriber__name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" />  </span></p>
<p><span class=rvts7>Prénom : <input id="subscriber__lastname"  name="subscriber__lastname" placeholder="nom du l'abonnée"  value="<?php  if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"/></span></p>
<?php
}
?>


<p><span class=rvts7>Age : <input name="CL_age" placeholder="Age" value="<?php if(isset ($CL_age)) echo $CL_age; ?>"></input> </span></p>
<input name="CL_reference"  type="hidden" value="V/Réf: " />
<p class=rvps7><span class=rvts7>V/Réf:  <input name="reference_customer" placeholder="reference Client" value="<?php if(isset ($reference_customer)) echo $reference_customer; ?>"></input> <input name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value="<?php if(isset ($customer_id__name2)) echo $customer_id__name2; ?>" /></span></p>
<p class=rvps7><span class=rvts7>O/Ref:  <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"></input/> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>" /> </span></p>
<p class=rvps7><span class=rvts7>Hospitalisé à : </span><span class=rvts8>
<input type="text" list="inter__structure" name="inter__structure" value="<?php  if(isset ($inter__structure)) echo $inter__structure;?>"   />
        <datalist id="inter__structure">
            <?php
foreach ($array_struc as $struc) {
    
    echo "<option value='".$struc['hospital_address']."' >".$struc['hospital_address']."</option>";
}
?>
</span></p>
<p class=rvps7><span class=rvts7>Médecin traitant : </span><span class=rvts8> 
<input type="text" list="inter__medecin" name="inter__medecin" value="<?php  if(isset ($inter__medecin)) echo $inter__medecin;?>" />
        <datalist id="inter__medecin">
            <?php
foreach ($array_prest as $prest) {
    if($prest['id']===1)
    {
if(!empty($detailagt['nom']))
{
$medec='('.$detailagt['nom'].')';}
else
{$medec='';}
    echo "<option value='".$prest['medecin_traitant'].''.$medec."' >".$prest['medecin_traitant'].''.$medec."</option>"; }
if($prest['id']===2)
    {
if(!empty($detailagt1['nom']))
{
$medec1='('.$detailagt1['nom'].')';}
else
{$medec1='';}
    echo "<option value='".$prest['medecin_traitant'].''.$medec1."' >".$prest['medecin_traitant'].''.$medec1."</option>"; }
if($prest['id']===3)
    {
if(!empty($detailagt2['nom']))
{
$medec2='('.$detailagt2['nom'].')';}
else
{$medec2='';}
    echo "<option value='".$prest['medecin_traitant'].''.$medec2."' >".$prest['medecin_traitant'].''.$medec2."</option>"; }
  }
?>
</span></p>
<p class=rvps3><span class=rvts3><br></span></p>
<p class=rvps7><span class=rvts7><textarea name="CL_rapport" rows="10" cols="90" form="formchamps" placeholder="" value=""><?php if(isset ($CL_rapport)) { $ligne = str_replace('\\', "\n", $CL_rapport); echo $ligne;} ?></textarea></span></p>
<h1 class=rvps6><span class=rvts0><span class=rvts12><br></span></span></h1>
</body></html>


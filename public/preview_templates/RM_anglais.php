<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['ville'])) {$ville=$_GET['ville'];}
if (isset($_GET['CL_text'])) {$CL_text=$_GET['CL_text'];}
if (isset($_GET['inter__structure'])) {$inter__structure=$_GET['inter__structure'];}
if (isset($_GET['inter__medecin'])) {$inter__medecin=$_GET['inter__medecin'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname'];$subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['CL_age'])) {$CL_age=$_GET['CL_age'];}
if (isset($_GET['reference_customer'])) {$reference_customer=$_GET['reference_customer']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
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
            $array_prest[] = array('medecin_traitant' => $rowvh["medecin_traitant"]  );
            $array_prest[] = array('medecin_traitant' => $rowvh["medecin_traitant2"]  );
            $array_prest[] = array('medecin_traitant' => $rowvh["medecin_traitant3"]  );
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>v0v3vdqk8khqpdac0lr3ipig4xm8mf3m_RM_anglais</title>
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
 <?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_text"  type="hidden" value="Att: " />
<span class=rvts2>Att: <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<?php 
}
 else
 {
?>
<input name="CL_text"  type="hidden" value="" />
<span class=rvts14> <input  type="hidden" name="customer_id__name" id="customer_id__name" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?><p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps6><span class=rvts5>Sousse <input name="date_heure" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps3><span class=rvts3>Medical report</span></p>
<p><span class=rvts6><br></span></p>
<p><span class=rvts6><br></span></p>
<p class=rvps7><span class=rvts7>Name: <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p><span class=rvts7>Surname: <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> </span></p>
<p><span class=rvts7>Age : <input name="CL_age" placeholder="Age" value="<?php if(isset ($CL_age)) echo $CL_age; ?>"></input> </span></p>
<?php 
{if( $IMA==='oui' )
 {
?>
<input name="CL_reference"  type="hidden" value="Y/Ref : " />
<p class=rvps7><span class=rvts7>Y/Ref:  <input name="reference_customer" placeholder="reference Client" value="<?php if(isset ($reference_customer)) echo $reference_customer; ?>"></input>  <input name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value="<?php if(isset ($customer_id__name2)) echo $customer_id__name2; ?>" /></span></p>

<?php 
}
 else
 {
?>
<input name="CL_reference"  type="hidden" value="" />
<p class=rvps9><span class=rvts14> <input  type="hidden" name="reference_customer" id="reference_customer" placeholder="compagnie" value=" " /></span></p>
<p class=rvps9><span class=rvts14> <input  type="hidden" name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value=" " /></span></p>
<?php 
}
 }
?>
<p class=rvps7><span class=rvts7>O/Ref:  <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /> </span></p>
<p class=rvps7><span class=rvts7>Hospitalized in:</span><span class=rvts8>
<input type="text" list="inter__structure" name="inter__structure" value="<?php  if(isset ($inter__structure)) echo $inter__structure;?>"   />
        <datalist id="inter__structure">
            <?php
foreach ($array_struc as $struc) {
    
    echo "<option value='".$struc['hospital_address']."' >".$struc['hospital_address']."</option>";
}
?>
</span></p>
<p class=rvps7><span class=rvts7>Treating doctor: </span><span class=rvts8>
<input type="text" list="inter__medecin" name="inter__medecin" value="<?php  if(isset ($inter__medecin)) echo $inter__medecin;?>" />
        <datalist id="inter__medecin">
            <?php
foreach ($array_prest as $prest) {
    
    echo "<option value='".$prest['medecin_traitant']."' >".$prest['medecin_traitant']."</option>"; }
?>
</span></p>
<p class=rvps3><span class=rvts3><br></span></p>
<h1 class=rvps6><span class=rvts0><span class=rvts12><br></span></span></h1>
</body></html>

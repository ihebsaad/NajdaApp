<?php
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['reference_customer'])) {$reference_customer=$_GET['reference_customer']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name'];  }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['CL_rapport'])) {$CL_rapport=$_GET['CL_rapport'];}
if (isset($_GET['CL_action1'])) {$CL_action1=$_GET['CL_action1'];}
if (isset($_GET['CL_delai'])) {$CL_delai=$_GET['CL_delai'];}
if (isset($_GET['CL_action2'])) {$CL_action2=$_GET['CL_action2'];}
if (isset($_GET['CL_delai2'])) {$CL_delai2=$_GET['CL_delai2'];}
if (isset($_GET['CL_action3'])) {$CL_action3=$_GET['CL_action3'];}
if (isset($_GET['CL_delai3'])) {$CL_delai3=$_GET['CL_delai3'];}
if (isset($_GET['CL_action4'])) {$CL_action4=$_GET['CL_action4'];}
if (isset($_GET['CL_delai4'])) {$CL_delai4=$_GET['CL_delai4'];}

if (isset($_GET['CL_observation'])) {$CL_observation=$_GET['CL_observation'];}
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
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

$sqlexpert = "SELECT id,date_prestation,type_prestations_id,specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 23";

    $resultexpert = $conn->query($sqlexpert);
    if ($resultexpert->num_rows > 0) {

        $array_expert = array();
        while($rowexpert = $resultexpert->fetch_assoc()) {
            $array_expert[] = array('id' => $rowexpert["id"],"date_prestation" => $rowexpert["date_prestation"],'type_prestations_id' => $rowexpert["type_prestations_id"],"specialite" => $rowexpert["specialite"]);
        }}
$sqlhotel = "SELECT id,date_prestation,type_prestations_id,specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 18";

    $resulthotel = $conn->query($sqlhotel);
    if ($resulthotel->num_rows > 0) {

        $array_hotel = array();
        while($rowhotel = $resulthotel->fetch_assoc()) {
            $array_hotel[] = array('id' => $rowhotel["id"],"date_prestation" => $rowhotel["date_prestation"],'type_prestations_id' => $rowhotel["type_prestations_id"],"specialite" => $rowhotel["specialite"]);
        }}
$sqltax = "SELECT id,date_prestation,type_prestations_id,specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 2";

    $resulttax = $conn->query($sqltax);
    if ($resulttax->num_rows > 0) {

        $array_tax = array();
        while($rowtax = $resulttax->fetch_assoc()) {
            $array_tax[] = array('id' => $rowtax["id"],"date_prestation" => $rowtax["date_prestation"],'type_prestations_id' => $rowtax["type_prestations_id"],"specialite" => $rowtax["specialite"]  );
        }}
$sqlremor = "SELECT id,date_prestation,type_prestations_id,specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 1";

    $resultremor= $conn->query($sqlremor);
    if ($resultremor->num_rows > 0) {

        $array_remor = array();
        while($rowremor= $resultremor->fetch_assoc()) {
            $array_remor[] = array('id' => $rowremor["id"],"date_prestation" => $rowremor["date_prestation"],'type_prestations_id' => $rowremor["type_prestations_id"],"specialite" => $rowremor["specialite"]  );
        }}
$sqlautre = "SELECT id,date_prestation,type_prestations_id,specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id NOT IN (1,2,23,18)";

    $resultautre= $conn->query($sqlautre);
    if ($resultautre->num_rows > 0) {

        $array_autre = array();
        while($rowautre= $resultautre->fetch_assoc()) {
            $array_autre[] = array('id' => $rowautre["id"],"date_prestation" => $rowautre["date_prestation"],'type_prestations_id' => $rowautre["type_prestations_id"],"specialite" => $rowautre["specialite"]  );
        }}
$sqlse = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 23)";

    $resultse = $conn->query($sqlse);
    if ($resultse->num_rows > 0) {

        $array_se = array();
        while($rowse = $resultse->fetch_assoc()) {
            $array_se[] = array('id' => $rowse["id"],"nom" => $rowse["nom"]  );
        }}
$sqlsh = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 18)";

    $resultsh = $conn->query($sqlsh);
    if ($resultsh->num_rows > 0) {

        $array_sh = array();
        while($rowsh = $resultsh->fetch_assoc()) {
            $array_sh[] = array('id' => $rowsh["id"],"nom" => $rowsh["nom"]  );
        }}
$sqlst = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 2)";

    $resultst = $conn->query($sqlst);
    if ($resultst->num_rows > 0) {

        $array_st = array();
        while($rowst = $resultst->fetch_assoc()) {
            $array_st[] = array('id' => $rowst["id"],"nom" => $rowst["nom"]  );
        }}
$sqlsr = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1  AND type_prestations_id = 1)";

    $resultsr = $conn->query($sqlsr);
    if ($resultsr->num_rows > 0) {

        $array_sr = array();
        while($rowsr = $resultsr->fetch_assoc()) {
            $array_sr[] = array('id' => $rowsr["id"],"nom" => $rowsr["nom"]  );
        }}
$sqlsa = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1 AND type_prestations_id NOT IN (1,2,23,18))";

    $resultsa = $conn->query($sqlsa);
    if ($resultsa->num_rows > 0) {

        $array_sa = array();
        while($rowsa = $resultsa->fetch_assoc()) {
            $array_sa[] = array('id' => $rowsa["id"],"nom" => $rowsa["nom"]  );
        }}
$sqlpa = "SELECT id,name FROM type_prestations WHERE id IN (SELECT type_prestations_id FROM prestations WHERE dossier_id=".$iddossier." AND effectue= 1 AND type_prestations_id NOT IN (1,2,23,18))";

    $resultpa = $conn->query($sqlpa);
    if ($resultpa->num_rows > 0) {

        $array_pa = array();
        while($rowpa = $resultpa->fetch_assoc()) {
            $array_pa[] = array('id' => $rowpa["id"],"name" => $rowpa["name"]  );
        }}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>lnozr6vuqhzbh2ivjidu8xla90htp32y_Modele_Fax_Ima_Rev</title>
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
 font-size: 11pt;
 font-family: 'Arial Narrow';
 font-weight: bold;
}
span.rvts2
{
 font-size: 12pt;
 font-family: 'Arial Narrow';
 font-weight: bold;
}
span.rvts3
{
 font-family: 'Arial Narrow';
}
span.rvts4
{
 font-family: 'Arial', 'Helvetica', sans-serif;
 font-style: italic;
}
span.rvts5
{
 font-family: 'Arial', 'Helvetica', sans-serif;
 color: #000000;
}
span.rvts6
{
 color: #000000;
}
span.rvts7
{
 font-weight: bold;
}
span.rvts8
{
}
span.rvts9
{
 font-size: 28pt;
 font-family: 'Harlow Solid Italic';
 font-weight: bold;
}
span.rvts10
{
 font-family: 'Arial', 'Helvetica', sans-serif;
 font-weight: bold;
}
span.rvts11
{
 font-size: 12pt;
 font-family: 'Arial', 'Helvetica', sans-serif;
 font-weight: bold;
}
span.rvts12
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #000000;
}
span.rvts13
{
 font-size: 12pt;
 font-family: 'Arial', 'Helvetica', sans-serif;
 font-weight: bold;
 color: #000000;
}
span.rvts14
{
 font-family: 'Arial Narrow';
 font-weight: bold;
}
span.rvts15
{
 font-family: 'Arial', 'Helvetica', sans-serif;
}
span.rvts16
{
 font-size: 8pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
 color: #000000;
}
span.rvts17
{
 font-size: 8pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #000000;
}
span.rvts18
{
 font-family: 'Arial Narrow';
 color: #0070c0;
}
span.rvts19
{
 font-family: 'Arial Narrow';
 color: #000000;
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
 widows: 2;
 orphans: 2;
 margin: 0px 0px 8px 0px;
}
.rvps5
{
 widows: 2;
 orphans: 2;
}
.rvps6
{
 widows: 2;
 orphans: 2;
 margin: 12px 0px 12px 0px;
}
.rvps7
{
 text-align: center;
 widows: 2;
 orphans: 2;
 margin: 4px 0px 0px 0px;
}
.rvps8
{
 widows: 2;
 orphans: 2;
 margin: 5px 0px 5px 0px;
}
.rvps9
{
 widows: 2;
 orphans: 2;
}
.rvps10
{
 text-align: center;
 widows: 2;
 orphans: 2;
 margin: 16px 0px 0px 0px;
}
.rvps11
{
 text-align: center;
 widows: 2;
 orphans: 2;
 margin: 8px 0px 0px 0px;
}
.rvps12
{
 widows: 2;
 orphans: 2;
}
.rvps13
{
 widows: 2;
 orphans: 2;
}
--></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<p class=rvps1><span class=rvts1>Identification du correspondant </span><span class=rvts2>:&nbsp;&nbsp;&nbsp; </span></p>
<p class=rvps2><span class=rvts3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>
<p class=rvps2><span class=rvts1>Référence Najda </span><span class=rvts4>: </span><span class=rvts5></span><span class=rvts6> <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p class=rvps2><span class=rvts4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>
<p class=rvps2><span class=rvts7>Référence IMA : </span><span class=rvts8><input name="reference_customer" id="reference_customer"placeholder="reference client" value="<?php if(isset ($reference_customer)) echo $reference_customer; ?>"></input></span></p>
<p class=rvps2><span class=rvts8><br></span></p>
<p class=rvps2><span class=rvts7>Nom : </span><span class=rvts8><input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p class=rvps2><span class=rvts4>&nbsp;</span><span class=rvts9>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>
<p class=rvps3><span class=rvts9> </span><span class=rvts10>Sousse le&nbsp;<input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input> </span></p>
<p><span class=rvts11><br></span></p>
<p><span class=rvts11>Informations de Najda vers IMA : </span><span class=rvts12>
<p><span class=rvts13><br></span></p>
<textarea name="CL_rapport" rows="10" cols="90" form="formchamps" placeholder="" value=""><?php if(isset ($CL_rapport)) { $ligne = str_replace('\\', "\n", $CL_rapport); echo $ligne;} ?></textarea></span></p>
<p><span class=rvts13><br></span></p>
<p class=rvps4><span class=rvts11>Actions à mettre en oeuvre par IMA:</span></p>
<p class=rvps5><span class=rvts11><br></span></p>
<div class=rvps9><table border=1 cellpadding=4 cellspacing=-1 style="border-width: 0px; border-collapse: collapse;">
<tr valign=top>
<td width=380 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none; background-color: #c0c0c0;"><p class=rvps6><span class=rvts14> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts14>Actions</span><span class=rvts14> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
</td>
<td width=213 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; background-color: #c0c0c0;"><p class=rvps7><span class=rvts14>Délai de mise en œuvre</span><br><span class=rvts14>Date et heure</span></p>
</td>
</tr>
<tr valign=top>
<td width=380 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p class=rvps8><span class=rvts5><input name="CL_action1" placeholder="Action 1" value="<?php if(isset ($CL_action1)) echo $CL_action1; ?>"></input></span></p>
</td>
<td width=213 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px;"><p class=rvps8><span class=rvts5><input name="CL_delai" placeholder="Delai 1" value="<?php if(isset ($CL_delai)) echo $CL_delai; ?>"></input></span></p>
</td>
</tr>
<tr valign=top>
<td width=380 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p class=rvps8><span class=rvts5><input name="CL_action2" placeholder="Action 2" value="<?php if(isset ($CL_action2)) echo $CL_action2; ?>"></input></span></p>
</td>
<td width=213 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px;"><p class=rvps8><span class=rvts5><input name="CL_delai2" placeholder="Delai 2" value="<?php if(isset ($CL_delai2)) echo $CL_delai2; ?>"></input></span></p>
</td>
</tr>
<tr valign=top>
<td width=380 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p class=rvps8><span class=rvts5><input name="CL_action3" placeholder="Action 3" value="<?php if(isset ($CL_action3)) echo $CL_action3; ?>"></input></span></p>
</td>
<td width=213 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px;"><p class=rvps8><span class=rvts5><input name="CL_delai3" placeholder="Delai 3" value="<?php if(isset ($CL_delai3)) echo $CL_delai3; ?>"></input></span></p>
</td>
</tr>
<tr valign=top>
<td width=380 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p class=rvps8><span class=rvts5><input name="CL_action4" placeholder="Action 4" value="<?php if(isset ($CL_action4)) echo $CL_action4; ?>"></input></span></p>
</td>
<td width=213 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px;"><p class=rvps8><span class=rvts5><input name="CL_delai4" placeholder="Delai 4" value="<?php if(isset ($CL_delai4)) echo $CL_delai4; ?>"></input> </span></p>
</td>
</tr>
</table>
</div>
<p class=rvps5><span class=rvts11><br></span></p>
<p class=rvps5><span class=rvts11>Synthèse des moyens mis en œuvre par Najda :</span></p>
<p class=rvps5><span class=rvts11><br></span></p>
<p class=rvps5><span class=rvts15> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
<div class=rvps9><table border=1 cellpadding=4 cellspacing=-1 style="border-width: 0px; border-collapse: collapse;">
<tr valign=top>
<td width=110 height=43 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-top: none; border-right: none; border-left: none; background-color: #ffffff;"><p class=rvps10><span class=rvts14><br></span></p>
</td>
<td width=104 height=43 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none; background-color: #c0c0c0;"><p class=rvps11><span class=rvts14>DEPANNAGE / REMORQUAGE</span></p>
</td>
<td width=92 height=43 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none; background-color: #c0c0c0;"><p class=rvps10><span class=rvts14>TAXI (S)</span></p>
</td>
<td width=75 height=43 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none; background-color: #c0c0c0;"><p class=rvps10><span class=rvts14>HOTEL</span></p>
</td>
<td width=76 height=43 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none; background-color: #c0c0c0;"><p class=rvps10><span class=rvts14>EXPERT</span><br><span class=rvts8><br></span></p>
</td>
<td width=76 height=43 valign=top style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; background-color: #c0c0c0;"><p class=rvps10><span class=rvts14>AUTRES</span></p>
</td>
</tr>
<tr valign=top>
<td width=110 height=91 valign=middle style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p class=rvps12><span class=rvts14><br></span></p>
</td>
<td width=104 height=91 valign=middle style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p><span class=rvts16><br></span></p>
<p><span class=rvts17><textarea name="CL_prest" rows="5" cols="12" form="formchamps" placeholder="" value=""><?php if(isset ($CL_prest)) { $ligne = str_replace('\\', "\n", $CL_prest); echo $ligne;}
foreach ($array_remor as $remor) 
{     $specr='';
		foreach ($array_sr as $sr)
		{  if($remor['specialite']==$sr['id']){$specr=$sr['nom'].": ";} }
echo '-Remorquage: '.$specr.$remor['date_prestation']."\n";  
	      } ?></textarea></span></p>
<p><span class=rvts17><br></span></p>
</td>
<td width=92 height=91 valign=middle style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p><span class=rvts17><textarea name="CL_prest1" rows="5" cols="10" form="formchamps" placeholder="" value=""><?php if(isset ($CL_prest1)) { $ligne = str_replace('\\', "\n", $CL_prest1); echo $ligne;} foreach ($array_tax as $taxi) 
{     $spect='';
		foreach ($array_st as $st)
		{  if($taxi['specialite']==$st['id']){$spect=$st['nom'].": ";} }
echo '-Taxi : '.$spect.$taxi['date_prestation']."\n";  
	      }?></textarea></span></p>
</td>
<td width=75 height=91 valign=middle style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p><span class=rvts17><textarea name="CL_prest2" rows="5" cols="10" form="formchamps" placeholder="" value=""><?php if(isset ($CL_prest2)) { $ligne = str_replace('\\', "\n", $CL_prest2); echo $ligne;}    
$spech='';
foreach ($array_hotel as $hotel) 
{     $spech='';
		foreach ($array_sh as $sh)
		{  if($hotel['specialite']==$sh['id']){$spech=$sh['nom'].": ";} }
echo '-Hôtel : '.$spech.$hotel['date_prestation']."\n";  
	      }  ?>  </textarea></span></p>
</td>
<td width=76 height=91 valign=middle style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px; border-right: none;"><p><span class=rvts17><br></span></p>
<p><span class=rvts17><textarea list="CL_prest3" name="CL_prest3" rows="5" cols="10" form="formchamps" placeholder="" value=""><?php if(isset ($CL_prest3)) { $ligne = str_replace('\\', "\n", $CL_prest3); echo $ligne;
}  

foreach ($array_expert as $expert) 
{  $spec='';
foreach ($array_se as $se)
		{  if($expert['specialite']==$se['id']){$spec=$se['nom'].": ";} }
echo '-Expertise auto : '.$spec.$expert['date_prestation']."\n"; 
	       }?>
</textarea></span></p>
<p><span class=rvts17><br></span></p>
</td>
<td width=76 height=91 valign=middle style="border-width : 1px; border-color: #000000; border-style: solid; padding: 0px 7px;"><p><span class=rvts17><textarea name="CL_prest4" rows="5" cols="10" form="formchamps" placeholder="" value=""><?php if(isset ($CL_prest4)) { $ligne = str_replace('\\', "\n", $CL_prest4); echo $ligne;} 
foreach ($array_autre as $autre) 
{  $speca='';
 $presta='-Autre : ';
foreach ($array_sa as $sa)
		{  if($autre['specialite']==$sa['id']){$speca=$sa['nom'].": ";} }foreach ($array_pa as $pa)
		{  if($autre['type_prestations_id']==$pa['id']){$presta='-'.$pa['name'].": ";} }
echo $presta.$speca.$autre['date_prestation']."\n"; 
	       }

?></textarea></span></p>
</td>
</tr>
</table>
</div>
<p><span class=rvts15><br></span></p>
<p class=rvps13><span class=rvts14>Observations </span><span class=rvts3>: </span><span class=rvts18></span><span class=rvts19><input name="CL_observation" placeholder="Observations" value="<?php if(isset ($CL_observation)) echo $CL_observation; ?>"></input></span></p>
</body></html>

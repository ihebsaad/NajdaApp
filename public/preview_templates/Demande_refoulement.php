<?php 
if (isset($_GET['ID_DOSSIER'])) {$iddossier=$_GET['ID_DOSSIER'];}
if (isset($_GET['ville'])) {$ville=$_GET['ville'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['subscriber__name'])) {$subscriber__name=$_GET['subscriber__name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber__lastname'])) {$subscriber__lastname=$_GET['subscriber__lastname']; $subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['CL_payee'])) {$CL_payee=$_GET['CL_payee'];}
if (isset($_GET['CL_passeport'])) {$CL_passeport=$_GET['CL_passeport'];}
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_marque'])) {$vehicule_marque=$_GET['vehicule_marque'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['CL_accidente'])) {$CL_accidente=$_GET['CL_accidente'];}
if (isset($_GET['CL_enpanne'])) {$CL_enpanne=$_GET['CL_enpanne'];}
if (isset($_GET['CL_incendie'])) {$CL_incendie=$_GET['CL_incendie'];}
if (isset($_GET['CL_intact'])) {$CL_intact=$_GET['CL_intact'];}
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
$sqldos = "SELECT id,benefdiff,subscriber_name,subscriber_lastname FROM dossiers WHERE id=".$iddossier."";
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
<html><head><title>Demande_refoulement</title>
<!-- https://www.coolutils.com/online/RTF-to-HTML# -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css"><!-- 

span.rvts1
{
 font-size: 12pt;
}
span.rvts2
{
 font-size: 12pt;
 color: #0070c0;
}
span.rvts3
{
 font-size: 16pt;
}
span.rvts4
{
 font-size: 16pt;
 font-weight: bold;
}
span.rvts5
{
}
span.rvts6
{
 font-size: 12pt;
 font-weight: bold;
}
span.rvts7
{
 font-size: 12pt;
 text-decoration: underline;
}
span.rvts8
{
 font-size: 12pt;
}
span.rvts9
{
 font-size: 12pt;
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
 text-align: right;
 widows: 2;
 orphans: 2;
}
.rvps2
{
 text-align: center;
 widows: 2;
 orphans: 2;
}
.rvps3
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 2.00;
 widows: 2;
 orphans: 2;
}
.rvps4
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 text-indent: -24px;
 line-height: 2.00;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 48px;
}
.rvps5
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 2.00;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 48px;
}
/* ========== Lists ========== */
.list0 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: disc;}
.list1 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: circle;}
.list2 {text-indent: 0px; padding: 0; margin: 0 0 0 48px; list-style-position: outside; list-style-type: square;}
--></style>
</head>
<body>
<form id="formchamps">
<input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"></input>
<p class=rvps1><span class=rvts1> <input name="ville" id="ville" placeholder="" readonly value="<?php if(isset ($ville)) {echo $ville;}  if (empty($ville)){ echo ".............................." ;}?>" /><input name="date_heure" type="hidden" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps2><span class=rvts2><br></span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p class=rvps2><span class=rvts4><br></span></p>
<p class=rvps2><span class=rvts4>Att Mr le chef de Bureau de la douane du Port de Rades </span></p>
<p class=rvps2><span class=rvts3><br></span></p>
<p><span class=rvts5><br></span></p>
<p><span class=rvts6><br></span></p>
<p><span class=rvts6>Objet : </span><span class=rvts7>Demande de refoulement</span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7><br></span></p>
<p class=rvps3><span class=rvts1>Je soussigné Mr/Mme 
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
</select><select id="subscriber__name" name="subscriber__name" autocomplete="off"  >
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
 <input id="subscriber__lastname"  name="subscriber__lastname" placeholder="nom du l'abonnée"  value="<?php  if(isset ($detaildos['subscriber_lastname'])) echo $detaildos['subscriber_lastname']; ?>"/> <input name="subscriber__name" id="subscriber__name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildos['subscriber_name'])) echo $detaildos['subscriber_name']; ?>"
  />
	<?php
}
?>


	titulaire du passeport <input name="CL_payee" placeholder="pays"  value="<?php if(isset ($CL_payee)) echo $CL_payee; ?>"></input> n° <input name="CL_passeport" placeholder="numéro du passeport" value="<?php if(isset ($CL_passeport)) echo $CL_passeport; ?>"></input>, viens par la présente vous prier de bien vouloir me donner votre accord pour le refoulement de mon véhicule <input name="vehicule_marque" placeholder="marque du véhicule
" value="<?php if(isset ($vehicule_marque)) echo $vehicule_marque; ?>"></input> <input name="vehicule_type" placeholder="Type du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input>  immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input>.</span></p>
<p class=rvps3><span class=rvts1><br></span></p>
<?php
{ if (isset($CL_accidente))
	{ if ($CL_accidente === "Accidenté") { ?>	
		<input type="checkbox" name="CL_accidente" id="CL_accidente" checked value="oui">
		 <label for="CL_accidente">Accidenté</label>
		<?php } else { ?>
		<input type="checkbox" name="CL_accidente" id="CL_accidente" value="">
		 <label for="CL_accidente">Accidenté</label>
<?php }} else { ?>
<input type="checkbox" name="CL_accidente" id="CL_accidente" value="oui">
 <label for="CL_accidente">Accidenté</label>
<?php }
} ?>
<p class=rvps3><span class=rvts1><br></span></p>
<?php
{if (isset($CL_enpanne))
	{ if ($CL_enpanne === "En panne") { ?>	
		<input type="checkbox" name="CL_enpanne" id="CL_enpanne"  checked value="oui">
		 <label for="CL_enpanne">En panne</label>
		<?php } else { ?>
		<input type="checkbox" name="CL_enpanne" id="CL_enpanne"  value="">
		 <label for="CL_enpanne">En panne</label>
<?php }} else {?>
<input type="checkbox" name="CL_enpanne" id="CL_enpanne" value="oui" >
 <label for="CL_enpanne">En panne</label>
<?php 
}
} ?>
<p class=rvps3><span class=rvts1><br></span></p>
<?php
{ if (isset($CL_incendie))
	{ if ($CL_incendie === "Incendié") { ?>	
		<input type="checkbox" name="CL_incendie" id="CL_incendie" checked value="oui">
		 <label for="CL_incendie">Incendié</label>
		<?php } else { ?>
		<input type="checkbox" name="CL_incendie" id="CL_incendie" value="">
		 <label for="CL_incendie">Incendié</label>
<?php }} else { ?>
<input type="checkbox" name="CL_incendie" id="CL_incendie" value="oui">
 <label for="CL_incendie">Incendié</label>
<?php }
} ?>
<p class=rvps3><span class=rvts1><br></span></p>
<?php
{ if (isset($CL_intact))
	{ if ($CL_intact === "Intact") { ?>	
		<input type="checkbox" name="CL_intact" id="CL_intact" checked value="oui">
		 <label for="CL_intact">Intact</label>
		<?php } else { ?>
		<input type="checkbox" name="CL_intact" id="CL_intact" value="">
		 <label for="CL_intact">Intact</label>
<?php }} else { ?>
<input type="checkbox" name="CL_intact" id="CL_intact" value="oui">
 <label for="CL_intact">Intact</label>
<?php }
} ?>

<p class=rvps3><span class=rvts1><br></span></p>
<p class=rvps3><span class=rvts1>Dans l</span><span class=rvts9>’</span><span class=rvts1>attente d</span><span class=rvts9>’</span><span class=rvts1>une suite que j</span><span class=rvts9>’</span><span class=rvts1>espère favorable, je vous prie d</span><span class=rvts9>’</span><span class=rvts1>agréer, Monsieur le chef du bureau de la douane, l</span><span class=rvts9>’</span><span class=rvts1>expression de mes salutations distinguées.</span></p>
<p class=rvps3><span class=rvts1><br></span></p>
<p class=rvps3><span class=rvts1>Nom et prénom : <input name="subscriber_lastname2" placeholder="nom du l'abonnée" value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> <input name="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>"></input></span></p>
<p class=rvps3><span class=rvts1>Signature légalisée </span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts5><br></span></p>
<p><span class=rvts5><br></span></p>
</form>
</body></html>

<?php 
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['CL_nombateau'])) {$CL_nombateau=$_GET['CL_nombateau'];}
if (isset($_GET['CL_nomport'])) {$CL_nomport=$_GET['CL_nomport'];}
if (isset($_GET['CL_dateheure'])) {$CL_dateheure=$_GET['CL_dateheure'];}
if (isset($_GET['CL_coordprestataire'])) {$CL_coordprestataire=$_GET['CL_coordprestataire'];}
if (isset($_GET['CL_compagniemaritime'])) {$CL_compagniemaritime=$_GET['CL_compagniemaritime'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>Attestation_reception_vehic_a_l_arrivee</title>
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
 font-size: 14pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts4
{
 font-size: 16pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts5
{
 font-size: 16pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
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
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts8
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #0070c0;
}
span.rvts9
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #ff0000;
}
span.rvts10
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
 line-height: 1.50;
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
 margin: 8px 0px 0px 0px;
}
.rvps6
{
 text-align: left;
 text-indent: 0px;
 page-break-after: avoid;
 widows: 2;
 orphans: 2;
 padding: 0px 0px 0px 0px;
 margin: 0px 0px 0px 0px;
}
--></style>
</head>
<body>
<form id="formchamps">
<input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"></input>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br><input name="CL_compagniemaritime" placeholder="Compagnie Maritime" type="text" value="<?php if(isset ($CL_compagniemaritime)) echo $CL_compagniemaritime; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts3></span></p>
<p class=rvps2><span class=rvts2>Le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps3><span class=rvts4> </span></p>
<p class=rvps3><span class=rvts4>ATTESTATION DE RECEPTION DE VEHICULE </span></p>
<p class=rvps3><span class=rvts4>A L</span><span class=rvts5>’</span><span class=rvts4>ARRIVEE</span></p>
<p class=rvps4><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts2>Nous soussignés, </span><span class=rvts6>Najda Assistance</span><span class=rvts2>, société correspondante pour la Tunisie de la compagnie <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" />, vous confirmons par la présente que le véhicule <input name="vehicule_type" placeholder="Type et marque du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input> immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input> appartenant à l</span><span class=rvts7>’</span><span class=rvts2>assuré(e)&nbsp; </span><span class=rvts6>Mr/Mme </span><span class=rvts2><input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> sera assisté et pris en charge à l</span><span class=rvts7>’</span><span class=rvts2>arrivée du bateau <input name="CL_nombateau" placeholder="nom du bateau"  value="<?php if(isset ($CL_nombateau)) echo $CL_nombateau; ?>"></input> au port de <input name="CL_nomport" placeholder="nom du port"  value="<?php if(isset ($CL_nomport)) echo $CL_nomport; ?>"></input> le <input name="CL_dateheure" placeholder="Date et heure" value="<?php if(isset ($CL_dateheure)) echo $CL_dateheure; ?>"></input> par le service de remorquage</span><span class=rvts8> </span><span class=rvts2><input name="CL_coordprestataire" placeholder="coordonnées du prestataire"  value="<?php if(isset ($CL_coordprestataire)) echo $CL_coordprestataire; ?>"></input>.</span></p>
<p class=rvps4><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts2>Ce prestataire de service est missionné par la compagnie <input name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value="<?php if(isset ($customer_id__name2)) echo $customer_id__name2; ?>" />.</span></p>
<p class=rvps4><span class=rvts2> </span></p>
<p class=rvps4><span class=rvts2>Cette attestation est établie pour servir et valoir ce que de droit.</span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2>Cordialement,</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" /></span></p>
<p class=rvps5><span class=rvts2>Plateau d</span><span class=rvts7>’</span><span class=rvts2>assistance technique</span></p>
<h1 class=rvps6></h1>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
<p><span class=rvts10><br></span></p>
</form>
</body></html>

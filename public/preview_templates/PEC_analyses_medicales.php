<?php 
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname'];}
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic'];}
if (isset($_GET['CL_montantmax'])) {$CL_montantmax=$_GET['CL_montantmax'];}
if (isset($_GET['CL_montantlettres'])) {$CL_montantlettres=$_GET['CL_montantlettres'];}
if (isset($_GET['CL_natureexamen'])) {$CL_natureexamen=$_GET['CL_natureexamen'];}
if (isset($_GET['CL_dateexamen'])) {$CL_dateexamen=$_GET['CL_dateexamen'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_analyses_medicales</title>
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
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
 text-decoration: underline;
}
span.rvts4
{
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts5
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts6
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts7
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts8
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts9
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #0070c0;
}
span.rvts10
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 letter-spacing: -1px;
}
span.rvts11
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
 letter-spacing: -1px;
}
span.rvts12
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #0070c0;
 letter-spacing: -1px;
}
span.rvts13
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts14
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts15
{
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 font-weight: bold;
 text-decoration: underline;
}
span.rvts16
{
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 text-decoration: underline;
}
span.rvts17
{
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
}
span.rvts18
{
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
 font-weight: bold;
}
span.rvts19
{
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-style: italic;
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
 page-break-after: avoid;
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
 margin: 0px 0px 0px 8px;
}
.rvps5
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 8px;
}
.rvps6
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 widows: 2;
 orphans: 2;
 margin: 1px 0px 0px 0px;
}
.rvps7
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 1.20;
 widows: 2;
 orphans: 2;
 margin: 0px 0px 0px 8px;
}
.rvps8
{
 text-align: justify;
 text-justify: inter-word;
 text-align-last: auto;
 line-height: 1.25;
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
 margin: 8px 0px 0px 0px;
}
--></style>
</head>
<body>
<form id="formchamps">
<input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"></input>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps2><span class=rvts1>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps3><span class=rvts3><br></span></p>
<p class=rvps3><span class=rvts3>PRISE EN CHARGE ANALYSES MEDICALES</span></p>
<p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps4><span class=rvts5>Client</span><span class=rvts6> : <input name="customer_id__name" id="customer_id__name" placeholder="client" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<p class=rvps4><span class=rvts5>Nom patient</span><span class=rvts6> : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p class=rvps4><span class=rvts7>Prénom</span><span class=rvts8> : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /></span></p>
<p class=rvps4><span class=rvts7>Notre réf. dossier</span><span class=rvts8> : <input name="reference_medic" placeholder="Notre Reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p class=rvps5><span class=rvts5>Montant maximal de prise en charge (TND)</span><span class=rvts6>:&nbsp;</span><span class=rvts9> </span><span class=rvts6><input name="CL_montantmax" placeholder="Montant maximal"  value="<?php if(isset ($CL_montantmax)) echo $CL_montantmax; ?>"></input></span><span class=rvts9>&nbsp;&nbsp;&nbsp; </span><span class=rvts5>Toutes lettres</span><span class=rvts6> : <input name="CL_montantlettres" placeholder="Montant en toutes lettres"  value="<?php if(isset ($CL_montantlettres)) echo $CL_montantlettres; ?>"></input></span></p>
<p class=rvps6><span class=rvts6><br></span></p>
<p class=rvps7><span class=rvts6>Nous</span><span class=rvts10> </span><span class=rvts6>soussignés,</span><span class=rvts10> </span><span class=rvts5>Najda</span><span class=rvts11> </span><span class=rvts5>Assistance</span><span class=rvts6>,</span><span class=rvts10> </span><span class=rvts6>nous</span><span class=rvts10> </span><span class=rvts6>engageons</span><span class=rvts10> </span><span class=rvts6>à</span><span class=rvts10> </span><span class=rvts6>prendre</span><span class=rvts10> </span><span class=rvts6>en</span><span class=rvts10> </span><span class=rvts6>charge</span><span class=rvts10> </span><span class=rvts6>frais</span><span class=rvts10> </span><span class=rvts6>des</span><span class=rvts10> </span><span class=rvts6>examens</span><span class=rvts10>&nbsp;</span><span class=rvts12> </span><span class=rvts10><input name="CL_natureexamen" placeholder="Nature d examen"  value="<?php if(isset ($CL_natureexamen)) echo $CL_natureexamen; ?>"></input></span><span class=rvts6> à réaliser</span><span class=rvts10> </span><span class=rvts6>le</span><span class=rvts10> </span><span class=rvts6><input name="CL_dateexamen" placeholder="Date d examen"  value="<?php if(isset ($CL_dateexamen)) echo $CL_dateexamen; ?>"></input> au</span><span class=rvts10> </span><span class=rvts6>profit</span><span class=rvts10> </span><span class=rvts6>de</span><span class=rvts10> </span><span class=rvts6>du (de la) patient(e) ci-dessus pour le montant maximal mentionné ci-dessus.</span></p>
<p class=rvps6><span class=rvts6><br></span></p>
<p class=rvps8><span class=rvts6>Merci</span><span class=rvts10> </span><span class=rvts6>de</span><span class=rvts10> </span><span class=rvts6>nous</span><span class=rvts10> </span><span class=rvts6>adresser</span><span class=rvts10> </span><span class=rvts6>votre</span><span class=rvts10> </span><span class=rvts6>facture</span><span class=rvts10> </span><span class=rvts6>originale</span><span class=rvts10> </span><span class=rvts6>dès</span><span class=rvts10> </span><span class=rvts6>que</span><span class=rvts10> </span><span class=rvts6>possible</span><span class=rvts10> </span><span class=rvts6>(et</span><span class=rvts10> </span><span class=rvts6>au</span><span class=rvts10> </span><span class=rvts6>plus</span><span class=rvts10> </span><span class=rvts6>tard</span><span class=rvts10> </span><span class=rvts6>dans</span><span class=rvts10> </span><span class=rvts6>un</span><span class=rvts10> </span><span class=rvts6>délai</span><span class=rvts10> </span><span class=rvts6>de</span><span class=rvts10> </span><span class=rvts6>20</span><span class=rvts10> </span><span class=rvts6>jours après réalisation des examens),</span><span class=rvts10> </span><span class=rvts6>à</span><span class=rvts10> </span><span class=rvts6>l</span><span class=rvts13>’</span><span class=rvts6>adresse</span><span class=rvts10> </span><span class=rvts6>ci-dessus,</span><span class=rvts10> </span><span class=rvts6>en mentionnant notre référence</span><span class=rvts10> </span><span class=rvts6>de dossier.</span></p>
<p class=rvps1><span class=rvts14><br></span></p>
<p class=rvps1><span class=rvts14><br></span></p>
<p class=rvps1><span class=rvts15>ATTENTION IMPORTANT</span><span class=rvts16> </span></p>
<p class=rvps1><span class=rvts17><br></span></p>
<p class=rvps1><span class=rvts18>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts18>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts19>’</span><span class=rvts18>accompagnerait.</span></p>
<p class=rvps9><span class=rvts5><br></span></p>
<p class=rvps9><span class=rvts6>Merci de votre collaboration</span></p>
<p class=rvps9><span class=rvts6><br></span></p>
<p class=rvps9><span class=rvts6>P/La Gérante</span></p>
<p class=rvps1><span class=rvts14><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" /></span></p>
<p class=rvps1><span class=rvts14>Plateau TPA</span></p>
<p class=rvps1><span class=rvts14>« courrier électronique, sans signature »</span></p>
</form>
</body></html>
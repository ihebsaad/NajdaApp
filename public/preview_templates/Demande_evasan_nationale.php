<?php 
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname'];}
if (isset($_GET['CL_localisation'])) {$CL_localisation=$_GET['CL_localisation'];}
if (isset($_GET['CL_nationalite'])) {$CL_nationalite=$_GET['CL_nationalite'];}
if (isset($_GET['CL_diagnostic'])) {$CL_diagnostic=$_GET['CL_diagnostic'];}
if (isset($_GET['CL_lsortie'])) {$CL_lsortie=$_GET['CL_lsortie'];}
if (isset($_GET['CL_larrivee'])) {$CL_larrivee=$_GET['CL_larrivee'];}
if (isset($_GET['CL_dateevac'])) {$CL_dateevac=$_GET['CL_dateevac'];}
if (isset($_GET['CL_matine_apresmidi'])) {$CL_matine_apresmidi=$_GET['CL_matine_apresmidi'];}
if (isset($_GET['CL_heureamb'])) {$CL_heureamb=$_GET['CL_heureamb'];}
if (isset($_GET['CL_decollage'])) {$CL_decollage=$_GET['CL_decollage'];}
if (isset($_GET['CL_coorddocteur'])) {$CL_coorddocteur=$_GET['CL_coorddocteur'];}
if (isset($_GET['CL_cindocteur'])) {$CL_cindocteur=$_GET['CL_cindocteur'];}
if (isset($_GET['CL_coordtechnicien'])) {$CL_coordtechnicien=$_GET['CL_coordtechnicien'];}
if (isset($_GET['CL_cintechnicien'])) {$CL_cintechnicien=$_GET['CL_cintechnicien'];}
if (isset($_GET['CL_coordambulancier'])) {$CL_coordambulancier=$_GET['CL_coordambulancier'];}
if (isset($_GET['CL_cinambulancier'])) {$CL_cinambulancier=$_GET['CL_cinambulancier'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>Demande_evasan_nationale</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css"><!--
body {
  margin: 48px 48px 48px 48px;
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
 font-size: 12pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts4
{
 font-family: 'Tahoma', 'Geneva', sans-serif;
}
span.rvts5
{
 font-size: 18pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts6
{
 font-size: 18pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 font-weight: bold;
}
span.rvts7
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
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
 font-weight: bold;
}
span.rvts10
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #548dd4;
}
span.rvts11
{
 font-size: 11pt;
 font-family: 'Tahoma', 'Geneva', sans-serif;
 color: #ff0000;
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
 text-align: center;
 widows: 2;
 orphans: 2;
}
.rvps3
{
 text-align: right;
 widows: 2;
 orphans: 2;
}
--></style>
</head>
<body>
<form id="formchamps">
<input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"></input>
<p><span class=rvts1><br></span></p>

<p><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps2><span class=rvts2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
<p class=rvps1><span class=rvts3><br></span></p>
<p class=rvps1><span class=rvts3><br></span></p>
<p class=rvps3><span class=rvts3> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts3><br></span></p>
<p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps2><span class=rvts5>Demande d</span><span class=rvts6>’</span><span class=rvts5>évacuation sanitaire aérienne</span></p>
<p class=rvps1><span class=rvts3><br></span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>Bonjour Monsieur le Ministre,</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>Nous avons été sollicités par la compagnie <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /> pour le cas d</span><span class=rvts8>’</span><span class=rvts7>un de leurs assurés de trouvant à <input name="CL_localisation" id="CL_localisation" placeholder="localisation"  value="<?php if(isset ($CL_localisation)) echo $CL_localisation; ?>"></input>.</span></p>
<p class=rvps1><span class=rvts7>Il s</span><span class=rvts8>’</span><span class=rvts7>agit de Mr/Mme <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> de nationalité <input name="CL_nationalite" placeholder="nationalité"  value="<?php if(isset ($CL_nationalite)) echo $CL_nationalite; ?>"></input> qui présente</span><span class=rvts9> </span><span class=rvts7><input name="CL_diagnostic" placeholder="diagnostic"  value="<?php if(isset ($CL_diagnostic)) echo $CL_diagnostic; ?>"></input>.</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>La demande est une </span><span class=rvts9>évacuation sanitaire par avion de <input name="CL_lsortie" placeholder="sortie"  value="<?php if(isset ($CL_lsortie)) echo $CL_lsortie; ?>"></input> à <input name="CL_larrivee" placeholder="arrivee"  value="<?php if(isset ($CL_larrivee)) echo $CL_larrivee; ?>"></input> </span><span class=rvts7>en date du</span><span class=rvts10> </span><span class=rvts7><input name="CL_dateevac" placeholder="date"  value="<?php if(isset ($CL_dateevac)) echo $CL_dateevac; ?>"></input> dans <input name="CL_matine_apresmidi" placeholder="la matinée/après-midi"  value="<?php if(isset ($CL_matine_apresmidi)) echo $CL_matine_apresmidi; ?>"></input>.</span></p>
<p class=rvps1><span class=rvts7>Si notre demande obtient votre accord, notre équipe médicale se rendra à Tunis à bord de l</span><span class=rvts8>’</span><span class=rvts7>ambulance de Medic</span><span class=rvts8>’</span><span class=rvts7> Multiservices, immatriculée </span><span class=rvts11>immatriculation base des véhicules </span><span class=rvts7>Elle devrait se présenter à l</span><span class=rvts8>’</span><span class=rvts7>entrée de la base aérienne de Laouina vers <input name="CL_heureamb" placeholder="heure"  value="<?php if(isset ($CL_heureamb)) echo $CL_heureamb; ?>"></input> pour un décollage vers <input name="CL_decollage" placeholder="décollage"  value="<?php if(isset ($CL_decollage)) echo $CL_decollage; ?>"></input>.</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>Elle sera composée du </span><span class=rvts9>Dr <input name="CL_coorddocteur" placeholder="coordonnés médecin"  value="<?php if(isset ($CL_coorddocteur)) echo $CL_coorddocteur; ?>"></input>., CIN # <input name="CL_cindocteur" placeholder="CIN médecin"  value="<?php if(isset ($CL_cindocteur)) echo $CL_cindocteur; ?>"></input>, </span><span class=rvts7>médecin, de </span><span class=rvts9>Mr <input name="CL_coordtechnicien" placeholder="coordonnés technicien"  value="<?php if(isset ($CL_coordtechnicien)) echo $CL_coordtechnicien; ?>"></input>, CIN # <input name="CL_cintechnicien" placeholder="CIN technicien"  value="<?php if(isset ($CL_cintechnicien)) echo $CL_cintechnicien; ?>"></input> </span><span class=rvts7>emergency medical technician, et de </span><span class=rvts9>Mr <input name="CL_coordambulancier" placeholder="coordonnés ambulancier"  value="<?php if(isset ($CL_coordambulancier)) echo $CL_coordambulancier; ?>"></input>, CIN # <input name="CL_cinambulancier" placeholder="CIN ambulancier"  value="<?php if(isset ($CL_cinambulancier)) echo $CL_cinambulancier; ?>"></input>, </span><span class=rvts7>chauffeur ambulancier.</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>Après votre accord, il vous sera adressé comme d</span><span class=rvts8>’</span><span class=rvts7>usage un fax de prise en charge pour cette opération.</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p class=rvps1><span class=rvts7>Avec nos sincères remerciements pour votre aide habituelle.</span></p>
<p class=rvps1><span class=rvts7><br></span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7>Cordialement</span></p>
<p><span class=rvts7><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" /></span></p>
<p><span class=rvts7>Plateau Médical</span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7><br></span></p>
</form>
</body></html>

<?php 
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; $subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['CL_payee'])) {$Cl_payee=$_GET['CL_payee'];}
if (isset($_GET['CL_passeport'])) {$CL_passeport=$_GET['CL_passeport'];}
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
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
<p class=rvps1><span class=rvts1>Tunis le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
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
<p class=rvps3><span class=rvts1>Je soussigné Mr/Mme <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /><input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input> titulaire du passeport <input name="CL_payee" placeholder="pays"  value="<?php if(isset ($Cl_payee)) echo $Cl_payee; ?>"></input> n° <input name="CL_passeport" placeholder="numéro du passeport" value="<?php if(isset ($CL_passeport)) echo $CL_passeport; ?>"></input>, viens par la présente vous prier de bien vouloir me donner votre accord pour le refoulement de mon véhicule <input name="vehicule_type" placeholder="Type et marque du véhicule
" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input> immatriculé <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input>.</span></p>
<p class=rvps3><span class=rvts1><br></span></p>
<ul class=list0>
<li style="margin-left: 0px" class=rvps5><span class=rvts8>Accidenté </span></li>
<li style="margin-left: 0px" class=rvps5><span class=rvts8>En panne </span></li>
<li style="margin-left: 0px" class=rvps5><span class=rvts8>Incendié </span></li>
<li style="margin-left: 0px" class=rvps5><span class=rvts8>Intact </span></li>
</ul>
<p class=rvps3><span class=rvts1><br></span></p>
<p class=rvps3><span class=rvts1>Dans l</span><span class=rvts9>’</span><span class=rvts1>attente d</span><span class=rvts9>’</span><span class=rvts1>une suite que j</span><span class=rvts9>’</span><span class=rvts1>espère favorable, je vous prie d</span><span class=rvts9>’</span><span class=rvts1>agréer, Monsieur le chef du bureau de la douane, l</span><span class=rvts9>’</span><span class=rvts1>expression de mes salutations distinguées.</span></p>
<p class=rvps3><span class=rvts1><br></span></p>
<p class=rvps3><span class=rvts1>Nom et prénom : <input name="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>"></input><input name="subscriber_lastname2" placeholder="nom du l'abonnée" value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input></span></p>
<p class=rvps3><span class=rvts1>Signature légalisée </span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts5><br></span></p>
<p><span class=rvts5><br></span></p>
</form>
</body></html>
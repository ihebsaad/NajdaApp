<?php
if (isset($_GET['prest__pharm'])) {$prest__pharm=$_GET['prest__pharm'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_name_medecin'])) {$CL_name_medecin=$_GET['CL_name_medecin'];}
if (isset($_GET['CL_montant_numerique'])) {$CL_montant_numerique=$_GET['CL_montant_numerique'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['montantgop'])) {$montantgop=$_GET['montantgop'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_pharmacie</title>
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
            font-size: 14pt;
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
        }
        span.rvts10
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts15
        {
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts16
        {
            font-size: 12pt;
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
            text-align: justify;
            text-align-last: auto;
            text-indent: 0px;
            page-break-after: avoid;
            widows: 2;
            orphans: 2;
            padding: 0px 0px 0px 0px;
            margin: 0px 0px 0px 0px;
        }
        .rvps4
        {
            text-align: center;
            page-break-after: avoid;
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
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2><input name="prest__pharm" style="width:300px" placeholder="Prestataire pharmacie" value="<?php if(isset ($prest__pharm)) echo $prest__pharm; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps2><span class=rvts1>Sousse le  <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<h1 class=rvps3><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps4><span class=rvts4>PRISE EN CHARGE</span></p>
<p class=rvps1><span class=rvts5><br></span></p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts6>Client : <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /> </span></p>
<p class=rvps1><span class=rvts6>Nom patient : </span><span class=rvts7> <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /></span></p>
<p class=rvps1><span class=rvts6>Prénom : </span><span class=rvts7><input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p class=rvps1><span class=rvts6>Notre réf. dossier : </span><span class=rvts7>  <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p class=rvps1><span class=rvts6>Médecin prescripteur : <input name="CL_name_medecin" placeholder="nom du medecin" value="<?php if(isset ($CL_name_medecin)) echo $CL_name_medecin; ?>"></input></span></p>
<p class=rvps1><span class=rvts6>Montant (TND): <span style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label><input name="CL_montant_numerique" placeholder="Montant Numerique" value="<?php if(isset ($CL_montant_numerique)) echo $CL_montant_numerique; ?>" onKeyUp=" keyUpHandler(this)"></input></br></span> Montant toutes lettres : <input name="CL_montant_toutes_lettres" id="CL_montant_toutes_lettres" placeholder="Montant toutes lettres" value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input></span> dinars</p>
<p class=rvps1><span class=rvts6><br></span></p>
<p class=rvps1><span class=rvts8>Nous soussignés, </span><span class=rvts7>Najda Assistance</span><span class=rvts8>, nous engageons par la présente à prendre en charge les frais relatifs à l</span><span class=rvts9>’</span><span class=rvts8>achat des médicaments prescrit pour le compte du (de la) patient(e) ci-dessus.</span><span class=rvts7> </span></p>
<p><span class=rvts2>Merci de nous adresser votre facture originale par voie postale dans les 20 jours au maximum, accompagnée d</span><span class=rvts10>’</span><span class=rvts2>une copie de la présente prise en charge à l</span><span class=rvts10>’</span><span class=rvts2>adresse susmentionnée en entête.</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts11>ATTENTION IMPORTANT</span><span class=rvts12> </span></p>
<p><span class=rvts13><br></span></p>
<p class=rvps1><span class=rvts14>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts14>Toute facture devra être envoyée accompagnée de la présente prise en charge.</span></p>
<p><span class=rvts15><br></span></p>
<p class=rvps5><span class=rvts16>Merci de votre collaboration</span></p>
<p class=rvps5><span class=rvts16>P/La Gérante</span></p>
<p class=rvps1><span class=rvts2><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" > </input></span></p>
<p class=rvps1><span class=rvts2>Plateau TPA</span></p>
<p class=rvps1><span class=rvts2>« courrier électronique, sans signature »</span></p>
</form>
<script language="javascript" src="nombre_en_lettre.js"></script>
<script type="text/javascript">
    function keyUpHandler(obj){
            //document.getElementById("CL_montant_toutes_lettres").firstChild.nodeValue =   NumberToLetter(obj.value)
            document.getElementById("CL_montant_toutes_lettres").value  = NumberToLetter(obj.value)
            if (obj.value > <?php echo $montantgop; ?>) {document.getElementById("alertGOP").style.display="block";}
            else {document.getElementById("alertGOP").style.display="none";}
        }//fin de keypressHandler
</script>
</body></html>


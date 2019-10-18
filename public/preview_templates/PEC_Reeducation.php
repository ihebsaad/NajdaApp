<?php
if (isset($_GET['prest__reeduc'])) {$prest__reeduc=$_GET['prest__reeduc'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_nombre_seance'])) {$CL_nombre_seance=$_GET['CL_nombre_seance'];}
if (isset($_GET['CL_montant_seance_numerique'])) {$CL_montant_seance_numerique=$_GET['CL_montant_seance_numerique'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
if (isset($_GET['CL_montant_total'])) {$CL_montant_total=$_GET['CL_montant_total'];}
if (isset($_GET['CL_date_reeduction'])) {$CL_date_reeduction=$_GET['CL_date_reeduction'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['montantgop'])) {$montantgop=$_GET['montantgop'];}
if (isset($_GET['idtaggop'])) 
    {
        $idtaggop=$_GET['idtaggop']; 
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_Reeducation</title>
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
            font-size: 16pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 24pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts6
        {
            font-size: 11pt;
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
            font-weight: bold;
        }
        span.rvts9
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #ff0000;
        }
        span.rvts11
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #0070c0;
        }
        span.rvts12
        {
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts13
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts14
        {
            font-size: 16pt;
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
            text-align: justify;
            text-align-last: auto;
            text-indent: 0px;
            page-break-after: avoid;
            widows: 2;
            orphans: 2;
            padding: 0px 0px 0px 0px;
            margin: 0px 0px 0px 0px;
        }
        .rvps3
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
        }
        .rvps4
        {
            text-align: right;
            widows: 2;
            orphans: 2;
        }
        .rvps5
        {
            text-align: center;
            widows: 2;
            orphans: 2;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 5px 0px 0px 0px;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 2px 0px 0px 0px;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
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
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<input name="idtaggop" type="hidden" value="<?php if(isset ($idtaggop)) echo $idtaggop; ?>"></input>
<p><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts1><br></span></p>
<p class=rvps1><span class=rvts2><input name="prest__reeduc" style="width:300px" placeholder="Prestataire rééducation" value="<?php if(isset ($prest__reeduc)) echo $prest__reeduc; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps3><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span></p>
<p class=rvps4><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps5><span class=rvts4>PRISE EN CHARGE </span></p>
<p class=rvps5><span class=rvts4>SEANCES DE REEDUCATION FONCTIONNELLE</span></p>
<p class=rvps5><span class=rvts5><br></span></p>
<p class=rvps6><span class=rvts6>*Client :</span><span class=rvts7> </span><span class=rvts6><input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<p class=rvps7><span class=rvts6>*Nom patient : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /></span></p>
<p class=rvps7><span class=rvts6>*Prénom : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p class=rvps1><span class=rvts8>*Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p><span class=rvts9>*</span><span class=rvts8>Nombre de séances</span><span class=rvts9> : <input name="CL_nombre_seance" id="CL_nombre_seance" placeholder="Nombre de Séances" value="<?php if(isset ($CL_nombre_seance)) echo $CL_nombre_seance; ?>" onKeyUp=" calcultotal()"></input> </span><span class=rvts8>Montant/séance (TND): <input name="CL_montant_seance_numerique" id="CL_montant_seance_numerique" width=70% placeholder="Montant Numerique par Séance" value="<?php if(isset ($CL_montant_seance_numerique)) echo $CL_montant_seance_numerique; ?>" onKeyUp=" calcultotal()"></input></span><span class=rvts9> </span></p>
<p class=rvps6><span class=rvts6>*Montant total (TND): </span><span style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label><input name="CL_montant_total" id="CL_montant_total" placeholder="Montant total" value="<?php if(isset ($CL_montant_total)) echo $CL_montant_total; ?>" onchange=" keyUpHandler(this)"></input></span> <span class=rvts11>&nbsp;&nbsp; </span><span class=rvts6>Toutes lettres</span><span class=rvts7> :  <input name="CL_montant_toutes_lettres" id="CL_montant_toutes_lettres" placeholder="Montant toutes lettres" value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input> dinars</span></p>
<p><span class=rvts12><br></span></p>
<p class=rvps1><span class=rvts9>Nous soussignés, </span><span class=rvts8>Najda Assistance</span><span class=rvts9>, nous engageons à prendre en charge, pour le compte de notre client, les frais de rééducation fonctionnelle effectuée à partir du  <input name="CL_date_reeduction" placeholder="Date Reeduction" value="<?php if(isset ($CL_date_reeduction)) echo $CL_date_reeduction; ?>"></input> au profit du patient nommé ci-dessus pour le nombre de séances et le montant total susmentionné.</span></p>
<p class=rvps1><span class=rvts9><br></span></p>
<p class=rvps1><span class=rvts9>Merci de nous adresser votre facture dans les 20 jours max après la dernière séance, accompagnée d</span><span class=rvts13>’</span><span class=rvts9>une copie de la présente prise en charge par courrier à l</span><span class=rvts13>’</span><span class=rvts9>adresse ci-dessus en entête.</span></p>
<p><span class=rvts14><br></span></p>
<p class=rvps1><span class=rvts15>ATTENTION IMPORTANT</span><span class=rvts16> </span></p>
<p class=rvps1><span class=rvts17><br></span></p>
<p class=rvps1><span class=rvts18>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts18>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts19>’</span><span class=rvts18>accompagnerait.</span></p>
<p><span class=rvts14><br></span></p>
<p><span class=rvts9>Cordialement</span></p>
<p class=rvps1><span class=rvts9><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" > </input></span></p>
<p class=rvps8><span class=rvts7>Plateau TPA</span></p>
<p class=rvps1><span class=rvts9>« courrier électronique, sans signature »</span></p>
<p class=rvps9><span class=rvts7><br></span></p>
</form>
<script language="javascript" src="nombre_en_lettre.js"></script>
<script type="text/javascript">
    function keyUpHandler(obj){
            if (obj.value > <?php echo $montantgop; ?>) {document.getElementById("alertGOP").style.display="block";}
            else {document.getElementById("alertGOP").style.display="none";}
            document.getElementById("CL_montant_toutes_lettres").value  = NumberToLetter(obj.value)
        }//fin de keypressHandler
    function calcultotal() {
        var montantse=document.getElementById("CL_montant_seance_numerique").value;
        var nbrese=document.getElementById("CL_nombre_seance").value;
        document.getElementById("CL_montant_total").value = (parseInt(montantse) * parseInt(nbrese));
        document.getElementById("CL_montant_total").onchange();
    }
/*    $("#CL_montant_total").on("change paste keyup", function() {
   alert($(this).val()); 
});*/
</script>
</body></html>

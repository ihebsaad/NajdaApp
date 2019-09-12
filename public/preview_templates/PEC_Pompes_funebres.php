<?php
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_lieu_depart_corps'])) {$CL_lieu_depart_corps=$_GET['CL_lieu_depart_corps'];}
if (isset($_GET['CL_lieu_destination_corps'])) {$CL_lieu_destination_corps=$_GET['CL_lieu_destination_corps'];}
if (isset($_GET['CL_montant_numerique'])) {$CL_montant_numerique=$_GET['CL_montant_numerique'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_Pompes_funebres</title>
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
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts4
        {
            font-size: 24pt;
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
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts11
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts12
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts18
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts19
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts20
        {
            font-size: 11pt;
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
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 5px 0px 0px 0px;
        }
        .rvps5
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 2px 0px 0px 0px;
        }
        .rvps6
        {
            widows: 2;
            orphans: 2;
        }
        .rvps7
        {
            widows: 2;
            orphans: 2;
        }
        .rvps8
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
    <p><span class=rvts1><br></span></p>
    <p class=rvps1><span class=rvts1><br></span></p>
    <p class=rvps1><span class=rvts1><br></span></p>
    <p class=rvps1><span class=rvts1><br></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps2><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
    <p class=rvps1><span class=rvts2><br></span></p>
    <p class=rvps3><span class=rvts3>PRISE EN CHARGE </span></p>
    <p class=rvps3><span class=rvts4><br></span></p>
    <p class=rvps4><span class=rvts5>*Client :</span><span class=rvts6> </span><span class=rvts5><input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
    <p class=rvps5><span class=rvts5>*Nom patient :</span><span class=rvts6> </span><span class=rvts5> </span><span class=rvts7> <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /></span></p>
    <p class=rvps5><span class=rvts5>*Prénom :</span><span class=rvts6> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
    <p><span class=rvts7>*Notre réf. dossier</span><span class=rvts8>: </span><span class=rvts7><input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span><span class=rvts8> </span></p>
    <p><span class=rvts7>*Lieu de départ du corps :</span><span class=rvts8> <input name="CL_lieu_depart_corps" placeholder="Lieu Depart Corps" value="<?php if(isset ($CL_lieu_depart_corps)) echo $CL_lieu_depart_corps; ?>"></input></span></p>
    <p><span class=rvts7>*Destination du corps :</span><span class=rvts8> <input name="CL_lieu_destination_corps" placeholder="Lieu Destination Corps" value="<?php if(isset ($CL_lieu_destination_corps)) echo $CL_lieu_destination_corps; ?>"></input></span></p></span></p>
    <p><span class=rvts7>*Montant max garanti (TND): </span><span class=rvts8><input name="CL_montant_numerique" placeholder="Montant Numerique" value="<?php if(isset ($CL_montant_numerique)) echo $CL_montant_numerique; ?>" onKeyUp=" keyUpHandler(this)"></input></span><span class=rvts7>Toutes lettres</span><span class=rvts8> :  <input name="CL_montant_toutes_lettres" id="CL_montant_toutes_lettres" placeholder="Montant toutes lettres" value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input> </span> dinars</p>
    <p><span class=rvts9><br></span></p>
    <p class=rvps6><span class=rvts10>Messieurs,</span></p>
    <p class=rvps7><span class=rvts6>Suite</span><span class=rvts11> </span><span class=rvts6>à</span><span class=rvts11> </span><span class=rvts6>votre</span><span class=rvts11> </span><span class=rvts6>devis,</span><span class=rvts11> </span><span class=rvts6>nous</span><span class=rvts11> </span><span class=rvts6>vous</span><span class=rvts11> </span><span class=rvts6>confirmons</span><span class=rvts11> </span><span class=rvts6>notre</span><span class=rvts11> </span><span class=rvts6>prise</span><span class=rvts11> </span><span class=rvts6>en</span><span class=rvts11> </span><span class=rvts6>charge</span><span class=rvts11> </span><span class=rvts6>des</span><span class=rvts11> </span><span class=rvts6>frais</span><span class=rvts11> </span><span class=rvts6>de</span><span class=rvts11> </span><span class=rvts6>rapatriement</span><span class=rvts11> </span><span class=rvts6>du</span><span class=rvts11> </span><span class=rvts6>corps</span><span class=rvts11> </span><span class=rvts6>du</span><span class=rvts11> </span><span class=rvts6>défunt</span><span class=rvts11> </span><span class=rvts6>selon</span><span class=rvts11> </span><span class=rvts6>les</span><span class=rvts11> </span><span class=rvts6>conditions énoncées ci-dessus.</span></p>
    <p class=rvps7><span class=rvts6>Merci de nous communiquer dès que possible, les informations relatives au rapatriement (date de départ du cercueil et coordonnées du vol + LTA). </span></p>
    <p class=rvps7><span class=rvts6>Merci</span><span class=rvts11> </span><span class=rvts6>de</span><span class=rvts11> </span><span class=rvts6>nous</span><span class=rvts11> </span><span class=rvts6>adresser</span><span class=rvts11> </span><span class=rvts6>votre</span><span class=rvts11> </span><span class=rvts6>facture</span><span class=rvts11> </span><span class=rvts6>originale</span><span class=rvts11> </span><span class=rvts6>dès</span><span class=rvts11> </span><span class=rvts6>que</span><span class=rvts11> </span><span class=rvts6>possible</span><span class=rvts11> </span><span class=rvts6>(dans</span><span class=rvts11> </span><span class=rvts6>un</span><span class=rvts11> </span><span class=rvts6>délai</span><span class=rvts11> </span><span class=rvts6>max</span><span class=rvts11> </span><span class=rvts6>de</span><span class=rvts11> </span><span class=rvts6>20</span><span class=rvts11> </span><span class=rvts6>jours après départ du corps)</span><span class=rvts11> </span><span class=rvts6>à</span><span class=rvts11> </span><span class=rvts6>l</span><span class=rvts12>’</span><span class=rvts6>adresse</span><span class=rvts11> </span><span class=rvts6>ci-dessus,</span><span class=rvts11> </span><span class=rvts6>en</span><span class=rvts11> y </span><span class=rvts6>mentionnant </span><span class=rvts10>notre référence de dossier.</span></p>
    <p class=rvps7><span class=rvts6><br></span></p>
    <p class=rvps1><span class=rvts13>ATTENTION IMPORTANT</span><span class=rvts14> </span></p>
    <p class=rvps1><span class=rvts15><br></span></p>
    <p class=rvps1><span class=rvts16>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
    <p class=rvps1><span class=rvts16>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts17>’</span><span class=rvts16>accompagnerait.</span></p>
    <p class=rvps8><span class=rvts18><br></span></p>
    <p class=rvps8><span class=rvts6>Merci de votre collaboration</span></p>
    <p class=rvps8><span class=rvts19><br></span></p>
    <p class=rvps8><span class=rvts6>P/La Gérante</span></p>
    <p class=rvps1><span class=rvts8><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" > </input></span></p>
    <p class=rvps1><span class=rvts8>Plateau d</span><span class=rvts20>’</span><span class=rvts8>assistance médicale</span></p>
    <p class=rvps1><span class=rvts8>« courrier électronique, sans signature »</span></p>
    <p><span class=rvts2><br></span></p>
</form>
<script language="javascript" src="nombre_en_lettre.js"></script>
<script type="text/javascript">
    function keyUpHandler(obj){
            //document.getElementById("CL_montant_toutes_lettres").firstChild.nodeValue =   NumberToLetter(obj.value)
            document.getElementById("CL_montant_toutes_lettres").value  = NumberToLetter(obj.value)
        }//fin de keypressHandler
</script>
</body></html>


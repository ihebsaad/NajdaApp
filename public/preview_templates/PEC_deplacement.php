<?php
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_date_heure_deplacement'])) {$CL_date_heure_deplacement=$_GET['CL_date_heure_deplacement'];}
if (isset($_GET['CL_lieu_deplacement'])) {$CL_lieu_deplacement=$_GET['CL_lieu_deplacement'];}
if (isset($_GET['CL_motif_deplacement'])) {$CL_motif_deplacement=$_GET['CL_motif_deplacement'];}
if (isset($_GET['CL_taxi_ambulance_remorquer'])) {$CL_taxi_ambulance_remorquer=$_GET['CL_taxi_ambulance_remorquer'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>2kiphu1x42vxaia67kcyo68zpjsjck9q_PEC_deplacement</title>
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
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
        }
        span.rvts5
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts6
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts7
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts8
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts11
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts17
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts18
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
            text-align: right;
            widows: 2;
            orphans: 2;
        }
        .rvps4
        {
            text-align: center;
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
            margin: 5px 0px 0px 0px;
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
<p class=rvps1><span class=rvts2><br></span></p>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<h1 class=rvps2><span class=rvts0><span class=rvts3><br></span></span></h1>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps3><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts4>PRISE EN CHARGE DEPLACEMENT</span></p>
<p class=rvps4><span class=rvts4><br></span></p>
<p><span class=rvts5>Client : <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /> </span></p>
<p class=rvps5><span class=rvts6>Nom Assuré</span><span class=rvts7> : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /></span></p>
<p><span class=rvts5>Prénom : </span><span class=rvts8> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p><span class=rvts5>Tél assuré : </span><span class=rvts8> <input name="subscriber_phone_cell" placeholder="téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell;?>"/> </span></p>
<p><span class=rvts5>Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p><span class=rvts5>Date et heure du déplacement: </span><span class=rvts8> <input name="CL_date_heure_deplacement" placeholder="Date Heure Deplacement" value="<?php if(isset ($CL_date_heure_deplacement)) echo $CL_date_heure_deplacement; ?>"></input></span><span class=rvts9>&nbsp;&nbsp; </span></p>
<p><span class=rvts5>Lieu du déplacement : <input name="CL_lieu_deplacement" placeholder="Lieu Deplacement" value="<?php if(isset ($CL_lieu_deplacement)) echo $CL_lieu_deplacement; ?>"></input></span></p>
<p><span class=rvts5>Motif du déplacement : <input name="CL_motif_deplacement" placeholder="Motif Deplacement" value="<?php if(isset ($CL_motif_deplacement)) echo $CL_motif_deplacement; ?>"></input></span><span class=rvts9>&nbsp;&nbsp; </span></p>
<p><span class=rvts9><br></span></p>
<p><span class=rvts2>Suite à notre entretien téléphonique, nous vous confirmons la prise en charge de votre déplacement en </span><span class=rvts10><input name="CL_taxi_ambulance_remorquer" placeholder="Taxi Ambulance Remorquer" value="<?php if(isset ($CL_taxi_ambulance_remorquer)) echo $CL_taxi_ambulance_remorquer; ?>"></input> </span><span class=rvts2>tel que spécifié ci- dessus.</span></p>
<p><span class=rvts2><br></span></p>
<p><span class=rvts2>Merci de nous adresser votre facture originale dès que possible </span><span class=rvts10>(dans un délai de 15 jours) </span><span class=rvts2>à l</span><span class=rvts11>’</span><span class=rvts2>adresse ci-dessus, en mentionnant </span><span class=rvts10>notre référence de dossier ci-dessus.</span></p>
<p><span class=rvts5><br></span></p>
<p><span class=rvts12>ATTENTION IMPORTANT</span><span class=rvts13> </span></p>
<p><span class=rvts14><br></span></p>
<p><span class=rvts15>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p><span class=rvts15>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts16>’</span><span class=rvts15>accompagnerait.</span></p>
<p><span class=rvts5><br></span></p>
<p><span class=rvts9><br></span></p>
<p><span class=rvts2>Merci de votre collaboration.</span></p>
<p><span class=rvts9><br></span></p>
<p><span class=rvts9><br></span></p>
<p><span class=rvts2>P/ la Gérante</span></p>
<p class=rvps1><span class=rvts2><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>"/> </span></p>
<p><span class=rvts2>Plateau d</span><span class=rvts11>’</span><span class=rvts2>assistance technique/médicale</span></p>
<p class=rvps6><span class=rvts17>« courrier électronique, sans signature »</span></p>
<p class=rvps4><span class=rvts18><br></span></p>
</body></html>

<?php
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['CL_date_heure_debut'])) {$CL_date_heure_debut=$_GET['CL_date_heure_debut'];}
if (isset($_GET['CL_date_heure_fin'])) {$CL_date_heure_fin=$_GET['CL_date_heure_fin'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['vehicule_type'])) {$vehicule_type=$_GET['vehicule_type'];}
if (isset($_GET['vehicule_immatriculation'])) {$vehicule_immatriculation=$_GET['vehicule_immatriculation'];}
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell']; }
if (isset($_GET['CL_lieu_localisation'])) {$CL_lieu_localisation=$_GET['CL_lieu_localisation'];}
if (isset($_GET['CL_type-expertise'])) {$CL_type_expertise=$_GET['CL_type-expertise'];}
if (isset($_GET['reference_medic2'])) {$reference_medic2=$_GET['reference_medic2']; }
if (isset($_GET['CL_coordonner'])) {$CL_coordonner=$_GET['CL_coordonner'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>taml0bzv01qwdlqbdo97kjxixaeohm1q_PEC_expertise</title>
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
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #538135;
            background-color: #ffff00;
        }
        span.rvts5
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #538135;
            background-color: #ffff00;
        }
        span.rvts6
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #538135;
        }
        span.rvts7
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts8
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            letter-spacing: -2px;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts18
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts19
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts20
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            letter-spacing: -1px;
        }
        span.rvts21
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts22
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts23
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts24
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts25
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts26
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts27
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts28
        {
            font-size: 14pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts29
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts30
        {
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
            margin: 5px 0px 0px 8px;
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
            widows: 2;
            orphans: 2;
            margin: 4px 0px 0px 8px;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 8px;
        }
        .rvps9
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 0px 0px 0px 8px;
        }
        .rvps10
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
        }
        .rvps11
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 8px 0px 0px 8px;
        }
        .rvps12
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.17;
            widows: 2;
            orphans: 2;
            margin: 0px 4px 0px 8px;
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
<p class=rvps1><span class=rvts4>Aux développeurs : Chaque fois qu</span><span class=rvts5>’</span><span class=rvts4>on définit une date et heure, prévoir qu</span><span class=rvts5>’</span><span class=rvts4>elle servira aux rappels</span><span class=rvts6> </span><span class=rvts4>SVP</span></p>
<p class=rvps3><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse Le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts7>PRISE EN CHARGE EXPERTISE</span></p>
<p class=rvps4><span class=rvts8><br></span></p>
<p class=rvps5><span class=rvts9>Bonjour de Sousse,</span></p>
<p class=rvps6><span class=rvts9><br></span></p>
<p class=rvps7><span class=rvts9>Suite</span><span class=rvts10> </span><span class=rvts9>à</span><span class=rvts10> </span><span class=rvts9>notre</span><span class=rvts10> </span><span class=rvts9>conversation</span><span class=rvts10> </span><span class=rvts9>téléphonique,</span><span class=rvts11> </span><span class=rvts9>nous</span><span class=rvts10> </span><span class=rvts9>vous</span><span class=rvts10> </span><span class=rvts9>confirmons</span><span class=rvts10> </span><span class=rvts9>notre</span><span class=rvts10> </span><span class=rvts9>demande</span><span class=rvts10> </span><span class=rvts9>d</span><span class=rvts12>’</span><span class=rvts9>expertise</span><span class=rvts10> </span><span class=rvts9>du</span><span class=rvts10> </span><span class=rvts9>véhicule</span><span class=rvts10> </span><span class=rvts9>en</span><span class=rvts10> </span><span class=rvts9>date du</span><span class=rvts10> <input name="CL_date_heure_debut" placeholder="Date Heure Debut" value="<?php if(isset ($CL_date_heure_debut)) echo $CL_date_heure_debut; ?>"></input> à <input name="CL_date_heure_fin" placeholder="Date Heure fin" value="<?php if(isset ($CL_date_heure_fin)) echo $CL_date_heure_fin; ?>"></input></span><span class=rvts13>, selon </span><span class=rvts9>les informations ci-dessous relatives à ce dossier :</span></p>
<p class=rvps8><span class=rvts9><br></span></p>
<p class=rvps9><span class=rvts14>Client</span><span class=rvts9> : <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<p class=rvps9><span class=rvts15>Nom</span><span class=rvts16> </span><span class=rvts15>assuré</span><span class=rvts17> </span><span class=rvts13>:</span><span class=rvts17>  <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /></span></p>
<p class=rvps9><span class=rvts15>Prénom</span><span class=rvts16> </span><span class=rvts15>assuré</span><span class=rvts17> </span><span class=rvts13>:</span><span class=rvts17><input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p class=rvps9><span class=rvts15>Notre référence</span><span class=rvts13>: <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p class=rvps9><span class=rvts15>Type du véhicule</span><span class=rvts17> </span><span class=rvts13>: <input name="vehicule_type" placeholder="Type et marque du véhicule" value="<?php if(isset ($vehicule_type)) echo $vehicule_type; ?>"></input></span></p>
<p class=rvps9><span class=rvts15>Immatriculation</span><span class=rvts17> </span><span class=rvts13>: <input name="vehicule_immatriculation" placeholder="immatriculation" value="<?php if(isset ($vehicule_immatriculation)) echo $vehicule_immatriculation; ?>"></input></span></p>
<p class=rvps9><span class=rvts15>Contact abonné</span><span class=rvts17> </span><span class=rvts13>:<input name="subscriber_phone_cell" placeholder="téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell;?>"/></span></p>
<p class=rvps9><span class=rvts15>Localisation du véhicule</span><span class=rvts13> :<input name="CL_lieu_localisation" placeholder="Lieu Localisation" value="<?php if(isset ($CL_lieu_localisation)) echo $CL_lieu_localisation; ?>"></input></span></p>
<p class=rvps9><span class=rvts15>Type d</span><span class=rvts18>’</span><span class=rvts15>expertise</span><span class=rvts13> :<input name="CL_type_expertise" placeholder="Type Expertise" value="<?php if(isset ($CL_type_expertise)) echo $CL_type_expertise; ?>"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span class=rvts15>Référence client</span><span class=rvts13>:  <input name="reference_medic2" id="reference_medic2"placeholder="reference2" value="<?php if(isset ($reference_medic2)) echo $reference_medic2; ?>"></input></span></p>
<p class=rvps9><span class=rvts13>&nbsp;</span><span class=rvts9> (à noter sur le rapport si IMA)&nbsp; </span></p>
<p class=rvps10><span class=rvts9><br></span></p>
<p class=rvps11><span class=rvts9>Merci de coordonner avec </span><span class=rvts13><input name="CL_coordonner" placeholder="Coordonner" value="<?php if(isset ($CL_coordonner)) echo $CL_coordonner; ?>"></input> pour convenir de l</span><span class=rvts19>’</span><span class=rvts13>heure du rendez-vous.</span></p>
<p class=rvps11><span class=rvts9><br></span></p>
<p class=rvps12><span class=rvts9>Nous,</span><span class=rvts10> </span><span class=rvts14>Najda</span><span class=rvts20> </span><span class=rvts14>Assistance</span><span class=rvts9>,</span><span class=rvts10> </span><span class=rvts9>nous</span><span class=rvts10> </span><span class=rvts9>engageons</span><span class=rvts10> </span><span class=rvts9>à</span><span class=rvts10> </span><span class=rvts9>prendre</span><span class=rvts10> </span><span class=rvts9>en</span><span class=rvts10> </span><span class=rvts9>charge</span><span class=rvts10> </span><span class=rvts9>les</span><span class=rvts10> </span><span class=rvts9>frais</span><span class=rvts10> </span><span class=rvts9>de</span><span class=rvts10> </span><span class=rvts9>cette expertise</span><span class=rvts10> </span><span class=rvts9>tel</span><span class=rvts10> </span><span class=rvts9>que</span><span class=rvts10> </span><span class=rvts9>spécifié</span><span class=rvts10> </span><span class=rvts9>ci-dessus.</span><span class=rvts10> </span><span class=rvts9>Merci</span><span class=rvts10> </span><span class=rvts9>de</span><span class=rvts10> </span><span class=rvts9>nous adresser</span><span class=rvts11> </span><span class=rvts9>votre</span><span class=rvts11> </span><span class=rvts9>facture</span><span class=rvts11> </span><span class=rvts9>originale</span><span class=rvts11> </span><span class=rvts9>accompagnée</span><span class=rvts11> </span><span class=rvts9>de</span><span class=rvts11> </span><span class=rvts9>l'original</span><span class=rvts11> </span><span class=rvts9>du</span><span class=rvts11> </span><span class=rvts9>rapport</span><span class=rvts11> </span><span class=rvts9>d'expertise</span><span class=rvts11> </span><span class=rvts9>(dans</span><span class=rvts11> </span><span class=rvts9>un</span><span class=rvts11> </span><span class=rvts9>délai</span><span class=rvts11> </span><span class=rvts9>max</span><span class=rvts11> </span><span class=rvts9>de</span><span class=rvts11> </span><span class=rvts9>15</span><span class=rvts11> </span><span class=rvts9>jours)</span><span class=rvts11> </span><span class=rvts9>à</span><span class=rvts11> </span><span class=rvts9>l'adresse</span><span class=rvts11> </span><span class=rvts9>ci-dessus, en mentionnant notre référence</span><span class=rvts10> </span><span class=rvts9>de dossier.</span></p>
<p><span class=rvts21><br></span></p>
<p><span class=rvts22>ATTENTION IMPORTANT</span><span class=rvts23> </span></p>
<p><span class=rvts24><br></span></p>
<p class=rvps1><span class=rvts25>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts25>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts26>’</span><span class=rvts25>accompagnerait</span><span class=rvts27>.</span></p>
<p><span class=rvts28><br></span></p>
<p><span class=rvts29>Merci de votre collaboration.</span></p>
<p><span class=rvts29><br></span></p>
<p><span class=rvts29>P/la Gérante</span></p>
<p class=rvps1><span class=rvts29><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>"/></span></p>
<p><span class=rvts29>Plateau d</span><span class=rvts30>’</span><span class=rvts29>assistance technique</span></p>
<p class=rvps1><span class=rvts29>« courrier électronique, sans signature »</span></p>
</body></html>


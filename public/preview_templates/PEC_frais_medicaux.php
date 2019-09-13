<?php
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['CL_montant_numerique'])) {$CL_montant_numerique=$_GET['CL_montant_numerique'];}
if (isset($_GET['CL_montant_toutes_lettres'])) {$CL_montant_toutes_lettres=$_GET['CL_montant_toutes_lettres'];}
if (isset($_GET['CL_text'])) {$CL_text=$_GET['CL_text'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['montantgop'])) {$montantgop=$_GET['montantgop'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_frais_medicaux</title>
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
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts4
        {
            font-size: 14pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts5
        {
            font-size: 15pt;
            font-family: 'TimesNewRomanPS-BoldMT';
            font-weight: bold;
        }
        span.rvts6
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts7
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #00b050;
        }
        span.rvts8
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #ff0000;
        }
        span.rvts9
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts10
        {
            font-size: 12pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #0070c0;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            text-decoration: underline;
        }
        span.rvts17
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
            text-decoration: underline;
        }
        span.rvts18
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts19
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts20
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
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
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts23
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts24
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts25
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts26
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts27
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #00b050;
        }
        span.rvts28
        {
            font-size: 9pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #00b050;
        }
        span.rvts29
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts30
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
        }
        span.rvts31
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            text-decoration: underline;
        }
        span.rvts32
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts33
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts34
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #00b050;
        }
        span.rvts35
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -2px;
            text-decoration: underline;
        }
        span.rvts36
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts37
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts38
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts39
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts40
        {
            font-size: 7pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts41
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
            margin: 5px 0px 0px 8px;
        }
        .rvps5
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 2px 0px 0px 8px;
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.18;
            widows: 2;
            orphans: 2;
            margin: 2px 0px 0px 8px;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 1px 0px 0px 8px;
        }
        .rvps8
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.18;
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
<p class=rvps2><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts3>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input> </span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps3><span class=rvts4>PRISE EN CHARGE DE FRAIS MEDICAUX</span></p>
<p class=rvps3><span class=rvts5><br></span></p>
<p class=rvps4><span class=rvts6>Client : <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<p class=rvps5><span class=rvts6>Nom patient : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> Prénom :<input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p class=rvps6><span class=rvts6>Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p class=rvps6><span style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label>Montant maximal prise en charge (TND): <input name="CL_montant_numerique" placeholder="Montant Numerique" value="<?php if(isset ($CL_montant_numerique)) echo $CL_montant_numerique; ?>" onKeyUp=" keyUpHandler(this)"></input>&nbsp; </span>Toutes lettres : <input name="CL_montant_toutes_lettres" id="CL_montant_toutes_lettres" placeholder="Montant toutes lettres" value="<?php if(isset ($CL_montant_toutes_lettres)) echo $CL_montant_toutes_lettres; ?>"></input> dinars</p>
<p class=rvps6><span class=rvts6>Franchise: </span><span class=rvts7>(OUI si montant saisi dans le champs franchise. Sinon NON)</span></p>
<p class=rvps7><span class=rvts6>Montant de la franchise: </span><span class=rvts8>Franchise_dossier</span><span class=rvts6>&nbsp; (ligne apparait uniquement s</span><span class=rvts9>’</span><span class=rvts6>il y</span><span class=rvts9>’</span><span class=rvts6>a montant saisi dans dossier) </span></p>
<p class=rvps5><span class=rvts6>Document à signer: </span><span class=rvts7>(OUI si coché dans dossier, sinon NON) </span></p>
<p><span class=rvts10>&nbsp; </span></p>
<p class=rvps5><span class=rvts11>Nous</span><span class=rvts12> </span><span class=rvts11>soussignés,</span><span class=rvts12> </span><span class=rvts11>Najda</span><span class=rvts12> </span><span class=rvts11>Assistance,</span><span class=rvts12> </span><span class=rvts11>nous</span><span class=rvts12> </span><span class=rvts11>engageons</span><span class=rvts12> </span><span class=rvts11>à</span><span class=rvts12> </span><span class=rvts11>prendre</span><span class=rvts12> </span><span class=rvts11>en</span><span class=rvts12> </span><span class=rvts11>charge,</span><span class=rvts12> </span><span class=rvts11>pour</span><span class=rvts12> </span><span class=rvts11>le</span><span class=rvts12> </span><span class=rvts11>compte</span><span class=rvts12> </span><span class=rvts11>de</span><span class=rvts12> </span><span class=rvts11>notre</span><span class=rvts12> </span><span class=rvts11>client</span><span class=rvts13>,</span><span class=rvts12> </span><span class=rvts11>les</span><span class=rvts12> </span><span class=rvts11>frais</span><span class=rvts12> </span><span class=rvts11>médicaux</span><span class=rvts12> </span><span class=rvts11>et</span><span class=rvts12> </span><span class=rvts11>d</span><span class=rvts14>’</span><span class=rvts11>hospitalisation</span><span class=rvts12> </span><span class=rvts11>du</span><span class=rvts12> </span><span class=rvts11>(de</span><span class=rvts12> </span><span class=rvts11>la) patient(e) ci-dessus mentionné(e)</span><span class=rvts15> </span><span class=rvts11>pour le</span><span class=rvts15> </span><span class=rvts11>montant maximal ci-dessus.</span></p>
<p class=rvps8><span class=rvts11>Merci</span><span class=rvts15> </span><span class=rvts11>de</span><span class=rvts15> </span><span class=rvts11>nous</span><span class=rvts15> </span><span class=rvts11>adresser</span><span class=rvts15> </span><span class=rvts11>votre</span><span class=rvts15> </span><span class=rvts11>facture</span><span class=rvts15> </span><span class=rvts11>originale</span><span class=rvts15> </span><span class=rvts11>dès</span><span class=rvts15> </span><span class=rvts11>que</span><span class=rvts15> </span><span class=rvts11>possible</span><span class=rvts15> </span><span class=rvts11>(et</span><span class=rvts15> </span><span class=rvts11>au</span><span class=rvts15> </span><span class=rvts11>plus</span><span class=rvts15> </span><span class=rvts11>tard</span><span class=rvts15> </span><span class=rvts11>30</span><span class=rvts15> </span><span class=rvts11>jours</span><span class=rvts15> </span><span class=rvts11>après</span><span class=rvts15> </span><span class=rvts11>la</span><span class=rvts15> </span><span class=rvts11>sortie),</span><span class=rvts15> </span><span class=rvts16>accompagnée</span><span class=rvts17> </span><span class=rvts16>de</span><span class=rvts17> </span><span class=rvts16>tous</span><span class=rvts15> </span><span class=rvts16>les</span><span class=rvts15> </span><span class=rvts16>justificatifs</span><span class=rvts15> </span><span class=rvts11>(notamment</span><span class=rvts15> </span><span class=rvts11>articles</span><span class=rvts15> </span><span class=rvts11>de </span><span class=rvts18>pharmacie,</span><span class=rvts19> </span><span class=rvts18>laboratoire,</span><span class=rvts19> </span><span class=rvts18>notes</span><span class=rvts19> </span><span class=rvts18>d</span><span class=rvts20>’</span><span class=rvts18>honoraires,</span><span class=rvts19> </span><span class=rvts18>rapport</span><span class=rvts19> </span><span class=rvts18>médical</span><span class=rvts19> </span><span class=rvts18>avec</span><span class=rvts19> </span><span class=rvts18>codification</span><span class=rvts19> </span><span class=rvts18>précise</span><span class=rvts19> </span><span class=rvts18>des</span><span class=rvts19> </span><span class=rvts18>actes</span><span class=rvts19> </span><span class=rvts18>pratiqués…),</span><span class=rvts19> </span><span class=rvts18>à</span><span class=rvts19> </span><span class=rvts18>notre adresse</span><span class=rvts19> </span><span class=rvts18>ci-dessus,</span><span class=rvts19> </span><span class=rvts18>en</span><span class=rvts19> </span><span class=rvts18>mentionnant</span><span class=rvts19> </span><span class=rvts18>notre</span><span class=rvts19> </span><span class=rvts18>référence</span><span class=rvts19> </span><span class=rvts21>de dossier</span><span class=rvts18>.</span></p>
<p class=rvps9><span class=rvts13><br></span></p>
<p class=rvps9><span class=rvts7>Apparait par défaut coché. Case à décocher si extra pris en charge</span></p>
<p class=rvps9><span class=rvts22>Attention</span><span class=rvts23> </span><span class=rvts24>:</span><span class=rvts23> </span><span class=rvts24>Cette</span><span class=rvts25> </span><span class=rvts24>prise</span><span class=rvts25> </span><span class=rvts24>en</span><span class=rvts23> </span><span class=rvts24>charge</span><span class=rvts25> </span><span class=rvts24>s</span><span class=rvts26>’</span><span class=rvts24>entend</span><span class=rvts25> </span><span class=rvts24>hors</span><span class=rvts25> </span><span class=rvts24>extra</span><span class=rvts25> </span><span class=rvts24>(y</span><span class=rvts25> </span><span class=rvts24>compris</span><span class=rvts25> </span><span class=rvts24>surclassement</span><span class=rvts23> </span><span class=rvts24>de</span><span class=rvts25> </span><span class=rvts24>chambre)</span><span class=rvts23> </span><span class=rvts24>et</span><span class=rvts23> </span><span class=rvts24>conformément</span><span class=rvts23> </span><span class=rvts24>à</span><span class=rvts25> </span><span class=rvts24>la</span><span class=rvts25> </span><span class=rvts24>nomenclature</span><span class=rvts25> </span><span class=rvts24>officielle</span><span class=rvts25> </span><span class=rvts24>des</span><span class=rvts25> </span><span class=rvts24>actes médicaux</span><span class=rvts25> </span><span class=rvts24>et</span><span class=rvts23> </span><span class=rvts24>à</span><span class=rvts25> </span><span class=rvts24>votre</span><span class=rvts25> </span><span class=rvts24>liste</span><span class=rvts25> </span><span class=rvts24>de</span><span class=rvts25> </span><span class=rvts24>prix</span></p>
<p class=rvps9><span class=rvts27><br></span></p>
<p class=rvps9><span class=rvts7>Apparait si coché dans dossier « document à signer » . Sinon tout ce paragraphe n</span><span class=rvts28>’</span><span class=rvts7>apparait pas</span></p>
<p><span class=rvts29>Attention</span><span class=rvts30> </span><span class=rvts31>:</span><span class=rvts30> </span><span class=rvts32>Cette</span><span class=rvts30> </span><span class=rvts32>prise</span><span class=rvts30> </span><span class=rvts32>en</span><span class=rvts30> </span><span class=rvts32>charge</span><span class=rvts30> </span><span class=rvts32>ne</span><span class=rvts30> </span><span class=rvts32>sera</span><span class=rvts30> </span><span class=rvts32>valable</span><span class=rvts30> </span><span class=rvts32>que</span><span class=rvts30> </span><span class=rvts32>si</span><span class=rvts30> </span><span class=rvts32>la</span><span class=rvts30> </span><span class=rvts32>facture</span><span class=rvts30> </span><span class=rvts32>nous</span><span class=rvts30> </span><span class=rvts32>parvient</span><span class=rvts30> </span><span class=rvts32>accompagnée</span><span class=rvts30> </span><span class=rvts32>de</span><span class=rvts30> </span><span class=rvts29>l</span><span class=rvts33>’</span><span class=rvts29>original</span><span class=rvts30> </span><span class=rvts32>de</span><span class=rvts30>&nbsp;</span><span class=rvts32> </span><span class=rvts34>document désigné dossier</span><span class=rvts32> </span><span class=rvts31>dûment</span><span class=rvts35> </span><span class=rvts31>complétée</span><span class=rvts30> </span><span class=rvts32>et</span><span class=rvts30> </span><span class=rvts32>signée</span><span class=rvts30> </span><span class=rvts32>par</span><span class=rvts30> </span><span class=rvts32>le</span><span class=rvts30> </span><span class=rvts32>(la) patient(e)</span></p>
<p><span class=rvts32><br></span></p>
<p class=rvps9><span class=rvts16>Observations:</span><span class=rvts11> <input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
<p><span class=rvts32><br></span></p>
<p><span class=rvts36><br></span></p>
<p class=rvps1><span class=rvts37>ATTENTION IMPORTANT</span><span class=rvts38> </span></p>
<p class=rvps1><span class=rvts39>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. Cette prise en charge a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts39>Toute facture devra être envoyée accompagnée de la présente prise en charge, ainsi que de l'original de tout document à signer qui l</span><span class=rvts40>’</span><span class=rvts39>accompagnerait</span><span class=rvts36>.</span></p>
<p><span class=rvts3><br></span></p>
<p><span class=rvts3>Merci de votre collaboration.</span></p>
<p><span class=rvts3><br></span></p>
<p><span class=rvts3><br></span></p>
<p><span class=rvts3>P/ la Gérante</span></p>
<p class=rvps1><span class=rvts3><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" > </input></span></p>
<p><span class=rvts3>Plateau d</span><span class=rvts41>’</span><span class=rvts3>assistance médicale</span></p>
<p class=rvps1><span class=rvts3>« courrier électronique, sans signature »</span></p>
</form>
<script language="javascript" src="nombre_en_lettre.js"></script>
<script type="text/javascript">
    function keyUpHandler(obj){
            if (obj.value > <?php echo $montantgop; ?>) {document.getElementById("alertGOP").style.display="block";}
            else {document.getElementById("alertGOP").style.display="none";}
            document.getElementById("CL_montant_toutes_lettres").value  = NumberToLetter(obj.value)
        }//fin de keypressHandler
</script>
</body></html>

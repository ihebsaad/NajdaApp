<?php
if (isset($_GET['ville'])) {$ville=$_GET['ville'];}
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; $subscriber_name2=$_GET['subscriber_name'];}
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname'];$subscriber_lastname2=$_GET['subscriber_lastname'];}
if (isset($_GET['CL_age'])) {$CL_age=$_GET['CL_age'];}
if (isset($_GET['reference_customer'])) {$reference_customer=$_GET['reference_customer']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//FR">
<html><head><title>v0v3vdqk8khqpdac0lr3ipig4xm8mf3m_RM_francais</title>
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
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-weight: bold;
            text-decoration: underline;
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
            font-size: 11pt;
            font-family: 'Arial', 'Helvetica', sans-serif;
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
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #000000;
        }
        span.rvts8
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #000000;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            color: #000000;
        }
        span.rvts11
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
            widows: 2;
            orphans: 2;
            border-color: #000000;
            border-style: solid;
            border-width: 2px;
            border-top: none;
            border-right: none;
            border-left: none;
            padding: 0px 0px 1px 0px;
        }
        .rvps5
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
		 .rvps6
        {
            text-align: right;
            widows: 2;
            orphans: 2;
        }
        --></style>
</head>
<body>
<form id="formchamps">
    <input name="pre_dateheure" type="hidden" value="<?php if(isset ($pre_dateheure)) echo $pre_dateheure; ?>"> </input>
<p class=rvps6><span class=rvts1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span class=rvts2>Att: <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps6><span class=rvts5><input name="ville" id="ville" placeholder="" readonly value="<?php if(isset ($ville)) {echo $ville;}  if (empty($ville)){ echo ".............................." ;}?>" /><input name="date_heure" type="hidden" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts4><br></span></p>
<p class=rvps3><span class=rvts3>Rapport Médical</span></p>
<p><span class=rvts6><br></span></p>
<p><span class=rvts6><br></span></p>
<p class=rvps7><span class=rvts7>Nom patient : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p><span class=rvts7>Prénom:<input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> </span></p
<p><span class=rvts7>Age : <input name="CL_age" placeholder="Age" value="<?php if(isset ($CL_age)) echo $CL_age; ?>"></input> </span></p>
<p class=rvps7><span class=rvts7>V/Réf:  <input name="reference_customer" placeholder="reference Client" value="<?php if(isset ($reference_customer)) echo $reference_customer; ?>"></input> | <input name="customer_id__name2" id="customer_id__name2" placeholder="compagnie" value="<?php if(isset ($customer_id__name2)) echo $customer_id__name2; ?>" /></span></p>
<p class=rvps7><span class=rvts7>O/Ref:  <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input> | <input name="subscriber_name2" id="subscriber_name2" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name2)) echo $subscriber_name2; ?>" /><input name="subscriber_lastname2" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname2)) echo $subscriber_lastname2; ?>"></input> </span></p>
<p class=rvps7><span class=rvts7>Hospitalisé à : </span><span class=rvts8>structure d</span><span class=rvts9>’</span><span class=rvts8>hospitalisation_dossier</span><span class=rvts10> </span><span class=rvts3>(avec possibilité de choix)</span></p>
<p class=rvps7><span class=rvts7>Médecin traitant : </span><span class=rvts8>medecin intervenant_dossier </span><span class=rvts11>(</span><span class=rvts3>avec possibilité de choix)</span></p>
<p class=rvps3><span class=rvts3><br></span></p>
<h1 class=rvps6><span class=rvts0><span class=rvts12><br></span></span></h1>
</body></html>


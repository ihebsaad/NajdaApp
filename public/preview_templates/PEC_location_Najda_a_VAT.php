<?php
if (isset($_GET['date_heure'])) {$date_heure=$_GET['date_heure'];}
if (isset($_GET['customer_id__name'])) {$customer_id__name=$_GET['customer_id__name']; $customer_id__name2=$_GET['customer_id__name']; }
if (isset($_GET['subscriber_name'])) {$subscriber_name=$_GET['subscriber_name']; }
if (isset($_GET['subscriber_lastname'])) {$subscriber_lastname=$_GET['subscriber_lastname']; }
if (isset($_GET['reference_medic'])) {$reference_medic=$_GET['reference_medic']; }
if (isset($_GET['subscriber_phone_cell'])) {$subscriber_phone_cell=$_GET['subscriber_phone_cell'];}
if (isset($_GET['CL_date_debut_location'])) {$CL_date_debut_location=$_GET['CL_date_debut_location'];}
if (isset($_GET['CL_date_fin_location'])) {$CL_date_fin_location=$_GET['CL_date_fin_location'];}
if (isset($_GET['CL_duree_location'])) {$CL_duree_location=$_GET['CL_duree_location'];}
if (isset($_GET['CL_adresse_livraison'])) {$CL_adresse_livraison=$_GET['CL_adresse_livraison'];}
if (isset($_GET['CL_categorie'])) {$CL_categorie=$_GET['CL_categorie'];}
if (isset($_GET['CL_cout_location'])) {$CL_cout_location=$_GET['CL_cout_location'];}
if (isset($_GET['CL_cout_total'])) {$CL_cout_total=$_GET['CL_cout_total'];}
if (isset($_GET['CL_text'])) {$CL_text=$_GET['CL_text'];}
if (isset($_GET['agent__name'])) {$agent__name=$_GET['agent__name']; }
if (isset($_GET['pre_dateheure'])) {$pre_dateheure=$_GET['pre_dateheure'];}
if (isset($_GET['montantgop'])) {$montantgop=$_GET['montantgop'];}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>PEC_location_Najda_a_VAT</title>
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
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts7
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts8
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts9
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts10
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #2e74b5;
        }
        span.rvts11
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
            color: #ff0000;
        }
        span.rvts12
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts13
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            letter-spacing: -1px;
        }
        span.rvts14
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts15
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts16
        {
            font-family: 'Tahoma', 'Geneva', sans-serif;
        }
        span.rvts17
        {
            font-size: 11pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts18
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
            text-decoration: underline;
        }
        span.rvts19
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            text-decoration: underline;
        }
        span.rvts20
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
        }
        span.rvts21
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-style: italic;
            font-weight: bold;
        }
        span.rvts22
        {
            font-size: 8pt;
            font-family: 'Tahoma', 'Geneva', sans-serif;
            font-weight: bold;
        }
        span.rvts23
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
        }
        .rvps6
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            widows: 2;
            orphans: 2;
            margin: 4px 0px 0px 0px;
        }
        .rvps7
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 1.15;
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
            margin: 1px 0px 0px 0px;
        }
        .rvps9
        {
            text-align: justify;
            text-justify: inter-word;
            text-align-last: auto;
            line-height: 26px;
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
<p class=rvps3><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2> &nbsp; &nbsp; &nbsp; &nbsp;</span><span class=rvts2>Sousse le <input name="date_heure" type="text" value="<?php if(isset ($date_heure)) echo $date_heure; ?>"></input></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps1><span class=rvts2><br></span></p>
<p class=rvps4><span class=rvts4>PRISE EN CHARGE LOCATION DE VOITURE</span></p>
<p class=rvps4><span class=rvts5><br></span></p>
<p><span class=rvts6>Client </span><span class=rvts7>: <input name="customer_id__name" id="customer_id__name" placeholder="compagnie" value="<?php if(isset ($customer_id__name)) echo $customer_id__name; ?>" /></span></p>
<p class=rvps5><span class=rvts8>Nom Assuré : <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($subscriber_name)) echo $subscriber_name; ?>" /> </span></p>
<p><span class=rvts6>Prénom : <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($subscriber_lastname)) echo $subscriber_lastname; ?>"></input></span></p>
<p><span class=rvts6>Notre réf. dossier : <input name="reference_medic" placeholder="reference" value="<?php if(isset ($reference_medic)) echo $reference_medic; ?>"></input></span></p>
<p class=rvps6><span class=rvts9>Téléphone assuré :</span><span class=rvts8> <input name="subscriber_phone_cell"id="subscriber_phone_cell" placeholder="num de téléphone du l'abonnée"  value="<?php if(isset ($subscriber_phone_cell)) echo $subscriber_phone_cell; ?>"></input></span></p>
<p class=rvps6><span class=rvts9>Date début de location:<input type="datetime-local" name="CL_date_debut_location" placeholder="Date Debut Location" value="<?php if(isset ($CL_date_debut_location)) echo $CL_date_debut_location; ?>" onKeyUp=" calculduree()"></input></span></p>
<p class=rvps6><span class=rvts9>Date fin de location: <input type="datetime-local" name="CL_date_fin_location"  id="CL_date_fin_location" placeholder="Date Fin Location" value="<?php if(isset ($CL_date_fin_location)) echo $CL_date_fin_location; ?>" onKeyUp=" calculduree()"></input></span></p>
<p class=rvps6><span class=rvts9>Durée de la location :</span><span class=rvts10> </span><span class=rvts11><input name="CL_duree_location"  id="CL_duree_location" placeholder="Duree Location" value="<?php if(isset ($CL_duree_location)) echo $CL_duree_location; ?>"  onchange=" keyUpHandler(this)"></input></span></p>
<p class=rvps6><span class=rvts9>Adresse de livraison : <input name="CL_adresse_livraison" placeholder="Adresse Livraison" value="<?php if(isset ($CL_adresse_livraison)) echo $CL_adresse_livraison; ?>"></input></span></p>
<p class=rvps6><span class=rvts9>Type véhicule : <input name="CL_categorie" placeholder="Categorie" value="<?php if(isset ($CL_categorie)) echo $CL_categorie; ?>"></input></span></p>
<p class=rvps6><span class=rvts9>Coût de location journalier : <input name="CL_cout_location" id="CL_cout_location" placeholder="Cout Location" value="<?php if(isset ($CL_cout_location)) echo $CL_cout_location; ?>"  onchange=" keyUpHandler(this)" onKeyUp=" keyUpHandler(this) " ></input></span></p>
<p class=rvps6><span class=rvts9>Coût total : </span><span  style="display:inline-block; "><label id="alertGOP" for="CL_montant_numerique" style="display:none; color:red;">Montant GOP dépassé <?php if (isset($montantgop)) { echo " <b>(Max: ".$montantgop.")</b>";} ?></label><input name="CL_cout_total" id="CL_cout_total" placeholder="Cout total Location" value="<?php if(isset ($CL_cout_total)) echo $CL_cout_total; ?>"  onchange=" montantverif(this)" onKeyUp=" montantverif(this) " ></input></span></p>
<p class=rvps5><span class=rvts12><br></span></p>
<p class=rvps7><span class=rvts12>Suite</span><span class=rvts13> </span><span class=rvts12>à</span><span class=rvts13> </span><span class=rvts12>notre</span><span class=rvts13> </span><span class=rvts12>entretien</span><span class=rvts13> </span><span class=rvts12>téléphonique,</span><span class=rvts13> </span><span class=rvts12>nous</span><span class=rvts13> </span><span class=rvts12>vous</span><span class=rvts13> </span><span class=rvts12>confirmons</span><span class=rvts13> </span><span class=rvts12>la</span><span class=rvts13> </span><span class=rvts12>prise</span><span class=rvts13> </span><span class=rvts12>en</span><span class=rvts13> </span><span class=rvts12>charge</span><span class=rvts13> </span><span class=rvts12>des</span><span class=rvts13> </span><span class=rvts12>frais</span><span class=rvts13> </span><span class=rvts12>de</span><span class=rvts13> </span><span class=rvts12>location</span><span class=rvts13> </span><span class=rvts12>d</span><span class=rvts14>’</span><span class=rvts12>une</span><span class=rvts13> </span><span class=rvts12>voiture</span><span class=rvts13> </span><span class=rvts12>tel</span><span class=rvts13> </span><span class=rvts12>que</span><span class=rvts13> </span><span class=rvts12>spécifié</span><span class=rvts13> </span><span class=rvts12>ci-dessus.</span></p>
<p class=rvps8><span class=rvts12><br></span></p>
<p class=rvps5><span class=rvts12>Merci de nous adresser votre facture originale dès que possible (dans un délai max de 30 jours) à l</span><span class=rvts14>’</span><span class=rvts12>adresse ci-dessus, en mentionnant notre référence de dossier.</span></p>
<p class=rvps5><span class=rvts12><br></span></p>
<p class=rvps9><span class=rvts15>Observations</span><span class=rvts16> : </span><span class=rvts12><input name="CL_text" placeholder="text" value="<?php if(isset ($CL_text)) echo $CL_text; ?>"></input></span></p>
<p class=rvps9><span class=rvts17><br></span></p>
<p><span class=rvts18>ATTENTION IMPORTANT</span><span class=rvts19> </span></p>
<p><span class=rvts20><br></span></p>
<p class=rvps1><span class=rvts21>Toute facture reçue dans nos locaux plus de 60 jours après le service rendu ne pourra plus être garantie pour règlement. La prise en charge ci-joint a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p>
<p class=rvps1><span class=rvts21>Toute facture devra être envoyée accompagnée de la présente prise en charge</span><span class=rvts22>.</span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7>Merci de votre collaboration.</span></p>
<p><span class=rvts7><br></span></p>
<p><span class=rvts7>P/ la Gérante</span></p>
<p><span class=rvts7><input name="agent__name" id="agent__name" placeholder="nom du lagent" value="<?php if(isset ($agent__name)) echo $agent__name; ?>" > </input></span></p>
<p><span class=rvts7>Plateau d</span><span class=rvts23>’</span><span class=rvts7>assistance technique</span></p>
<p class=rvps1><span class=rvts7>« courrier électronique, sans signature »</span></p>
<p class=rvps4><span class=rvts7><br></span></p>
<p><span class=rvts7><br></span></p>
<script type="text/javascript">
    function keyUpHandler(obj){
        var coutloc=document.getElementById("CL_cout_location").value;
        var dureeloc=document.getElementById("CL_duree_location").value;
        document.getElementById("CL_cout_total").value = (parseInt(coutloc) * parseInt(dureeloc));
        document.getElementById("CL_cout_total").onchange();
        }//fin de keypressHandler
    function calculduree() {
        var inputdate1 = document.getElementsByName("CL_date_debut_location")[0].value;
        var inputdate2 = document.getElementById("CL_date_fin_location").value;
        var date1 = inputdate1.substring(0, 10);
        var date2 = inputdate2.substring(0, 10);

        /*if (date1.indexOf('/') > -1)
        {
            var date1Parts = date1.split("/"); }
        if (date1.indexOf('-') > -1)
        {
            var date1Parts = date1.split("-"); }
        if (date2.indexOf('/') > -1)
        {
            var date2Parts = date2.split("/"); }
        if (date2.indexOf('-') > -1)
        {
            var date2Parts = date2.split("-"); }

        // month is 0-based, that's why we need dataParts[1] - 1
        var datedeb = new Date(+date1Parts[2], date1Parts[1] - 1, +date1Parts[0]); 
        var datefin = new Date(+date2Parts[2], date2Parts[1] - 1, +date2Parts[0]); */
        var datedeb = new Date(date1);
        var datefin = new Date(date2);



        diffTime = Math.abs(datefin.getTime() - datedeb.getTime());
        diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
       // alert(diffDays);
        document.getElementById("CL_duree_location").value = diffDays;
        document.getElementById("CL_duree_location").onchange();
    }

    function montantverif(obj){
            //document.getElementById("CL_montant_toutes_lettres").firstChild.nodeValue =   NumberToLetter(obj.value)
            if (obj.value > <?php echo $montantgop; ?>) {document.getElementById("alertGOP").style.display="block";}
            else {document.getElementById("alertGOP").style.display="none";}
        }
</script>
</body></html>

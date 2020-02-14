<?php

if (! isset($_GET['remplace']))
{
    //if (isset($_GET['emispar'])) {$emispar=$_GET['emispar'];}
    if (isset($_GET['dossier'])) {$dossier=$_GET['dossier'];}
}


if (isset($_GET['DB_HOST'])) {$dbHost=$_GET['DB_HOST'];}
if (isset($_GET['DB_DATABASE'])) {$dbname=$_GET['DB_DATABASE'];}
if (isset($_GET['DB_USERNAME'])) {$dbuser=$_GET['DB_USERNAME'];}
if (isset($_GET['DB_PASSWORD'])) {$dbpass=$_GET['DB_PASSWORD'];}

//recuperation iduser connecte
if (isset($_GET['iduser'])) {$iduser=$_GET['iduser'];}

// Create connection
$conn = mysqli_connect($dbHost, $dbuser, $dbpass,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn,"set names 'utf8'");

// cas remplace: recuperation info parent
if ((isset($_GET['remplace']) || isset($_GET['complete'])) && isset($_GET['parent']))
{
    $parentom=$_GET['parent'];
    $sqlp = "SELECT * FROM om_remorquage WHERE id=".$parentom;
    $resultp = $conn->query($sqlp);
    if (!empty($resultp) && $resultp->num_rows > 0) {
        $detailom = $resultp->fetch_assoc();
        $emispar = $detailom['emispar'];
    $dossier = $detailom['dossier'];
        if (isset($_GET['affectea'])) {$affectea=$_GET['affectea'];} else {$affectea = $detailom['affectea'];}

    }
    else { exit("impossible de recuperer les informations de lordre de mission ".$parentom);}

    // infos agent
    

        $sqlagt = "SELECT name,lastname FROM users WHERE id=".$iduser;
        $resultagt = $conn->query($sqlagt);
        if (!empty($resultagt) && $resultagt->num_rows > 0) {
        // output data of each row
        $detailagtcomp = $resultagt->fetch_assoc();
        
        } else {
        echo "0 results agent";
        }

        // recuperer type dossier pour la signature (medicale ou technique)
        $sqlsign = "SELECT type_dossier FROM dossiers WHERE id=".$detailom['dossier'];
            $resultsign = $conn->query($sqlsign);

            if (!empty($resultsign) && $resultsign->num_rows > 0) {
                // output data of each row
                $agtsign = $resultsign->fetch_assoc();
                $signaturetype = strtolower($agtsign['type_dossier']);
            }
}
else
{

    $sql = "SELECT reference_medic, subscriber_name, subscriber_lastname, subscriber_phone_cell,subscriber_local_address, customer_id, reference_customer,vehicule_type,vehicule_marque,vehicule_immatriculation, lieu_immobilisation, affecte,type_dossier FROM dossiers WHERE id=".$dossier;
    $result = $conn->query($sql);

    if (!empty($result) && $result->num_rows > 0) {
        // output data of each row
        $detaildoss = $result->fetch_assoc();

        // recuperer type dossier pour la signature (medicale ou technique)
        $signaturetype = strtolower($detaildoss['type_dossier']);

        // infos client
        $sqlcl = "SELECT name, groupe FROM clients WHERE id=".$detaildoss['customer_id'];
        $resultcl = $conn->query($sqlcl);
        if (!empty($resultcl) && $resultcl->num_rows > 0) {
            // output data of each row
            $detailcl = $resultcl->fetch_assoc();

        } else {
            echo "0 results client";
        }

        // infos agent
        $sqlagt = "SELECT name, lastname FROM users WHERE id=".$iduser;
        $resultagt = $conn->query($sqlagt);
        if (!empty($resultagt) && $resultagt->num_rows > 0) {
            // output data of each row
            $detailagt = $resultagt->fetch_assoc();

        } else {
            echo "0 results agent";
        }

        

        // recuperation OM emis par

        // Najda
        if ((stristr($detaildoss['reference_medic'],'N')!== FALSE) && (stristr($detaildoss['reference_medic'],'TN')=== FALSE))
        {$emispar = "najda";}
        // VAT
        if ((stristr($detaildoss['reference_medic'],'V')!== FALSE) && (stristr($detaildoss['reference_medic'],'TV')=== FALSE))
        {$emispar = "vat";}
        // MEDIC
        if ((stristr($detaildoss['reference_medic'],'M')!== FALSE) && (stristr($detaildoss['reference_medic'],'TM')=== FALSE) && (stristr($detaildoss['reference_medic'],'MI')=== FALSE))
        {$emispar = "medicm";}
        // Transport MEDIC
        if (stristr($detaildoss['reference_medic'],'TM')!== FALSE)
        {$emispar = "medicm";}
        // Transport VAT
        if (stristr($detaildoss['reference_medic'],'TV')!== FALSE)
        {$emispar = "vat";}
        // Medic International
        if (stristr($detaildoss['reference_medic'],'MI')!== FALSE)
        {$emispar = "medici";}
        // Najda TPA
        if (stristr($detaildoss['reference_medic'],'TPA')!== FALSE)
        {$emispar = "najda";}
        // Transport Najda
        if (stristr($detaildoss['reference_medic'],'TN')!== FALSE)
        {$emispar = "najda";}
        // X-Press
        if (stristr($detaildoss['reference_medic'],'XP')!== FALSE)
        {$emispar = "xpress";}

    } else {
        echo "0 results dossier";
    }

}

// base_hotels(18)_cliniques(8)_hopitaux(9)_ports(72,38-assistance)_aeroports(73) (name/tel) garage (22)
$sqltypeprest = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (72,22))";

$resulttp = $conn->query($sqltypeprest);
if (!empty($resulttp) && $resulttp->num_rows > 0) {
    // output data of each row
    $array_prest = array();
    while($row = $resulttp->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
        if (intval($row["ville_id"]) > 0)
        {
            $sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$row['ville_id'];
            $resultvprest = $conn->query($sqlvilleprest);
            $vprest = $resultvprest->fetch_assoc();
            $nnameprest = $row['name']." [".$vprest['name']."]";
        }
        elseif (! empty($row["ville"]))
        {
            $nnameprest = $row['name']." [".$row['ville']."]";
        }
        elseif (empty($row["ville"]))
        {
            $nnameprest = $row['name'];
        }
        $array_prest[] = array('id' => $row["id"],'name' => $nnameprest, 'phone_home' => $row["phone_home"]);
    }
    //print_r($array_prest);
} else {
    echo "0 results prestataires";
}

// hotel clinique hopital
$sqlhch = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (18))";
$resulthch = $conn->query($sqlhch);
if (!empty($resulthch) && $resulthch->num_rows > 0) {
    // output data of each row
    $array_presthch = array();
    while($rowhch = $resulthch->fetch_assoc()) {
        if (intval($rowhch["ville_id"]) > 0)
        {
            $sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$rowhch['ville_id'];
            $resultvprest = $conn->query($sqlvilleprest);
            $vprest = $resultvprest->fetch_assoc();
            $nnameprest = $rowhch['name']." [".$vprest['name']."]";
        }
        elseif (! empty($rowhch["ville"]))
        {
            $nnameprest = $rowhch['name']." [".$rowhch['ville']."]";
        }
        elseif (empty($rowhch["ville"]))
        {
            $nnameprest = $rowhch['name'];
        }
        $array_presthch[] = array('id' => $rowhch["id"],'name' => $nnameprest, 'phone_home' => $rowhch["phone_home"]);
    }
    //print_r($array_prest);
} else {
    echo "0 results prestataires hch";
}

// Aeroport Port
$sqlap = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM prestataires_type_prestations WHERE type_prestation_id IN (72))";
$resultap = $conn->query($sqlap);
if (!empty($resultap) && $resultap->num_rows > 0) {
    // output data of each row
    $array_prestap = array();
    while($rowap = $resultap->fetch_assoc()) {
        if (intval($rowap["ville_id"]) > 0)
        {
            $sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$rowap['ville_id'];
            $resultvprest = $conn->query($sqlvilleprest);
            $vprest = $resultvprest->fetch_assoc();
            $nnameprest = $rowap['name']." [".$vprest['name']."]";
        }
        elseif (! empty($rowap["ville"]))
        {
            $nnameprest = $rowap['name']." [".$rowap['ville']."]";
        }
        elseif (empty($rowap["ville"]))
        {
            $nnameprest = $rowap['name'];
        }
        $array_prestap[] = array('id' => $rowap["id"],'name' => $nnameprest, 'phone_home' => $rowap["phone_home"]);
    }
    //print_r($array_prest);
} else {
    echo "0 results prestataires ap";
}


$sqlgg = "SELECT id,name,phone_home,ville,ville_id FROM prestataires WHERE id IN (SELECT prestataire_id FROM specialites_prestataires WHERE specialite IN (116))";
$resultgg = $conn->query($sqlgg);
if ( mysqli_num_rows($resultgg)) {
    // output data of each row
    $array_prestgg = array();
    while($row = $resultgg->fetch_assoc()) {
        if (intval($row["ville_id"]) > 0)
        {
            $sqlvilleprest = "SELECT id,name FROM villes WHERE id =".$row['ville_id'];
            $resultvpre = $conn->query($sqlvilleprest);
            $vprest = $resultvprest->fetch_assoc();
            $nnameprest = $row['name']." [".$vprest['name']."]";
        }
        elseif (! empty($row["ville"]))
        {
            $nnameprest = $row['name']." [".$row['ville']."]";
        }

        elseif (empty($row["ville"]))
        {
            $nnameprest = $row['name'];
        }
        $array_prestgg[] = array('id' => $row["id"],'name' => $nnameprest, 'phone_home' => $row["phone_home"]);
    }
    //print_r($array_prest);
} else {
    echo "0 results prestataires gg";
}


// vehicules - vehic
/*$sqlvh = "SELECT id,name FROM voitures";
$resultvh = $conn->query($sqlvh);
if (!empty($resultvh) && $resultvh->num_rows > 0) {
    // output data of each row
    $array_vehic = array();
    while($rowvh = $resultvh->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
        $array_vehic[] = array('id' => $rowvh["id"],'name' => $rowvh["name"]);
    }
    //print_r($array_prest);
}*/
// personnels - chauffeur
/*$sqlchauff = "SELECT id,name,tel FROM personnes";
$resultchauff = $conn->query($sqlchauff);
if (!empty($resultchauff) && $resultchauff->num_rows > 0) {
    // output data of each row
    $array_chauff = array();
    while($rowchauff = $resultchauff->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
        $array_chauff[] = array('id' => $rowchauff["id"],'name' => $rowchauff["name"], 'tel' => $rowchauff["tel"]);
    }
    //print_r($array_prest);
}*/


$sqldossier = "SELECT reference_medic,type_dossier FROM dossiers";
$resultdossier= $conn->query($sqldossier);
if (!empty($resultdossier) && $resultdossier->num_rows > 0) {
    // output data of each row
    $array_dossier = array();
    while($rowdossier = $resultdossier->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
          $array_dossier[] = array('reference_medic' => $rowdossier["reference_medic"],'type_dossier' => $rowdossier["type_dossier"]);
    }
    //print_r($array_prest);
}

$sqlspec = "SELECT id,nom FROM specialites WHERE id IN (SELECT specialite FROM specialites_typeprestations WHERE type_prestation IN (1))";
$resultspec= $conn->query($sqlspec);
if (!empty($resultspec) && $resultspec->num_rows > 0) {
    // output data of each row
    $array_spec = array();
    while($rowspec = $resultspec->fetch_assoc()) {
        //echo "name: " . $row["name"]. " - phone_home: " . $row["phone_home"]. "<br>";
          $array_spec[] = array('id' => $rowspec["id"],'nom' => $rowspec["nom"]);
    }
    //print_r($array_prest);
}

header("Content-Type: text/html;charset=UTF-8");
?>
<html>
<head>
    <!-- https://www.aconvert.com/document/html-to-doc/ -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta http-equiv="Content-Style-Type" content="text/css" />
    <title></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<body>
<form id="formchamps">
    <div class="row">
        <div id="entetelogo" class="col-md-3">
            <input name="emispar" id="emispar" type="hidden" value="<?php if(isset ($emispar)) echo $emispar; ?>"></input>
            <?php if (isset($emispar)) {  ?>
            <?php if ($emispar == "najda") { ?>
            <div>
                <p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="najda.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
            </div>
            <br style="clear:both; mso-break-type:section-break" />
            <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
            <p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
                <span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane - Sahloul III</span>
            </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
                <span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">B.P. 41 - 4054 Sousse-Sahloul - Tunisie Tel : (+216) 3600 3600</span>
            </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
                <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">Fax : (+216) 73 82 03 33</span>
            </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
                <span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">24ops@najda-assistance.com DD-(FP-03/04)-08/01</span>
                <?php } ?>

                <?php if ($emispar == "medici") { ?>
                <div>
            <p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="medici.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
        </div>
        <br style="clear:both; mso-break-type:section-break" />
        <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
        <p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
            <span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane BP41,  Sahloul III </span>
        </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
            <span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">4054 Sousse Sahloul, Tunisie - Tel : (+216) 3600 3600</span>
        </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
            <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">Fax : (+216) 73 36 90 01</span>
        </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
            <span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">international@medicmultiservices.com</span>
            <?php } ?>


            <?php if (($emispar == "medicm") || ($emispar == "medict"))  { ?>
            <div>
        <p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="medicm.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    </div>
    <br style="clear:both; mso-break-type:section-break" />
    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    <p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane - Sahloul III</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">B.P. 41 - 4054 Sousse-Sahloul - Tunisie </span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 00</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 01</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
        <span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">ambulance.transp@medicmultiservices.com</span>
        <?php } ?>

        <?php if ($emispar == "vat")  { ?>
        <div>
    <p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="vat.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    </div>
    <br style="clear:both; mso-break-type:section-break" />
    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    <p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane - Sahloul III</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">B.P. 41 - 4054 Sousse-Sahloul - Tunisie </span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 00</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 73 36 90 01</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt">
        <span id="Eligne4" style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">ambulance.transp@medicmultiservices.com</span>
        <?php } ?>

        <?php if ($emispar == "xpress")   { ?>
        <div>
    <p style="margin-left:7px;margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:5.5pt"><span style="height:0pt; margin-top:-0.35pt; display:block; position:absolute; z-index:0"><img src="xpress.png" width="161" height="98" alt="" style="margin-top:10pt; -aw-left-pos:16pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:-10.4pt; -aw-wrap-type:none; position:absolute" /></span><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    </div>
    <br style="clear:both; mso-break-type:section-break" />
    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:6pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p><p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    <p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne1" style="font-family:'Times New Roman'; font-weight:bold">Rue Mohamed Hamdane</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne2" style="font-family:'Times New Roman'; font-weight:bold">B.P. 41 - 4054 Sousse-Sahloul - Tunisie </span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">(+216) 36 003 610</span>
    </p><p style="margin-top:2.7pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt">
        <span id="Eligne3" style="font-family:'Times New Roman'; font-weight:bold">FAX (+216) 73 820 333</span>
        <p style="margin-top:0.2pt; margin-bottom:0pt; widows:0; orphans:0; font-size:7.5pt"><span style="font-family:'Times New Roman'">&#xa0;</span></p>
    </p>
    <?php } ?>

    <?php } ?>
    </div>
    <div class="col-md-9">
        <?php if (isset($detailom['prestataire_remorquage'])) { ?>
            <span style="font-family:'Times New Roman';font-weight: bold;">Prestataire: </span>
            <span id="prestataire_remorquage" style="font-family:'Times New Roman'; "><?php echo $detailom['prestataire_remorquage']; ?></span>
            <input name="type_affectation_post" id="type_affectation_post" type="hidden" value="<?php echo $detailom['prestataire_remorquage']; ?>"></input>
        <?php } ?>

        <h1 style="margin-top:8.75pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:20pt"><span style="font-family:'Times New Roman'; text-decoration:underline">ORDRE DE MISSION</span><span style="font-family:'Times New Roman'; text-decoration:underline"> </span><span style="font-family:'Times New Roman'; text-decoration:underline">REMORQUAGE</span></h1><p style="margin-top:0.6pt; margin-left:5.85pt; margin-bottom:0pt; text-align:right; widows:0; orphans:0; font-size:8pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p>
        <p style="margin-top:0.6pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'"></span>
            <span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">        </span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">S</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">pecialite Remorqueur</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">&#xa0;</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">: </span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt"> </span>
       <input type="text" list="CL_spec" name="CL_spec" <?php if (isset($detailom)) { if (isset($detailom['CL_spec'])) {echo "value='".$detailom['CL_spec']."'";}} ?> />
        <datalist id="CL_spec">
            <?php
foreach ($array_spec as $spec) {
    
    echo "<option value='".$spec['nom']."' telprest='".$spec['nom']."'>".$spec['nom']."</option>";
}
?>
        </datalist>
            <span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">        </span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">K</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">m approximatif</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">&#xa0;</span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">: </span><span style="font-family:'Times New Roman'; letter-spacing:-0.35pt"> </span>
            <input name="CL_km_approximatif" type="number" id="CL_km_approximatif" min="0" max="10000" <?php if (isset($detailom)) { if (isset($detailom['CL_km_approximatif'])) {echo "value='".$detailom['CL_km_approximatif']."'";}} ?> >
            <span style="font-family:'Times New Roman'; letter-spacing:-0.35pt">   </span><span style="font-family:'Times New Roman'; font-weight:bold">     </span><span style="font-family:'Times New Roman'; font-weight:bold">M</span><span style="font-family:'Times New Roman'; font-weight:bold">ontant Max</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
            <input name="CL_montant_max" type="number" id="CL_montant_max" <?php if (isset($detailom)) { if (isset($detailom['CL_montant_max'])) {echo "value='".$detailom['CL_montant_max']."'";}} ?> >
            <span style="font-family:'Times New Roman'; font-weight:bold">                                     </span></p>
        <?php  // Si client IMA
        if (isset($detailom))
        { if (isset($detailom['clientIMA']))
        { ?>
            <p style="margin-top:6.95pt; margin-bottom:0pt; widows:0; orphans:0; font-size:12pt"><span style="font-family:'Times New Roman'; font-weight:bold; color:#000"><?php print($detailom['clientIMA']); ?></span></p>
            <input name="clientIMA" id="clientIMA" type="hidden" value="<?php echo $detailom['clientIMA']; ?>"></input>
        <?php	}}
        else
        {
            if ($detailcl['groupe'] == 3) { ?>
                <p style="margin-top:6.95pt; margin-bottom:0pt; widows:0; orphans:0; font-size:12pt"><span style="font-family:'Times New Roman'; font-weight:bold; color:#000"><?php print($detailcl['name']); ?></span></p>
                <input name="clientIMA" id="clientIMA" type="hidden" value="<?php echo $detailcl['name']; ?>"></input>
            <?php }}  ?>
        <p style="margin-top:6.95pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Pour</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.7pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">le:</span>
            <input type="datetime-local" name="CL_heuredateRDV" id="CL_heuredateRDV" <?php if (isset($detailom)) { if (isset($detailom['CL_heuredateRDV'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['CL_heuredateRDV']))."'";}} ?> >
            <span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0">  </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.85pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Dimanche</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.9pt">   </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:  </span>
            <?php  if (isset($detailom))
            { if (isset($detailom['CL_Dimanche']))
            { if (($detailom['CL_Dimanche'] === "oui")||($detailom['CL_Dimanche'] === "on")) { ?>
                <input type="checkbox" name="CL_Dimanche" id="CL_Dimanche" checked value="oui">
            <?php } else { ?>
                <input type="checkbox" name="CL_Dimanche" id="CL_Dimanche" value="">
            <?php }} else { ?>
                <input type="checkbox" name="CL_Dimanche" id="CL_Dimanche" value="oui">
            <?php }
            } else { ?>
                <input type="checkbox" name="CL_Dimanche" id="CL_Dimanche" value="oui">
            <?php } ?>
            <span style="width:18.05pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Férié</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.8pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">:</span>
            <?php  if (isset($detailom))
            { if (isset($detailom['CL_Ferie']))
            { if (($detailom['CL_Ferie'] === "oui")||($detailom['CL_Ferie'] === "on")) { ?>
                <input type="checkbox" name="CL_Ferie" id="CL_Ferie" checked value="oui">
            <?php } else { ?>
                <input type="checkbox" name="CL_Ferie" id="CL_Ferie" value="">
            <?php }} else { ?>
                <input type="checkbox" name="CL_Ferie" id="CL_Ferie" value="oui">
            <?php }
            } else { ?>
                <input type="checkbox" name="CL_Ferie" id="CL_Ferie" value="oui">
            <?php } ?>
            <span style="width:13.57pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">Nuit</span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold; letter-spacing:-0.25pt"> </span><span style="font-family:'Times New Roman'; font-size:8pt; font-weight:bold">: </span>
            <?php  if (isset($detailom))
            { if (isset($detailom['CL_Nuit']))
            { if (($detailom['CL_Nuit'] === "oui")||($detailom['CL_Nuit'] === "on")) { ?>
                <input type="checkbox" name="CL_Nuit" id="CL_Nuit" checked value="oui">
            <?php } else { ?>
                <input type="checkbox" name="CL_Nuit" id="CL_Nuit" value="">
            <?php }} else { ?>
                <input type="checkbox" name="CL_Nuit" id="CL_Nuit" value="oui">
            <?php }
            } else { ?>
                <input type="checkbox" name="CL_Nuit" id="CL_Nuit" value="oui">
            <?php } ?>


    </div>
    </div></br>
<p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:12pt"><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic; color:#000000">Le présent ordre de mission fait office de prise en charge et copie doit être adressée avec votre facture</span></p>
    <div class="row" style=" margin-left: 0px;margin-top: 20px ">
        <span style="font-family:'Times New Roman'; font-weight:bold">Identité personne à transporter:</span><span style="font-family:'Times New Roman'; color:#ff0000">&#xa0;
<?php  if (isset($detailom))
{  ?>
    <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(! empty($detailom['subscriber_name'])) echo $detailom['subscriber_name']; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(! empty($detailom['subscriber_lastname'])) echo $detailom['subscriber_lastname']; ?>"></input>
<?php } else { ?>
    <input name="subscriber_name" id="subscriber_name" placeholder="prénom du l'abonnée" value="<?php if(isset ($detaildoss)) echo $detaildoss['subscriber_name']; ?>" /> <input name="subscriber_lastname" placeholder="nom du l'abonnée"  value="<?php if(isset ($detaildoss)) echo $detaildoss['subscriber_lastname']; ?>"></input>
<?php } ?>
			</span>
        <span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="width:2.79pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">N/Réf</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:  </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
<?php  if (isset($detailom))
{  ?>
    <input name="reference_medic" placeholder="Notre Reference" value="<?php if(! empty($detailom['reference_medic'])) echo $detailom['reference_medic']; ?>"></input>
<?php } else { ?>
    <input name="reference_medic" placeholder="Notre Reference" value="<?php if(isset ($detaildoss)) echo $detaildoss['reference_medic']; ?>"></input>
<?php } ?>
</span></p>
        <span style="font-family:'Times New Roman'; font-weight:bold">Vehicule (marqrue et type):</span><span style="font-family:'Times New Roman'; color:#ff0000">&#xa0;
<?php  if (isset($detailom))
{  ?>
    <input name="vehicule_type" id="vehicule_type" placeholder="type et marque" value="<?php if(! empty($detailom['vehicule_type'])) echo $detailom['vehicule_type']; ?>" />
<?php } else { ?>
    <input name="vehicule_type" id="vehicule_type" placeholder="type et marque" value="<?php if(isset ($detaildoss)) echo $detaildoss['vehicule_marque']." ".$detaildoss['vehicule_type']; ?>" />
<?php } ?>
			</span>
        <span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="width:2.79pt; display:inline-block">&#xa0;</span><span style="width:36pt; display:inline-block">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">Immatriculation</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:  </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
<?php  if (isset($detailom))
{ ?>
    <input name="vehicule_immatriculation" placeholder="Immatriculation" value="<?php if(! empty($detailom['vehicule_immatriculation'])) echo $detailom['vehicule_immatriculation']; ?>"></input>
<?php } else { ?>
    <input name="vehicule_immatriculation" placeholder="Imm" value="<?php if(isset ($detaildoss)) echo $detaildoss['vehicule_immatriculation']; ?>"></input>
<?php } ?>
</span></p>
    </div><p style="margin-top:0.6pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
<span style="font-family:'Times New Roman'; font-weight:bold">Boite de vitesse : </span>
        <input name="CL_automatique_normal" id="CL_automatique_normal" placeholder="Automatique ou Normal" <?php if (isset($detailom)) { if (isset($detailom['CL_automatique_normal'])) {echo "value='".$detailom['CL_automatique_normal']."'";}} ?> >
        <span style="font-family:'Times New Roman'">Etat du véhicule:&#xa0;</span>
        <?php if (isset($detailom)) { ?>
            <select id="CL_etat_vehicule" name="CL_etat_vehicule">
                <option value="Panne" <?php if (isset($detailom['CL_etat_vehicule'])) { if ($detailom['CL_etat_vehicule'] === "Panne") {echo "selected";}} ?> >Panne</option>
                <option value="accidenté" <?php if (isset($detailom['CL_etat_vehicule'])) { if ($detailom['CL_etat_vehicule'] === "accidenté") {echo "selected";}} ?> >accidenté</option>
                <option value="incendié" <?php if (isset($detailom['CL_etat_vehicule'])) { if ($detailom['CL_etat_vehicule'] === "incendié") {echo "selected";}} ?> >incendié</option>
                <option value="Intact " <?php if (isset($detailom['CL_etat_vehicule'])) { if ($detailom['CL_etat_vehicule'] === "Intact") {echo "selected";}} ?> >Intact</option>
            </select>
        <?php } else { ?>
            <select id="CL_etat_vehicule" name="CL_etat_vehicule">
                <option value="Panne" selected>Panne</option>
                <option value="accidenté">accidenté</option>
                <option value="incendié">incendié</option>
                <option value="Intact">Intact</option>
            </select>
        <?php } ?>
        </p>
        <div class="row" style=" margin-left: 0px; ">
    <p style="margin-top:4.65pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">RDV pour </span><span style="font-family:'Times New Roman'; font-weight:bold"> le remorquage </span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
        <input type="time" id="CL_heure_RDV" name="CL_heure_RDV" min="00:00" max="23:59"  <?php if (isset($detailom)) { if (isset($detailom['CL_heure_RDV'])) {echo "value='".date('H:i',strtotime($detailom['CL_heure_RDV']))."'";}} ?> >
        <span style="font-family:'Times New Roman'; color:#0070c0">            </span><span style="font-family:'Times New Roman'; font-weight:bold">Contact téléphonique</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">:   </span><span style="font-family:'Times New Roman'; color:#31849b">
<input name="CL_contacttel" placeholder="Contact téléphonique" pattern= "^[0–9]$" <?php if (isset($detailom)) { if (isset($detailom['CL_contacttel'])) {echo "value='".$detailom['CL_contacttel']."'";}} ?> ></input>
				 </span><span style="font-family:'Times New Roman'">Qualité</span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'">: </span>
        <input name="CL_qualite" placeholder="Qualité" <?php if (isset($detailom)) { if (isset($detailom['CL_qualite'])) {echo "value='".$detailom['CL_qualite']."'";}} ?> ></input>
    </p>

    </div>

    <p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">Trajet</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">:</span><span style="font-family:'Times New Roman'; font-weight:bold">  </span><span style="font-family:'Times New Roman'; font-weight:bold">Lieu prise en charge</span><span style="font-family:'Times New Roman'">: </span>
        <input type="text" list="CL_lieuprest_pc" name="CL_lieuprest_pc" <?php if (isset($detailom)) { if (isset($detailom['CL_lieuprest_pc'])) {echo "value='".$detailom['CL_lieuprest_pc']."'";}} ?> />
        <!--<datalist id="CL_lieuprest_pc">
            <?php
            //foreach ($array_prest as $prest) {
                //echo "<option value='".$detaildoss['lieu_immobilisation']."' telprest='".$detaildoss['subscriber_phone_cell']."'>".$detaildoss['lieu_immobilisation']."</option>";
            //}
            ?>
        </datalist>-->
        <datalist id="CL_lieuprest_pc">
           <?php
            foreach ($array_prest as $prest) {
                echo "<option value='".$prest['name']."' telprest='".$prest['phone_home']."'>".$prest['name']."</option>";
            }
           foreach ($array_prestgg as $prestg) {
           echo "<option value='".$prestg['name']."' telprest='".$prestg['phone_home']."'>".$prestg['name']."</option>";
           }
            ?>
        </datalist>
        <span style="font-family:'Times New Roman'; font-weight:bold">Tel: </span>
        <input name="CL_prestatairetel_pc" id="CL_prestatairetel_pc" placeholder="Téléphone du prestataire" pattern= "^[0–9]$" <?php if (isset($detailom)) { if (isset($detailom['CL_prestatairetel_pc'])) {echo "value='".$detailom['CL_prestatairetel_pc']."'";}} ?> ></input>
        <span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">    </span></p><p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:Wingdings; font-weight:bold"></span><span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0"> </span><span style="font-family:'Times New Roman'; font-weight:bold">Lieu décharge: </span>
        <input type="text" list="CL_lieudecharge_dec" name="CL_lieudecharge_dec" <?php if (isset($detailom)) { if (isset($detailom['CL_lieudecharge_dec'])) {echo "value='".$detailom['CL_lieudecharge_dec']."'";}} ?> />
        <!--<datalist id="CL_lieudecharge_dec">
            <?php
            /*foreach ($array_prest as $prest) {
                echo "<option value='".$prest['name']."' telprest='".$prest['phone_home']."'>".$prest['name']."</option>";
            }*/
            ?>
        </datalist>-->
        <datalist id="CL_lieudecharge_dec">
            <?php
            foreach ($array_prest as $prest) {
                echo "<option value='".$prest['name']."' telprest='".$prest['phone_home']."'>".$prest['name']."</option>";
            }
            foreach ($array_prestgg as $prestg) {
             echo "<option value='".$prestg['name']."' telprest='".$prestg['phone_home']."'>".$prestg['name']."</option>";
            }
            ?>
        </datalist>
        <span style="font-family:'Times New Roman'; font-weight:bold">Tel: </span>
        <input name="CL_prestatairetel_dec" id="CL_prestatairetel_dec" placeholder="Téléphone du prestataire" pattern= "^[0–9]$" <?php if (isset($detailom)) { if (isset($detailom['CL_prestatairetel_dec'])) {echo "value='".$detailom['CL_prestatairetel_dec']."'";}} ?> ></input>
        <span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">   </span></p><p style="margin-top:4.65pt; margin-left:5.85pt;  margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000"> </span>
        <?php if (isset($detailom['CB_preetape'])) { if (($detailom['CB_preetape'] === "oui")||($detailom['CB_preetape'] === "on")) { $preetape = true; ?>
            <input type="checkbox" name="CB_preetape" id="CB_preetape" value="oui" checked>
        <?php } else {  ?>
            <input type="checkbox" name="CB_preetape" id="CB_preetape" >
        <?php }} else { if (empty($detailom['CB_preetape'])) { ?>
            <input type="checkbox" name="CB_preetape" id="CB_preetape" >
        <?php } else { $preetape = true; ?>
            <input type="checkbox" name="CB_preetape" id="CB_preetape" value="oui" checked>
        <?php }} ?>
        <span style="font-family:'Times New Roman'; font-weight:bold"> Première étape</span>
        <?php if (isset($preetape)) { ?>
        <span id="preetape" style="display: inline-block;">
<?php } else { ?>
<span id="preetape" style="display: none;">
<?php } ?>

				<span style="font-family:'Times New Roman'; font-weight:bold"> (passage </span><span style="font-family:'Times New Roman'; font-weight:bold">par</span><span style="font-family:'Times New Roman'; font-weight:bold"> </span><span style="font-family:'Times New Roman'; font-weight:bold">lieu de séjour</span><span style="font-family:'Times New Roman'; font-weight:bold">.</span><span style="font-family:'Times New Roman'; font-weight:bold">):  </span>

<input type="text" list="CL_lieupre" name="CL_lieupre" <?php if (isset($detailom)) { if (isset($detailom['CL_lieupre'])) {echo "value='".$detailom['CL_lieupre']."'";}} ?> />
<datalist id="CL_lieupre">
<?php
if (!empty($detaildoss['subscriber_local_address']))
{
    echo "<option value='".$detaildoss['subscriber_local_address']."' telprest='".$detaildoss['subscriber_phone_cell']."'>".$detaildoss['subscriber_local_address']."</option>";
}
foreach ($array_presthch as $presthch) {
    echo "<option value='".$presthch['name']."' telprest='".$presthch['phone_home']."'>".$presthch['name']."</option>";
}
?>
</datalist>
    </p>
    </span>
    <p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
        <?php if (isset($detailom['CB_trpersonne'])) { if (($detailom['CB_trpersonne'] === "oui")||($detailom['CB_trpersonne'] === "on")) { $trpersonne = true; ?>
            <input type="checkbox" name="CB_trpersonne" id="CB_trpersonne" value="oui" checked>
        <?php } else {  ?>
            <input type="checkbox" name="CB_trpersonne" id="CB_trpersonne" >
        <?php }} else { if (empty($detailom['CB_trpersonne'])) { ?>
            <input type="checkbox" name="CB_trpersonne" id="CB_trpersonne" >
        <?php } else { $trpersonne = true; ?>
            <input type="checkbox" name="CB_trpersonne" id="CB_trpersonne" value="oui" checked>
        <?php }} ?>
            <span style="font-family:'Times New Roman'; font-weight:bold">T</span><span style="font-family:'Times New Roman'; font-weight:bold">ransfert vers Dépot</span><span style="font-family:'Times New Roman'; font-weight:bold">/garage</span>
        <?php if (isset($trpersonne)) { ?>
        <span id="trpersonne" style="display: inline-block;">
<?php } else { ?>
<span id="trpersonne" style="display: none;">
<?php } ?>
					<span style="font-family:'Times New Roman'; font-weight:bold">,&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">nom et </span><span style="font-family:'Times New Roman'; font-weight:bold">coordonnés personne ayant donné l’accord d’acceptation </span>
<input name="CL_infospersonne" id="CL_infospersonne" placeholder="nom et coordonnés personne" <?php if (isset($detailom)) { if (isset($detailom['CL_infospersonne'])) {echo "value='".$detailom['CL_infospersonne']."'";}} ?> />
</span>
    </p><p style="margin-top:4.65pt; margin-left:5.85pt;margin-bottom:0pt; widows:0; orphans:0; font-size:10pt">
        <?php if (isset($detailom['CB_pregoulette'])) { if (($detailom['CB_pregoulette'] === "oui")||($detailom['CB_pregoulette'] === "on")) { $pregoulette = true; ?>
            <input type="checkbox" name="CB_pregoulette" id="CB_pregoulette" value="oui" checked>
        <?php } else {  ?>
            <input type="checkbox" name="CB_pregoulette" id="CB_pregoulette" >
        <?php }} else { if (empty($detailom['CB_pregoulette'])) { ?>
            <input type="checkbox" name="CB_pregoulette" id="CB_pregoulette" >
        <?php } else { $pregoulette = true; ?>
            <input type="checkbox" name="CB_pregoulette" id="CB_pregoulette" value="oui" checked>
        <?php }} ?>
        <span style="font-family:'Times New Roman'; font-weight:bold">Si de/vers port La Goulette </span>
        <?php if (isset($pregoulette)) { ?>
        <span id="pregoulette" style="display: inline-block;">
<?php } else { ?>
<span id="pregoulette" style="display: none;">
<?php } ?>
			<span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000"> </span><span style="font-family:'Times New Roman'; font-weight:bold"> : </span>

			<span style="font-family:'Times New Roman'; font-weight:bold">Destination: </span>
<input type="text" list="CL_apdestor" name="CL_apdestor" <?php if (isset($detailom)) { if (isset($detailom['CL_apdestor'])) {echo "value='".$detailom['CL_apdestor']."'";}} ?> />
<datalist id="CL_apdestor">
<?php
foreach ($array_prestap as $prestap) {
    echo "<option value='".$prestap['name']."' telprest='".$prestap['phone_home']."'>".$prestap['name']."</option>";
}
?>
</datalist>
			<span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000"> </span><span style="font-family:'Times New Roman'; font-weight:bold">bateau : </span>
<input name="CL_nombateau" id="CL_nombateau" placeholder="nom du bateau" <?php if (isset($detailom)) { if (isset($detailom['CL_nombateau'])) {echo "value='".$detailom['CL_nombateau']."'";}} ?> />
				<span style="font-family:'Times New Roman'; font-weight:bold">Départ bateau </span>

				<span style="font-family:'Times New Roman'; font-weight:bold">: </span>
<input type="time" id="CL_heure_D" name="CL_heure_D" min="00:00" max="23:59" <?php if (isset($detailom)) { if (isset($detailom['CL_heure_D'])) {echo "value='".date('H:i',strtotime($detailom['CL_heure_D']))."'";}} ?> >

    </p>
    <?php if (isset($detailom['CB_prerades'])) { if (($detailom['CB_prerades'] === "oui")||($detailom['CB_prerades'] === "on")) { $prerades = true; ?>
        <input type="checkbox" name="CB_prerades" id="CB_prerades" value="oui" checked>
    <?php } else {  ?>
        <input type="checkbox" name="CB_prerades" id="CB_prerades" >
    <?php }} else { if (empty($detailom['CB_prerades'])) { ?>
        <input type="checkbox" name="CB_prerades" id="CB_prerades" >
    <?php } else { $prerades = true; ?>
        <input type="checkbox" name="CB_prerades" id="CB_prerades" value="oui" checked>
    <?php }} ?>
    <span style="font-family:'Times New Roman'; font-weight:bold">Si de/vers port de Rades :</span>
    <?php if (isset($prerades)) { ?>
    <span id="prerades" style="display: inline-block;">
<?php } else { ?>
<span id="prerades" style="display: none;">
<?php } ?>


    <span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000"> </span><span style="font-family:'Times New Roman'; font-weight:bold">Type de rapatriement : </span>


<select id="CL_type_rapatriement" name="CL_type_rapatriement">
<?php if (isset($detailom['CL_type_rapatriement'])) { ?>
    <option value="Conteneur" <?php if (isset($detailom['CL_type_rapatriement'])) { if ($detailom['CL_type_rapatriement'] === "Conteneur") {echo "selected";}} ?> >Conteneur</option>
    <option value="conventionnel" <?php if (isset($detailom['CL_type_rapatriement'])) { if ($detailom['CL_type_rapatriement'] === "conventionnel") {echo "selected";}} ?> >conventionnel</option>
<?php } else { ?>
    <option value="Conteneur" selected>Conteneur</option>
    <option value="conventionnel">conventionnel</option>
<?php } ?>
</select>
    </span>
    <?php if (isset($detailom['CB_pregoulette']) || isset($detailom['CB_prerades'])) { if ((($detailom['CB_prerades'] === "oui"||($detailom['CB_prerades'] === "on"))) ||(($detailom['CB_pregoulette'] === "oui"||($detailom['CB_pregoulette'] === "on")) )){ ?>
<p id="preradeszone" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;">
<?php } else { ?>
<p id="preradeszone" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;display:none;">
<?php } } else { ?>
<p id="preradeszone" style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;display:none;">
        <?php } ?>

        <span style="font-family:'Times New Roman'; font-weight:bold">Heure </span><span style="font-family:'Times New Roman'; font-weight:bold">souhaitée </span><span style="font-family:'Times New Roman'; font-weight:bold">arrivée port: </span>
        <input type="time" id="CL_heurearr" name="CL_heurearr" min="00:00" max="23:59" <?php if (isset($detailom)) { if (isset($detailom['CL_heurearr'])) {echo "value='".date('H:i',strtotime($detailom['CL_heurearr']))."'";}} ?> >
        <span style="font-family:'Times New Roman'"> Si vers Port de la Goulette par défaut heure départ -3 heures, si vers </span><span style="font-family:'Times New Roman'"></span><span style="font-family:'Times New Roman'">Port de Rades par défaut RDV au port à 8h. </span><span style="font-family:'Times New Roman'"></span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'"></span><span style="font-family:'Times New Roman'">&#xa0;</span><span style="font-family:'Times New Roman'"></span><span style="font-family:'Times New Roman'"> </span></p>

    </span>

    <p style="margin-top:3.75pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><div style="margin-top:0pt;clear:both"><p style="margin-left:5.85pt;margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Remarque</span><span style="font-family:'Times New Roman'; font-weight:bold">s</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
            <textarea id="CL_remarque" name="CL_remarque" rows="2" cols="50" placeholder="Remarques">
 <?php if (isset($detailom)) { if (isset($detailom['CL_remarque'])) {echo $detailom['CL_remarque'];}} ?>
</textarea>
        </p>

        <div id="prestinterne" <?php if (isset($affectea)){ if ($affectea === "externe") { echo 'style="display:none"'; }} ?>	>
            <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; padding-bottom:1pt; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; color:#0070c0">&#xa0;</span></p><p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt;border-top: 1.5pt solid #000000;padding-top:10px"><span style="font-family:'Times New Roman'; font-weight:bold">Origine de la demande: </span><span style="font-family:'Times New Roman'; font-weight:bold; color:#ff0000">
<input name="client_dossier" placeholder="Client du dossier" value="<?php if(isset ($detailcl)) {echo $detailcl['name'];} if (isset($detailom)) { if (isset($detailom['client_dossier'])) {echo $detailom['client_dossier'];}} ?>"></input></span><span style="font-family:'Times New Roman'; font-weight:bold">   Date demande: </span>
                <input type="date" id="CL_datedemande" name="CL_datedemande" <?php if (isset($detailom)) { if (isset($detailom['CL_datedemande'])) {echo "value='".date('Y-m-d',strtotime($detailom['CL_datedemande']))."'";}} ?> >
                <span style="font-family:'Times New Roman'; font-weight:bold"> Heure: </span>
                <input type="time" id="CL_heuredemande" name="CL_heuredemande" min="00:00" max="23:59" <?php if (isset($detailom)) { if (isset($detailom['CL_heuredemande'])) {echo "value='".date('H:i',strtotime($detailom['CL_heuredemande']))."'";}} ?> >
                <span style="font-family:'Times New Roman'; font-weight:bold">  </span></p><p style="margin-top:0pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; border-bottom:1.5pt solid #000000; padding-bottom:10px; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Notre réf.</span><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold">: </span>
                <input name="reference_medic2" placeholder="Notre Reference" value="<?php if(isset ($detaildoss)) {echo $detaildoss['reference_medic'];} if (isset($detailom)) { if (isset($detailom['reference_medic2'])) {echo $detailom['reference_medic2'];}} ?>"></input>
                <span style="font-family:'Times New Roman'; font-weight:bold">   Réf. client: </span>
                <input name="reference_customer" placeholder="Reference client" value="<?php if(isset ($detaildoss)) {echo $detaildoss['reference_customer'];} if (isset($detailom)) { if (isset($detailom['reference_customer'])) {echo $detailom['reference_customer'];}} ?>"></input>
            </p>
        </div>


 <div id="prestexterne" <?php if (isset($affectea)){ if ($affectea === "interne") { echo 'style="display:none"'; }} ?>>
            <?php if (isset($affectea)){ if ($affectea === "externe") { echo '<input name="affectea" id="affectea" type="hidden" value="externe"></input>'; }} ?>
        <?php if (isset($affectea)){ if ($affectea === "interne") { echo '<input name="affectea" id="affectea" type="hidden" value="interne"></input>'; }} ?>
        <p style="margin-top:4.65pt; margin-left:5.85pt; margin-bottom:0pt; widows:0; orphans:0; font-size:12pt"><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic; color:#ff0000">Veuillez SVP nous rappeler après réception de cet ordre de mission pour nous donner le nom et numéro de téléphone du chauffeur chargé de cette mission.</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic; color:#ff0000"> Et appeler en cours de mission pour indiquer d’éventuelles attentes ou changements ou évènements imprévus pendant la mission. </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">              </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic"> </span></p><p style="margin-top:0pt; margin-bottom:0pt; line-height:125%; widows:0; orphans:0; font-size:8pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p>
</div>

        <div id="prestexterne" <?php if (isset($affectea)){ if ($affectea === "interne") { echo 'style="display:none"'; }} ?>>
            <?php if (isset($affectea)){ if ($affectea === "externe") { echo '<input name="affectea" id="affectea" type="hidden" value="externe"></input>'; }} ?>
            <p style="margin-top:4.65pt; margin-bottom:0pt; line-height:125%; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Merci</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">de</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">nous</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">adresser</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">votre</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">facture</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">originale</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">dès</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">que</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">possible</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">(et</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">au</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">plus</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">tard</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.45pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">dans</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">un</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.45pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">délai</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">de</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">30</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">jours),</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.7pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">à</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.35pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">notre </span><span style="font-family:'Times New Roman'; font-weight:bold">adresse</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.4pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">ci-dessus,</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.65pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">en mentionnant notre référence</span><span style="font-family:'Times New Roman'; font-weight:bold; letter-spacing:-0.6pt"> </span><span style="font-family:'Times New Roman'; font-weight:bold">ci-</span><span style="font-family:'Times New Roman'; font-weight:bold">haut</span><span style="font-family:'Times New Roman'; font-weight:bold">.</span></p><p style="margin-top:0.55pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">RAPPEL</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline"> IMPORTANT+++</span><span style="font-family:'Times New Roman'; font-weight:bold; text-decoration:underline">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">&#xa0;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">Toute facture reçue dans nos locaux</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">plus de 60 jours</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">après le service rendu</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">ne pourra plus être garantie</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">&#xa0;</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">pour règlement. La </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">garantie de </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">prise en charge </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">de ce service</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic"> a donc une validité maximale de 60 jours après la date de la prestation de service.</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">La</span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic"> facture devra être accompagnée </span><span style="font-family:'Times New Roman'; font-weight:bold; font-style:italic">impérativement d’une copie de cet ordre de mission</span></p><p style="margin-top:0.35pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><img src="bz7qd-pjv8o.003.png" width="752" height="2" alt="" style="-aw-left-pos:21.9pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:248.4pt; -aw-wrap-type:topbottom" /><br /></p>

        </div>

        <?php if (isset($_GET['complete']) ){ ?>
            <!-- DIV for complete action -->
            <div class="row">
                <div id="compsup" class="col-md-3">
                    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="height:0pt; display:block; position:absolute; z-index:-1"><img  width="320" height="195" alt="" style="margin-top:4.21pt; margin-left:7.72pt; -aw-left-pos:24pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:4.5pt; -aw-wrap-type:none; position:absolute"></span><span style="font-family:&#39;Times New Roman&#39;">&nbsp;</span></p><p style="margin-top:5.4pt; margin-left:5.75pt; margin-bottom:0pt; text-indent:15.55pt; line-height:10.05pt; widows:0; orphans:0"><span style="font-family:&#39;Times New Roman&#39;; font-size:9pt; font-weight:bold">Valid.</span><span style="font-family:&#39;Times New Roman&#39;; font-size:9pt; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-size:9pt; font-weight:bold">superviseur</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:21.3pt; line-height:9.8pt; widows:0; orphans:0">
                        <input type="hidden" name="complete" value="1">
                        <input name="supervisordate" id="supervisordate" placeholder=" " value="<?php if (isset($detailagtcomp)) {echo $detailagtcomp['name'].' '.$detailagtcomp['lastname'].date('Y-m-d').' '.date('H:i');  }  ?>" />
                    </p><p style="margin-top:0.05pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin:0pt 0pt 0pt 5.75pt; text-indent:15.55pt; line-height:150%; widows:0; orphans:0; font-size:9pt;font-family:&#39;Times New Roman&#39;; font-weight:bold">OM remis par (date/sign)</p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:21.3pt; line-height:9.8pt; widows:0; orphans:0">
                        <input name="remispardate" id="remispardate" readonly placeholder="date/sign" <?php if (isset($detailom['remispardate'])) { if (!empty($detailom['remispardate'])) {echo "value='".$detailom['remispardate']."'";}} ?> ></input>
                    </p><p style="margin:0pt 0pt 0pt 5.75pt; text-indent:15.55pt; line-height:150%; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">OM récupéré par (date/sign)</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:21.3pt; line-height:9.8pt; widows:0; orphans:0">
                        <input name="recuperepardate" id="recuperepardate" readonly placeholder="date/sign" <?php if (isset($detailom['recuperepardate'])) { if (!empty($detailom['recuperepardate'])) {echo "value='".$detailom['recuperepardate']."'";}} ?> ></input>
                    </p></div>
                <div class="col-md-3">
                </div>
                <div id="modtransp" class="col-md-3" style="padding-right: 0px!important">
                    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="height:0pt; display:block; position:absolute; z-index:-1"><img  width="320" height="600" alt="" style="margin-top:3.91pt; margin-left:7.72pt; -aw-left-pos:24pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:4.2pt; -aw-wrap-type:none; position:absolute"></span><span style="font-family:&#39;Times New Roman&#39;">&nbsp;</span></p><p style="margin-top:0pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; text-decoration:underline">Modalités du transport</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:7.15pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Date</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">/heure</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> départ base:</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; letter-spacing:0.65pt;"> </span>
                        <input type="datetime-local" name="dateheuredep" id="dateheuredep" <?php if (isset($detailom['dateheuredep'])) { if (!empty($detailom['dateheuredep'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dateheuredep']))."'";}} ?> style=" margin-left: 20px; "/>
                    </p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:8pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Première </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">étape (hôtel ?):</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span>
                        <input name="prehotel" id="prehotel" placeholder="" <?php if (isset($detailom['prehotel'])) { if (!empty($detailom['prehotel'])) {echo "value='".$detailom['prehotel']."'";}} ?> style=" margin-left: 20px; "></input>
                        <span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Date</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">/heure</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">dispo</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">prévisible:</span></p>
                    <input type="datetime-local" name="dateheuredispprev" id="dateheuredispprev" <?php if (isset($detailom['dateheuredispprev'])) { if (!empty($detailom['dateheuredispprev'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dateheuredispprev']))."'";}} ?> style=" margin-left: 27px; "/>
                    <p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Date/heure retour base prévisible:</span></p>
                    <input type="datetime-local" name="dhretbaseprev" id="dhretbaseprev" <?php if (isset($detailom['dhretbaseprev'])) { if (!empty($detailom['dhretbaseprev'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhretbaseprev']))."'";}} ?> style=" margin-left: 27px; "/>
                    <p style="margin-top:0pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:8pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Véhicule: </span>
                        <input type="text" list="lvehicule" name="lvehicule" <?php if (isset($detailom['lvehicule'])) { if (!empty($detailom['lvehicule'])) {echo "value='".$detailom['lvehicule']."'";}} ?> />
                        <datalist id="lvehicule">
                        <?php
                        /*foreach ($array_vehic as $vehic) {
                            echo "<option value='".$vehic['name']."' idv='".$vehic['id']."' carburant='".$vehic['carburant']."' telepeage='".$vehic['telepeage']."' >".$vehic['name']."</option>";
                        }*/
                        ?>
                        </datalist>
                        <input type="hidden" name="idvehic" id="idvehic"   <?php if (isset($detailom['idvehic'])) { if (!empty($detailom['idvehic'])) {echo "value='".$detailom['idvehic']."'";}} ?> />
                    </p>
                    <p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Carte de Carburant: </span>
                    <input type="text" name="cartecarburant" id="cartecarburant" <?php if (isset($detailom['cartecarburant'])) { if (!empty($detailom['cartecarburant'])) {echo "value='".$detailom['cartecarburant']."'";}} ?> style="width:60%"/></p>
                    <p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Carte de telepeage: </span>
                    <input type="text" name="cartetelepeage" id="cartetelepeage" <?php if (isset($detailom['cartetelepeage'])) { if (!empty($detailom['cartetelepeage'])) {echo "value='".$detailom['cartetelepeage']."'";}} ?> style="width:60%"/></p>
                    <p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Chauffeur : </span>
                        <!-- affiche pour le moment toute la liste des personnels -->
                        <input type="text" list="lchauff" name="lchauff"  <?php if (isset($detailom['lchauff'])) { if (!empty($detailom['lchauff'])) {echo "value='".$detailom['lchauff']."'";}} ?> />
                        <datalist id="lchauff">
                            <?php
                            /*foreach ($array_chauff as $chauff) {
                                echo "<option value='".$chauff['name']."'  >".$chauff['name']."</option>";
                            }*/
                            ?>
                        </datalist>
                        <input type="hidden" name="idchauff" id="idchauff"   <?php if (isset($detailom['idchauff'])) { if (!empty($detailom['idchauff'])) {echo "value='".$detailom['idchauff']."'";}} ?> />
                    </p><p style="margin:0pt 0pt 0pt 20.9pt;  widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Heures sup ?: </span>
                        <input name="heuressup" id="heuressup" placeholder="" <?php if (isset($detailom['heuressup'])) { if (!empty($detailom['heuressup'])) {echo "value='".$detailom['heuressup']."'";}} ?> style=""></input>
                    </p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p>
                </div>
            </div>
            <div class="row">
                <div id="deroulmiss" class="col-md-3" style="margin-top: -380px;padding-bottom:50px">
                    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="height:0pt; display:block; position:absolute; z-index:-1"><img width="320" height="480" alt="" style="margin-top:4.11pt; margin-left:7.72pt; -aw-left-pos:24pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:4.4pt; -aw-wrap-type:none; position:absolute"></span><span style="font-family:&#39;Times New Roman&#39;">&nbsp;</span></p><p style="margin-top:0pt;  margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; text-decoration:underline">Déroulement mission</span></p><p style="margin:0pt 120.4pt 0pt 120.3pt; text-indent:-120.3pt; widows:0; orphans:0; font-size:14pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Départ </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">pour mission: </span>
                        <input type="datetime-local" name="dhdepartmiss" id="dhdepartmiss" <?php if (isset($detailom['dhdepartmiss'])) { if (!empty($detailom['dhdepartmiss'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhdepartmiss']))."'";}} ?> style="margin-left:15px;width: 85%  "/>
                    </p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Arrivée</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;lieux: </span>
                        <input type="datetime-local" name="dharrivelieu" id="dharrivelieu" <?php if (isset($detailom['dharrivelieu'])) { if (!empty($detailom['dharrivelieu'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dharrivelieu']))."'";}} ?> style="margin-left:49px;width:85%" />
                    </p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width: 100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Départ lieux: </span>
                        <input type="datetime-local" name="dhdepartlieu" id="dhdepartlieu" <?php if (isset($detailom['dhdepartlieu'])) { if (!empty($detailom['dhdepartlieu'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhdepartlieu']))."'";}} ?> style="margin-left:55px;width: 85% "/>
                    </p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width: 100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Arrivée</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> à</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> destination: </span>
                        <input type="datetime-local" name="dharrivedest" id="dharrivedest" <?php if (isset($detailom['dharrivedest'])) { if (!empty($detailom['dharrivedest'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dharrivedest']))."'";}} ?> style="margin-left:12px;width: 85% "/>
                    </p>
                    </p><p style="margin:0.55pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:95%; widows:0; orphans:0; font-size:9pt;width: 100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Départ</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; letter-spacing:-1pt"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">vers </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">base:</span>
                        <input type="datetime-local" name="dhdepbase" id="dhdepbase" <?php if (isset($detailom['dhdepbase'])) { if (!empty($detailom['dhdepbase'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhdepbase']))."'";}} ?> style="margin-left:34px;width: 85% "/>
                    </p><p style="margin:0.1pt 120.4pt 0pt 120.3pt; text-indent:-120.3pt; widows:0; orphans:0; font-size:8pt;width: 100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="   font-size:9pt;margin-left:45px;width:150%"><span style="font-family:'Times New Roman'; font-weight:bold">Arrivée base:</span>
                        <input type="datetime-local" name="dharrbase" id="dharrbase" <?php if (isset($detailom['dharrbase'])) { if (!empty($detailom['dharrbase'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dharrbase']))."'";}} ?> style="margin-left:28px;width: 57% "/>
                    </p><p style="margin-left:20px;font-size:9pt;width:150%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Durée totale mission: </span>
                        <input type="time" id="duremiss" name="duremiss" min="00:00" max="23:59" <?php if (isset($detailom['duremiss'])) { if (!empty($detailom['duremiss'])) {echo "value='".date('H:i',strtotime($detailom['duremiss']))."'";}} ?> >
                    </p><p style="margin:3.65pt 63.7pt 0pt 134.5pt; text-indent:-120.3pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; color:#0070c0">&nbsp;</span></p><p style="margin:0pt 120.4pt 0pt 242.5pt; text-indent:-207.05pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">K</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">m</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> départ</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">: </span>
                        <input name="km_depart" type="number" id="km_depart" min="0" max="100000"  <?php if (isset($detailom['km_depart'])) { if (!empty($detailom['km_depart'])) {echo "value='".$detailom['km_depart']."'";}} ?> style="margin-left:2px ">
                    </p><p style="margin:0pt 120.4pt 0pt 223.5pt; text-indent:-188.05pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">K</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">m</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> arrivée</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">: </span>
                        <input name="km_arrive" type="number" id="km_arrive" min="0" max="100000"  <?php if (isset($detailom['km_arrive'])) { if (!empty($detailom['km_arrive'])) {echo "value='".$detailom['km_arrive']."'";}} ?> >
                    </p><p style="margin:0pt 120.4pt 0pt 223.5pt; text-indent:-188.05pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin:0pt 120.4pt 0pt 170.5pt; text-indent:-156.3pt; widows:0; orphans:0; font-size:9pt;width:150%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Distance </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">réelle mission </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">(km): </span>
                        <input name="km_distance" type="number" id="km_distance" min="0" max="100000" <?php if (isset($detailom['km_distance'])) { if (!empty($detailom['km_distance'])) {echo "value='".$detailom['km_distance']."'";}} ?> >
                    </p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p>
                </div>
            </div>
            <?php
        } elseif (isset($detailom)){ if (isset($detailom['complete'])) { ?>
            <div class="row">
                <div id="compsup" class="col-md-3">
                    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="height:0pt; display:block; position:absolute; z-index:-1"><img  width="320" height="195" alt="" style="margin-top:4.21pt; margin-left:7.72pt; -aw-left-pos:24pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:4.5pt; -aw-wrap-type:none; position:absolute"></span><span style="font-family:&#39;Times New Roman&#39;">&nbsp;</span></p><p style="margin-top:5.4pt; margin-left:5.75pt; margin-bottom:0pt; text-indent:15.55pt; line-height:10.05pt; widows:0; orphans:0"><span style="font-family:&#39;Times New Roman&#39;; font-size:9pt; font-weight:bold">Valid.</span><span style="font-family:&#39;Times New Roman&#39;; font-size:9pt; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-size:9pt; font-weight:bold">superviseur</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:21.3pt; line-height:9.8pt; widows:0; orphans:0">
                        <input type="hidden" name="complete" value="1">
                        <input name="supervisordate" id="supervisordate" placeholder=" " value="<?php if (isset($detailagtcomp)) {echo $detailagtcomp['name'].' '.$detailagtcomp['lastname'].' / '.date('Y-m-d').' '.date('H:i');  }  ?>" />
                    </p><p style="margin-top:0.05pt; margin-bottom:0pt; widows:0; orphans:0; font-size:8pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin:0pt 0pt 0pt 5.75pt; text-indent:15.55pt; line-height:150%; widows:0; orphans:0; font-size:9pt;font-family:&#39;Times New Roman&#39;; font-weight:bold">OM remis par (date/sign)</p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:21.3pt; line-height:9.8pt; widows:0; orphans:0">
                        <input name="remispardate" id="remispardate" placeholder="date/sign" <?php if (isset($detailom['remispardate'])) { if (!empty($detailom['remispardate'])) {echo "value='".$detailom['remispardate']."'";}} ?> ></input>
                    </p><p style="margin:0pt 0pt 0pt 5.75pt; text-indent:15.55pt; line-height:150%; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">OM récupéré par (date/sign)</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:21.3pt; line-height:9.8pt; widows:0; orphans:0">
                        <input name="recuperepardate" id="recuperepardate" placeholder="date/sign" <?php if (isset($detailom['recuperepardate'])) { if (!empty($detailom['recuperepardate'])) {echo "value='".$detailom['recuperepardate']."'";}} ?> ></input>
                    </p></div>
                <div class="col-md-3">
                </div>
                <div id="modtransp" class="col-md-3" style="padding-right: 0px!important">
                    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="height:0pt; display:block; position:absolute; z-index:-1"><img  width="320" height="600" alt="" style="margin-top:3.91pt; margin-left:7.72pt; -aw-left-pos:24pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:4.2pt; -aw-wrap-type:none; position:absolute"></span><span style="font-family:&#39;Times New Roman&#39;">&nbsp;</span></p><p style="margin-top:0pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; text-decoration:underline">Modalités du transport</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:7.15pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Date</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">/heure</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> départ base:</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; letter-spacing:0.65pt;"> </span>
                        <input type="datetime-local" name="dateheuredep" id="dateheuredep" <?php if (isset($detailom['dateheuredep'])) { if (!empty($detailom['dateheuredep'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dateheuredep']))."'";}} ?> style=" margin-left: 20px; "/>
                    </p><p style="margin-top:0pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:8pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Première </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">étape (hôtel ?):</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span>
                        <input name="prehotel" id="prehotel" placeholder="" <?php if (isset($detailom['prehotel'])) { if (!empty($detailom['prehotel'])) {echo "value='".$detailom['prehotel']."'";}} ?> style=" margin-left: 20px; "></input>
                        <span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Date</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">/heure</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">dispo</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">prévisible:</span></p>
                    <input type="datetime-local" name="dateheuredispprev" id="dateheuredispprev" <?php if (isset($detailom['dateheuredispprev'])) { if (!empty($detailom['dateheuredispprev'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dateheuredispprev']))."'";}} ?> style=" margin-left: 27px; "/>
                    <p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0.05pt; margin-left:6.9pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Date/heure retour base prévisible:</span></p>
                    <input type="datetime-local" name="dhretbaseprev" id="dhretbaseprev" <?php if (isset($detailom['dhretbaseprev'])) { if (!empty($detailom['dhretbaseprev'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhretbaseprev']))."'";}} ?> style=" margin-left: 27px; "/>
                    <p style="margin-top:0pt; margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:8pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Véhicule: </span>
                        <input type="text" list="lvehicule" name="lvehicule" <?php if (isset($detailom['lvehicule'])) { if (!empty($detailom['lvehicule'])) {echo "value='".$detailom['lvehicule']."'";}} ?> />
                        <datalist id="lvehicule">
                        <?php
                        /*foreach ($array_vehic as $vehic) {
                            echo "<option value='".$vehic['name']."' idv='".$vehic['id']."' carburant='".$vehic['carburant']."' telepeage='".$vehic['telepeage']."' >".$vehic['name']."</option>";
                        }*/
                        ?>
                        </datalist>
                        <input type="hidden" name="idvehic" id="idvehic"   <?php if (isset($detailom['idvehic'])) { if (!empty($detailom['idvehic'])) {echo "value='".$detailom['idvehic']."'";}} ?> />
                    </p>
                    <p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Carte de Carburant: </span>
                    <input type="text" name="cartecarburant" id="cartecarburant" <?php if (isset($detailom['cartecarburant'])) { if (!empty($detailom['cartecarburant'])) {echo "value='".$detailom['cartecarburant']."'";}} ?> style="width:60%"/></p>
                    <p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Carte de telepeage: </span>
                    <input type="text" name="cartetelepeage" id="cartetelepeage" <?php if (isset($detailom['cartetelepeage'])) { if (!empty($detailom['cartetelepeage'])) {echo "value='".$detailom['cartetelepeage']."'";}} ?> style="width:60%"/></p>
                    <p style="margin-top:0pt; margin-left:20.9pt; margin-bottom:0pt; line-height:195%; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Chauffeur : </span>
                        <!-- affiche pour le moment toute la liste des personnels -->
                        <input type="text" list="lchauff" name="lchauff"  <?php if (isset($detailom['lchauff'])) { if (!empty($detailom['lchauff'])) {echo "value='".$detailom['lchauff']."'";}} ?> />
                        <datalist id="lchauff">
                            <?php
                            /*foreach ($array_chauff as $chauff) {
                                echo "<option value='".$chauff['name']."'  >".$chauff['name']."</option>";
                            }*/
                            ?>
                        </datalist>
                        <input type="hidden" name="idchauff" id="idchauff"   <?php if (isset($detailom['idchauff'])) { if (!empty($detailom['idchauff'])) {echo "value='".$detailom['idchauff']."'";}} ?> />
                    </p><p style="margin:0pt 0pt 0pt 20.9pt;  widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Heures sup ?: </span>
                        <input name="heuressup" id="heuressup" placeholder="" <?php if (isset($detailom['heuressup'])) { if (!empty($detailom['heuressup'])) {echo "value='".$detailom['heuressup']."'";}} ?> style=""></input>
                    </p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p>
                </div>
            </div>
            <div class="row">
                <div id="deroulmiss" class="col-md-3" style="margin-top: -380px;padding-bottom:50px">
                    <p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="height:0pt; display:block; position:absolute; z-index:-1"><img width="320" height="480" alt="" style="margin-top:4.11pt; margin-left:7.72pt; -aw-left-pos:24pt; -aw-rel-hpos:page; -aw-rel-vpos:paragraph; -aw-top-pos:4.4pt; -aw-wrap-type:none; position:absolute"></span><span style="font-family:&#39;Times New Roman&#39;">&nbsp;</span></p><p style="margin-top:0pt;  margin-bottom:0pt; text-indent:14.4pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; text-decoration:underline">Déroulement mission</span></p><p style="margin:0pt 120.4pt 0pt 120.3pt; text-indent:-120.3pt; widows:0; orphans:0; font-size:14pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Départ </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">pour mission: </span>
                        <input type="datetime-local" name="dhdepartmiss" id="dhdepartmiss" <?php if (isset($detailom['dhdepartmiss'])) { if (!empty($detailom['dhdepartmiss'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhdepartmiss']))."'";}} ?> style="margin-left:15px;width:85%"/>
                    </p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Arrivée</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;lieux: </span>
                        <input type="datetime-local" name="dharrivelieu" id="dharrivelieu" <?php if (isset($detailom['dharrivelieu'])) { if (!empty($detailom['dharrivelieu'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dharrivelieu']))."'";}} ?> style="margin-left:49px;width:85% "/>
                    </p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Départ lieux: </span>
                        <input type="datetime-local" name="dhdepartlieu" id="dhdepartlieu" <?php if (isset($detailom['dhdepartlieu'])) { if (!empty($detailom['dhdepartlieu'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhdepartlieu']))."'";}} ?> style="margin-left:55px;width:85%"/>
                    </p><p style="margin:3.65pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:189%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Arrivée</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> à</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> destination: </span>
                        <input type="datetime-local" name="dharrivedest" id="dharrivedest" <?php if (isset($detailom['dharrivedest'])) { if (!empty($detailom['dharrivedest'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dharrivedest']))."'";}} ?> style="margin-left:12px;width:85%"/>
                    </p>
                    </p><p style="margin:0.55pt 120.4pt 0pt 238.7pt; text-indent:-224.5pt; line-height:95%; widows:0; orphans:0; font-size:9pt;width:100%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Départ</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; letter-spacing:-1pt"> </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">vers </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">base:</span>
                        <input type="datetime-local" name="dhdepbase" id="dhdepbase" <?php if (isset($detailom['dhdepbase'])) { if (!empty($detailom['dhdepbase'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dhdepbase']))."'";}} ?> style="margin-left:34px;width:85%"/>
                    </p><p style="margin:0.1pt 120.4pt 0pt 120.3pt; text-indent:-120.3pt; widows:0; orphans:0; font-size:8pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="   font-size:9pt;margin-left:45px;width:150%"><span style="font-family:'Times New Roman'; font-weight:bold">Arrivée base:</span>
                        <input type="datetime-local" name="dharrbase" id="dharrbase" <?php if (isset($detailom['dharrbase'])) { if (!empty($detailom['dharrbase'])) {echo "value='".date('Y-m-d\TH:i',strtotime($detailom['dharrbase']))."'";}} ?> style="margin-left:28px;width:57%"/>
                    </p><p style="margin-left:20px;font-size:9pt;width:150%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Durée totale mission: </span>
                        <input type="time" id="duremiss" name="duremiss" min="00:00" max="23:59" <?php if (isset($detailom['duremiss'])) { if (!empty($detailom['duremiss'])) {echo "value='".date('H:i',strtotime($detailom['duremiss']))."'";}} ?> >
                    </p><p style="margin:3.65pt 63.7pt 0pt 134.5pt; text-indent:-120.3pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold; color:#0070c0">&nbsp;</span></p><p style="margin:0pt 120.4pt 0pt 242.5pt; text-indent:-207.05pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">K</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">m</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> départ</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">: </span>
                        <input name="km_depart" type="number" id="km_depart" min="0" max="100000"  <?php if (isset($detailom['km_depart'])) { if (!empty($detailom['km_depart'])) {echo "value='".$detailom['km_depart']."'";}} ?> style="margin-left:2px ">
                    </p><p style="margin:0pt 120.4pt 0pt 223.5pt; text-indent:-188.05pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">K</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">m</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold"> arrivée</span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">: </span>
                        <input name="km_arrive" type="number" id="km_arrive" min="0" max="100000"  <?php if (isset($detailom['km_arrive'])) { if (!empty($detailom['km_arrive'])) {echo "value='".$detailom['km_arrive']."'";}} ?> >
                    </p><p style="margin:0pt 120.4pt 0pt 223.5pt; text-indent:-188.05pt; widows:0; orphans:0; font-size:9pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p><p style="margin:0pt 120.4pt 0pt 170.5pt; text-indent:-156.3pt; widows:0; orphans:0; font-size:9pt;width:150%"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">Distance </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">réelle mission </span><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">(km): </span>
                        <input name="km_distance" type="number" id="km_distance" min="0" max="100000" <?php if (isset($detailom['km_distance'])) { if (!empty($detailom['km_distance'])) {echo "value='".$detailom['km_distance']."'";}} ?> >
                    </p><p style="margin-top:0pt; margin-bottom:0pt; widows:0; orphans:0; font-size:11pt"><span style="font-family:&#39;Times New Roman&#39;; font-weight:bold">&nbsp;</span></p>
                </div>
            </div>
            <?php
// fin bloc completer
        }}
        ?>
        <div id="signatureagent">
            <p style="margin-top:8.85pt; margin-right:4.95pt; margin-bottom:0pt; line-height:115%; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Merci de votre </span><span style="font-family:'Times New Roman'; font-weight:bold">c</span><span style="font-family:'Times New Roman'; font-weight:bold">ollaboration. </span></p><p style="margin-top:0pt; margin-right:447.1pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">P/la Gérante</span></p><p style="margin-top:0pt; margin-bottom:0pt; text-align:justify; widows:0; orphans:0; font-size:10pt">

                <span style="font-family:'Times New Roman'; font-weight:bold; color:#000"> <?php if (isset($detailagt)) {echo $detailagt['name']." ".$detailagt['lastname']; } ?><?php if (isset($detailagtcomp)) {echo $detailagtcomp['name']." ".$detailagtcomp['lastname']; } ?> </span>
<input name="agent" id="agent" type="hidden" value="<?php if (isset($detailagt)) {echo $detailagt['name']." ".$detailagt['lastname']; } ?><?php if (isset($detailagtcomp)) {echo $detailagtcomp['name']." ".$detailagtcomp['lastname']; } ?>"></input>
            </p><p style="margin-top:0.05pt; margin-right:434.35pt; margin-bottom:0pt; line-height:115%; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">Plateau d’assistance 
<?php
if (isset($signaturetype)) 
    {
        echo trim($signaturetype); 
        echo '<input name="signagent" id="signagent" type="hidden" value="'.$signaturetype.'"></input>';}
?></span></p><p style="margin-top:0.1pt; margin-bottom:0pt; widows:0; orphans:0; font-size:10pt"><span style="font-family:'Times New Roman'; font-weight:bold">« courrier</span><span style="font-family:'Times New Roman'; font-weight:bold"> électronique, sans signature »</span></p>
        </div>
</form>
<script type="text/javascript">
function verifdispvoiture()
{

    var date1 = $("#dateheuredep").val();
    var date2 = $("#dateheuredispprev").val();

    $.ajax({ url: 'VoituresDispoParDate.php',
             data: {DB_HOST: '<?php echo $dbHost; ?>',
             DB_DATABASE: '<?php echo $dbname; ?>', 
             DB_USERNAME: '<?php echo $dbuser; ?>',
             DB_PASSWORD: '<?php echo $dbpass; ?>',
             datedepmission: date1,
             datedispprev: date2,
             typeom: 'remorquage' },
             type: 'post',
             success: function(output) {
                         /*dispvehicules = output;
                         $.each(output, function() {
                          $.each(this, function(k, v) {
                            //alert(JSON.stringify(v));
                          });
                         });*/
                         //alert(output);
                    if (output !== 'aucune vehicule est disponible')
                    {
                        // Clear vehicules list
                        $("#lvehicule").empty();
                        var len = output.length;
                        if (len > 1)
                        {   
                            
                                for(var i=0; i<len; i++){
                                    //alert(output[i].name);
                                    $("<option />", {
                                        val: output[i].name,
                                        text: output[i].name,
                                        idv: output[i].id,
                                        carburant: output[i].carburant,
                                        telepeage: output[i].telepeage
                                    }).appendTo("#lvehicule");
                                }
                           
                        }else{alert('wrrong');}
                          //alert(JSON.stringify(output));
                    }
                    else
                    {
                        // Clear vehicules list
                        $("#lvehicule").empty();
                        $("<option />", {
                                        val: "aucune vehicule est disponible",
                                    }).appendTo("#lvehicule");
                    }    
                        
            },
            dataType:"json"
            });
    }

    function verifdisppersonnel()
{

    var date1 = $("#dateheuredep").val();
    var date2 = $("#dateheuredispprev").val();

    $.ajax({ url: 'PersonnelDispoParDate.php',
             data: {DB_HOST: '<?php echo $dbHost; ?>',
             DB_DATABASE: '<?php echo $dbname; ?>', 
             DB_USERNAME: '<?php echo $dbuser; ?>',
             DB_PASSWORD: '<?php echo $dbpass; ?>',
             datedepmission: date1,
             datedispprev: date2,
             typeom: 'remorquage',
             typeperso: 'chauffeur' },
             type: 'post',
             success: function(output) {
                         /*dispvehicules = output;
                         $.each(output, function() {
                          $.each(this, function(k, v) {
                            //alert(JSON.stringify(v));
                          });
                         });*/
                         //alert(output);
                    if (output !== 'aucun personnel est disponible')
                    {
                        // Clear vehicules list
                        $("#lchauff").empty();
                        var len = output.length;
                        if (len >= 1)
                        {   
                            
                                for(var i=0; i<len; i++){
                                    //alert(output[i].name);
                                    $("<option />", {
                                        val: output[i].name,
                                        text: output[i].name,
                                        idperso: output[i].id
                                    }).appendTo("#lchauff");
                                }
                           
                        }else{alert('wrrong');}
                         // alert(JSON.stringify(output));
                    }
                    else
                    {
                        // Clear vehicules list
                        $("#lchauff").empty();
                        $("<option />", {
                                        val: "aucun personnel est disponible",
                                    }).appendTo("#lchauff");
                    }    
                        
            },
            dataType:"json"
            });
    }

// dateheuredep
$("#dateheuredep").change(function() {
  
  if( ($(this).val() !== "") && ($("#dateheuredispprev").val() !== "") ) {
    verifdispvoiture();
    verifdisppersonnel();
  }
});
// dateheuredispprev
$("#dateheuredispprev").change(function() {
  
  if( ($(this).val() !== "") && ($("#dateheuredep").val() !== "") ) {
    verifdispvoiture();
    verifdisppersonnel();
  }
});

$('input[list="lvehicule"]').on('input', function () {
    var val = this.value;
    if($('#lvehicule option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();        
    }).length) {
        //send ajax request
        verifdispvoiture();
        verifdisppersonnel();
    }
});
$('input[list="lchauff"]').on('input', function () {
    var val = this.value;
    if($('#lchauff option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();        
    }).length) {
        //send ajax request
        verifdispvoiture();
        verifdisppersonnel();
    }
});
    // CB_preetape
    $("#CB_preetape").change(function() {
        if(this.checked) {
            $("#preetape").show();
        }
        else
        {
            $("#preetape").hide();
        }
    });
    // CB_trmedecin
    $("#CB_trpersonne").change(function() {
        if(this.checked) {
            $("#trpersonne").show();
        }
        else
        {
            $("#trpersonne").hide();
        }
    });
    // CB_preportaeroport
    $("#CB_pregoulette").change(function() {
        if(this.checked) {
            $("#pregoulette").show();
            $("#preradeszone").css("display", "block");
        }
        else
        {
            $("#pregoulette").hide();
            //
            if (! $('#CB_prerades').is(':checked')) {$("#preradeszone").css("display", "none");}
        }
        /*a1 = $('#CB_pregoulette').is(':checked');
        a2= $('#CB_prerades').is(':checked');
        alert("display it: "+a1 +" "+ a2);*/
    });
    $("#CB_prerades").change(function() {
        if(this.checked) {
            $("#prerades").show();
            $("#preradeszone").show();
        }
        else
        {$("#prerades").hide();
            $("#preradeszone").hide();
        }
    });


    /// fill phone number on select prest Prise en charge
    document.querySelector('input[list="CL_lieuprest_pc"]').addEventListener('input', onInput);

    function onInput(e) {
        var input = e.target,
            val = input.value;
        list = input.getAttribute('list'),
            options = document.getElementById(list).childNodes;

        for(var i = 0; i < options.length; i++) {
            if(options[i].innerText === val) {
                // An item was selected from the list
                document.getElementById("CL_prestatairetel_pc").value = options[i].getAttribute("telprest");
                break;
            }
        }
    }

    /// fill phone number on select prest Decharge
    document.querySelector('input[list="CL_lieudecharge_dec"]').addEventListener('input', onInputdec);

    function onInputdec(e) {
        var input = e.target,
            val = input.value;
        list = input.getAttribute('list'),
            options = document.getElementById(list).childNodes;

        for(var i = 0; i < options.length; i++) {
            if(options[i].innerText === val) {
                // An item was selected from the list
                document.getElementById("CL_prestatairetel_dec").value = options[i].getAttribute("telprest");
                break;
            }
        }
    }
        /// fill cartecarburant et telepeage
    document.querySelector('input[list="lvehicule"]').addEventListener('input', onInputveh);

    function onInputveh(e) {
       var input = e.target,
           val = input.value;
           list = input.getAttribute('list'),
           options = document.getElementById(list).childNodes;

      for(var i = 0; i < options.length; i++) {
        if(options[i].innerText === val) {
          // An item was selected from the list
          document.getElementById("cartecarburant").value = options[i].getAttribute("carburant");
          document.getElementById("cartetelepeage").value = options[i].getAttribute("telepeage");
          document.getElementById("idvehic").value = options[i].getAttribute("idv");
          break;
        }
      }
    }

            /// fill id chauffeur
    document.querySelector('input[list="lchauff"]').addEventListener('input', onInputchauff);

    function onInputchauff(e) {
       var input = e.target,
           val = input.value;
           list = input.getAttribute('list'),
           options = document.getElementById(list).childNodes;

      for(var i = 0; i < options.length; i++) {
        if(options[i].innerText === val) {
          // An item was selected from the list
          document.getElementById("idchauff").value = options[i].getAttribute("idperso");
          break;
        }
      }
    }
</script>
</body>

</html>


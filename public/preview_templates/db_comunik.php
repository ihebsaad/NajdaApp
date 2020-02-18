<?php
// Create connection
//$conn = mysqli_connect('192.168.1.208', 'najda2017', '9g3\jHtFeEA-*?@[','medic_preprod');
$conn = mysqli_connect('192.168.1.249', 'cmk', 'CMKnajda*2020','najda');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//mysqli_query($conn,"set names 'utf8'");

// recuperation des prestataires HOTEL ayant prestations dans dossier

$sqlvh = "SELECT * FROM cmk_recordings";

    $resultvh = $conn->query($sqlvh);
    if ($resultvh->num_rows > 0) {

        $array_prest = array();
        while($rowvh = $resultvh->fetch_assoc()) {
            echo $rowvh["caller"].' | '.$rowvh["called"].'</br>';
        }
}
else {echo 'req not well';}
?>

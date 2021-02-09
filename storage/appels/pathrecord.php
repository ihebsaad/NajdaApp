<?php
// Connect to FTP host
$host='192.168.1.249';
$user='ftpmizuuser';
$pass='Najda2020';
$port='21';
$conn = ftp_connect($host, $port) or die("Could not connect to {$host}\n");

// Login
var_dump(ftp_login($conn, $user, $pass));
if(ftp_login($conn, $user, $pass)) {
ftp_pasv ($conn, true) or die("Passive mode failed");
  // Retrieve directory listing
  $files = ftp_nlist($conn, '.');

}

// Close Connect
ftp_close($conn);
$ftpFiles = array();
foreach($files as $file)
{
  // Get just the filename without directory information
  $thisFile = basename($file);
  if($thisFile != '.' && $thisFile != '..')
  {
    // Append to our new array
    $ftpFiles[] = $thisFile;
  }
}
$currentFiles = array();
$cache_file='./filemizu.txt';
if(file_exists($cache_file))
{
  // Read contents of file
  $handle = fopen($cache_file, "r");
  if($handle)
  {
    $contents = fread($handle, filesize($cache_file));
	//dd($contents);
    fclose($handle);

    // Unserialize our array from the file contents
    $currentFiles = unserialize($contents);
//var_dump($currentFiles);
  }
//print_r($currentFiles);
}
$diff = array_diff($ftpFiles, $currentFiles);
if(count($diff) > 0)
{



$hostname="127.0.0.1";
$dbname="najdatest";
$user="phpmyadmin";
$mdp="Najda2020";
// Create connection
$conn = mysqli_connect($hostname, $user, $mdp,$dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_query($conn,"set names 'utf8'");
foreach($diff as $dif)
 {
 
$dif1 = str_replace("_", "-", $dif);


 $difs= explode('-', $dif1);
$emet=$difs[1];
$emet1=$difs[1].' -RG-Najda_assistance';
$dest=$difs[2];
echo($difs[1]);
echo($difs[2]);
$difpath="http://192.168.1.249/najdaapp/storage/appels/".$dif;
$sqlU = "UPDATE entrees SET path='".$difpath."' WHERE emetteur IN ('".$emet."','".$emet1."') AND destinataire='".$dest."'AND path IS NULL";
$sqlUI = "UPDATE envoyes SET path='".$difpath."' WHERE emetteur='".$emet."'AND destinataire='".$dest."'AND path IS NULL";
   
   if (mysqli_query($conn, $sqlU)) {
      echo "Record updated successfully";
   } else {
      echo "Error updating record: " . mysqli_error($conn);
   }
if (mysqli_query($conn, $sqlUI)) {
      echo "Record updated successfully";
   } else {
      echo "Error updating record: " . mysqli_error($conn);
   }}
   mysqli_close($conn);
 
}
// Write new file list out to cache
$handle = fopen($cache_file, "w");
fwrite($handle, serialize($ftpFiles));
fflush($handle);
fclose($handle);
?>

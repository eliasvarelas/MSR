<?php
session_start();
$patientID = $_GET["id"];
$usersid = $_SESSION['user_id'];
$docID = $_GET['docid'];

$servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{ 
  // executes for doctor removal from the admin
  $sql = "DELETE FROM patients WHERE Doctor_ID = $docID AND Patient_id = $patientID";
  $result = $pdo->query($sql);
  if ($sql) {
    $script = file_get_contents('redirectadmin.js');
    echo "<script>".$script."</script>";                //redirect
  } else{
    echo "Something went wrong, please try again";
  }


} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
?>

<?php
session_start();
$patientID = $_GET["id"];
$usersid = $_SESSION['user_id'];

$servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{
  $sql = "DELETE FROM patients WHERE Doctor_ID = $usersid AND Patient_id = $patientID";
  $result = $pdo->query($sql);
  if ($sql) {
    $script = file_get_contents('redirectPatientsTable.js');
    echo "<script>".$script."</script>";                //redirect
  } else{
    echo "Something went wrong, please try again";
  }

} catch(PDOException $e){
  echo"<div class='error'>";
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
  echo "</div>";
}
?>

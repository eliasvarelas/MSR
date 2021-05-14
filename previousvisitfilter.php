<?php
session_start();
$usersid = $_SESSION['user_id'] ;
$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{
  $sql = "SELECT Patient_id FROM patients WHERE Doctor_ID = $usersid";
  $result = $pdo->query($sql);

  while($row = $result->fetch()){
    $pat_id = $row['Patient_id']; // for filtering previous visits, currently prints all the patients of the same doc
  }
} catch(PDOException $e){
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}
?>

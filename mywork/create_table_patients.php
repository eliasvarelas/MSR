<?php
$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

try {     //Connection!
  $conn = new PDO("mysql:host=$servername;dbname=$dbname;", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully<br>";

// sql to create table

  $sql = "CREATE TABLE patients (
   Doctor_ID INT(5) NOT NULL,
   Patient_id INT(5) NOT NULL UNIQUE,
   Patient_name VARCHAR(1000) NOT NULL,
   Phonenum VARCHAR(1000) NOT NULL,
   Email VARCHAR(200) NOT NULL,
   Submit VARCHAR(20) NOT NULL,
   reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

 // use exec() because no results are returned
 $conn->exec($sql);
 echo "Table created successfully";
} catch(PDOException $e) {
 echo $sql . " \n" . $e->getMessage();
}

$conn = null;         // terminates the connection to the DB
if ($conn==null) {
  echo" connection terminated";
}
else {
  echo" still active";
}

?>

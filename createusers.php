<?php
$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

try {
  $sql = "CREATE TABLE users(
    id INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(200) NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP
  )";

} catch (\Exception $e) {
    echo "$e";
}

?>

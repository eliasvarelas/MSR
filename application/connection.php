//database connection
$servername = "ftpupload.net";
$username = "epiz_32166686";
$password = "ntpDheD7z9";
$dbname = "epiz_32166686_MSR";

//initiallizing the pdo argument
$pdo = new PDO("mysql:host=$servername;port=21;dbname=$dbname", $username, $password);

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e)
{
  echo $e->getMessage();                         
}
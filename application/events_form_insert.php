<?php
  session_start();
  //database connection
  $servername = "127.0.0.1";
  $servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";
  $table = "eventsLog";

  // get the $_POST info
  $event_name = $_POST['event_name'];
  $persons_invited = $_POST['No_of_Persons'];
  $event_location = $_POST['location'];
  $submit = $_POST['ok_button'];
  $user_id = $_SESSION['user_id'];

  try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // inserting the data from the form in the MSR table
    $sql = "INSERT INTO $table (Event_name,persons_invited,event_location,Submit,Doctor_ID)
    VALUES (?,?,?,?,?)";  //using prepared statements for security towards sql injections

    //Execute
    if (isset($submit)) {
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$event_name,$persons_invited,$event_location,$submit,$user_id]);
      // echo "records inserted successfully!!!!!!!!";

    } else {
      echo "Sorry, Something Went Wrong. Please Try again";
    }
    if ($sql) {
      //Redirect to the Doctors Menu
      $script = file_get_contents('redirectMenu.js');
      echo "<script>".$script."</script>";
    }

  } catch (\Exception $e) {
    echo $sql . "<br>" . $e->getMessage();
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
  }

?>

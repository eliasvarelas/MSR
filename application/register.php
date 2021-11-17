<?php
session_start();
require 'lib/password.php';

$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

if(isset($_POST['register'])){
  //Retrieve the field values from the registration form.
  $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
  $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;

  //Construct the SQL statement and prepare it.
  $sql = "SELECT * FROM users WHERE username = :username";
  $stmt = $pdo->prepare($sql);

  //Bind the provided username to the prepared statement.
  $stmt->bindValue(':username', $username);

  //Execute.
  $stmt->execute();

  //Fetch the row.
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  //If the provided username already exists - display error.
  if($row > 0){
    $scriptuser = file_get_contents('redirect_error_register.js');
    echo "<script>".$scriptuser."</script>";
  }

  //password_hash
  $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));

  //INSERT statement.
  $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
  $stmt = $pdo->prepare($sql);

  //Bind the variables.
  $stmt->bindValue(':username', $username);
  $stmt->bindValue(':password', $passwordHash);

  //Execute the statement and insert the new account.
  $result = $stmt->execute();

  //If the signup process is successful.
  if($result){
      echo 'Thank you for registering with our website.';

      //Redirect to the login page
      $script = file_get_contents('jsredirectlogin.js');
      echo "<script>".$script."</script>";

  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Doctor Registration</title>
    <link rel="stylesheet" href="/login.css">
  </head>
  <body>
    <div>
      <img src="MSregistry_ionian_new_logo.png">
      <h3>Welcome Doctor, please create an account below:</h3>
      <form action="register.php" method="post" class="box">
          <!-- <label for="username">Username</label> -->
          <input type="text" id="username" name="username" placeholder="Username" required><br>
          <!-- <label for="password">Password</label> -->
          <input type="password" id="password" name="password" placeholder="Password" required><br>
          <!-- <label for="password">Re-enter password</label> -->
          <input type="password" id="password" name="password" placeholder="Re-enter Password" required> <br><br>
          <input type="submit" name="register" value="Register" required></button>
      </form>
      <p> Already have an Account? <br><button type="button" id="login" name="Sign in" >Sign in</button>
        <script type="text/javascript">
          document.getElementById("login").onclick = function () {
              location.href = "/login.php";
          };
        </script>
      </p>
    </div>
  </body>
</html>

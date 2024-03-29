<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login : MS Registry</title>
  <link rel="stylesheet" href="/MSR/application/login_new.css">
</head>
<body>
  <div>
    <form action="login.php" method="post" class="box" style="text-align:center;"> <!-- basic login form -->
      <img src="MSregistry_ionian_new_logo.png">
        <p>
          <h3>Please Login </h3>
          <!-- <label for="user_name">Username:</label> -->
          <input type="text" name="user_name" id="user_name" placeholder="Username" required>
        </p>
        <p>
          <!-- <label for="password">Password:</label> -->
          <input type="password" name="password" id="pass" placeholder="Password" required>
        </p>

        <input type="submit" value="Login" name="Submit" class="button">
    </form>
    <p> Don't have an Account? <br> <button type="button" id="register" name="Sign up" >Sign up</button> <!-- redirecting to the register page -->
    </p>
  </div>

  <script type="text/javascript">
        document.getElementById("register").onclick = function () {
          location.href = "register.php";
        };
      </script>
</body>
</html>



<?php
require 'lib/password.php';

//database connection
$servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";

//initiallizing the pdo argument
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

try {
  // if(isset($_SESSION['use'])){
  //   header("Location:menu.php");
  // }
  //checking if the form has been submitted
  if(isset($_POST['Submit'])){

    //Retrieve the field values from the login form.
    $user_name = !empty($_POST['user_name']) ? trim($_POST['user_name']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

    //Retrieve the user account information for the given username.
    $sql = "SELECT id, username, password FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':username', $user_name);

    //Execute.
    $stmt->execute();

    //Fetch row.
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if($user === false){
      //username doesnt exist in database
      $scriptuser = file_get_contents('redirect_error_login_user.js');
      echo "<script>".$scriptuser."</script>";

    } else{

      $validPassword = password_verify($passwordAttempt, $user['password']);
      if($validPassword && $user_name !== 'admin'){
          //Provide the user with a login session.
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['logged_in'] = time();
          $_SESSION['user'] = $user['username'];

          //Redirect to the Menu page
          $script = file_get_contents('redirectMenu.js');
          echo "<script>".$script."</script>";

          // if (condition) {   // if the user is still active, dont allow entry without terminating the previous connection
          //   # code...
          // }

      } elseif ($validPassword && $user_name === 'admin') {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['logged_in'] = time();
        $_SESSION['user'] = $user['username'];

        //Redirect to the Menu page
        $script = file_get_contents('redirectmenu_admin.js');
        echo "<script>".$script."</script>";

      } else{
          //Password error.
          $scriptpass = file_get_contents('redirect_errorlogin.js');
          echo "<script>".$scriptpass."</script>";

          ;
        }
      }
  }
} catch (\Exception $e) {

}
?>

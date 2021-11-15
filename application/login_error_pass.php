<?php
session_start();

require 'lib/password.php';

//database connection
$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

//initiallizing the pdo argument
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

try {
  if(isset($_SESSION['use'])){
    header("Location:doctors_menu.php");
  }
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
      //user doesnt exist
      $scriptuser = file_get_contents('redirect_error_user.js');
      echo "<script>".$scriptuser."</script>";
    } else{
      //Comparing the encrypted passwords
      $validPassword = password_verify($passwordAttempt, $user['password']);
      if($validPassword){
          //Provide the user with a login session.
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['logged_in'] = time();
          $_SESSION['user'] = $user['username'];

          //Redirect to the Menu page
          $script = file_get_contents('redirectMenu.js');
          echo "<script>".$script."</script>";

      } else{
          //Passwords do not match.
          $scriptpass = file_get_contents('redirect_errorlogin.js');
          echo "<script>".$scriptpass."</script>";

          ;
        }
      }
  }
} catch (\Exception $e) {

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login : MS Registry</title>
  <style>
    body{
      background-color: #7386D5;
      display: block;
    }
    div{
      display: block;
      margin: 0;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 1.5em 1.5em;
      text-align: center;
      font-family: arial;
      background-color: white;
      border-style: solid;
      border-radius: 14%;
    }
    .box input[type = "text"], .box input[type = "password"]{

      text-align: center;
      background-color: #fafafa;
    }
    .box input[type = "submit"]{
      cursor: pointer;
    }
    .button{
      cursor: pointer;
    }
    .red-alert-error{
      display: block;
      background-color: red;
      color: white;
      margin: 0;
      border-radius: 0;
      margin-top: 0em;
      width: 80%;
    }
    button{
      cursor: pointer;
      margin-top: 0.3em;
    }
    img{
      margin-bottom: 4em;
    }
  </style>
</head>
<body>
  <div>
    <form action="login.php" method="post" class="box" style="text-align:center;"> <!-- basic login form -->
      <div class="red-alert-error">
        <h5>Your Password was Incorrect, Please try Again!</h5>
      </div>
      <img src="MSregistry_ionian_new_logo.png">

        <p>
          <h3>Please Login </h3>
          <label for="user_name">Username:</label>
          <input type="text" name="user_name" id="user_name" required>
        </p>
        <p>
          <label for="password">Password:</label>
          <input type="password" name="password" id="pass" required>
        </p>

        <input type="submit" value="Login" name="Submit" class="button">
    </form>
    <p> Don't have an Account? <br> <button type="button" id="register" name="Sign up" >Sign up</button> <!-- redirecting to the register page -->
      <script type="text/javascript">
        document.getElementById("register").onclick = function () {
          location.href = "/register.php";
        };
      </script>
    </p>
  </div>

</body>
</html>

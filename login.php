<?php
session_start();

require 'lib/password.php';

//mysql database info required for connection
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

  //If the POST var "login" exists (our submit button), then we can
  //assume that the user has submitted the login form.
  if(isset($_POST['Submit'])){

      //Retrieve the field values from our login form.
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

      //If $row is FALSE.
      if($user === false){
          //Could not find a user with that username!
          //PS: You might want to handle this error in a more user-friendly manner!
          die('Incorrect Username, Please Try Again');
      } else{
          //User account found. Check to see if the given password matches the
          //password hash that we stored in our users table.

          //Compare the passwords.
          $validPassword = password_verify($passwordAttempt, $user['password']);

          //If $validPassword is TRUE, the login has been successful.
          if($validPassword){

              //Provide the user with a login session.
              echo "Welcome Dr. $user_name";
              $_SESSION['user_id'] = $user['id'];
              $_SESSION['logged_in'] = time();
              $_SESSION['user'] = $user['username'];


              //Redirect to the Menu page
              $script = file_get_contents('redirectMenu.js');
              echo "<script>".$script."</script>";

          } else{
              //$validPassword was FALSE. Passwords do not match.
              echo "<p> Incorrect Username or Password, Please Try Again</p>";
              // die('Incorrect username/password');
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
      background-color: lightblue;
      display :block;
    }
    div{
      margin: auto;
      width: 60%;
      padding: 0;
      top: 50%;
      left: 50%;
      bottom: 50%;
      text-align: center;
      font-family: arial;
      background-color: lightblue;
    }
    .box input[type = "text"], .box input[type = "password"]{

      text-align: center;
      /* border-radius: 24px; */
    }
    .box input[type = "submit"]{
      cursor: pointer;
    }
    .button{
      cursor: pointer;
    }
    button{
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div>
    <form action="login.php" method="post" class="box" style="text-align:center;">
      <img src="MSregistry_ionian2_bg_lightblue.png">
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
    <p> Don't have an Account? <br> <button type="button" id="register" name="Sign up" >Sign up</button>
      <script type="text/javascript">
        document.getElementById("register").onclick = function () {
            location.href = "/register.php";
        };
      </script>
    </p>
  </div>

</body>
</html>

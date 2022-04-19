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
  
    if(isset($_POST['Submit'])){

        //Retrieve the field values from the login form.
        // $user_name = !empty($_POST['user_name']) ? trim($_POST['user_name']) : null;
        // $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
        $user_name = $_POST['user_name'];
        $passwordAttempt = $_POST['password'];
    
        //Retrieve the user account information for the given username.
        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
    
        //Bind value.
        $stmt->bindValue(':username', $user_name);
        
        // $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));

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
              $script = file_get_contents('redirectpass.js');
              echo "<script>".$script."</script>";
    
              // if (condition) {   // if the user is still active, dont allow entry without terminating the previous connection
              //   # code...
              // }
    
          } else{
            //Password error.
            //   $scriptpass = file_get_contents('redirect_errorlogin.js');
            //   echo "<script>".$scriptpass."</script>";
            echo "Error during login";
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
  <title>MSR - Change Password</title>
  <link rel="stylesheet" href="/MSR/application/login.css">
</head>
<body>
  <div>
    <form action="changepass.php" method="post" class="box" style="text-align:center;"> <!-- basic login form -->
      <img src="MSregistry_ionian_new_logo.png">        
        <h3>Please Login Using the Password that You Recieved in an Email From <i> <u>MSRegistryRegistriationservice@gmail.com</u></i></h3>          
        <input type="text" name="user_name" id="user_name" placeholder="Username" required>        
        <p>
          <input type="password" name="password" id="pass" placeholder="Password" required>
        </p>
        <input type="submit" value="Login" name="Submit" class="button">
    </form>
  </div>  
</body>
</html>
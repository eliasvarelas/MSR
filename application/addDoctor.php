<?php
session_start();
error_reporting(0);
header('Content-Type: text/html; charset=utf-8');
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 18000)) {
  // last request was more than 30 minutes ago
  session_unset();     // unset $_SESSION variable for the run-time
  session_destroy();   // destroy session data in storage
  $scripttimedout = file_get_contents('timeout.js');
  echo "<script>" . $scripttimedout . "</script>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


if (!isset($_SESSION['user_id']) && !isset($_SESSION['logged_in']) &&  !isset($_SESSION['user'])) {
  $return_to_login = file_get_contents('jsredirectlogin.js');
  echo "<script>" . $return_to_login . "</script>";
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>MS Registry Adding a Patient</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="basicapp-notnow.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

</head>

<body>

  <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
      <div class="sidebar-header">
        <h3><a href="admins_menu.php" id="logo">Multiple Sclerosis Registry<a /></h3>
        <strong><a href="menu.php" id="logo">MSR</a></strong>
      </div>

      <ul class="list-unstyled components">
        <li>
            <a href="admins_menu.php">
                <i class="fas fa-home"></i>
                Admins Menu
            </a>
        </li>
        <li class="active">
            <a href="addDoctor.php">
                <i class="fas fa-user-plus"></i>
                Add a Doctor
            </a>
        </li>
        <li class="">
            <a href="admin_searching.php" class="dropdown-toggle" ::after>
                <i class="fas fa-search"></i>
                Search Doctors
            </a>
        </li>
        <li class="">
            <a href="admin_patient_searching.php">
                <i class="fas fa-search"></i>
                Advanced Patient Search
            </a>
        </li> 
        <li>
          <a href="visual_analytics_google_admin.php">
            <i class="fas fa-chart-bar"></i>
            Visual Analytics Tool
          </a>
        </li>
      </ul>
    </nav>

    <!-- Page Content  -->
    <div id="content">

      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

          <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="fas fa-align-left"></i>
            <span>Toggle Sidebar</span>
          </button>
          <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-align-justify"></i>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
              <li class="navbar-nav">
                <a class="nav-link" id="">
                  <i class="fas fa-user"></i>
                  Doctor: <u><?php $user_name = $_SESSION['user'];
                              echo $user_name; ?></u>
                </a>
                <a href="logout.php" onclick="return confirm('Are you sure to logout?');">
                  <button type="button" id="logoutBtn" class="navbar-btn btn btn-info">
                    <!-- <i class="fa fa-sign-out"></i> -->
                    <span>Logout</span>
                  </button>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <?php
      //database connection
      $servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $doc = $_SESSION['user_id']; ?>
      <div class="container block">
        <h2>Create a New User</h2>
      </div>
    
      <!-- <div class="block"> -->

    <form action="addDoctor.php" method="post" class="form">
          <!-- basic form to pass the data in the database for the creation of a new doc -->
          <div class="container bg-blue w-100">
            <div class="split">  
              <div class="left"> 
                <div class="row">
					<label for="assignid">Doctors ID:</label>
					<input type="number" name="assignid" placeholder="Doc ID" required>
				</div>
				<div class="row">
					<label for="pass">Temporary Password</label>   
					  <input type="text" name="docPass" id="" required>
				</div>
				<div class="row">
					<label for="flname">Doctors Name:</label>
                  	<input type="text" name="flname" placeholder="First and Last Name" required>  
				</div>
              </div>
              <div class="right">
                <div class = "row">
					<label for="email">E-mail:</label>
                  	<input type="email" name="email" placeholder="Ipokratis@email.com" required>
				</div>
                <div class = "row">
					  <label for="">Phone Number</label>
                  	  <input type="number" name="phone" placeholder="Phone Number" required>
				</div>
              </div>
            </div>
			  <input type="submit" name="Submit" class="bttn" value="Submit">
          </div>            
    </form>
    <!-- </div> -->
		
    <!-- </div> -->

      <?php
      //getting the POST data
      $doc_id = $_POST['assignid'];
      $flname = $_POST['flname'];
    //   $dob = $_POST['dob'];
      $phonenum = $_POST['phone'];
      $email = $_POST['email'];
      $pass = $_POST['docPass'];
      $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
    //   $address = $_POST['pat_address'];
      $Submit = $_POST['Submit'];

      try {  //using MySQL PDOAttribute for the Exceptions
        $sql = "INSERT INTO users (id,username,`password`,doc_phone,doc_Email) VALUES (?,?,?,?,?)";

        if (isset($_POST['Submit'])) {
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$doc_id, $flname,$passwordHash, $phonenum, $email]);
          if ($sql) {
            $from = "MSRegistryRegistriationservice@gmail.com";
            $to = $email;
            $subject = "Welcome to The Multiple Sclerosis Registry Doctor:". $flname ;
            $message = "Mr/Ms ". $flname ." welcome. An account has been created for you via an Admin of our systems with the following information:
              Name: ".$flname ."  
              Doctors ID: ". $doc_id." 
              Email: ". $email ." 
              Password: " . $pass ." 
              To login, please click the following link: http://localhost:3000/MSR/application/changepass.php";            
            $headers = "From:" . $from;
            if(mail($to,$subject,$message, $headers)) {
              echo "The email message was sent.";
            } else {
              echo "The email message was not sent.";
            }
          } else {
            echo "The email was not sent";
          }
        }

      } catch (PDOException $e) {
        echo"<div class='error'>";
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
          echo "</div>";
      }
      ?>
      <footer id="foo">
        <div class="line"></div>
        <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
      </footer>
    </div>
  </div>

  <!-- jQuery CDN - Slim version (=without AJAX) -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <!-- Popper.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
      });
    });
  </script>

</body>

</html>
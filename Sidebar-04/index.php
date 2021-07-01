<?php session_start(); ?>
<!doctype html>
<html lang="en">
  <head>
  	<title>Doctors Menu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/style.css">
  </head>
  <body>

		<div class="wrapper d-flex align-items-stretch">
			<nav id="sidebar">
				<div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
	          <i class="fa fa-bars"></i>
	          <span class="sr-only">Toggle Menu</span>
	        </button>
        </div>
	  		<h1><a href="index.php" class="logo"><img class="img" src="/mywork/MSregistry_ionian2_bg_lightblue_small.png" alt="Logo"></a></h1>
        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="/mywork/doctors_menu_likeopencour.php"><span class="fa fa-home mr-3"></span> Home</a>
          </li>
          <li>
              <a href="/mywork/patientsinfo.php"><span class="fa fa-user mr-3"></span> Existing Patients</a>
          </li>
          <li>
            <a href="/mywork/addpatient.php"><span class="fa fa-sticky-note mr-3"></span> Add a New Patient</a>
          </li>
          <li>
            <a href="/mywork/searching.php"><span class="fa fa-sticky-note mr-3"></span> Search Query</a>
          </li>

        </ul>

    	</nav>

        <!-- Page Content  -->
      <div id="content" class="p-4 p-md-5 pt-5">
        <h1>Welcome Doctor: <u><?php $user_name = $_SESSION['user'];
        echo $user_name;?></u>!</h1> <!-- prints the active username -->

        <br>
        <br>
        <!-- <img src="MSregistry_ionian2_bg_lightblue.png" alt="Logo"> -->
        <?php
        //database connection
        $usersid = $_SESSION['user_id'];
        $servername = "127.0.0.1";
        $username = "root";
        $password = "bioinformatics";
        $dbname = "BIHElab";

        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //establishing the connection and informing the user of its status
        if (is_null($pdo)) {
          return $pdo -> prepare($query);
          echo"The Connection with the Database has failed \n";
        } else {
            echo "<p>The Connection with the Database is Active! \n</p>";
        }
        ?>
        <!-- <h4>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</h4>  <!-- Basic information for the app -->
        <div class="aligner">
          <table>    <!-- prints the table with the patients -->
                <tr>
                  <th>Patient Id</th><th>Patient Name</th><th>Phone Number</th><th>Email</th><th>History</th>
                  <th>Add a Follow Up Visit</th><th>Remove Patient</th>
                </tr>
                  <?php  $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid"; //filters the patients for the active user/doctor
                  $result = $pdo->query($sql);
                  if($result->rowCount() > 0){
                    while($row = $result->fetch()){?>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td><?php echo $row['Patient_name'] ; ?></td>
                          <td><?php echo $row['Phonenum'] ; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='/previousvisits.php?id=".$row['Patient_id']."'>Previous Visits</a>"; ?></td>
                          <td><?php echo "<a href='/Multiple_Sclerosis_app.php?id=".$row['Patient_id']. "&?nm=". $row['Patient_name'] ."'>Add Follow up</a>"; ?></td> <!-- Passes the patients id in the form for minimazing user error -->
                          <td><button onclick="remove_user()" id="removeuser"><?php echo "<a href='/removeuser.php?id=".$row['Patient_id']."'>Remove Patient</a>"; ?></button></td>  <!-- Removes only the patient with the particular id -->
                        </tr>
                      <?php } } ?>
          </table>
        </div>
        <footer>
          <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
        </footer>
      </div>
      </div>
		</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>

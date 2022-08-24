<?php // session_start and timeout function
session_start();
error_reporting(0);
$docID = $_GET["docid"];   // used to pass the patient id directly in the form
$docNAME = $_GET["nm"]; // used to pass the pateint name directly in the form
$docEmail = $_GET["em"]; // used to pass the pateints age directly in the form
$docPhonenum = $_GET["phone"];
$fname = $_GET["fname"];
$lname = $_GET["lname"];
// $docAdr = $_GET['adr'];
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

    <title>MS Registry Patient History</title>

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
                <h3><a href="menu.php" id="logo">Multiple Sclerosis Registry<a /></h3>
                <strong><a href="menu.php" id="logo">MSR</a></strong>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="admins_menu.php">
                        <i class="fas fa-home"></i>
                        Admins Page
                    </a>

                </li>
                <!-- <li>
                    <a href="/MSR/application/patientinfo-bootstrap.php">
                        <i class="fas fa-folder"></i>
                        Existing users
                    </a>


                </li> -->
                <!-- <li>
                    <a href="/MSR/application/editPatientInfo.php">
                        <i class="fas fa-edit"></i>
                        Edit Patient Info
                    </a>
                </li> -->
                <!-- <li>
                    <a href="/MSR/application/addpatient-bootstrap.php">
                        <i class="fas fa-user-plus"></i>
                        Add a new Patient
                    </a>
                </li>
                <li>
                    <a href="/MSR/application/searching-bootstrap.php">
                        <i class="fas fa-search"></i>
                        Advanced Search
                    </a>
                </li>
                <li>
                    <a href="/MSR/application/visual_analytics.php">
                        <i class="fas fa-chart-bar"></i>
                        Visual Analytics Tool D3
                    </a>
                </li> -->
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
            <div class="content">


                <div class="block">
                    <h2>Edit the Doctors Info</h2>
                </div>

                <form action="editDoctorForm.php?docid=<?php echo $docID ?>" method="POST" class ="form w-100">
                    <div class="container bg-blue w-100">
                        <div class="split">
                            <div class="left">
                                <div class="row">
                                    <h2>Old Information</h2>
                                </div>
                                <div class="row">
                                    <label for="ID">Doctor ID:</label>
                                    <input type="text" name="docID" value="<?php echo ($docID ?? "N/A"); ?>" disabled>
                                </div>
                                <div class="row">
                                    <label for="Name">Doctors Username:</label>
                                    <input type="text" value="<?php echo ($docNAME ?? "N/A"); ?>" disabled>
                                </div>
								<div class="row">
                                    <label for="fname">Doctors First Name:</label>
                                    <input type="text" value="<?php echo ($fname ?? "N/A"); ?>" disabled>
                                </div>
								<div class="row">
                                    <label for="lname">Doctors Last Name:</label>
                                    <input type="text" value="<?php echo ($lname ?? "N/A"); ?>" disabled>
                                </div>
                                <div class="row">
                                    <label for="Email">Doctors Email:</label>
                                    <input type="email" value="<?php echo ($docEmail ?? "N/A"); ?>" disabled>
                                </div>
                                <div class="row">
                                    <label for="Phone Number">Doctor Phone Number:</label>
                                    <input type="text" value="<?php echo ($docPhonenum ?? "N/A"); ?>" disabled>
                                </div>
                                
                                
                            </div>
                            <div class="right">
                                
                                <div class="row">
                                    <h3>New Information</h3>
                                </div>
                                <div class="row">
                                    <label for="docid">Doc ID:</label> 
                                    <input type="number" id="docidinp" name="newDocID" >
                                    <!-- <button id="docidbtn">Enable Field</button> -->
                                </div>
								<div class="row">
                                    <label for="patName">Doctors Username:</label>
                                    <input type="text" name="newDocName" id="docnameinp" >
                                    <!-- <button id="docnamebtn"></button> -->
                                </div>
                                <div class="row">
                                    <label for="newFname">Doctors First Name:</label>
                                    <input type="text" name="newDocFName" id="docfnameinp" >
                                </div>
								<div class="row">
                                    <label for="newLname">Doctors Last Name:</label>
                                    <input type="text" name="newDocLName" id="doclnameinp" >
                                </div>
                                <div class="row">
                                    <label for="patEmail">Doctors Email:</label>
                                    <input type="email" name="newDocEmail" id="docemailinp" >
                                    <!-- <button id="docemailbtn"></button> -->
                                </div>
                                <div class="row">
                                    <label for="phonenum">Doctors Phone Number:</label>
                                    <input type="number" name="newDocPhonenum" id="">
                                </div>
                                
                            </table>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="bttn">Submit</button>
                </div>
                
            </form>
            
            

    <?php
        //database connection
        $usersid = $_SESSION['user_id']; // this is the id of the admin.
        $servername = "localhost";
		$username = "phpmyadmin";
		$password = "root";
		$dbname = "MSR";
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            if (isset($_POST['submit'])) {
                //get new info from the form
                $newDocID = $_POST['newDocID'];
                $newDocName = $_POST['newDocName'];
                $newDocEmail = $_POST['newDocEmail'];
                $newDocPhonenum = $_POST['newDocPhonenum'];
				$newDocFName = $_POST['newDocFName'];
				$newDocLName = $_POST['newDocLName'];

                // check if there is change on all the data except from the new ID
                if (!empty($_POST['newDocName']) && !empty($_POST['newDocEmail']) && !empty($_POST['newDocPhonenum'])  && empty($_POST['newDocID']) && !empty($_POST['newDocFName']) && !empty($_POST['newDocLName']) ) {
                    $sql = "UPDATE users SET doc_Email = :newDocEmail, username = :newDocName, doc_phone = :newDocPhonenum, fname = :fname, lname = :lname WHERE id = :docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocEmail", $newDocEmail);
                    $stmt->bindValue(":newDocName", $newDocName);
                    $stmt->bindValue(":newDocPhonenum", $newDocPhonenum);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->bindValue(":fname", $newDocFName);
                    $stmt->bindValue(":lname", $newDocLName);
                    $stmt->execute();
                    echo "Data changed succesfully";
                    // check if only the name is entered
                } elseif (!empty($_POST['newDocName']) && empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newDocID']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) { //user enters only a new Name
                    $sql = "UPDATE users SET username =:newDocName WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocName", $newDocName);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                    echo "Name changed succesfully";
                    //check if only the email is entered
                } elseif (empty($_POST['newDocName']) && !empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newDocID']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) {    // user enters only a new email
                    $sql = "UPDATE users SET doc_Email =:newDocEmail WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocEmail", $newDocEmail);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                    echo "Email changed succesfully<br>";
                    // check if only the phonenum is entered
                } elseif (empty($_POST['newDocName']) && empty($_POST['newDocEmail']) && !empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newDocID']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) {    // user enters only a new phone number
                    $sql = "UPDATE users SET doc_phone =:newDocPhonenum WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocPhonenum", $newDocPhonenum);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                    echo "Number changed succesfully";
                    // check if only the address is entered
                } elseif (empty($_POST['newDocName']) && !empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && !empty($_POST['newPatAddress']) && empty($_POST['newDocID']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) {    //user enters only a new Address
                    $sql = "UPDATE users SET Patient_address =:newPatAddress WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatAddress", $newPatAddress);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                    echo "Address changed succesfully";
                } elseif (!empty($_POST['newDocID']) && empty($_POST['newDocName']) && empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) {
                    // check if only the PatientID is entered 
                    $sql = "UPDATE users SET id =:newDocID WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocID", $newDocID);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                } elseif (empty($_POST['newDocName']) && empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newDocID']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLname']) ) {   // check for fname only
                    $sql = "UPDATE users SET fname = :fname WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":fname", $newDocFName);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                    echo "Address changed succesfully fname only";
                } elseif (empty($_POST['newDocName']) && empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newDocID']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFname']) && !empty($_POST['newDocLname']) ) {   // check for lname only
                    $sql = "UPDATE users SET lname = :lname WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":lname", $newDocLName);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                    echo "Address changed succesfully lname only";
                } elseif (empty($_POST['newDocName']) && empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newDocID']) && empty($_POST['newPatDOB']) && !empty($_POST['newDocFname']) && !empty($_POST['newDocLname']) ) {   // check for fname&lname only
                    $sql = "UPDATE users SET fname = :fname, lname =:lname WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":fname", $newDocFName);
                    $stmt->bindValue(":lname", $newDocLName);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                    echo "Address changed succesfully fname&lane";
                } elseif (empty($_POST['newDocID']) && empty($_POST['newDocName']) && !empty($_POST['newDocEmail']) && !empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) {
                    // check if email&phonenumber
                    $sql = "UPDATE users SET doc_Email =:newDocEmail, doc_phone=:newDocPhonenum WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocEmail", $newDocEmail);
                    $stmt->bindValue(":newDocPhonenum", $newDocPhonenum);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                } elseif (empty($_POST['newDocID']) && !empty($_POST['newDocName']) && !empty($_POST['newDocEmail']) && empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) {
                    // check if email&name
                    $sql = "UPDATE users SET Email =:newDocEmail, username=:newDocName WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocEmail", $newDocEmail);
                    $stmt->bindValue(":newDocName", $newDocName);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                } elseif (empty($_POST['newDocID']) && !empty($_POST['newDocName']) && empty($_POST['newDocEmail']) && !empty($_POST['newDocPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB']) && empty($_POST['newDocFName']) && empty($_POST['newDocLName'])) {
                    // check if name&phonenumber
                    $sql = "UPDATE users SET doc_phone =:newDocPhonenum, username=:newDocName WHERE id =:docID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newDocPhonenum", $newDocPhonenum);
                    $stmt->bindValue(":newDocName", $newDocName);
                    $stmt->bindValue(":docID", $docID);
                    $stmt->execute();
                } else {
					echo "something went wrong";
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
                    Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.
                </footer>
            </div>
        </div>
</body>

</html>
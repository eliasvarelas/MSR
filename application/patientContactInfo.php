<?php // session_start and timeout function
session_start();
error_reporting(0);
$patientID = $_GET["patientid"];   // used to pass the patient id directly in the form
$patientNAME = $_GET["nm"]; // used to pass the pateint name directly in the form
$patientEmail = $_GET["em"]; // used to pass the pateints age directly in the form
$patientPhonenum = $_GET["phone"];
$patientAdr = $_GET['adr'];
$DOB = $_GET['dob'];
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
    <link rel="stylesheet" href="basicapp.css">

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
                    <a href="menu.php">
                        <i class="fas fa-home"></i>
                        Home
                    </a>

                </li>
                <li>
                    <a href="patientinfo-bootstrap.php">
                        <i class="fas fa-folder"></i>
                        Existing Patients
                    </a>


                </li>
                <!-- <li>
                    <a href="editPatientInfo.php">
                        <i class="fas fa-edit"></i>
                        Edit Patient Info
                    </a>
                </li> -->
                <!-- <li>
                    <a href="addpatient-bootstrap.php">
                        <i class="fas fa-user-plus"></i>
                        Add a new Patient
                    </a>
                </li>
                <li>
                    <a href="searching-bootstrap.php">
                        <i class="fas fa-search"></i>
                        Advanced Search
                    </a>
                </li>
                <li>
                    <a href="visual_analytics.php">
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

                <form action="patientContactInfo.php?qpatid=<?php echo $patientID ?>" method="POST">
                    <div class="container block">
                        <div class="split">
                            <div class="left bg-white">
                                <!-- modern stylign -->
                                <p>
                                    <h2>Old Information</h2>
                                </p>
                                <p>
                                    <label for="ID">Patient ID:</label>
                                    <input type="text" name="patID" value="<?php echo ($patientID ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="Name">Name:</label>
                                    <input type="text" value="<?php echo ($patientNAME ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="oldDOB">Date of Birth:</label>
                                    <input type="date" value="<?php echo ($DOB ?? "N/A"); ?>" disabled>
                                </p>
                                
                            </div>
                            <div class="right bg-white">
                                <p>
                                    <label for="Email">Email:</label>
                                    <input type="text" value="<?php echo ($patientEmail ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="Phone Number">Phone Number:</label>
                                    <input type="text" value="<?php echo ($patientPhonenum ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="old address">Address:</label>
                                    <input type="text" value="<?php echo ($patientAdr ?? "N/A"); ?>" disabled>
                                </p>
                            </div>
                            </table>
                        </div>
                    </div>
                    <!-- <button type="submit" name="submit" class="bttn">Submit</button> -->
                </div>
                
            </form>
            
            

    <?php
        //database connection
        $usersid = $_SESSION['user_id'];
        $servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        

    ?>
                <footer id="foo">
                    <div class="line"></div>
                    Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.
                </footer>
            </div>
        </div>

</body>

</html>
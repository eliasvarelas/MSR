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
                    <a href="/MSR/application/menu.php">
                        <i class="fas fa-home"></i>
                        Home
                    </a>

                </li>
                <li>
                    <a href="/MSR/application/patientinfo-bootstrap.php">
                        <i class="fas fa-folder"></i>
                        Existing Patients
                    </a>


                </li>
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

                <form action="editPatientForm.php?qpatid=<?php echo $patientID ?>" method="POST">
                    <div class="container block ">
                        <div class="split">
                            <div class="left w-100">
                                <!-- modern stylign -->
                                <p>
                                    <h2>Old Information</h2>
                                </p>
                                <p>
                                    <label for="ID">Patient ID*:</label>
                                    <input type="text" name="patID" value="<?php echo ($patientID ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                --------------------------------------------------------------
                                </p>
                                <p>
                                    <label for="Name">Patient Name:</label>
                                    <input type="text" value="<?php echo ($patientNAME ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="oldDOB">Date of Birth:</label>
                                    <input type="date" value="<?php echo ($DOB ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="Email">Patient Email:</label>
                                    <input type="text" value="<?php echo ($patientEmail ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="Phone Number">Patient Phone Number:</label>
                                    <input type="text" value="<?php echo ($patientPhonenum ?? "N/A"); ?>" disabled>
                                </p>
                                <p>
                                    <label for="old address">Patient Address:</label>
                                    <input type="text" value="<?php echo ($patientAdr ?? "N/A"); ?>" disabled>
                                </p>
                            </div>
                            <div class="right w-100">
                                
                                <p>
                                    <h3>New Information</h3>
                                </p>
                                <p>
                                    <label for="patid">Patient ID*:</label>
                                    <input type="number" name="newPatID" id="newPatID">
                                    <!-- <input type="checkbox" name="enable ID" id="enableID"> -->
                                </p>
                                <p>
                                    --------------------------------------------------------------
                                </p>
                                <p>
                                    <label for="patName">Patient Name:</label>
                                    <input type="text" name="newPatName" id="newPatName">
                                </p>
                                <p>
                                    <label for="DOB">Date of Birth:</label>
                                    <input type="date" name="newPatDOB" id="newPatDOB">
                                </p>
                                <p>
                                    <label for="patEmail">Patient Email:</label>
                                    <input type="email" name="newPatEmail" id="newPatEmail">
                                </p>
                                <p>
                                    <label for="phonenum">Patient Phone Number:</label>
                                    <input type="number" name="newPatPhonenum" id="newPatPhonenum">
                                </p>
                                <p>
                                    <label for="patAdd">Patient Address:</label>
                                    <input type="text" name="newPatAddress" id="newPatAddress">
                                </p>
                            </table>
                        </div>
                    </div>
                </div>
                <p>*If the new Patient ID field is used, all the other fields will be disabled</p>
                <button type="submit" name="submit" class="bttn centered">Submit</button>
                
            </form>
            
            

    <?php
        //database connection
        $usersid = $_SESSION['user_id'];
        $servername = "127.0.0.1";
        $username = "root";
        $password = "bioinformatics";
        $dbname = "BIHElab";
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // θέλουμε ιεραρχική δομή των if statements, αν είναι όλα isset -> nest if -> nest if etc etc....        
        try {
            if (isset($_POST['submit'])) {
                //get new info from the form
                $newPatID = $_POST['newPatID'];
                // $qPatID = $_POST['querypatientid']; // trying to fix the queries 
                $newPatName = $_POST['newPatName'];
                $newPatEmail = $_POST['newPatEmail'];
                $newPatPhonenum = $_POST['newPatPhonenum'];
                $newPatAddress = $_POST['newPatAddress'];
                $newPatDOB = $_POST['newPatDOB'];
                $qPatID = $_GET['qpatid'];

                
                

                // check if there is change on all the data
                if (!empty($_POST['newPatName']) && !empty($_POST['newPatEmail']) && !empty($_POST['newPatPhonenum']) && !empty($_POST['newPatAddress']) && empty($_POST['newPatID']) && !empty($_POST['newPatDOB'])) {
                    $sql = "UPDATE patients SET Email = :newPatEmail, Patient_name = :newPatName, Patient_Address = :newPatAddress, Phonenum = :newPatPhonenum, DOB = :newPatDOB WHERE Patient_id = :patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatEmail", $newPatEmail);
                    $stmt->bindValue(":newPatName", $newPatName);
                    $stmt->bindValue(":newPatAddress", $newPatAddress);
                    $stmt->bindValue(":newPatPhonenum", $newPatPhonenum);
                    $stmt->bindValue(":newPatDOB", $newPatDOB);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                    echo "Data changed succesfully";
                    // check if only the name is entered
                } elseif (!empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatID']) && empty($_POST['newPatDOB'])) { //user enters only a new Name
                    $sql = "UPDATE patients SET Patient_name =:newPatName WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatName", $newPatName);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                    echo "Name changed succesfully";
                    //check if only the email is entered
                } elseif (empty($_POST['newPatName']) && !empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatID']) && empty($_POST['newPatDOB'])) {    // user enters only a new email
                    $sql = "UPDATE patients SET Email =:newPatEmail WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatEmail", $newPatEmail);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                    echo "Email changed succesfully<br>";
                    // check if only the phonenum is entered
                } elseif (empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && !empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatID']) && empty($_POST['newPatDOB'])) {    // user enters only a new phone number
                    $sql = "UPDATE patients SET Phonenum =:newPatPhonenum WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatPhonenum", $newPatPhonenum);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                    echo "Number changed succesfully";
                    // check if only the address is entered
                } elseif (empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && !empty($_POST['newPatAddress']) && empty($_POST['newPatID']) && empty($_POST['newPatDOB'])) {    //user enters only a new Address
                    $sql = "UPDATE patients SET Patient_address =:newPatAddress WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatAddress", $newPatAddress);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                    echo "Address changed succesfully";
                } elseif (!empty($_POST['newPatID']) && empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB'])) {
                    // check if only the PatientID is entered 
                    $sql = "UPDATE patients SET Patient_id =:newPatID WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatID", $newPatID);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                } elseif (empty($_POST['newPatID']) && empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && !empty($_POST['newPatDOB'])) {
                    // check if DOB is entered 
                    $sql = "UPDATE patients SET DOB =:newPatDOB WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatDOB", $newPatDOB);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && empty($_POST['newPatName']) && !empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && !empty($_POST['newPatAddress']) && empty($_POST['newPatDOB'])) {
                    // check if email&address
                    $sql = "UPDATE patients SET Patient_address =:newPatAddress, Email=:newPatEmail WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatAddress", $newPatAddress);
                    $stmt->bindValue(":newPatEmail", $newPatEmail);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && empty($_POST['newPatName']) && !empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && !empty($_POST['newPatDOB'])) {
                    // check if email&dob
                    $sql = "UPDATE patients SET Email =:newPatEmail, DOB=:newPatDOB WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatEmail", $newPatEmail);
                    $stmt->bindValue(":newPatDOB", $newPatDOB);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && empty($_POST['newPatName']) && !empty($_POST['newPatEmail']) && !empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB'])) {
                    // check if email&phonenumber
                    $sql = "UPDATE patients SET Email =:newPatEmail, Phonenum=:newPatPhonenum WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatEmail", $newPatEmail);
                    $stmt->bindValue(":newPatPhonenum", $newPatPhonenum);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && !empty($_POST['newPatName']) && !empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB'])) {
                    // check if email&name - none of the rest
                    $sql = "UPDATE patients SET Email =:newPatEmail, Patient_name=:newPatName WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatEmail", $newPatEmail);
                    $stmt->bindValue(":newPatName", $newPatName);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && !empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && !empty($_POST['newPatAddress']) && empty($_POST['newPatDOB'])) {
                    // check if name&address
                    $sql = "UPDATE patients SET Patient_address =:newPatAddress, Patient_name=:newPatName WHERE Patient_id =:patientID";
                    // $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatAddress", $newPatAddress);
                    $stmt->bindValue(":newPatName", $newPatName);
                    $stmt->bindValue(":patientID", $qPatID);
                    // $stmt->execute();
                }elseif (empty($_POST['newPatID']) && !empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && !empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && empty($_POST['newPatDOB'])) {
                    // check if name&phonenumber
                    $sql = "UPDATE patients SET Phonenum =:newPatPhonenum, Patient_name=:newPatName WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatPhonenum", $newPatPhonenum);
                    $stmt->bindValue(":newPatName", $newPatName);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && !empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && !empty($_POST['newPatDOB'])) {
                    // check if name&dob
                    $sql = "UPDATE patients SET Patient_name =:newPatAddress, DOB=:newPatDOB WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatName", $newPatName);
                    $stmt->bindValue(":newPatDOB", $newPatDOB);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && !empty($_POST['newPatPhonenum']) && !empty($_POST['newPatAddress']) && empty($_POST['newPatDOB'])) {
                    // check if address&phonenum
                    $sql = "UPDATE patients SET Patient_address =:newPatAddress, Phonenum=:newPatPhonenum WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatAddress", $newPatAddress);
                    $stmt->bindValue(":newPatPhonenum", $newPatPhonenum);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }elseif (empty($_POST['newPatID']) && empty($_POST['newPatName']) && empty($_POST['newPatEmail']) && !empty($_POST['newPatPhonenum']) && empty($_POST['newPatAddress']) && !empty($_POST['newPatDOB'])) {
                    // check if phonenum&dob
                    $sql = "UPDATE patients SET Phonenum = :newPatPhonenum, DOB=:newPatDOB WHERE Patient_id =:patientID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(":newPatPhonenum", $newPatPhonenum);
                    $stmt->bindValue(":newPatDOB", $newPatDOB);
                    $stmt->bindValue(":patientID", $qPatID);
                    $stmt->execute();
                }

                // redirect to patientinfo
                $script = file_get_contents('redirectPatientsTable.js');
                echo "<script>".$script."</script>";
            }

                
            } catch (PDOException $e) {
                echo"<div class='error'>";
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
          echo "</div>";
        }

    ?>
                <footer>
                    <div class="line"></div>
                    Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.
                </footer>
            </div>
        </div>


        <script>
            var newPatID = document.getElementById('newPatID');
            function useValue(){

            
                var idValue = newPatID.value;

                var newPatName = document.getElementById('newPatName');
                var newPatDOB = document.getElementById('newPatDOB');
                var newPatEmail = document.getElementById('newPatEmail');
                var newPatPhonenum = document.getElementById('newPatPhonenum');
                var newPatAddress = document.getElementById('newPatAddress');

                if (idValue !== null){
                    newPatName.setAttribute('disabled',true);
                    newPatDOB.setAttribute('disabled',true);
                    newPatEmail.setAttribute('disabled',true);
                    newPatPhonenum.setAttribute('disabled',true);
                    newPatAddress.setAttribute('disabled',true);
                } else {
                    newPatName.setAttribute('disabled',false);
                    newPatDOB.setAttribute('disabled',false);
                    newPatEmail.setAttribute('disabled',false);
                    newPatPhonenum.setAttribute('disabled',false);
                    newPatAddress.setAttribute('disabled',false);
                }
                
                
                
                
            };
            newPatID.onchange = useValue;  
            // newPatID.onblur = useValue;
            
            
        </script>

</body>

</html>
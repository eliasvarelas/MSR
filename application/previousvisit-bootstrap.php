<?php
session_start();
error_reporting(0);
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
                <h3><a href="menu.php" id="logo">Multiple Sclerosis Registry</a></h3>
                <strong>MSR</strong>
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
                <li>
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
                    <a href="visual_analytics_google.php">
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
            <div class="container">
                <h2>Patient History</h2>
                <?php
                    $servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";

                    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    try {
                        $patientID = $_GET["id"]; // passes the id of the patient that was "clicked" in the patientsinfo.php table in order to get the right info
                        $visitID = 0;
                        
                        $sql = "SELECT * FROM MSR WHERE NDSnum = $patientID ORDER BY id DESC";
                        $result = $pdo->query($sql);
                        if ($result->rowCount() > 0 ) {
                            while ($row = $result->fetch()) { //make it with more html for responsiveness

                                    echo "<table class=''>";  // the MSR table for the particular patient id
                                    echo "<tr>";
                                        echo "<th>Name</th>";
                                        echo "<th>Date of Visit</th>";
                                        echo "<th>Patient Id</th>";
                                        echo "<th>Patient Address</th>";
                                        echo "<th>Gender</th>";
                                        echo "<th>Age</th>";
                                        echo "<th>Race</th>";
                                        echo "<th>Comorbidities</th>";
                                        echo "<th>MS Type NOW</th>";
                                        echo "<th>Conversion to SP</th>";
                                        echo "<th>Date of Diagnosis</th>";
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<td class='  '>" . $row['NDS'] . "</td>";
                                        echo "<td>" . $row['NDSdate'] . "</td>";
                                        echo "<td class='  '>" . $row['NDSnum'] . "</td>";
                                        echo "<td>" . ($row['address'] ?? "N/A") . "</td>";
                                        echo "<td class='  '>" . $row['Sex'] . "</td>";
                                        echo "<td>" . $row['Age'] . "</td>";
                                        echo "<td>" . $row['Race'] . "</td>";
                                        echo "<td class='  '>" . $row['Comorbidities'] . "</td>";
                                        echo "<td>" . $row['convsprad'] . "</td>";
                                        echo "<td class='  '>" . $row['convspnum'] . "</td>";
                                        echo "<td>" . $row['dateofdia'] . "</td>";
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<th>MS Type at Diagnosis</th>";
                                        echo "<th>No. of Relapses (RR)</th>";
                                        echo "<th>Severity</th>";
                                        echo "<th>Date of Past treatment (Start)</th>"; //check
                                        echo "<th>Past Medication</th>"; //check past
                                        echo "<th>Date of past treatment (End)</th>"; //not ok
                                        echo "<th>End of past treatment (Reason)</th>"; //not ok
                                        echo "<th>Date of Present Treatment</th>";
                                        echo "<th>Present Medication</th>";
                                        echo "<th>Current EDSS Score</th>";
                                        echo "<th>7.5 meters Timed walk </th>";
                                        
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<td>" . $row['dateofdiarad'] . "</td>";
                                        echo "<td class=''>" . $row['Noofrelapses'] . "</td>";
                                        echo "<td>" . $row['Noofrelapsesrad'] . "</td>";
                                        echo "<td class=''>" . ($row['pastTREATMENTstart']?? "N/A") . "</td>"; // check
                                        echo "<td>" . $row['pastTREATMENT'] . "</td>"; //check past
                                        echo "<td>" . $row['pastTREATMENTdate'] . "</td>"; //not ok
                                        echo "<td class=''>" . $row['pastTREATMENTcheck'] . "</td>";
                                        echo "<td>" . $row['TREATMENTdate'] . "</td>";
                                        echo "<td class='  '>" . $row['TREATMENT'] . "</td>";
                                        echo "<td>" . $row['eddsscore'] . "</td>";
                                        echo "<td class='  '>" . $row['edsstime7_5m']  . "</td>";
                                        
                                    
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<th>9-Hole PEG test</th>";
                                        echo "<th>Date of EDSS</th>"; //2 outputs
                                        echo "<th>Pregnant</th>";
                                        echo "<th>Date of Onset</th>";
                                        echo "<th>Onset Localisation</th>";
                                        echo "<th>Onset Symptoms</th>";
                                        echo "<th>Smoker</th>"; //3 outputs
                                        echo "<th>Number of Cigars</th>";
                                        echo "<th>Smoked Since</th>";
                                        echo "<th>MRI Onset Localisation</th>";
                                        echo "<th>CNS MRI Lesions Y/N </th>";
                                        
                                    
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<td>" . ($row['edsstimePEG'] ?? "N/A") . "</td>";
                                        echo "<td>" . $row['EDSSdate'] . '<br>' . $row['EDSSdaterad'] . "</td>";
                                        echo "<td>" . ($row['Pregnant'] ?? "N/A") . "</td>";
                                        echo "<td class='  '>" . $row['onsetdate'] . "</td>";
                                        echo "<td><span style='max-width:100px;'>" . ($row['Onsetlocalisation'] ?? "N/A") . "</span></td>";
                                        echo "<td>" . $row['onsetsymptoms'] . "</td>";
                                        echo "<td class='  '>" . ($row['smoker'] ?? "N/A") . "</td>";
                                        echo "<td>" . ($row['cigars'] ?? "N/A") . "</td>";
                                        echo "<td>" . ($row['cigardate'] ?? "N/A") . "</td>";
                                        echo "<td class='  '>" . ($row['MRIonsetlocalisation'] ?? "N/A") . "</td>";
                                        echo "<td>" . $row['MRIenhancing'] . "</td>";
                                        
                                    
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<th>CNS MRI Lesions No.</th>";
                                        echo "<th>CNS MRI Location</th>";
                                        echo "<th>Person Signing the form</th>";
                                        echo "<th colspan='9'>Documented at</th>";
                                    echo "</tr>";
                                    echo "<tr>";
                                        echo "<td class='  '>" . ($row['MRInum'] ?? "N/A") . "</td>";
                                        echo "<td>" . ($row['MRIenhancinglocation'] ?? "N/A") . "</td>";
                                        echo "<td class='  '>" . $row['signer'] . "</td>";
                                        echo "<td colspan='9'>" . $row['reg_date'] . "</td>";
                                    echo "</tr>";
                                echo "</table>";
                                echo "<div class='line'></div>";


                                    // echo "<div class='tier1-1'>";
                                    // echo "<div class='infoBox'>";
                                    //     echo "<label>Date of Visit: </label>";
                                    //     echo $row['NDSdate'];
                                    // echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Patient Name: </label>";
                                    //         echo $row['NDS'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Patient ID: </label>";
                                    //         echo $row['NDSnum'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Patient Address: </label>";
                                    //         echo ($row['Patient_address'] ?? "Ν/Α");
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Gender: </label>";
                                    //         echo $row['Sex'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Age: </label>";
                                    //         echo $row['Age'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Race: </label>";
                                    //         echo $row['Race'];
                                    //     echo "</div>";
                                    // echo "</div>";
                                    
                                    // echo "<div class='tier1-2'>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Comorbidities: </label>";
                                    //         echo $row['Comorbidities'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>MS Type Now: </label>";
                                    //         echo $row['convsprad'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Conversion to SP: </label>";
                                    //         echo $row['convspnum'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Date of Diagnosis: </label>";
                                    //         echo $row['dateofdia'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>MS Type at Diagnosis: </label>";
                                    //         echo $row[''];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Number of Relapses (RR): </label>";
                                    //         echo $row[''];
                                    //         echo "</div>";
                                    // echo "</div>";
                                    // echo "<div class='tier1-3'>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Severity: </label>";
                                    //         echo $row[''];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Start Date of Past Treatment: </label>";
                                    //         echo $row[''];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Stopped Medication: </label>";
                                    //         echo $row['pastTREATMENT'];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>End Date of Past Treatment: </label>";
                                    //         echo $row[''];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Start Date of Present Treatment: </label>";
                                    //         echo $row[''];
                                    //     echo "</div>";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Present Medication: </label>";
                                    //         echo $row['TREATMENT'];
                                    //     echo "</div>";
                                    // echo"</div>";
                                    // echo "<div class='tier2-1>'";
                                    //     echo "<div class='infoBox'>";
                                    //         echo "<label>Current EDSS Score: </label>";
                                    //         echo $row['eddsscore'];
                                    //     echo "</div>";
                                    // echo"</div>";
                                
                                
                                // echo "</div>";
                                
                                
                                // echo "<div class='marg-bot'><input type='text' value='some' hidden></div>";
                            
                ?>



<!-- <div class="line"></div> -->

<?php       }
                // add the visitID number to the db
                
                // Free result set
                unset($result);
            } else {   // basic error checking
                echo "No records matching your query were found.";
            }
            
            
        } catch (PDOException $e) {
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
        }
        ?>

        </div>
        <footer id="some">        
            <!-- <div class="line"></div> -->
            Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.
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
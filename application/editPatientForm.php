<?php // session_start and timeout function
session_start();
error_reporting(0);
$patientID = $_GET["id"];   // used to pass the patient id directly in the form
$patientNAME = $_GET["nm"]; // used to pass the pateint name directly in the form
$patientEmail = $_GET["em"]; // used to pass the pateints age directly in the form
$patientPhonenum = $_GET["phone"];
$patientAdr = $_GET['adr'];
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 18000)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    $scripttimedout = file_get_contents('timeout.js');
    echo "<script>" . $scripttimedout . "</script>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient Info</title>
    <link rel="stylesheet" href="basicapp.css">

</head>

<body>
    <div class="header text-center">
        <h1>Edit the Patients Information Below</h1>
    </div>
    <div class="editForm">
        <form action="editPatientForm.php" method="POST">
            <table class="w-100 text-center">
                <tr>
                    <th colspan="2">Previous Information</th>       <!-- -----------Old Info------------- -->
                </tr>
                <tr>
                    <td>Patient Name:</td>
                    <td><?php echo ($patientNAME ?? "N/A"); ?></td>
                </tr>
                <tr>
                    <td>Patient Email:</td>
                    <td><?php echo ($patientEmail ?? "N/A"); ?></td>
                </tr>
                <tr>
                    <td>Phone Number:</td>
                    <td><?php echo ($patientPhonenum ?? "N/A"); ?></td>
                </tr>
                <tr>
                    <td>Patients Address:</td>
                    <td><?php echo ($patientAdr ?? "N/A"); ?></td>
                </tr>
                <tr>
                    <th colspan="2">New Information</th>       <!-- -----------New Info------------- -->
                </tr>
                <tr>
                    <td>Patient Name:</td>
                    <td><input type="text" name="newPatName"></td>
                </tr>
                <tr>
                    <td>Patient Email:</td>
                    <td><input type="email" name="newPatEmail" id=""></td>
                </tr>
                <tr>
                    <td>Phone Number</td>
                    <td><input type="number" name="newPatPhonenum" id=""></td>
                </tr>
                <tr>
                    <td>Patient Address</td>
                    <td><input type="text" name="newPatAddress" id=""></td>
                </tr>
            </table>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>

    <?php
        //database connection
        $usersid = $_SESSION['user_id'];
        $servername = "127.0.0.1";
        $username = "root";
        $password = "bioinformatics";
        $dbname = "BIHElab";

        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //get new info from the form
        $newPatName = $_POST['newPatName'];
        $newPatEmail = $_POST['newPatEmail'];
        $newPatPhonenum = $_POST['newPatPhonenum'];
        $newPatAddress = $_POST['newPatAddress'];
        $submit = $_POST['submit'];

        if (isset($_POST['submit'])) {
            try{
                // $sql = "UPDATE " ;//query to update the info
                $result = $pdo->query($sql);
                          
            } catch(PDOException $e){
                die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            }
        }
        

    ?>
    

</body>

</html>
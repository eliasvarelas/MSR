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

  <title>MS Registry Searching Queries</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="basicapp.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <script src="searching.js"></script>

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
        <li>
          <a href="patientinfo-bootstrap.php">
            <i class="fas fa-user-plus"></i>
            Add a Doctor
          </a>


        </li>
        <!-- <li>
          <a href="/editPatientInfo.php">
            <i class="fas fa-edit"></i>
            Edit Patient Info
          </a>
        </li> -->
        <!-- <li>
          <a href="addpatient-bootstrap.php">
            <i class="fas fa-user-plus"></i>
            Add a new Patient
          </a>
        </li> -->
        <li class="">
            <a href="admin_searching.php" class="dropdown-toggle" ::after>
                <i class="fas fa-search"></i>
                Search Doctors
            </a>
        </li>
        <li class="active">
            <a href="admin_patient_searching.php">
                <i class="fas fa-search"></i>
                Advanced Patient Search
            </a>
        </li> 
        <li>
          <a href="">
            <i class="fas fa-chart-bar"></i>
            Visual Analytics Tool D3
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
      $usersid = $_SESSION['user_id'];
      $servername = "127.0.0.1";
      $username = "root";
      $password = "bioinformatics";
      $dbname = "BIHElab";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      try { ?>
        <form class="form" action="admin_patient_searching.php" method="post">
          <!-- Heading for explaining the following element to the user -->
          <h5 id="intro"> Please Enter the Name of the Patient You Are Looking For </h5>

          <!-- Advanced Searching API my ass!!! need to copy what i did for the second row in the main event as well!!! -->

          <table class="query_header" id="searching_query_table">
            <tr id="tablerow" class="">
              <th id="selectth">
                <select class="selection" name="Attributes" id="Attributes">
                  <!-- List of all the available attributes for the user to select for the searching queries -->
                  <option disabled>Options</option>
                  <option value="Name" id="p_Name">Name</option>
                  <option value="ID" id="p_Id">Patient ID</option>
                  <option value="Sex" id="p_Sex">Sex</option>
                  <option value="Email" id="p_Email">Patient Email</option>
                  <option value="Age" id="p_Age">Age ></option>
                  <option value="Agesmaller">Age < </option>
                  <option value="Race" id="p_Race">Race</option>
                  <option value="PhoneNumber" id="p_Phonenum">Phone Number</option>
                  <option value="Comorbidities" id="p_Comorbidities">Comorbidities</option>
                  <option value="EDSS" id="p_eddsscore">EDSS Score</option>
                  <option value="Pregnant" id="p_Pregnant">Is Pregnant</option>
                  <option value="Onsetlocalisation" id="p_Onsetlocalisation">Onset Localisation</option>
                  <option value="Smoker" id="p_Smoker">Is a Smoker</option>
                  <option value="onsetsymptoms" id="p_onsetsymptoms">Onset Symptoms</option>
                  <option value="MRIenhancing" id="p_MRIenhancing">MRI Enhancing Lesions</option>
                  <option value="MRInum" id="p_MRInum">MRI Lesion No.</option>
                  <option value="MRIonsetlocalisation" id="p_MRIonsetlocalisation">MRI Onset Localisation</option>
                </select>
              </th>

              <!-- using hidden table cells in order to create an advanced tool that only prints the neccesary input fields -->
              <!-- <tbody> -->
              <td id="inputBox" hidden>
                <!-- inputBox with joker role for text and number inputs -->
                <input type="text" name="srchoption" id="srchoption" placeholder=" Full Name">
              </td>
              <td id="Sex_td_male" hidden>
                <!-- shows Male / Female radio buttons for Sex entry -->
                <input type="radio" name="Sex_td" value="Male">Male
              </td>
              <td id="Sex_td_female" hidden>
                <input type="radio" name="Sex_td" value="Female">Female
                <!-- outputs 2 radio buttons for the available Sex -->
              </td>

              <td id="Race_td" hidden>
                <!-- gives all the available races to the user to select one -->
                <select name="Race_td">
                  <option value="American Indian">American Indian</option>
                  <option value="Asian">Asian</option>
                  <option value="Black">Black</option>
                  <option value="Hispanic">Hispanic</option>
                  <option value="Caucasian">Caucasian</option>
                  <option value="Unknown">Unknown</option>
                </select>
              </td>
              <td id="Comorbidities_td" hidden>
                <!-- pritns the list with the comorbidities -->
                <input type="text" list="Comorbidities" name="Comorbidities" placeholder=" Ex. Obesity" />
                <datalist id="Comorbidities">
                  <option value="Diabetes">Diabetes</option>
                  <option value="Obesity">Obesity</option>
                  <option value="Heart Disease">Heart Disease</option>
                  <option value="Renal Failure">Renal Failure</option>
                  <option value="Hepatic Failure">Hepatic Failure</option>
                  <option value="Dyslipidemia">Dyslipidemia</option>
                  <option value="Autoimmune">Autoimmune</option>
                </datalist> <!-- a datalist with the available Comorbidities -->
              </td>
              <td id="Pregnant_Smoker_td" hidden>
                <!-- shows yes / no radio buttons for Pregnant and Smoker-->
                <input type="radio" name="Pregnant_Smoker" value="Yes">Yes<br>
                <input type="radio" name="Pregnant_Smoker" value="No">No <br>
              </td>
              <td id="onsetsymptoms_td" hidden>
                <!-- allows the user to select from a specific enum of available data -->
                <input type="text" name="Onsetsymptoms" list="Onsetsymptoms" placeholder=" Ex. Vision" />
                <datalist id="Onsetsymptoms">
                  <option value="Vision">Vision</option>
                  <option value="Motor">Motor</option>
                  <option value="Sensory">Sensory</option>
                  <option value="Coordination">Coordination</option>
                  <option value="Bowel/Bladder">Bowel/Bladder</option>
                  <option value="Fatigue">Fatigue</option>
                  <option value="Cognitive">Cognitive</option>
                  <option value="Encephalopathy">Encephalopathy</option>
                  <option value="Other">Other</option>
                </datalist>
              </td>
              <td id="MRIonsetlocalisation_td" hidden>
                <!-- shows Specific values based on an enum -->
                <input type="text" name="MRIonsetlocalisation" list="MRIonsetlocalisation" placeholder=" Ex. Visual" />
                <datalist id="MRIonsetlocalisation">
                  <option value="Spinal">Spinal</option>
                  <option value="Cortex">Cortex</option>
                  <option value="Brainstem">Brainstem</option>
                  <option value="Cerebellum">Cerebellum</option>
                  <option value="Visual">Visual</option>
                </datalist>
              </td>
              <td id="Onsetlocalisation_td" hidden>
                <!-- allows the user to select from a specific enum of available data -->
                <input type="text" name="Onsetlocalisation" list="Onsetlocalisation" placeholder=" Ex. Visual" />
                <datalist id="Onsetlocalisation">
                  <option value="Spinal">Spinal</option>
                  <option value="Cortex">Cortex</option>
                  <option value="Brainstem">Brainstem</option>
                  <option value="Cerebellum">Cerebellum</option>
                  <option value="Visual">Visual</option>
                </datalist>
              </td>
              <td id="MRIenhancing_td" hidden>
                <input type="radio" name="MRIenhancing" value="Yes" id="MRIenhancing_radio">Yes
                <input type="radio" name="MRIenhancing" value="No" id="MRIenhancing_radio">No
              </td>
            </tr>
            <tr id="MRIenhancing_tr" hidden>
              <td id="MRIenhancing_td_extented" hidden>
                <input type="number" name="MRIenhancing_num" placeholder=" Enter the No. of Lesions">
                <input type="text" name="MRIenhancing_list" id="MRIenhancing_list" list="MRIenhancing_list" hidden />
                <datalist>
                  <option value="Spinal">Spinal</option>
                  <option value="Cortex">Cortex</option>
                  <option value="Brainstem">Brainstem</option>
                  <option value="Cerebellum">Cerebellum</option>
                  <option value="Visual">Visual</option>
                </datalist>
              </td>
            </tr>
            <tr id="Email_tr" hidden>
              <td id="Email_td" hidden>
                <input type="text" name="searchemail" id="searchingEmail">
              </td>
            </tr>
            </tbody>
          </table>

          <input type="submit" name="Searchbtn" value="Search">
          <button type="button" id="new_row_btn" onclick="addRow()" name="new_row">Add an extra row</button>
        </form>
        <div id="results" class="search-results"">
          <h3 id=" resultheader">
          </h3>


          <?php
          $option = $_POST['Attributes'];
          $entry = $_POST['srchoption'];

          //! ATTENTION TO DETAIL for the first attributes
          $sex_entry = $_POST['Sex_td'];
          $race_entry = $_POST['Race_td'];
          $Pregnant_Smoker_entry = $_POST['Pregnant_Smoker'];
          $Onsetsymptoms_entry = $_POST['Onsetsymptoms'];
          $Onsetlocalisation_entry = $_POST['Onsetlocalisation'];
          $MRIonsetlocalisation_entry = $_POST['MRIonsetlocalisation'];
          $Comorbidities_entry = $_POST['Comorbidities'];
          $email_entry = $_POST['searchemail'];




          // second row of attributes
          $new_row = $_POST['new_row']; // the button that adds the new row
          $newoption = $_POST['newAttributes']; // the new select

          //** the new input fields */
          $newName = $_POST['newName'];
          $newID = $_POST['newID'];
          $newSex = $_POST['newSex'];
          $newAge = $_POST['newAge'];
          $newAgesmaller = $_POST['newAgesmaller'];
          $newRace = $_POST['newRace'];
          $newEmail = $_POST['newEmail'];
          $newPhonenum = $_POST['newPhonenum'];
          $newComorbidities = $_POST['newComorbidities'];
          $newEDSS = $_POST['newEDSS'];
          $newPregnant = $_POST['newPregnant'];
          $newOnsetlocalisation = $_POST['newOnsetlocalisation'];
          $newSmoker = $_POST['newSmoker'];
          $newOnsetsymptoms = $_POST['newOnsetsymptoms'];
          $newMRIenhancing = $_POST['newMRIenhancing'];
          $newMRInum = $_POST['newMRInum'];
          $newMRIonsetlocalisation = $_POST['newMRIonsetlocalisation'];

          //** the variable that checks the AND/OR */
          $and_or = $_POST['querySelector'];

          // check if the form has been submited, if yes, validate the info and continue
          if (isset($_POST['Searchbtn'])) {
            //checks if the user has inputed any values to the second row of attributes. if the new inputs are empty it continues with only the previous ones.
            if (empty($newName) && empty($newID) && empty($newSex) && empty($newAge) && empty($newAgesmaller) && empty($newRace) && empty($newEmail) && empty($newPhonenum) && empty($newComorbidities) && empty($newEDSS) && empty($newPregnant) && empty($newOnsetlocalisation) && empty($newOnsetsymptoms) && empty($newSmoker) && empty($newOnsetsymptoms) && empty($newMRIenhancing) && empty($newMRInum) && empty($newMRIonsetlocalisation)) {

              if ($option == 'Name') {
                $sql = "SELECT * FROM patients WHERE Patient_name LIKE '%$entry%' ORDER BY Patient_id";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient Id</th>
                        <th>Patient Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB'] ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Name";
                }
              }
              if ($option == 'ID') {
                $sql = "SELECT * FROM patients WHERE Patient_id =$entry ORDER BY Patient_id";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <td> <?php echo $row['Patient_id'];?></td>
                        <td><?php echo $row['Patient_name']; ?></td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line">

                    </div>

                  <?php }
                } else {
                  echo "No patient exists with this information. ID";
                }
              }
              if ($option == 'Sex') {

                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Sex = '$sex_entry' ORDER BY Patient_id";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Sex</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['Sex']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
              if ($option == 'Age') {
                $sql = "SELECT * FROM patients WHERE timestampdiff(year,dob,curdate()) > '$entry' ORDER BY Patient_id";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient Id</th>
                        <th>Patient Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td><?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB'] ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Age";
                }
              }
              if ($option == 'Agesmaller') {
                $sql = "SELECT * FROM patients WHERE timestampdiff(year,dob,curdate()) < '$entry' ORDER BY Patient_id";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient Id</th>
                        <th>Patient Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB'] ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Age";
                }
              }
              if ($option == 'Race') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.reg_date,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Race = '$race_entry' ORDER BY Patient_id";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Race</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo $row['Race']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
              if ($option == 'PhoneNumber') {
                $sql = "SELECT * FROM patients WHERE Phonenum ='$entry%'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient Id</th>
                        <th>Patient Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB'] ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Phone";
                }
              }
              if ($option == 'Comorbidities') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Comorbidities = '$Comorbidities_entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Comorbidities</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['Comorbidities']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Comorbidities";
                }
              }
              if ($option == 'EDSS') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.eddsscore,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.eddsscore = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>EDSS Score 1-10</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['eddsscore']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. EDSS";
                }
              }
              if ($option == 'Pregnant') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Pregnant = '$Pregnant_Smoker_entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Is Pregnant? (Y/N)</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['Pregnant']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Pregnant";
                }
              }
              if ($option == 'Onsetlocalisation') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Onsetlocalisation = '$Onsetlocalisation_entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Onset Localisation</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['Onsetlocalisation']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Comorbidities";
                }
              }
              if ($option == 'Smoker') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.smoker = '$Pregnant_Smoker_entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Is a Smoker? (Y/N)</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['smoker']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. Smoker";
                }
              }
              if ($option == 'MRIenhancing') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.MRIenhancing = '$Pregnant_Smoker_entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>MRI Enhancing Lesions (Yes/No)</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['MRIenhancing']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
              if ($option == 'onsetsymptoms') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.onsetsymptoms = '$Onsetsymptoms_entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Onset Symptoms</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['onsetsymptoms']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
              if ($option == 'MRInum') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.MRInum = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>MRI Enhancing Lesions No.</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['MRInum']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <div class="line"></div>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
              if ($option == 'MRIonsetlocalisation') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>MRI Onset Localisation</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
              if ($option == 'Email') {
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.Email = '$email_entry' ORDER BY patients.Patient_id";
                $result = $pdo->query($sql);
                if ($result->rowCount() > 0) {
                  while ($row = $result->fetch()) { ?>
                    <table id="standard">
                      <tr>
                        <th>Doctor ID</th>
                        <th>Patient ID</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Phone Number</th>
                        <th>Date of Visit</th>
                        <th>Previous Visits</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <td><?php echo $row['Patient_id']; ?></td>
                        <td><?php echo $row['Email']; ?></td>
                        <td> <?php echo $row['Patient_name']; ?> </td>
                        <td><?php echo $row['DOB']; ?></td>
                        <td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                      </tr>
                    </table>
                    <?php }
                } else {
                  echo "No patient exists with this information. Email";
                }
              }
            } else {
              //** enter the queries for the second attributes here (works fine)

              if ($option == 'Name') {
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT * FROM patients WHERE Patient_name LIKE '%$entry%' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <!-- <th>Date of Visit</th> -->
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>

                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Sex = '$sex_entry' AND patients.Patient_name LIKE '%$entry%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Sex";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.Email = '$newEmail' AND patients.Patient_name LIKE '%$entry%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) { // something here doesnt work....
                  $sql = "SELECT * FROM patients WHERE timestampdiff(year,dob,curdate()) > '$newAge' AND patients.Patient_name LIKE '%$entry%' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <!-- <th>Date of Visit</th> -->
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>

                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) { // something here doesnt work....
                  $sql = "SELECT * FROM patients WHERE timestampdiff(year,dob,curdate()) < '$entry' AND patients.Patient_name LIKE '%$entry%' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>

                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>

                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Agesmaller";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Race = '$race_entry' AND patients.Patient_name LIKE '%$entry%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Race";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients WHERE Phonenum ='$entry%' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>

                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>

                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Comorbidities = '$Comorbidities_entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.eddsscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.eddsscore = '$entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Pregnant = '$Pregnant_Smoker_entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.smoker = '$Pregnant_Smoker_entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.MRInum = '$entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND patients.Patient_name LIKE '%$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'ID') {
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = $entry AND MSR.Sex = '$newSex' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>

                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Sex";
                  }
                }
                if ($newoption == 'Name' && !empty($newName)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = $entry AND Patient_name LIKE '%$newName%' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Name";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = $entry AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) { // something here doesnt work....
                  $sql = "SELECT * FROM patients WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND patients.Patient_id = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) { // something here doesnt work....
                  $sql = "SELECT * FROM patients WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND patients.Patient_id = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  echo "Age smaller";
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.Race = '$newRace' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Race";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients WHERE Patient_id = '$entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.Comorbidities = '$newComorbidities'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.eddsscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>  
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onset symptoms";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = '$entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+MRIonset";
                  }
                }
              }
              if ($option == 'Sex') {
                if ($newoption == 'Name' && !empty($newName)) { //somethings not right right now...
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.Sex = '$sex_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Name";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Sex = '$sex_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.Sex = '$sex_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Sex</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.Sex = '$sex_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Sex</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Age<";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.Race,MSR.reg_date FROM patients join MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.Race = '$newRace' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <!-- <div class="line"></div> -->
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Race";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.Comorbidities = '$newComorbidities'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date,MSR.eddsscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid ORDER BY patients.Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Sex,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Sex = '$sex_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Sex,MSR.NDSnum,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Sex = '$sex_entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+Onset symp";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>\
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Sex = '$sex_entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Sex+MRIonset";
                  }
                }
              }
              if ($option == 'Age') { 
                if ($newoption == 'Name' && !empty($newName)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.(timestampdiff(year,dob,curdate()) > '$entry') AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>

                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>

                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age + Name";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.(timestampdiff(year,dob,curdate()) > '$entry') AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age+Email";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.Sex = '$newSex' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Sex</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age<+Sex";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients join MSR on patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND (timestampdiff(year,dob,curdate()) < '$newAgesmaller') AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <!-- <th>Sex</th> -->
                          <th>DOB</th>
                          <th>Date/Time of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <!-- <td><?php //echo $row['Sex']; 
                                    ?></td> -->
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age>+Age<";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.Race = '$newRace' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age+Race";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.Comorbidities = '$newComorbidities'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Sex,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.(timestampdiff(year,dob,curdate()) > '$entry') AND MSR.Pregnant = '$newPregnant' ORDER BY patients.Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Sex,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.(timestampdiff(year,dob,curdate()) > '$entry') AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$entry') AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'Agesmaller') { 
                if ($newoption == 'Name' && !empty($newName)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.(timestampdiff(year,dob,curdate()) < '$entry') AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>

                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Sex";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.(timestampdiff(year,dob,curdate()) < '$entry') AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND (timestampdiff(year,dob,curdate()) < '$entry') AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Sex</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.Sex = '$newSex' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Sex</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Age<+Sex";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.Race = '$newRace' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Race";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.Comorbidities = '$newComorbidities'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Sex,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.(timestampdiff(year,dob,curdate()) < '$entry') AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Sex,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.(timestampdiff(year,dob,curdate()) < '$entry') AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$entry') AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'Race') {
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.Race = '$race_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Race";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Race = '$race_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.Race = '$race_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.Race = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.reg_date,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.Sex = '$newSex' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Race";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.Comorbidities = '$newComorbidities'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.eddsscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Race,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Race = '$race_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Race,MSR.NDSnum,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Race = '$race_entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$race_entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'PhoneNumber') {
                if ($newoption == 'Name' && !empty($newName)) { //somethings not right right now...
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.patients.Phonenum = '$entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Race";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Race = '$entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Race = '$entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.Race = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.Race = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  // $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND  AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Race";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.Sex ='$newSex'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.Comorbidities = '$newComorbidities'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Race,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.patients.Phonenum = '$entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Race,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.patients.Phonenum = '$entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Race</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Race FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Phonenum = '$entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>z
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'Comorbidities') {
                if ($newoption == 'Name' && !empty($newName)) { //somethings not right right now...
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.Comorbidities = '$Comorbidities_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Comorbidities";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Comorbidities = '$entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.Comorbidities = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Comorbidities</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.Comorbidities = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Comorbidities</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.Comorbidities,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$Comorbidities_entry' AND MSR.Sex = '$newSex' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Comorbidities";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Comorbidities' && !empty($newComorbidities)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND MSR.Comorbidities = '$newComorbidities'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Comorbidities";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Comorbidities,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Comorbidities = '$entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Comorbidities,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Comorbidities = '$entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Comorbidities</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Comorbidities']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Comorbidities FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Comorbidities = '$entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'EDSS') {
                if ($newoption == 'Name' && !empty($newName)) { //somethings not right right now...
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.edssscore = '$entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>edssscore</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+edssscore";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.edssscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.edssscore = '$entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.edssscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.edssscore = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>edssscore</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.edssscore = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>edssscore</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'edssscore' && !empty($newedssscore)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.edssscore,MSR.edssscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.edssscore = '$newedssscore' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>edssscore</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+edssscore";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'edssscore' && !empty($newedssscore)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.edssscore,MSR.edssscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.edssscore = '$newedssscore'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>edssscore</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+edssscore";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.edssscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.Sex = '$newSex' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.edssscore,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.edssscore = '$entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>edssscore</th>
                          <th>Is Pregnant? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.edssscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>edssscore</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.edssscore,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.edssscore = '$entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>edssscore</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.edssscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.edssscore FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.edssscore = '$entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'Pregnant') {
                if ($newoption == 'Name' && !empty($newName)) { //somethings not right right now...
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.Pregnant = '$entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Pregnant";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Pregnant = '$entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.Pregnant = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Pregnant</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.Pregnant = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Pregnant</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'edssscore' && !empty($newedssscore)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.edssscore,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.edssscore = '$newedssscore' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS</th>
                          <th>Pregnant</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Pregnant";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant,MSR.Pregnant,patients.Doctor_ID,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Pregnant = '$Pregnant_Smoker_entry' AND MSR.Sex = '$newSex'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Pregnant";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Pregnant,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Pregnant = '$entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Pregnant = '$entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'Onsetlocalisation') { 
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onsetlocalisation";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onsetlocalisation</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onsetlocalisation</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'edssscore' && !empty($newedssscore)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.edssscore,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.edssscore = '$newedssscore' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS</th>
                          <th>Onsetlocalisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['edssscore']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.Sex = '$newSex'"; 
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Onsetlocalisation+Sex";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Race,MSR.reg_date,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.Race = '$newRace'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Pregnant FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Onsetlocalisation,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'Smoker') {
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.smoker = '$Pregnant_Smoker_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>smoker</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+smoker";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.smoker = '$Pregnant_Smoker_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Smoker</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.smoker = '$Pregnant_Smoker_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Smoker</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.smoker = '$Pregnant_Smoker_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Smoker</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.smoker = '$Pregnant_Smoker_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Smoker</th>
                          <th>DOB</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Phone Number";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.Sex = '$newSex'"; 
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Onsetlocalisation+Sex";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Race,MSR.reg_date,patients.Doctor_ID FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.Race = '$newRace'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Onsetlocalisation FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.Onsetlocalisation = '$Onsetlocalisation_entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onsetlocalisation</th>
                          <th>Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Pregnant,MSR.NDSnum FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.smoker = '$Pregnant_Smoker_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Is a Smoker? (Y/N)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.smoker = '$Pregnant_Smoker_entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Smoker</th>
                          <th>Onset Symptoms</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.smoker FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.smoker = '$Pregnant_Smoker_entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.smoker FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.smoker = '$Pregnant_Smoker_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.smoker FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.smoker = '$Pregnant_Smoker_entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'MRIenhancing') {
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date fo Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRIenhancing+Name";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) { // something is wrong, revisit this query
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRI Enhancing Lesions + Phonenum";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.Sex = '$newSex'"; 
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Onsetlocalisation+Sex";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.eddsscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.Race,MSR.reg_date,patients.Doctor_ID,MSR.MRIenhancing FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.Race = '$newRace' ORDER BY patients.Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Pregnant,MSR.NDSnum,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'Onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.smoker,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Smoker</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.MRIenhancing,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIenhancing = '$Pregnant_Smoker_entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'onsetsymptoms') {
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Date fo Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRIenhancing+Name";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onset Symptoms</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onset Symptoms</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onset Symptoms</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) { // something is wrong, revisit this query
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRI Enhancing Lesions + Phonenum";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.Sex = '$newSex'"; 
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Onsetlocalisation+Sex";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.eddsscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Race,MSR.reg_date,patients.Doctor_ID,MSR.MRIenhancing FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.Race = '$newRace' ORDER BY patients.Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Pregnant,MSR.NDSnum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Onset Symptoms</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.reg_date,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.smoker,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions (Yes/No)</th>
                          <th>Smoker</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.onsetsymptoms = '$Onsetsymptoms_entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'MRInum') {
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.MRInum = '$entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date fo Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRIenhancing+Name";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRInum = '$entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Number of MRI Lesions</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.MRInum = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Number of MRI Lesions</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.MRInum = '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Number of MRI Lesions</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) { // something is wrong, revisit this query
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRI Enhancing Lesions + Phonenum";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND MSR.Sex = '$newSex'"; 
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Number of MRI Lesions</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Onsetlocalisation+Sex";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.eddsscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Race,MSR.reg_date,patients.Doctor_ID,MSR.MRIenhancing FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRInum = '$entry' AND MSR.Race = '$newRace' ORDER BY patients.Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Number of MRI Lesions</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Pregnant,MSR.NDSnum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRInum = '$entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.reg_date,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.smoker,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Number of MRI Lesions</th>
                          <th>Smoker</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND MSR.onsetsymptoms = '$newOnsetsymptoms'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Symptoms</th>
                          <th>Number of MRI Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRInum = '$entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Number of MRI Lesions </th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'Email') {
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.patients.Email = '$email_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <!-- <th>Onset Symptoms</th> -->
                          <th>Date fo Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRIenhancing+Name";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.patients.Email = '$email_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <!-- <th>Onset Symptoms</th> -->
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onset Symptoms</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND patients.Email = '$email_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND patients.Email = '$email_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) { // something is wrong, revisit this query
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRI Enhancing Lesions + Phonenum";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND MSR.Sex = '$newSex'"; 
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <td><?php echo $row['Doctor_ID']; ?></td>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Onsetlocalisation+Sex";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.eddsscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>EDSS Score 1-10</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['eddsscore']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Race,MSR.reg_date,patients.Doctor_ID,MSR.MRIenhancing FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.patients.Email = '$email_entry' AND MSR.Race = '$newRace' ORDER BY patients.Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>Email</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Pregnant,MSR.NDSnum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.patients.Email = '$email_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Pregnant</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.reg_date,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.smoker,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>Smoker</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'MRIonsetlocalisation' && !empty($newMRIonsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Email = '$email_entry' AND MSR.MRIonsetlocalisation = '$newMRIonsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
              if ($option == 'MRIonsetlocalisation') {
                if ($newoption == 'Name' && !empty($newName)) { 
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND patients.Patient_name LIKE '$newName%' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <!-- <th>Onset Symptoms</th> -->
                          <th>Date fo Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRIenhancing+Name";
                  }
                }
                if ($newoption == 'ID' && !empty($newID)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND Patient_id = '$newID' ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient Id</th>
                          <th>Patient Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <!-- <th>Onset Symptoms</th> -->
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+ID";
                  }
                }
                if ($newoption == 'onsetsymptoms' && !empty($newOnsetsymptoms)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND patients.Email = '$newEmail' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Onset Symptoms</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['onsetsymptoms']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Email";
                  }
                }
                if ($newoption == 'Age' && !empty($newAge)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) > '$newAge') AND MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'Agesmaller' && !empty($newAgesmaller)) {
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE (timestampdiff(year,dob,curdate()) < '$newAge') AND MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>DOB</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Age";
                  }
                }
                if ($newoption == 'PhoneNumber' && !empty($newPhonenum)) { // something is wrong, revisit this query
                  $sql = "SELECT * FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND Phonenum ='$newPhonenum%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. MRI Enhancing Lesions + Phonenum";
                  }
                }
                if ($newoption == 'Sex' && !empty($newSex)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Sex,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.Sex = '$newSex'"; 
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>Sex</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['Sex']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Onsetlocalisation+Sex";
                  }
                }
                if ($newoption == 'EDSS' && !empty($newEDSS)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.eddsscore,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.eddsscore = '$newEDSS' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+EDSS";
                  }
                }
                if ($newoption == 'Race' && !empty($newRace)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.Race,MSR.reg_date,patients.Doctor_ID,MSR.MRIenhancing FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.Race = '$newRace' ORDER BY patients.Patient_id";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Race</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Race']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. ID+Onsetlocalisation";
                  }
                }
                if ($newoption == 'Onsetlocalisation' && !empty($newOnsetlocalisation)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.Onsetlocalisation = '$newOnsetlocalisation'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['Onsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+OnsetLocalisation";
                  }
                }
                if ($newoption == 'Pregnant' && !empty($newPregnant)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker,MSR.Pregnant,MSR.NDSnum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE patients.Patient_id = MSR.NDSnum AND patients.MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.Pregnant = '$newPregnant'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>Pregnant</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['Pregnant']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Smoker";
                  }
                }
                if ($newoption == 'MRIenhancing' && !empty($newMRIenhancing)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing,MSR.reg_date,MSR.onsetsymptoms FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.MRIenhancing = '$newMRIenhancing'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>MRI Enhancing Lesions</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['MRIenhancing']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+Onset";
                  }
                }
                if ($newoption == 'Smoker' && !empty($newSmoker)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms,MSR.smoker,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.smoker = '$newSmoker'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>Smoker</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['smoker']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRI enhancing";
                  }
                }
                if ($newoption == 'MRInum' && !empty($newMRInum)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND MSR.MRInum = '$newMRInum'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>MRI Onset Localisation</th>
                          <th>MRI Enhancing Lesions No.</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['MRInum']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                      <div class="line"></div>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRInum";
                  }
                }
                if ($newoption == 'Email' && !empty($newEmail)) {
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation,MSR.onsetsymptoms,MSR.reg_date FROM patients JOIN  MSR ON patients.Patient_id = MSR.NDSnum WHERE MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry' AND patients.Email = '$newEmail'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount() > 0) {
                    while ($row = $result->fetch()) { ?>
                      <table id="standard">
                        <tr>
                            <th>Doctor ID</th>
                          <th>Patient ID</th>
                          <th>Name</th>
                          <th>Date of Birth</th>
                          <th>Phone Number</th>
                          <th>Email</th>
                          <th>MRI Onset Localisation</th>
                          <th>Date of Visit</th>
                          <th>Previous Visits</th>
                        </tr>
                        <tr>
                            <td><?php echo $row['Doctor_ID']; ?></td>
                          <td><?php echo $row['Patient_id']; ?></td>
                          <td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB']; ?></td>
                          <td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td>
                          <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                          <td><?php echo $row['reg_date']; ?></td>
                          <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Name+MRIonset";
                  }
                }
              }
            }
          }
        } catch (PDOException $e) {
          echo"<div class='error'>";
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
          echo "</div>";
        }
        ?>
        </div>
        <!-- basic footer -->
        <footer>
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

  <script type="text/javascript">
    var inputBox = document.getElementById('inputBox').hidden = false;
    Sex_td_male.hidden = true;
    Sex_td_female.hidden = true;

    var sele = document.getElementById('selectth').onchange = function inputBoxChange() {

      // get all the elements from the DOM
      var srchoption = document.getElementById('srchoption');
      var introParagraph = document.getElementById('intro');
      var attr = document.getElementById('Attributes');
      var inputBox = document.getElementById('inputBox');
      var Race_td = document.getElementById('Race_td');
      var Sex_td_male = document.getElementById('Sex_td_male');
      var Sex_td_female = document.getElementById('Sex_td_female');
      var Comorbidities_td = document.getElementById('Comorbidities_td');
      var Pregnant_Smoker_td = document.getElementById('Pregnant_Smoker_td');
      var onsetsymptoms_td = document.getElementById('onsetsymptoms_td');
      var MRIonsetlocalisation_td = document.getElementById('MRIonsetlocalisation_td');
      var Onsetlocalisation_td = document.getElementById('Onsetlocalisation_td');
      var MRIenhancing_td = document.getElementById('MRIenhancing_td');
      var mriRadio = document.getElementById('MRIenhancing_radio');
      var MRIenhancing_tr = document.getElementById('MRIenhancing_tr');
      var MRIenhancing_td_extented = document.getElementById('MRIenhancing_td_extented');
      var MRIenhancing_num = document.getElementById('MRIenhancing_num');
      var MRIenhancing_list = document.getElementById('MRIenhancing_list');
      var email_tr = document.getElementById('Email_tr');
      var email_td = document.getElementById('Email_td');

      if (attr.value == 'ID') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', " Patient ID");
        introParagraph.innerHTML = "Enter the ID of the Patient You Are Looking for ";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'Sex') {
        introParagraph.innerHTML = "Enter the Sex of the Patient You Are Looking for ";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = false;
        Sex_td_female.hidden = false;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

      } else if (attr.value == 'Smoker') {
        introParagraph.innerHTML = "Enter if the Patient is a Smoker or Not";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = false;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

      } else if (attr.value == 'Name') {
        srchoption.type = 'text';
        srchoption.setAttribute('placeholder', " Full Name");
        introParagraph.innerHTML = "Enter the Name of the Patient You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

      } else if (attr.value == 'Race') {
        introParagraph.innerHTML = "Enter the Race of the Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = false;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

      } else if (attr.value == 'Comorbidities') {
        introParagraph.innerHTML = "Enter Any Comorbidities the Patient You Are Looking For May Have";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = false;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

      } else if (attr.value == 'Pregnant') {
        introParagraph.innerHTML = "Enter if the Patient is Pregnant or Not";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = false;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

      } else if (attr.value == 'Onsetlocalisation') {
        introParagraph.innerHTML = "Enter The Onset Localisation of The Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = false;

      } else if (attr.value == 'onsetsymptoms') {
        introParagraph.innerHTML = "Enter Any Onset Symptoms of The Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = false;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'MRIonsetlocalisation') {
        introParagraph.innerHTML = "Enter The MRI Onset Localisation of the Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = false;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'MRInum') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' MRI Lesions');
        introParagraph.innerHTML = "Enter The Number of MRI Lesions That The Patient You Are Looking For Has";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'PhoneNumber') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' Phone Number');
        introParagraph.innerHTML = "Enter The Phone Number of The Patient You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'MRIenhancing') {
        introParagraph.innerHTML = "Enter If the Patient Had Enhancing Lesions in His MRI";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = false;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'Age') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' Age > than');
        introParagraph.innerHTML = "Enter The Lower Age Threshold of The Patients You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'EDSS') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' EDSS Score');
        srchoption.setAttribute('maxlength', '2');
        introParagraph.innerHTML = "Enter The EDSS Score of The Patient You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


      } else if (attr.value == 'Agesmaller') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' Age < than');
        introParagraph.innerHTML = "Enter The Higher Age Threshold of The Patients You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

      } else if (attr.value == 'MRInum') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' No. of Lesions');
        introParagraph.innerHTML = "Enter the No. of Lesions the Patient You Are Looking For Had";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;
        MRIenhancing_td.hidden = true;
      } else if (attr.value == 'Email') {
        srchoption.type = 'email';
        srchoption.setAttribute('placeholder', ' Email');
        introParagraph.innerHTML = "Enter the Email of the Patient You Are Looking for";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;
        MRIenhancing_td.hidden = true;
        email_tr.hidden = false;
        email_td.hidden = false;
      }
    }

    function addRow() {
      // hide the button
      document.getElementById('new_row_btn').hidden = true;

      // counters to keep track of the previous attributes clicked


      // **  counter that finds the attributed that was selected on the first round
      var prevAtt = document.getElementById('Attributes');
      var prevAttval = prevAtt.options[prevAtt.selectedIndex].value;
      // works fine

      var table = document.getElementById("searching_query_table");

      // creates the row for the inputbox header
      var hrow = table.insertRow(2);

      // formats the table a bit nicer
      var col = document.getElementById('selectth');
      // col.setAttribute('colspan', '2');

      var col1 = document.getElementById('inputBox');
      // col1.setAttribute('colspan', '2');

      //creates a new tb row that needs to have the next header, and a new row that will have the input field
      var headCell = hrow.insertCell(0);
      var headerCell = document.createElement('th');
      headCell.id = 'newhCell';
      // creates the header for the second info box
      // headCell.innerHTML = "More Info"; 

      // create the row for the inputbox
      // var crow = table.insertRow(3);

      // create the second input field
      var cell = hrow.insertCell(1);
      cell.id = 'newInputCell';
      var newInputBox = document.createElement('input');



      var select = document.createElement('select');
      select.setAttribute('id', 'newSelect');
      select.setAttribute('name', 'newAttributes');


      // the options in the new select attributes row
      var op1 = document.createElement('option');
      op1.value = 'Options';
      op1.innerHTML = 'Select an Option from Below';

      select.appendChild(op1);

      var op2 = document.createElement('option');
      op2.value = 'Name';
      op2.setAttribute('id', 'newInput');
      op2.innerHTML = 'Name';
      select.appendChild(op2);

      var op3 = document.createElement('option');
      op3.value = 'ID';
      op3.setAttribute('id', 'newInput');
      op3.innerHTML = 'Patient ID';
      select.appendChild(op3);

      var op4 = document.createElement('option');
      op4.value = 'Sex';
      op4.setAttribute('id', 'newInput');
      op4.innerHTML = 'Sex';
      select.appendChild(op4);

      var op5 = document.createElement('option');
      op5.value = 'Email';
      op5.setAttribute('id', 'newInput');
      op5.innerHTML = 'Patient Email';
      select.appendChild(op5);

      var op6 = document.createElement('option');
      op6.value = 'Age';
      op6.setAttribute('id', 'newInput');
      op6.innerHTML = 'Age >';
      select.appendChild(op6);

      var op7 = document.createElement('option');
      op7.value = 'Agesmaller';
      op7.setAttribute('id', 'newInput');
      op7.innerHTML = 'Age <';
      select.appendChild(op7);

      var op8 = document.createElement('option');
      op8.value = 'Race';
      op8.setAttribute('id', 'newInput');
      op8.innerHTML = 'Race';
      select.appendChild(op8);

      var op9 = document.createElement('option');
      op9.value = 'PhoneNumber';
      op9.setAttribute('id', 'newInput');
      op9.innerHTML = 'Phone Number';
      select.appendChild(op9);

      var op10 = document.createElement('option');
      op10.value = 'Comorbidities';
      op10.setAttribute('id', 'newInput');
      op10.innerHTML = 'Comorbidities';
      select.appendChild(op10);

      var op11 = document.createElement('option');
      op11.value = 'EDSS';
      op11.setAttribute('id', 'newInput');
      op11.innerHTML = 'EDSS Score';
      select.appendChild(op11);

      var op12 = document.createElement('option');
      op12.value = 'Pregnant';
      op12.setAttribute('id', 'newInput');
      op12.innerHTML = 'Is Pregnant';
      select.appendChild(op12);

      var op13 = document.createElement('option');
      op13.value = 'Onsetlocalisation';
      op13.setAttribute('id', 'newInput');
      op13.innerHTML = 'Onset Localisation';
      select.appendChild(op13);

      var op14 = document.createElement('option');
      op14.value = 'Smoker';
      op14.setAttribute('id', 'newInput');
      op14.innerHTML = 'Is a Smoker';
      select.appendChild(op14);

      var op15 = document.createElement('option');
      op15.value = 'onsetsymptoms';
      op15.setAttribute('id', 'newInput');
      op15.innerHTML = 'Onset Symptoms';
      select.appendChild(op15);

      var op16 = document.createElement('option');
      op16.value = 'MRIenhancing';
      op16.setAttribute('id', 'newInput');
      op16.innerHTML = 'MRI Enhancing Lesions';
      select.appendChild(op16);

      var op17 = document.createElement('option');
      op17.value = 'MRInum';
      op17.setAttribute('id', 'newInput');
      op17.innerHTML = 'MRI Lesion No.';
      select.appendChild(op17);

      var op18 = document.createElement('option');
      op18.value = 'MRIonsetlocalisation';
      op18.setAttribute('id', 'newInput');
      op18.innerHTML = 'MRI Onset Localisation';
      select.appendChild(op18);

      //**  add the functionality to perform queries either with the AND portal or the OR portal
      // var queryHeadCell = hrow.insertCell(1);
      // var queryHead = document.createElement('th');
      // queryHead.innerHTML = 'Add with AND / OR';
      // queryHead.classList.toggle('text-center');
      // // queryHeadCell.appendChild(queryHead);
      // hrow.appendChild(queryHead);

      // var queryCell = crow.insertCell(1);
      // var andorSelect = document.createElement('select');
      // andorSelect.setAttribute('name','querySelector');
      // andorSelect.setAttribute('selected',true);
      // andorSelect.setAttribute('id','querySelector');

      // var queryOpt = document.createElement('option');
      // queryOpt.value = 'AND';
      // queryOpt.innerHTML = 'AND';
      // andorSelect.appendChild(queryOpt);

      // var queryOpt1 = document.createElement('option');
      // queryOpt1.value = 'OR';
      // queryOpt1.innerHTML = 'OR';
      // andorSelect.appendChild(queryOpt1);

      // queryCell.appendChild(andorSelect);

      select.addEventListener("change", function changeSelect() {
        //todo needs to remove the fields of the previous boxes after changing to a new select value
        // todo if the select.value!= something, somethingInputBox=hidden try changing the newInputBox for a new var in each if statement...



        if (select.value == 'Name') {
          var newNameBox = document.createElement('input');
          newNameBox.type = "text";
          newNameBox.setAttribute('name', 'newName');
          newNameBox.setAttribute('placeholder', 'Name');
          newNameBox.setAttribute('id', 'newNameBox');
          cell.appendChild(newNameBox);

        } else if (select.value == 'ID') {
          var newIdBox = document.createElement('input');
          newIdBox.type = "text";
          newIdBox.setAttribute('name', 'newID');
          newIdBox.setAttribute('placeholder', 'Patient ID');
          newIdBox.setAttribute('id', 'newIdBox');
          cell.appendChild(newIdBox);
        } else if (select.value == 'Sex') {
          var newMaleRadio = document.createElement('input');
          newMaleRadio.type = "radio";
          newMaleRadio.setAttribute('name', 'newSex');
          newMaleRadio.setAttribute('id', 'newMaleRadio');
          // creates a label for the button
          var sexLabel = document.createElement('label');
          sexLabel.setAttribute('for', 'Male');
          sexLabel.setAttribute('id', 'sexLabel');
          sexLabel.innerHTML = ": Male";
          newMaleRadio.setAttribute('value', 'Male');
          // creates the new radio button for the female value
          var newFemaleRadio = document.createElement('input');
          newFemaleRadio.type = "radio";
          newFemaleRadio.setAttribute('name', 'newSex');
          newFemaleRadio.setAttribute('value', 'Female');
          newFemaleRadio.setAttribute('id', 'newFemaleRadio');
          // creates a label for the button
          var sexLabel1 = document.createElement('label');
          sexLabel1.setAttribute('for', 'Female');
          sexLabel1.setAttribute('id', 'sexLabel1');
          sexLabel1.innerHTML = ": Female";

          // appends the elements to the table cell
          cell.appendChild(newMaleRadio);
          cell.appendChild(sexLabel);
          cell.appendChild(newFemaleRadio);
          cell.appendChild(sexLabel1);

        } else if (select.value == 'Email') {
          var newEmailBox = document.createElement('input');
          newEmailBox.type = "email";
          newEmailBox.setAttribute('name', 'newEmail')
          newEmailBox.setAttribute('placeholder', 'Email');
          newEmailBox.setAttribute('id', 'newEmailBox');
          cell.appendChild(newEmailBox);
        } else if (select.value == 'Age') {
          var newAgeBox = document.createElement('input');
          newAgeBox.type = "number";
          newAgeBox.setAttribute('name', 'newAge')
          newAgeBox.setAttribute('placeholder', 'Age greater than');
          newAgeBox.setAttribute('id', 'newAgeBox');
          cell.appendChild(newAgeBox);
        } else if (select.value == 'Agesmaller') {
          var newAgeSmallerBox = document.createElement('input');
          newAgeSmallerBox.type = "number";
          newAgeSmallerBox.setAttribute('name', 'newAgesmaller')
          newAgeSmallerBox.setAttribute('placeholder', 'Age smaller than');
          newAgeSmallerBox.setAttribute('id', 'newAgeSmallerBox');
          cell.appendChild(newAgeSmallerBox);
        } else if (select.value == 'Race') { //** it prints the values by the buttons with some style 
          var newRaceBox = document.createElement('input');
          newRaceBox.type = "radio";
          newRaceBox.setAttribute('name', 'newRace')
          newRaceBox.setAttribute('value', 'Caucasian');
          newRaceBox.setAttribute('id', 'newRaceBox');
          var raceLabel = document.createElement('label');
          raceLabel.setAttribute('for', 'Caucasian');
          raceLabel.setAttribute('id', 'raceLabel');
          raceLabel.innerHTML = ": Caucasian";
          cell.appendChild(newRaceBox);
          cell.appendChild(raceLabel);

          var newRacebox1 = document.createElement('input');
          newRacebox1.type = "radio";
          newRacebox1.setAttribute('name', 'newRace');
          newRacebox1.setAttribute('value', 'American Indian');
          newRacebox1.setAttribute('id', 'newRaceBox1');
          var raceLabel1 = document.createElement('label');
          raceLabel1.setAttribute('for', 'American Indian');
          raceLabel1.setAttribute('id', 'raceLabel1');
          raceLabel1.innerHTML = ": American Indian";
          cell.appendChild(newRacebox1);
          cell.appendChild(raceLabel1);

          var newRacebox2 = document.createElement('input');
          newRacebox2.type = "radio";
          newRacebox2.setAttribute('name', 'newRace');
          newRacebox2.setAttribute('value', 'Asian');
          newRacebox2.setAttribute('id', 'newRaceBox2');
          var raceLabel2 = document.createElement('label');
          raceLabel2.setAttribute('for', 'Asian');
          raceLabel2.setAttribute('id', 'raceLabel2');
          raceLabel2.innerHTML = ": Asian";
          cell.appendChild(newRacebox2);
          cell.appendChild(raceLabel2);


          var newRacebox3 = document.createElement('input');
          newRacebox3.type = "radio";
          newRacebox3.setAttribute('name', 'newRace');
          newRacebox3.setAttribute('value', 'Black');
          newRacebox3.setAttribute('id', 'newRaceBox3');
          var raceLabel3 = document.createElement('label');
          raceLabel3.setAttribute('for', 'Black');
          raceLabel3.setAttribute('id', 'raceLabel3');
          raceLabel3.innerHTML = ": Black";
          cell.appendChild(newRacebox3);
          cell.appendChild(raceLabel3);

          var newRacebox4 = document.createElement('input');
          newRacebox4.type = "radio";
          newRacebox4.setAttribute('name', 'newRace');
          newRacebox4.setAttribute('value', 'Hispanic');
          newRacebox4.setAttribute('id', 'newRaceBox4');
          var raceLabel4 = document.createElement('label');
          raceLabel4.setAttribute('for', 'Hispanic');
          raceLabel4.setAttribute('id', 'raceLabel4');
          raceLabel4.innerHTML = ": Hispanic";
          cell.appendChild(newRacebox4);
          cell.appendChild(raceLabel4);

          var newRacebox5 = document.createElement('input');
          newRacebox5.type = "radio";
          newRacebox5.setAttribute('name', 'newRace');
          newRacebox5.setAttribute('value', 'Unknown');
          newRacebox5.setAttribute('id', 'newRaceBox5');
          var raceLabel5 = document.createElement('label');
          raceLabel5.setAttribute('for', 'Unknown');
          raceLabel5.setAttribute('id', 'raceLabel5');
          raceLabel5.innerHTML = ": Unknown";
          cell.appendChild(newRacebox5);
          cell.appendChild(raceLabel5);
        } else if (select.value == 'PhoneNumber') {
          var newPhonenumBox = document.createElement('input');
          newPhonenumBox.type = "text";
          newPhonenumBox.setAttribute('name', 'newPhonenum')
          newPhonenumBox.setAttribute('placeholder', 'Phone Number');
          newPhonenumBox.setAttribute('id', 'newPhonenumBox');
          cell.appendChild(newPhonenumBox);

        } else if (select.value == 'Comorbidities') {
          // var newComorbiditiesBox = document.createElement('input');
          // newComorbiditiesBox.type = "checkbox";
          // newComorbiditiesBox.setAttribute('name', 'newComorbidities')
          // newComorbiditiesBox.setAttribute('name', 'newComorbidities')
          // newComorbiditiesBox.setAttribute('value', 'Diabetes');
          // cell.appendChild(newComorbiditiesBox);

          // var comorLabel = document.createElement('label');
          // comorLabel.setAttribute('for', 'Diabetes');
          // comorLabel.setAttribute('id', 'comorLabel');
          // comorLabel.innerHTML = ": Diabetes";
          // cell.appendChild(comorLabel);

          // var newComorBox1 = document.createElement('input');
          // newComorBox1.type = "checkbox";
          // newComorBox1.setAttribute('name', 'newComorbidities');
          // newComorBox1.setAttribute('value', 'Obesity');
          // cell.appendChild(newComorBox1);

          // var comorLabel1 = document.createElement('label');
          // comorLabel1.setAttribute('for', 'Obesity');
          // comorLabel1.setAttribute('id', 'comorLabel1');
          // comorLabel1.innerHTML = ": Obesity";
          // cell.appendChild(comorLabel1);

          // var newComorBox2 = document.createElement('input');
          // newComorBox2.type = "checkbox";
          // newComorBox2.setAttribute('name', 'newComorbidities');
          // newComorBox2.setAttribute('value', 'Heart Disease');
          // cell.appendChild(newComorBox2);

          // var comorLabel2 = document.createElement('label');
          // comorLabel2.setAttribute('for', 'Heart Disease');
          // comorLabel2.setAttribute('id', 'comorLabel2');
          // comorLabel2.innerHTML = ": Heart Disease";
          // cell.appendChild(comorLabel2);

          // var newComorBox3 = document.createElement('input');
          // newComorBox3.type = "checkbox";
          // newComorBox3.setAttribute('name', 'newComorbidities');
          // newComorBox3.setAttribute('value', 'Renal Failure');
          // cell.appendChild(newComorBox3);

          // var comorLabel3 = document.createElement('label');
          // comorLabel3.setAttribute('for', 'Renal Failure');
          // comorLabel3.setAttribute('id', 'comorLabel3');
          // comorLabel3.innerHTML = ": Renal Failure";
          // cell.appendChild(comorLabel3);

          // var newComorBox4 = document.createElement('input');
          // newComorBox4.type = "checkbox";
          // newComorBox4.setAttribute('name', 'newComorbidities');
          // newComorBox4.setAttribute('value', 'Dyslipidemia');
          // cell.appendChild(newComorBox4);

          // var comorLabel4 = document.createElement('label');
          // comorLabel4.setAttribute('for', 'Dyslipidemia');
          // comorLabel4.setAttribute('id', 'comorLabel4');
          // comorLabel4.innerHTML = ": Dyslipidemia";
          // cell.appendChild(comorLabel4);

          // var newComorBox5 = document.createElement('input');
          // newComorBox5.type = "checkbox";
          // newComorBox5.setAttribute('name', 'newComorbidities');
          // newComorBox5.setAttribute('value', 'Autoimmune');
          // cell.appendChild(newComorBox5);

          // var comorLabel5 = document.createElement('label');
          // comorLabel5.setAttribute('for', 'Autoimmune');
          // comorLabel5.setAttribute('id', 'comorLabel5');
          // comorLabel5.innerHTML = ": Autoimmune";
          // cell.appendChild(comorLabel5);

          var comorSelect = document.createElement('select');

          var comorOp1 = document.createElement('option');
          comorOp1.setAttribute('id','newComorbidities');
          comorOp1.value = 'Obesity';
          comorOp1.innerHTML = 'Obesity';
          comorSelect.appendChild(comorOp1);

          var comorOp2 = document.createElement('option');
          comorOp2.setAttribute('id','newComorbidities');
          comorOp2.value = 'Diabetes';
          comorOp2.innerHTML = 'Diabetes';
          comorSelect.appendChild(comorOp2);

          var comorOp3 = document.createElement('option');
          comorOp3.setAttribute('id','newComorbidities');
          comorOp3.value = 'Heart Disease';
          comorOp3.innerHTML = 'Heart Disease';
          comorSelect.appendChild(comorOp3);

          var comorOp4 = document.createElement('option');
          comorOp4.setAttribute('id','newComorbidities');
          comorOp4.value = 'Renal Failure';
          comorOp4.innerHTML = 'Renal Failure';
          comorSelect.appendChild(comorOp4);

          var comorOp5 = document.createElement('option');
          comorOp5.setAttribute('id','newComorbidities');
          comorOp5.value = 'Dyslipidemia';
          comorOp5.innerHTML = 'Dyslipidemia';
          comorSelect.appendChild(comorOp5);

          var comorOp6 = document.createElement('option');
          comorOp6.setAttribute('id','newComorbidities');
          comorOp6.value = 'Autoimmune';
          comorOp6.innerHTML = 'Autoimmune';
          comorSelect.appendChild(comorOp6);

          var comorOp7 = document.createElement('option');
          comorOp7.setAttribute('id','newComorbidities');
          comorOp7.value = 'None';
          comorOp7.innerHTML = 'None';
          comorSelect.appendChild(comorOp7);

          cell.appendChild(comorSelect)

        } else if (select.value == 'EDSS') {
          var newBox = document.createElement('input');
          newBox.type = "number";
          newBox.setAttribute('name', 'newEDSS');
          newBox.setAttribute('placeholder', '1-10');
          newBox.setAttribute('id', 'newBox');
          cell.appendChild(newBox);
          // cell.removeChild(newComorBox1);
          // cell.removeChild(newComorBox2);
          // cell.removeChild(newComorBox3);
          // cell.removeChild(newComorBox4);
          // cell.removeChild(newComorBox5);

        } else if (select.value == 'Pregnant') {
          var newPregnantBox = document.createElement('input');
          newPregnantBox.type = "radio";
          newPregnantBox.setAttribute('name', 'newPregnant');
          newPregnantBox.setAttribute('value', 'Yes');
          newPregnantBox.setAttribute('id', 'newPregnantBox');
          cell.appendChild(newPregnantBox);

          var pregnantLabel = document.createElement('label');
          pregnantLabel.setAttribute('for', 'Yes');
          pregnantLabel.setAttribute('id', 'pregnantLabel');
          pregnantLabel.innerHTML = ": Yes";
          cell.appendChild(pregnantLabel);

          var newPregnantBox1 = document.createElement('input');
          newPregnantBox1.type = "radio";
          newPregnantBox1.setAttribute('name', 'newPregnant');
          newPregnantBox1.setAttribute('value', 'No');
          newPregnantBox1.setAttribute('id', 'newPregnantBox1');
          cell.appendChild(newPregnantBox1);

          var pregnantLabel1 = document.createElement('label');
          pregnantLabel1.setAttribute('for', 'No');
          pregnantLabel1.setAttribute('id', 'pregnantLabel1');
          pregnantLabel1.innerHTML = ": No";
          cell.appendChild(pregnantLabel1);

        } else if (select.value == 'Onsetlocalisation') {
          var newOnsetBox = document.createElement('input');
          newOnsetBox.type = "checkbox";
          newOnsetBox.setAttribute('name', 'newOnsetlocalisation');
          newOnsetBox.setAttribute('value', 'Spinal');
          newOnsetBox.setAttribute('id', 'newOnsetBox');
          cell.appendChild(newOnsetBox);

          var onsetLabel = document.createElement('label');
          onsetLabel.setAttribute('for', 'Spinal');
          onsetLabel.setAttribute('id', 'onsetLabel');
          onsetLabel.innerHTML = ": Spinal";
          cell.appendChild(onsetLabel);

          var newOnsetBox1 = document.createElement('input');
          newOnsetBox1.type = "checkbox";
          newOnsetBox1.setAttribute('name', 'newOnsetlocalisation');
          newOnsetBox1.setAttribute('value', 'Cortex');
          newOnsetBox1.setAttribute('id', 'newOnsetBox1');
          cell.appendChild(newOnsetBox1);

          var onsetLabel1 = document.createElement('label');
          onsetLabel1.setAttribute('for', 'Cortex');
          onsetLabel1.setAttribute('id', 'onsetLabel1');
          onsetLabel1.innerHTML = ": Cortex";
          cell.appendChild(onsetLabel1);

          var newOnsetBox2 = document.createElement('input');
          newOnsetBox2.type = "checkbox";
          newOnsetBox2.setAttribute('name', 'newOnsetlocalisation');
          newOnsetBox2.setAttribute('value', 'Brainstem');
          newOnsetBox2.setAttribute('id', 'newOnsetBox2');
          cell.appendChild(newOnsetBox2);

          var onsetLabel2 = document.createElement('label');
          onsetLabel2.setAttribute('for', 'Brainstem');
          onsetLabel2.innerHTML = ": Brainstem";
          cell.appendChild(onsetLabel2);

          var newOnsetBox3 = document.createElement('input');
          newOnsetBox3.type = "checkbox";
          newOnsetBox3.setAttribute('name', 'newOnsetlocalisation');
          newOnsetBox3.setAttribute('value', 'Cerebellum');
          newOnsetBox3.setAttribute('id', 'newOnsetBox3');
          cell.appendChild(newOnsetBox3);

          var onsetLabel3 = document.createElement('label');
          onsetLabel3.setAttribute('for', 'Cerebellum');
          onsetLabel3.setAttribute('id', 'onsetLabel3');
          onsetLabel3.innerHTML = ": Cerebellum";
          cell.appendChild(onsetLabel3);

          var newOnsetBox4 = document.createElement('input');
          newOnsetBox4.type = "checkbox";
          newOnsetBox4.setAttribute('name', 'newOnsetlocalisation');
          newOnsetBox4.setAttribute('value', 'Visual');
          newOnsetBox4.setAttribute('id', 'newOnsetBox4');
          cell.appendChild(newOnsetBox4);

          var onsetLabel4 = document.createElement('label');
          onsetLabel4.setAttribute('for', 'Visual');
          onsetLabel4.setAttribute('id', 'onsetLabel4');
          onsetLabel4.innerHTML = ": Visual";
          cell.appendChild(onsetLabel4);
        } else if (select.value == 'Smoker') {
          var newSmokerbox = document.createElement('input');
          newSmokerbox.type = "radio";
          newSmokerbox.setAttribute('name', 'newSmoker');
          newSmokerbox.setAttribute('value', 'Yes');
          znewSmokerbox.setAttribute('id', 'newSmokerBox');
          cell.appendChild(newSmokerbox);

          var smokerLabel = document.createElement('label');
          smokerLabel.setAttribute('for', 'Yes');
          smokerLabel.setAttribute('id', 'smokerLabel');
          smokerLabel.innerHTML = ": Yes";
          cell.appendChild(smokerLabel);

          var newSmokerbox1 = document.createElement('input');
          newSmokerbox1.type = "radio";
          newSmokerbox1.setAttribute('name', 'newSmoker');
          newSmokerbox1.setAttribute('value', 'No');
          znewSmokerbox.setAttribute('id', 'newSmokerBox');
          cell.appendChild(newSmokerbox1);

          var smokerLabel1 = document.createElement('label');
          smokerLabel1.setAttribute('for', 'No');
          smokerLabel1.setAttribute('id', 'smokerLabel1');
          smokerLabel1.innerHTML = ": No";
          cell.appendChild(smokerLabel1);

        } else if (select.value == 'onsetsymptoms') {
          var newOnsetsymptomsbox = document.createElement('input');
          newOnsetsymptomsbox.type = "checkbox";
          newOnsetsymptomsbox.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox.setAttribute('value', 'Vision');
          newOnsetsymptomsbox.setAttribute('id', 'newOnsetsymptomsbox');
          cell.appendChild(newOnsetsymptomsbox);

          var onsympLabel = document.createElement('label');
          onsympLabel.setAttribute('for', 'Vision');
          onsympLabel.setAttribute('id', 'onsympLabel');
          onsympLabel.innerHTML = ": Vision";
          cell.appendChild(onsympLabel);

          var newOnsetsymptomsbox1 = document.createElement('input');
          newOnsetsymptomsbox1.type = "checkbox";
          newOnsetsymptomsbox1.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox1.setAttribute('value', 'Motor');
          newOnsetsymptomsbox1.setAttribute('id', 'newOnsetsymptomsbox1');
          cell.appendChild(newOnsetsymptomsbox1);

          var onsympLabel1 = document.createElement('label');
          onsympLabel1.setAttribute('for', 'Motor');
          onsympLabel1.setAttribute('id', 'onsympLabel1');
          onsympLabel1.innerHTML = ": Motor";
          cell.appendChild(onsympLabel1);

          var newOnsetsymptomsbox2 = document.createElement('input');
          newOnsetsymptomsbox2.type = "checkbox";
          newOnsetsymptomsbox2.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox2.setAttribute('value', 'Sensory');
          newOnsetsymptomsbox2.setAttribute('id', 'newOnsetsymptomsbox2');
          cell.appendChild(newOnsetsymptomsbox2);

          var onsympLabel2 = document.createElement('label');
          onsympLabel2.setAttribute('for', 'Sensory');
          onsympLabel2.innerHTML = ": Sensory";
          cell.appendChild(onsympLabel2);

          var newOnsetsymptomsbox3 = document.createElement('input');
          newOnsetsymptomsbox3.type = "checkbox";
          newOnsetsymptomsbox3.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox3.setAttribute('value', 'Coordination');
          newOnsetsymptomsbox3.setAttribute('id', 'newOnsetsymptomsbox3');
          cell.appendChild(newOnsetsymptomsbox3);

          var onsympLabel3 = document.createElement('label');
          onsympLabel3.setAttribute('for', 'Coordination');
          onsympLabel3.innerHTML = ": Coordination";
          cell.appendChild(onsympLabel3);

          var newOnsetsymptomsbox4 = document.createElement('input');
          newOnsetsymptomsbox4.type = "checkbox";
          newOnsetsymptomsbox4.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox4.setAttribute('value', 'Bowel/Bladder');
          newOnsetsymptomsbox4.setAttribute('id', 'newOnsetsymptomsbox4');
          cell.appendChild(newOnsetsymptomsbox4);

          var onsympLabel4 = document.createElement('label');
          onsympLabel4.setAttribute('for', 'Bowel/Bladder');
          onsympLabel4.innerHTML = ": Bowel/Bladder";
          cell.appendChild(onsympLabel4);

          var newOnsetsymptomsbox5 = document.createElement('input');
          newOnsetsymptomsbox5.type = "checkbox";
          newOnsetsymptomsbox5.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox5.setAttribute('value', 'Fatigue');
          newOnsetsymptomsbox5.setAttribute('id', 'newOnsetsymptomsbox5');
          cell.appendChild(newOnsetsymptomsbox5);

          var onsympLabel5 = document.createElement('label');
          onsympLabel5.setAttribute('for', 'Fatigue');
          onsympLabel5.innerHTML = ": Fatigue";
          cell.appendChild(onsympLabel5);

          var newOnsetsymptomsbox6 = document.createElement('input');
          newOnsetsymptomsbox6.type = "checkbox";
          newOnsetsymptomsbox6.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox6.setAttribute('value', 'Cognitive');
          newOnsetsymptomsbox6.setAttribute('id', 'newOnsetsymptomsbox6');
          cell.appendChild(newOnsetsymptomsbox6);

          var onsympLabel6 = document.createElement('label');
          onsympLabel6.setAttribute('for', 'Cognitive');
          onsympLabel6.innerHTML = ": Cognitive";
          cell.appendChild(onsympLabel6);

          var newOnsetsymptomsbox7 = document.createElement('input');
          newOnsetsymptomsbox7.type = "checkbox";
          newOnsetsymptomsbox7.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox7.setAttribute('value', 'Encephalopathy');
          newOnsetsymptomsbox7.setAttribute('id', 'newOnsetsymptomsbox7');
          cell.appendChild(newOnsetsymptomsbox7);

          var onsympLabel7 = document.createElement('label');
          onsympLabel7.setAttribute('for', 'Encephalopathy');
          onsympLabel7.innerHTML = ": Encephalopathy";
          cell.appendChild(onsympLabel7);

          var newOnsetsymptomsbox8 = document.createElement('input');
          newOnsetsymptomsbox8.type = "checkbox";
          newOnsetsymptomsbox8.setAttribute('name', 'newOnsetsymptoms');
          newOnsetsymptomsbox8.setAttribute('value', 'Other');
          newOnsetsymptomsbox8.setAttribute('id', 'newOnsetsymptomsbox8');
          cell.appendChild(newOnsetsymptomsbox8);

          var onsympLabel8 = document.createElement('label');
          onsympLabel8.setAttribute('for', 'Other');
          onsympLabel8.innerHTML = ": Other";
          cell.appendChild(onsympLabel8);

        } else if (select.value == 'MRIenhancing') {
          var newMRIenhBox = document.createElement('input');
          newMRIenhBox.type = "radio";
          newMRIenhBox.setAttribute('name', 'newMRIenhancing');
          newMRIenhBox.setAttribute('value', 'Yes');
          newMRIenhBox.setAttribute('id', 'newMRIenhBox');
          cell.appendChild(newMRIenhBox);

          var mrienhLabel = document.createElement('label');
          mrienhLabel.setAttribute('for', 'Yes');
          mrienhLabel.setAttribute('id', 'mrienhLabel');
          mrienhLabel.innerHTML = ": Yes";
          cell.appendChild(mrienhLabel);

          var newMRIenhBox1 = document.createElement('input');
          newMRIenhBox1.type = "radio";
          newMRIenhBox1.setAttribute('name', 'newMRIenhancing');
          newMRIenhBox1.setAttribute('value', 'No');
          newMRIenhBox1.setAttribute('id', 'newMRIenhBox1');
          cell.appendChild(newMRIenhBox1);

          var mrienhLabel1 = document.createElement('label');
          mrienhLabel1.setAttribute('for', 'No');
          mrienhLabel1.setAttribute('id', 'mrienhLabel1');
          mrienhLabel1.innerHTML = ": No";
          cell.appendChild(mrienhLabel1);

        } else if (select.value == 'MRInum') {
          var newMRInumBox = document.createElement('input');
          newMRInumBox.type = "number";
          newMRInumBox.setAttribute('name', 'newMRInum');
          newMRInumBox.setAttribute('placeholder', 'MRI Lesion No.');
          newMRInumBox.setAttribute('id', 'newMRInumBox');
          cell.appendChild(newMRInumBox);
        } else if (select.value == 'MRIonsetlocalisation') {
          var newMRIonsetBox = document.createElement('input');
          newMRIonsetBox.type = "checkbox";
          newMRIonsetBox.setAttribute('name', 'newMRIonsetlocalisation');
          newMRIonsetBox.setAttribute('value', 'Visual');
          cell.appendChild(newMRIonsetBox);

          var mrionsetLabel = document.createElement('label');
          mrionsetLabel.setAttribute('for', 'Visual');
          mrionsetLabel.setAttribute('id', 'mrionsetLabel');
          mrionsetLabel.innerHTML = ": Visual";
          cell.appendChild(mrionsetLabel);

          var newMRIonsetBox1 = document.createElement('input');
          newMRIonsetBox1.type = "checkbox";
          newMRIonsetBox1.setAttribute('name', 'newMRIonsetlocalisation');
          newMRIonsetBox1.setAttribute('value', 'Spinal');
          cell.appendChild(newMRIonsetBox1);

          var mrionsetLabel1 = document.createElement('label');
          mrionsetLabel1.setAttribute('for', 'Spinal');
          mrionsetLabel1.setAttribute('id', 'mrionsetLabel1');
          mrionsetLabel1.innerHTML = ": Spinal";
          cell.appendChild(mrionsetLabel1);

          var newMRIonsetBox4 = document.createElement('input');
          newMRIonsetBox4.type = "checkbox";
          newMRIonsetBox4.setAttribute('name', 'newMRIonsetlocalisation');
          newMRIonsetBox4.setAttribute('value', 'Cortex');
          cell.appendChild(newMRIonsetBox4);

          var mrionsetLabel4 = document.createElement('label');
          mrionsetLabel4.setAttribute('for', 'Cortex');
          mrionsetLabel4.setAttribute('id', 'mrionsetLabel4');
          mrionsetLabel4.innerHTML = ": Cortex";
          cell.appendChild(mrionsetLabel4);

          var newMRIonsetBox2 = document.createElement('input');
          newMRIonsetBox2.type = "checkbox";
          newMRIonsetBox2.setAttribute('name', 'newMRIonsetlocalisation');
          newMRIonsetBox2.setAttribute('value', 'Brainstem');
          cell.appendChild(newMRIonsetBox2);

          var mrionsetLabel2 = document.createElement('label');
          mrionsetLabel2.setAttribute('for', 'Brainstem');
          mrionsetLabel2.setAttribute('id', 'mrionsetLabel2');
          mrionsetLabel2.innerHTML = ": Brainstem";
          cell.appendChild(mrionsetLabel2);

          var newMRIonsetBox3 = document.createElement('input');
          newMRIonsetBox3.type = "checkbox";
          newMRIonsetBox3.setAttribute('name', 'newMRIonsetlocalisation');
          newMRIonsetBox3.setAttribute('value', 'Cerebellum');
          cell.appendChild(newMRIonsetBox3);

          var mrionsetLabel3 = document.createElement('label');
          mrionsetLabel3.setAttribute('for', 'Cerebellum');
          mrionsetLabel3.setAttribute('id', 'mrionsetLabel3');
          mrionsetLabel3.innerHTML = ": Cerebellum";
          cell.appendChild(mrionsetLabel3);
        } else {
          // newRacebox1.style.display = 'none';
          // newRacebox2.style.display = 'none';
          // newRacebox3.style.display = 'none';
          // newRacebox4.style.display = 'none';

          // newRacebox1.remove();
          // newRacebox2.remove();
          // newRacebox3.remove();
          // newRacebox4.remove();
          // cell.removeChild(newRaceBox);
          // cell.removeChild(newRaceBox1);
          // cell.removeChild(newRaceBox2);
          // cell.removeChild(newRaceBox3);
          // cell.removeChild(newRaceBox4);
        }

        // removes the elements that are not supposed to appear in the second row
        if (select.value !== 'Race') { //! this is buggy...
          document.getElementById('newRaceBox').style.display = 'none';
          document.getElementById('raceLabel').style.display = 'none';
          document.getElementById('newRaceBox1').style.display = 'none';
          document.getElementById('raceLabel1').style.display = 'none';
          document.getElementById('newRaceBox2').style.display = 'none';
          document.getElementById('raceLabel2').style.display = 'none';
          document.getElementById('newRaceBox3').style.display = 'none';
          document.getElementById('raceLabel3').style.display = 'none';
          document.getElementById('newRaceBox4').style.display = 'none';
          document.getElementById('raceLabel4').style.display = 'none';
          document.getElementById('newRaceBox5').style.display = 'none';
          document.getElementById('raceLabel5').style.display = 'none';
          console.log('Value !== Race'); //not neccesary
          // return;
        } else if (select.value !== 'Sex') {
          document.getElementById('newMaleRadio').style.display = 'none';
          document.getElementById('newFemaleRadio').style.display = 'none';
          document.getElementById('sexLabel').style.display = 'none';
          document.getElementById('sexLabel1').style.display = 'none';
        } else if (select.value !== 'Name') {
          var testing = document.getElementById('newNameBox');
          testing.setAttribute('hidden', 'true');
          console.log('name');
        } else if (select.value !== 'ID') {
          document.getElementById('newIdBox').style.display = 'none';
          console.log('id');
        } else if (select.value !== 'Email') {
          document.getElementById('newEmailBox').style.display = 'none';
          console.log('email');
        } else if (select.value !== 'Age') {
          document.getElementById('newAgeBox').style.display = 'none';
          console.log('age');
        } else if (select.value !== 'Phonenumber') {
          document.getElementById('newPhonenumBox').style.display = 'none';
          console.log('phonenum');
        } else if (select.value !== 'Agesmaller') {
          document.getElementById('newAgeSmallerBox').style.display = 'none';
          console.log('age<');
        } else if (select.value !== 'Comorbidities') {
          document.getElementsByName('newComorbidities').style.display = 'none';
          document.getElementById('comorLabel1').style.display = 'none';
          document.getElementById('comorLabel').style.display = 'none';
          document.getElementById('comorLabel2').style.display = 'none';
          document.getElementById('comorLabel3').style.display = 'none';
          document.getElementById('comorLabel4').style.display = 'none';
          document.getElementById('comorLabel5').style.display = 'none';
          console.log('Comorbidities');
        } else if (select.value !== 'EDSS') {
          document.getElementById('newBox').style.display = 'none';
        } else if (select.value !== 'Pregnant') {
          document.getElementById('pregnantLabel').style.display = 'none';
          document.getElementById('pregnantLabel1').style.display = 'none';
          document.getElementById('newPregnantBox').style.display = 'none';
          document.getElementById('newPregnantBox1').style.display = 'none';
        } else if (select.value !== 'Onsetlocalisation') {
          document.getElementById('newOnsetBox1').style.display = 'none';
          document.getElementById('newOnsetBox2').style.display = 'none';
          document.getElementById('newOnsetBox3').style.display = 'none';
          document.getElementById('newOnsetBox4').style.display = 'none';
          document.getElementById('onsetLabel1').style.display = 'none';
          document.getElementById('onsetLabel2').style.display = 'none';
          document.getElementById('onsetLabel3').style.display = 'none';
          document.getElementById('onsetLabel4').style.display = 'none';
        } else if (select.value !== 'Smoker') {
          smokerLabel.setAttribute('for', 'Yes');
          document.getElementById('newSmokerBox').style.display = 'none';
          document.getElementById('newSmokerBox1').style.display = 'none';
          document.getElementById('smokerLabel').style.display = 'none';
          document.getElementById('smokerLabel1').style.display = 'none';
        } else if (select.value !== 'onsetsymptoms') {
          document.getElementById('newOnsetsymptomsbox').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox1').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox2').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox3').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox4').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox5').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox6').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox7').style.display = 'none';
          document.getElementById('newOnsetsymptomsbox8').style.display = 'none';


          document.getElementById('onsympLabel').style.display = 'none';
          document.getElementById('onsympLabel1').style.display = 'none';
          document.getElementById('onsympLabel2').style.display = 'none';
          document.getElementById('onsympLabel3').style.display = 'none';
          document.getElementById('onsympLabel4').style.display = 'none';
          document.getElementById('onsympLabel5').style.display = 'none';
          document.getElementById('onsympLabel6').style.display = 'none';
          document.getElementById('onsympLabel7').style.display = 'none';
          document.getElementById('onsympLabel8').style.display = 'none';
        } else if (select.value !== 'MRIenhancing') {
          document.getElementById('newMRIenhBox').style.display = 'none';
          document.getElementById('newMRIenhBox1').style.display = 'none';

          document.getElementById('mrienhLabel').style.display = 'none';
          document.getElementById('mrienhLabel1').style.display = 'none';
        } else if (select.value !== 'MRInum') {
          document.getElementById('newMRInumBox').style.display = 'none';
        } else if (select.value !== 'MRIonsetlocalisation') {
          document.getElementsByName('newMRIonsetlocalisation').style.display = 'none';
          document.getElementsByName('mrionsetLabel').style.display = 'none';
          document.getElementsByName('mrionsetLabel1').style.display = 'none';
          document.getElementsByName('mrionsetLabel2').style.display = 'none';
          document.getElementsByName('mrionsetLabel3').style.display = 'none';
          document.getElementsByName('mrionsetLabel4').style.display = 'none';
        }

      });



      headCell.appendChild(select);




      //disable the element selected in the first attributes selection
      // ** works perfectly
      var op = document.getElementById("newSelect").getElementsByTagName("option");
      for (var i = 0; i < op.length; i++) {
        if (op[i].value == prevAttval) {
          op[i].selected = false;
          op[i].disabled = true;
        }
      }





    }
  </script>

</body>

</html>ß
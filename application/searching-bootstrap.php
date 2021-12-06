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

</head>

<body>

  <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
      <div class="sidebar-header">
        <h3>Multiple Sclerosis Registry</h3>
        <strong>MSR</strong>
      </div>

      <ul class="list-unstyled components">
        <li>
          <a href="/application/menu.php">
            <i class="fas fa-home"></i>
            Home
          </a>

        </li>
        <li>
          <a href="/application/patientinfo-bootstrap.php">
            <i class="fas fa-folder"></i>
            Existing Patients
          </a>


        </li>
        <li>
          <a href="/application/editPatientInfo.php">
            <i class="fas fa-edit"></i>
            Edit Patient Info
          </a>
        </li>
        <li>
          <a href="/application/addpatient-bootstrap.php">
            <i class="fas fa-user-plus"></i>
            Add a new Patient
          </a>
        </li>
        <li class="active">
          <a href="">
            <i class="fas fa-search"></i>
            Advanced Search
          </a>
        </li>
        <li>
          <a href="/application/visual_analytics.php">
            <i class="fas fa-paper-plane"></i>
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
        <form class="form" action="searching-bootstrap.php" method="post">
          <!-- Heading for explaining the following element to the user -->
          <h5 id="intro"> Please Enter the Name of the Patient You Are Looking For </h5>

          <!-- Advanced Searching API -->
          <table class="query_header" id="searching_query_table">

            <tr id="tablerow" class="">
              <th id="selectth">
                <select class="selection" name="Attributes" id="Attributes">
                  <!-- List of all the available attributes for the user to select for the searching queries -->
                  <option disabled>Options</option>
                  <option value="Name" id="p_Name">Name</option>
                  <option value="ID" id="p_Id">Patient ID</option>
                  <option value="Sex" id="p_Sex">Sex</option>
                  <option value="Age" id="p_Age">Age ></option>
                  <option value="Agesmaller">Age << /option>
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
              <tbody>
                <td id="inputBox" hidden>
                  <!-- inputBox with joker role for text and number inputs -->
                  <input type="text" name="srchoption" id="srchoption" placeholder=" Full Name">
                </td>
                <td id="Sex_td_male" hidden>
                  <!-- shows Male / Female radio buttons for Sex entry -->
                  <input type="radio" name="Sex_td" value="Male">Male
                </td>
                <td id="Sex_td_female">
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
            </tbody>
          </table>

          <input type="submit" name="Searchbtn" value="Search">
        </form>
        <button id="new_row_btn" onclick="addRow">Add an extra row</button>
        <div class="line"></div>

        <?php
        $option = $_POST['Attributes'];
        $entry = $_POST['srchoption'];
        $sex_entry = $_POST['Sex_td'];
        $race_entry = $_POST['Race_td'];
        $Pregnant_Smoker_entry = $_POST['Pregnant_Smoker'];
        $Onsetsymptoms_entry = $_POST['Onsetsymptoms'];
        $Onsetlocalisation_entry = $_POST['Onsetlocalisation'];
        $MRIonsetlocalisation_entry = $_POST['MRIonsetlocalisation'];
        $Comorbidities_entry = $_POST['Comorbidities'];

        // check if the form has been submited, if yes, validate the info and continue
        if (isset($_POST['Searchbtn'])) {

          if ($option == 'Name') {
            $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Patient_name LIKE '%$entry%' ORDER BY Patient_id";
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
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. Name";
            }
          }
          if ($option == 'ID') {
            $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Patient_id =$entry ORDER BY Patient_id";
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
                    <td> <?php echo $row['Patient_id']; ?> </td>
                    <td><?php echo $row['Patient_name']; ?></td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
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
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Sex = '$sex_entry' ORDER BY Patient_id";
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
                    <th>Sex</th>
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Sex']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. MRI enhancing";
            }
          }
          if ($option == 'Age') {
            $sql = "SELECT * FROM patients WHERE timestampdiff(year,dob,curdate()) > '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
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
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. Age";
            }
          }
          if ($option == 'Agesmaller') {
            $sql = "SELECT * FROM patients WHERE timestampdiff(year,dob,curdate()) < '$entry' AND Doctor_ID = $usersid ORDER BY Patient_id";
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
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. Age";
            }
          }
          if ($option == 'Race') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Race = '$race_entry' ORDER BY Patient_id";
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
                    <th>Race</th>
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Race']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. MRI enhancing";
            }
          }
          if ($option == 'PhoneNumber') {
            $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Phonenum ='$entry%'";
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
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            }
          } else {
            // echo "No patient exists with this information. Phone";
          }
          if ($option == 'Comorbidities') {
            $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Comorbidities = '$Comorbidities_entry'";
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
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. Comorbidities";
            }
          }
          if ($option == 'EDSS') {
            $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.eddsscore FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.eddsscore = '$entry'";
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
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. EDSS";
            }
          }
          if ($option == 'Pregnant') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Pregnant = '$Pregnant_Smoker_entry'";
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
                    <th>Is Pregnant? (Y/N)</th>
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Pregnant']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. Pregnant";
            }
          }
          if ($option == 'Onsetlocalisation') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Onsetlocalisation = '$Onsetlocalisation_entry'";
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
                    <th>Onset Localisation</th>
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['Onsetlocalisation']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. Comorbidities";
            }
          }
          if ($option == 'Smoker') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.smoker = '$Pregnant_Smoker_entry'";
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
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['smoker']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. Smoker";
            }
          }
          if ($option == 'MRIenhancing') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.MRIenhancing = '$Pregnant_Smoker_entry'";
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
                    <th>MRI Enhancing Lesions (Yes/No)</th>
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['MRIenhancing']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. MRI enhancing";
            }
          }
          if ($option == 'onsetsymptoms') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.onsetsymptoms = '$Onsetsymptoms_entry'";
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
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['onsetsymptoms']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. MRI enhancing";
            }
          }
          if ($option == 'MRInum') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.MRInum = '$entry'";
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
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['MRInum']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
              <?php }
            } else {
              echo "No patient exists with this information. MRI enhancing";
            }
          }
          if ($option == 'MRIonsetlocalisation') {
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.MRIonsetlocalisation = '$MRIonsetlocalisation_entry'";
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
                    <th>MRI Onset Localisation</th>
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['MRIonsetlocalisation']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <div class="line"></div>
      <?php }
            } else {
              echo "No patient exists with this information. MRI enhancing";
            }
          }
        }
      } catch (PDOException $e) {
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      ?>
      <footer>
        <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
      </footer> <!-- basic footer -->

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
      }
    }

    document.getElementById('new_row_btn').onchange = function addRow() {
      // var table = document.getElementById('searching_query_table');
      // var newRow = document.getElementById('new_row_btn');
      // newRow.insertRow(1);
      // var cell1 = newRow.insertCell(0);
      // var cell2 = newRow.insertCell(1);

      var tbodyRef = document.getElementById('searching_query_table').getElementsByTagName('tbody')[0];
      var newRow = tbodyRef.insertRow();
      var newCell = newRow.insertCell();

      // Append a text node to the cell
      var newText = document.createTextNode('new row');
      newCell.appendChild(newText);
    }
  </script>
</body>

</html>
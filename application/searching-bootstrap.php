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
      <h3><a href="menu.php" id="logo">Multiple Sclerosis Registry<a/></h3>
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
        <li>
          <a href="/MSR/application/addpatient-bootstrap.php">
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
          <a href="/MSR/application/visual_analytics.php">
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
        <form class="form" action="searching-bootstrap.php" method="post">
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
            <tr id="Email_tr" hidden>
              <td id="Email_td" hidden>
                <input type="email" name="searchemail" id="searchingEmail">
              </td>
            </tr>
            </tbody>
          </table>

          <input type="submit" name="Searchbtn" value="Search">
        </form>
        <button id="new_row_btn" onclick="addRow()" name="new_row">Add an extra row</button>
        <div id="results" class="search-results"">

          
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
        $email_entry = $_POST['searchemail'];
        
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
                <?php }
                
            } else {
              echo "No patient exists with this information. MRI enhancing";
            }
          }
          if ($option == 'Email') { //BUG!!! Prints only 4 outputs when there are more available
            $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND patients.Email = '$email_entry' ORDER BY patients.Patient_id";
            $result = $pdo->query($sql);
            if ($result->rowCount() > 0) {
              while ($row = $result->fetch()) { ?>
                <table id="standard">
                  <tr>
                    <th>Patient ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Phone Number</th>
                    <th>Previous Visits</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_id']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td> <?php echo $row['Patient_name']; ?> </td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td>
                    <td><?php echo "<a href='/application/previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  </tr>
                </table>
                <?php }
                
            } else {
              echo "No patient exists with this information. Email";
            }
          }
        }
      } catch (PDOException $e) {
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      ?>
      </div>
      <footer>
        <div class="line"></div>
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

    // document.getElementById('new_row_btn').onchange = function addRow() {
    //   // var table = document.getElementById('searching_query_table');
    //   // var newRow = document.getElementById('new_row_btn');
    //   // newRow.insertRow(1);
    //   // var cell1 = newRow.insertCell(0);
    //   // var cell2 = newRow.insertCell(1);

    //   var tbodyRef = document.getElementById('searching_query_table').getElementsByTagName('tbody')[0];
    //   var newRow = tbodyRef.insertRow();
    //   var newCell = newRow.insertCell();

    //   // Append a text node to the cell
    //   var inputbox = document.createElement('input');
    //   inputbox.type='text';
    //   inputbox.id = 'secondinput';
    //   inputbox.placeholder = 'new searching';
    //   inputbox.appendChild(newCell);
    //   var newText = document.createTextNode('new row');
    //   newCell.appendChild(newText);
    // }

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

        

        //creates a new tb row that needs to have the next header, and a new row that will have the input field
        var headCell = hrow.insertCell(0);
        var headerCell = document.createElement('th');
        headCell.id = 'newhCell';
        // creates the header for the second info box
        // headCell.innerHTML = "More Info"; 

        // create the row for the inputbox
        var crow = table.insertRow(3);
        
        // create the second input field
        var cell = crow.insertCell(0);
        cell.id = 'newInputCell';
        var newInputBox = document.createElement('input');

        

        var select = document.createElement('select');
        select.setAttribute('id','newSelect');
        select.setAttribute('name','newAttributes');
       
        
        // the options in the new select attributes row
        var op1 = document.createElement('option');
        op1.value= 'Options' ;
        op1.setAttribute('disabled',true);
        op1.innerHTML='Options';

        select.appendChild(op1);

        var op2 = document.createElement('option');
        op2.value= 'Name' ;
        op2.setAttribute('id','newInput')
        op2.innerHTML='Name';
        select.appendChild(op2);

        var op3 = document.createElement('option');
        op3.value= 'ID' ;
        op3.setAttribute('id','newInput')
        op3.innerHTML='Patient ID';
        select.appendChild(op3);

        var op4 = document.createElement('option');
        op4.value= 'Sex' ;
        op4.setAttribute('id','newInput')
        op4.innerHTML='Sex';
        select.appendChild(op4);
        
        var op5 = document.createElement('option');
        op5.value= 'Email' ;
        op5.setAttribute('id','newInput')
        op5.innerHTML='Patient Email';
        select.appendChild(op5);

        var op6 = document.createElement('option');
        op6.value= 'Age' ;
        op6.setAttribute('id','newInput')
        op6.innerHTML='Age >';
        select.appendChild(op6);

        var op7 = document.createElement('option');
        op7.value= 'Agesmaller' ;
        op7.setAttribute('id','newInput')
        op7.innerHTML='Age <';
        select.appendChild(op7);

        var op8 = document.createElement('option');
        op8.value= 'Race' ;
        op8.setAttribute('id','newInput')
        op8.innerHTML='Race';
        select.appendChild(op8);

        var op9 = document.createElement('option');
        op9.value= 'PhoneNumber' ;
        op9.setAttribute('id','newInput')
        op9.innerHTML='Phone Number';
        select.appendChild(op9);

        var op10 = document.createElement('option');
        op10.value= 'Comorbidities' ;
        op10.setAttribute('id','newInput')
        op10.innerHTML='Comorbidities';
        select.appendChild(op10);

        var op11 = document.createElement('option');
        op11.value= 'EDSS' ;
        op11.setAttribute('id','newInput')
        op11.innerHTML='EDSS Score';
        select.appendChild(op11);

        var op12 = document.createElement('option');
        op12.value= 'Pregnant' ;
        op12.setAttribute('id','newInput')
        op12.innerHTML='Is Pregnant';
        select.appendChild(op12);

        var op13 = document.createElement('option');
        op13.value= 'Onsetlocalisation' ;
        op13.setAttribute('id','newInput')
        op13.innerHTML='Onset Localisation';
        select.appendChild(op13);

        var op14 = document.createElement('option');
        op14.value= 'Smoker' ;
        op14.setAttribute('id','newInput')
        op14.innerHTML='Is a Smoker';
        select.appendChild(op14);

        var op15 = document.createElement('option');
        op15.value= 'onsetsymptoms' ;
        op15.setAttribute('id','newInput')
        op15.innerHTML='Onset Symptoms';
        select.appendChild(op15);

        var op16 = document.createElement('option');
        op16.value= 'MRIenhancing' ;
        op16.setAttribute('id','newInput')
        op16.innerHTML='MRI Enhancing Lesions';
        select.appendChild(op16);

        var op17 = document.createElement('option');
        op17.value= 'MRInum' ;
        op17.setAttribute('id','newInput')
        op17.innerHTML='MRI Lesion No.';
        select.appendChild(op17);

        var op18 = document.createElement('option');
        op18.value= 'MRIonsetlocalisation' ;
        op18.setAttribute('id','newInput')
        op18.innerHTML='MRI Onset Localisation';
        select.appendChild(op18);

        select.onchange = function changeSelect(){
          //todo needs to remove the fields of the previous boxes after changing to a new select value
          
          if (select.value == 'Name') {
            newInputBox.type = "text";
            newInputBox.setAttribute('name','newName');
            newInputBox.setAttribute('placeholder','Name');
            cell.appendChild(newInputBox);
          } else if (select.value == 'ID') {
            newInputBox.type = "text";
            newInputBox.setAttribute('name','newID');
            newInputBox.setAttribute('placeholder','Patient ID');
            cell.appendChild(newInputBox);
          } else if (select.value == 'Sex') { 
            newInputBox.type = "radio";
            newInputBox.setAttribute('name','newSex');
            // creates a label for the button
            var sexLabel = document.createElement('label');
            sexLabel.setAttribute('for','Male');
            sexLabel.innerHTML = ": Male";
            newInputBox.setAttribute('value','Male');
            // creates the new radio button for the female value
            var newFemaleRadio = document.createElement('input');
            newFemaleRadio.type = "radio";
            newFemaleRadio.setAttribute('name','newSex');
            newFemaleRadio.setAttribute('value','Female');
            // creates a label for the button
            var sexLabel1 = document.createElement('label');
            sexLabel1.setAttribute('for','Female');
            sexLabel1.innerHTML = ": Female";
            
            // appends the elements to the table cell
            cell.appendChild(newInputBox);
            cell.appendChild(sexLabel);
            cell.appendChild(newFemaleRadio);
            cell.appendChild(sexLabel1);
          } else if (select.value == 'Email') {
            newInputBox.type = "email";
            newInputBox.setAttribute('name','newEmail')
            newInputBox.setAttribute('placeholder','Email');
            cell.appendChild(newInputBox);
          } else if (select.value == 'Age') {
            newInputBox.type = "number";
            newInputBox.setAttribute('name','newAge')
            newInputBox.setAttribute('placeholder','Age greater than');
            cell.appendChild(newInputBox);
          } else if (select.value == 'Agesmaller') {
            newInputBox.type = "number";
            newInputBox.setAttribute('name','newAgesmaller')
            newInputBox.setAttribute('placeholder','Age smaller than');
            cell.appendChild(newInputBox);
          } else if (select.value == 'Race') { //** it prints the values by the buttons with some style 
            newInputBox.type = "radio";
            newInputBox.setAttribute('name','newRace')
            newInputBox.setAttribute('value','Caucasian');
            var raceLabel = document.createElement('label');
            raceLabel.setAttribute('for','Caucasian');
            raceLabel.innerHTML = ": Caucasian";
            cell.appendChild(newInputBox);
            cell.appendChild(raceLabel);

            var newRacebox1 = document.createElement('input');
            newRacebox1.type = "radio";
            newRacebox1.setAttribute('name','newRace');
            newRacebox1.setAttribute('value','American Indian');
            var raceLabel1 = document.createElement('label');
            raceLabel1.setAttribute('for','American Indian');
            raceLabel1.innerHTML = ": American Indian";
            cell.appendChild(newRacebox1);
            cell.appendChild(raceLabel1);

            var newRacebox2 = document.createElement('input');
            newRacebox2.type = "radio";
            newRacebox2.setAttribute('name','newRace');
            newRacebox2.setAttribute('value','Asian');
            var raceLabel2 = document.createElement('label');
            raceLabel2.setAttribute('for','Asian');
            raceLabel2.innerHTML = ": Asian";
            cell.appendChild(newRacebox2);
            cell.appendChild(raceLabel2);
            

            var newRacebox3 = document.createElement('input');
            newRacebox3.type = "radio";
            newRacebox3.setAttribute('name','newRace');
            newRacebox3.setAttribute('value','Black');
            var raceLabel3 = document.createElement('label');
            raceLabel3.setAttribute('for','Black');
            raceLabel3.innerHTML = ": Black";
            cell.appendChild(newRacebox3);
            cell.appendChild(raceLabel3);

            var newRacebox4 = document.createElement('input');
            newRacebox4.type = "radio";
            newRacebox4.setAttribute('name','newRace');
            newRacebox4.setAttribute('value','Hispanic');
            var raceLabel4 = document.createElement('label');
            raceLabel4.setAttribute('for','Hispanic');
            raceLabel4.innerHTML = ": Hispanic";
            cell.appendChild(newRacebox4);
            cell.appendChild(raceLabel4);

            var newRacebox5 = document.createElement('input');
            newRacebox5.type = "radio";
            newRacebox5.setAttribute('name','newRace');
            newRacebox5.setAttribute('value','Unknown');
            var raceLabel5 = document.createElement('label');
            raceLabel5.setAttribute('for','Unknown');
            raceLabel5.innerHTML = ": Unknown";
            cell.appendChild(newRacebox5);
            cell.appendChild(raceLabel5);
          } else if (select.value == 'PhoneNumber') {
            newInputBox.type = "text";
            newInputBox.setAttribute('name','newPhonenum')
            newInputBox.setAttribute('placeholder','Phone Number');
            cell.appendChild(newInputBox);

          } else if (select.value == 'Comorbidities') { 
            newInputBox.type = "checkbox";
            newInputBox.setAttribute('name','newComorbidities')
            newInputBox.setAttribute('value','Diabetes');
            cell.appendChild(newInputBox);

            var comorLabel = document.createElement('label');
            comorLabel.setAttribute('for','Diabetes');
            comorLabel.innerHTML = ": Diabetes";
            cell.appendChild(comorLabel);

            var newComorBox1 = document.createElement('input');
            newComorBox1.type = "checkbox";
            newComorBox1.setAttribute('name','newComorbidities');
            newComorBox1.setAttribute('value','Obesity');
            cell.appendChild(newComorBox1);

            var comorLabel1 = document.createElement('label');
            comorLabel1.setAttribute('for','Obesity');
            comorLabel1.innerHTML = ": Obesity";
            cell.appendChild(comorLabel1);

            var newComorBox2 = document.createElement('input');
            newComorBox2.type = "checkbox";
            newComorBox2.setAttribute('name','newComorbidities');
            newComorBox2.setAttribute('value','Heart Disease');
            cell.appendChild(newComorBox2);

            var comorLabel2 = document.createElement('label');
            comorLabel2.setAttribute('for','Heart Disease');
            comorLabel2.innerHTML = ": Heart Disease";
            cell.appendChild(comorLabel2);

            var newComorBox3 = document.createElement('input');
            newComorBox3.type = "checkbox";
            newComorBox3.setAttribute('name','newComorbidities');
            newComorBox3.setAttribute('value','Renal Failure');
            cell.appendChild(newComorBox3);

            var comorLabel3 = document.createElement('label');
            comorLabel3.setAttribute('for','Renal Failure');
            comorLabel3.innerHTML = ": Renal Failure";
            cell.appendChild(comorLabel3);

            var newComorBox4 = document.createElement('input');
            newComorBox4.type = "checkbox";
            newComorBox4.setAttribute('name','newComorbidities');
            newComorBox4.setAttribute('value','Dyslipidemia');
            cell.appendChild(newComorBox4);

            var comorLabel4 = document.createElement('label');
            comorLabel4.setAttribute('for','Dyslipidemia');
            comorLabel4.innerHTML = ": Dyslipidemia";
            cell.appendChild(comorLabel4);

            var newComorBox5 = document.createElement('input');
            newComorBox5.type = "checkbox";
            newComorBox5.setAttribute('name','newComorbidities');
            newComorBox5.setAttribute('value','Autoimmune');
            cell.appendChild(newComorBox5);

            var comorLabel5 = document.createElement('label');
            comorLabel5.setAttribute('for','Autoimmune');
            comorLabel5.innerHTML = ": Autoimmune";
            cell.appendChild(comorLabel5);

          } else if (select.value == 'EDSS') {
            var newBox = document.createElement('input');
            newBox.type = "number";
            newBox.setAttribute('name','newEDSS');
            newBox.setAttribute('placeholder','1-10');
            cell.appendChild(newBox);
            cell.removeChild(newComorBox1);
            cell.removeChild(newComorBox2);
            cell.removeChild(newComorBox3);
            cell.removeChild(newComorBox4);
            cell.removeChild(newComorBox5);

          } else if (select.value == 'Pregnant'){ 
            var newPregnantBox = document.createElement('input');
            newPregnantBox.type = "radio";
            newPregnantBox.setAttribute('name','newPregnant');
            newPregnantBox.setAttribute('value','Yes');
            cell.appendChild(newPregnantBox);

            var pregnantLabel = document.createElement('label');
            pregnantLabel.setAttribute('for','Yes');
            pregnantLabel.innerHTML = ": Yes";
            cell.appendChild(pregnantLabel);

            var newPregnantBox1 = document.createElement('input');
            newPregnantBox1.type = "radio";
            newPregnantBox1.setAttribute('name','newPregnant');
            newPregnantBox1.setAttribute('value','No');
            cell.appendChild(newPregnantBox1);

            var pregnantLabel1 = document.createElement('label');
            pregnantLabel1.setAttribute('for','No');
            pregnantLabel1.innerHTML = ": No";
            cell.appendChild(pregnantLabel1);

          } else if (select.value == 'Onsetlocalisation') { 
            var newOnsetBox = document.createElement('input');
            newOnsetBox.type = "checkbox";
            newOnsetBox.setAttribute('name','newOnsetlocalisation');
            newOnsetBox.setAttribute('value','Spinal');
            cell.appendChild(newOnsetBox);

            var onsetLabel = document.createElement('label');
            onsetLabel.setAttribute('for','Spinal');
            onsetLabel.innerHTML = ": Spinal";
            cell.appendChild(onsetLabel);

            var newOnsetBox1 = document.createElement('input');
            newOnsetBox1.type = "checkbox";
            newOnsetBox1.setAttribute('name','newOnsetlocalisation');
            newOnsetBox1.setAttribute('value','Cortex');
            cell.appendChild(newOnsetBox1);

            var onsetLabel1 = document.createElement('label');
            onsetLabel1.setAttribute('for','Cortex');
            onsetLabel1.innerHTML = ": Cortex";
            cell.appendChild(onsetLabel1);

            var newOnsetBox2 = document.createElement('input');
            newOnsetBox2.type = "checkbox";
            newOnsetBox2.setAttribute('name','newOnsetlocalisation');
            newOnsetBox2.setAttribute('value','Brainstem');
            cell.appendChild(newOnsetBox2);

            var onsetLabel2 = document.createElement('label');
            onsetLabel2.setAttribute('for','Brainstem');
            onsetLabel2.innerHTML = ": Brainstem";
            cell.appendChild(onsetLabel2);

            var newOnsetBox3 = document.createElement('input');
            newOnsetBox3.type = "checkbox";
            newOnsetBox3.setAttribute('name','newOnsetlocalisation');
            newOnsetBox3.setAttribute('value','Cerebellum');
            cell.appendChild(newOnsetBox3);

            var onsetLabel3 = document.createElement('label');
            onsetLabel3.setAttribute('for','Cerebellum');
            onsetLabel3.innerHTML = ": Cerebellum";
            cell.appendChild(onsetLabel3);

            var newOnsetBox4 = document.createElement('input');
            newOnsetBox4.type = "checkbox";
            newOnsetBox4.setAttribute('name','newOnsetlocalisation');
            newOnsetBox4.setAttribute('value','Visual');
            cell.appendChild(newOnsetBox4);

            var onsetLabel4 = document.createElement('label');
            onsetLabel4.setAttribute('for','Visual');
            onsetLabel4.innerHTML = ": Visual";
            cell.appendChild(onsetLabel4);
          } else if (select.value == 'Smoker') {  
            var newSmokerbox = document.createElement('input');
            newSmokerbox.type = "radio";
            newSmokerbox.setAttribute('name','newSmoker');
            newSmokerbox.setAttribute('value','Yes');
            cell.appendChild(newSmokerbox);

            var smokerLabel = document.createElement('label');
            smokerLabel.setAttribute('for','Yes');
            smokerLabel.innerHTML = ": Yes";
            cell.appendChild(smokerLabel);

            var newSmokerbox1 = document.createElement('input');
            newSmokerbox1.type = "radio";
            newSmokerbox1.setAttribute('name','newSmoker');
            newSmokerbox1.setAttribute('value','No');
            cell.appendChild(newSmokerbox1);

            var smokerLabel1 = document.createElement('label');
            smokerLabel1.setAttribute('for','No');
            smokerLabel1.innerHTML = ": No";
            cell.appendChild(smokerLabel1);

          } else if(select.value == 'onsetsymptoms'){ 
            var newOnsetsymptomsbox = document.createElement('input');
            newOnsetsymptomsbox.type = "checkbox";
            newOnsetsymptomsbox.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox.setAttribute('value','Vision');
            cell.appendChild(newOnsetsymptomsbox);

            var onsympLabel = document.createElement('label');
            onsympLabel.setAttribute('for','Vision');
            onsympLabel.innerHTML = ": Vision";
            cell.appendChild(onsympLabel);

            var newOnsetsymptomsbox1 = document.createElement('input');
            newOnsetsymptomsbox1.type = "checkbox";
            newOnsetsymptomsbox1.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox1.setAttribute('value','Motor');
            cell.appendChild(newOnsetsymptomsbox1);

            var onsympLabel1 = document.createElement('label');
            onsympLabel1.setAttribute('for','Motor');
            onsympLabel1.innerHTML = ": Motor";
            cell.appendChild(onsympLabel1);

            var newOnsetsymptomsbox2 = document.createElement('input');
            newOnsetsymptomsbox2.type = "checkbox";
            newOnsetsymptomsbox2.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox2.setAttribute('value','Sensory');
            cell.appendChild(newOnsetsymptomsbox2);

            var onsympLabel2 = document.createElement('label');
            onsympLabel2.setAttribute('for','Sensory');
            onsympLabel2.innerHTML = ": Sensory";
            cell.appendChild(onsympLabel2);

            var newOnsetsymptomsbox3 = document.createElement('input');
            newOnsetsymptomsbox3.type = "checkbox";
            newOnsetsymptomsbox3.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox3.setAttribute('value','Coordination');
            cell.appendChild(newOnsetsymptomsbox3);

            var onsympLabel3 = document.createElement('label');
            onsympLabel3.setAttribute('for','Coordination');
            onsympLabel3.innerHTML = ": Coordination";
            cell.appendChild(onsympLabel3);

            var newOnsetsymptomsbox4 = document.createElement('input');
            newOnsetsymptomsbox4.type = "checkbox";
            newOnsetsymptomsbox4.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox4.setAttribute('value','Bowel/Bladder');
            cell.appendChild(newOnsetsymptomsbox4);

            var onsympLabel4 = document.createElement('label');
            onsympLabel4.setAttribute('for','Bowel/Bladder');
            onsympLabel4.innerHTML = ": Bowel/Bladder";
            cell.appendChild(onsympLabel4);

            var newOnsetsymptomsbox5 = document.createElement('input');
            newOnsetsymptomsbox5.type = "checkbox";
            newOnsetsymptomsbox5.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox5.setAttribute('value','Fatigue');
            cell.appendChild(newOnsetsymptomsbox5);

            var onsympLabel5 = document.createElement('label');
            onsympLabel5.setAttribute('for','Fatigue');
            onsympLabel5.innerHTML = ": Fatigue";
            cell.appendChild(onsympLabel5);

            var newOnsetsymptomsbox6 = document.createElement('input');
            newOnsetsymptomsbox6.type = "checkbox";
            newOnsetsymptomsbox6.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox6.setAttribute('value','Cognitive');
            cell.appendChild(newOnsetsymptomsbox6);

            var onsympLabel6 = document.createElement('label');
            onsympLabel6.setAttribute('for','Cognitive');
            onsympLabel6.innerHTML = ": Cognitive";
            cell.appendChild(onsympLabel6);

            var newOnsetsymptomsbox7 = document.createElement('input');
            newOnsetsymptomsbox7.type = "checkbox";
            newOnsetsymptomsbox7.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox7.setAttribute('value','Encephalopathy');
            cell.appendChild(newOnsetsymptomsbox7);

            var onsympLabel7 = document.createElement('label');
            onsympLabel7.setAttribute('for','Encephalopathy');
            onsympLabel7.innerHTML = ": Encephalopathy";
            cell.appendChild(onsympLabel7);

            var newOnsetsymptomsbox8 = document.createElement('input');
            newOnsetsymptomsbox8.type = "checkbox";
            newOnsetsymptomsbox8.setAttribute('name','newOnsetsymptoms');
            newOnsetsymptomsbox8.setAttribute('value','Other');
            cell.appendChild(newOnsetsymptomsbox8);

            var onsympLabel8 = document.createElement('label');
            onsympLabel8.setAttribute('for','Other');
            onsympLabel8.innerHTML = ": Other";
            cell.appendChild(onsympLabel8);

          } else if (select.value == 'MRIenhancing') {  
            var newMRIenhBox = document.createElement('input');
            newMRIenhBox.type = "radio";
            newMRIenhBox.setAttribute('name','newMRIenhancing');
            newMRIenhBox.setAttribute('value','Yes');
            cell.appendChild(newMRIenhBox);

            var mrienhLabel = document.createElement('label');
            mrienhLabel.setAttribute('for','Yes');
            mrienhLabel.innerHTML = ": Yes";
            cell.appendChild(mrienhLabel);

            var newMRIenhBox1 = document.createElement('input');
            newMRIenhBox1.type = "radio";
            newMRIenhBox1.setAttribute('name','newMRIenhancing');
            newMRIenhBox1.setAttribute('value','No');
            cell.appendChild(newMRIenhBox1);

            var mrienhLabel1 = document.createElement('label');
            mrienhLabel1.setAttribute('for','No');
            mrienhLabel1.innerHTML = ": No";
            cell.appendChild(mrienhLabel1);

          } else if (select.value == 'MRInum') {
            var newMRInumBox = document.createElement('input');
            newMRInumBox.type = "number";
            newMRInumBox.setAttribute('name','newMRInum');
            newMRInumBox.setAttribute('placeholder','MRI Lesion No.');
            cell.appendChild(newMRInumBox);
          } else if (select.value == 'MRIonsetlocalisation'){ 
            var newMRIonsetBox = document.createElement('input');
            newMRIonsetBox.type = "checkbox";
            newMRIonsetBox.setAttribute('name','newMRIonsetlocalisation');
            newMRIonsetBox.setAttribute('value','Visual');
            cell.appendChild(newMRIonsetBox);

            var mrionsetLabel = document.createElement('label');
            mrionsetLabel.setAttribute('for','Visual');
            mrionsetLabel.innerHTML = ": Visual";
            cell.appendChild(mrionsetLabel);

            var newMRIonsetBox1 = document.createElement('input');
            newMRIonsetBox1.type = "checkbox";
            newMRIonsetBox1.setAttribute('name','newMRIonsetlocalisation');
            newMRIonsetBox1.setAttribute('value','Spinal');
            cell.appendChild(newMRIonsetBox1);

            var mrionsetLabel1 = document.createElement('label');
            mrionsetLabel1.setAttribute('for','Spinal');
            mrionsetLabel1.innerHTML = ": Spinal";
            cell.appendChild(mrionsetLabel1);

            var newMRIonsetBox4 = document.createElement('input');
            newMRIonsetBox4.type = "checkbox";
            newMRIonsetBox4.setAttribute('name','newMRIonsetlocalisation');
            newMRIonsetBox4.setAttribute('value','Cortex');
            cell.appendChild(newMRIonsetBox4);

            var mrionsetLabel1 = document.createElement('label');
            mrionsetLabel1.setAttribute('for','Cortex');
            mrionsetLabel1.innerHTML = ": Cortex";
            cell.appendChild(mrionsetLabel1);

            var newMRIonsetBox2 = document.createElement('input');
            newMRIonsetBox2.type = "checkbox";
            newMRIonsetBox2.setAttribute('name','newMRIonsetlocalisation');
            newMRIonsetBox2.setAttribute('value','Brainstem');
            cell.appendChild(newMRIonsetBox2);

            var mrionsetLabel2 = document.createElement('label');
            mrionsetLabel2.setAttribute('for','Brainstem');
            mrionsetLabel2.innerHTML = ": Brainstem";
            cell.appendChild(mrionsetLabel2);

            var newMRIonsetBox3 = document.createElement('input');
            newMRIonsetBox3.type = "checkbox";
            newMRIonsetBox3.setAttribute('name','newMRIonsetlocalisation');
            newMRIonsetBox3.setAttribute('value','Cerebellum');
            cell.appendChild(newMRIonsetBox3);

            var mrionsetLabel3 = document.createElement('label');
            mrionsetLabel3.setAttribute('for','Cerebellum');
            mrionsetLabel3.innerHTML = ": Cerebellum";
            cell.appendChild(mrionsetLabel3);
          }
        };

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

</html>
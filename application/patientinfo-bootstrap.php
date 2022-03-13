<?php
session_start();
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

  <title>MS Registry Existing Patients</title>

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
        <h3><a href="menu.php" id="logo">Multiple Sclerosis Registry</a></h3>
        <strong>MSR</strong>
      </div>

      <ul class="list-unstyled components">
        <li>
          <a href="/MSR/application/menu.php">
            <i class="fas fa-home"></i>
            Home
          </a>

        </li>
        <li class="active">
          <a href="">
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
      $usersid = $_SESSION['user_id'];
      $servername = "127.0.0.1";
      $username = "root";
      $password = "bioinformatics";
      $dbname = "BIHElab";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      try { ?>
        <div class="container">
          <input type="text" name="filter-patients" id="filter_Patients_table" onkeyup="filterPatients()" placeholder="Search Patient Name..." class="filter w-100">
          <table id="Patients_table" class="w-100 dual_bg">
            <!-- prints the table with the patients -->
            <tr>
              <th>Patient ID</th>
              <th>Patient Name</th>
              <th>Phone Number</th>
              <th>Email</th>
              <th>History</th>
              <th>Add a Follow Up Visit</th>
              <th>Edit Patient Info</th>
              <th>Remove Patient</th>
            </tr>
            <?php
            $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid ORDER BY Patient_id"; //filters the patients for the active user/doctor
            $result = $pdo->query($sql);
            if ($result->rowCount() > 0) {
              while ($row = $result->fetch()) {
            ?>
                <tr>
                  <td><?php echo $row['Patient_id']; ?></td>
                  <td><?php echo $row['Patient_name']; ?></td>
                  <td><?php echo $row['Phonenum']; ?></td>
                  <td><?php echo $row['Email']; ?></td>
                  <td><?php echo "<a href='previousvisit-bootstrap.php?id=" . $row['Patient_id'] . "'>Previous Visits</a>"; ?></td>
                  <td><?php echo "<a href='Multiple_Sclerosis_app.php?id=" . $row['Patient_id'] . "&nm=" . $row['Patient_name'] . "&adr=" . $row['Patient_address'] . "'>Add Follow up</a>"; ?></td> <!-- Passes the patients id in the form for minimazing user error -->
                  <td><?php echo "<a href='editPatientForm.php?patientid=" . $row['Patient_id'] . "&nm=" . $row['Patient_name'] . "&adr=" . $row['Patient_address'] . "&em=" . $row['Email'] . "&phone=".$row['Phonenum']."'>Edit</a>"; ?></td>
                  <td><a href="removeuser.php?id= <?php echo $row["Patient_id"]; ?>" onclick="return confirm('Are you sure you want to remove this Patient?')">Delete</a></td> <!-- removes the patient from the app -->
                  
                </tr>
                
                <?php
              }
              unset($result);
            } else {     // basic error checking
              echo "No records matching your query were found.";
            }
          } catch (PDOException $e) {
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
          }
          ?>
          </table>

        </div>
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
    //bootstrap sidebar collapse
    $(document).ready(function() {
      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
      });
    });
  </script>

  <script>
    //a simple function for confirming the removal of a patient
    function removeuser() {
      return confirm('Are you sure you want to remove this Patient?');
      if (this.value == true) {
        return true;
      }
    }
  </script>
  <script>
    //create an array with the names of the patients, and use the filter to look through the array and hide the rest of the names.
    function filterPatients() {
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("filter_Patients_table");
      filter = input.value.toUpperCase();
      table = document.getElementById("Patients_table");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }
  </script>

</body>

</html>
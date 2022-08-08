<?php session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// assign a variable to each doctor in the db table users
$select_query_total = "SELECT SQL_CALC_FOUND_ROWS users.username,users.id,patients.Patient_id,patients.Doctor_ID,patients.Patient_name,patients.DOB,patients.Email,patients.Patient_address,patients.Phonenum FROM users,patients WHERE username != 'admin' AND users.id = patients.Doctor_ID ORDER BY patients.Patient_id";
$select_query_doctors = "SELECT SQL_CALC_FOUND_ROWS users.username, users.id, users.doc_Email, users.doc_phone FROM users WHERE username != 'admin' ORDER BY users.id";

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 18000)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    $scripttimedout = file_get_contents('timeout.js');
    echo "<script>".$scripttimedout."</script>";
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

    <title>MS Registry ADMIN's Menu</title>

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
                <li class="active">
                    <a href="admins_menu.php">
                        <i class="fas fa-home"></i>
                        Admins Page
                    </a>
                </li>

                 <li>
                    <a href="addDoctor.php">
                        <i class="fas fa-user-plus"></i>
                        Add a Doctor
                    </a>
                </li>
                <!--<li>
                    <a href="">
                        <i class="fas fa-user-plus"></i>
                        Add a new Patient
                    </a>
                </li>-->
                <li class="">
            <a href="admin_searching.php" class="dropdown-toggle" ::after>
                <i class="fas fa-search"></i>
                Search Doctors
            </a>
        </li>
        <li class="">
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
                                  Admin: <u><?php $user_name = $_SESSION['user'];
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
            <!-- main Content -->
            <!-- create pagination for the tables -->

            <?php 
              //define total number of results you want per page  
              $results_per_page = 4;

              // $number_of_result = mysqli_num_rows($result);

              $rows = $pdo->prepare("SELECT FOUND_ROWS()"); 
              $rows->execute();
              $row_count =$rows->fetchColumn();
              // echo $row_count;

              //determine the total number of pages available  
              $number_of_page = ceil ($row_count / $results_per_page);

               //determine which page number visitor is currently on  
                if (!isset ($_GET['page']) ) {  
                  $page = 1;  
                } else {  
                  $page = $_GET['page'];  
                }
                
                //determine the sql LIMIT starting number for the results on the displaying page  
                $page_first_result = ($page-1) * $results_per_page;


            ?>
              
              
              
              <div class="split">
                <div class="left">
                  <table id="Doctors_table" class="w-100">
                    <tr>
                      <input type="text" name="filter-patients" id="filter_Doctors_table" onkeyup="filterDoctors()" placeholder="Search Doctors Name..." class="w-100">
                    </tr>
                    <tr>
                    <th colspan="6">Total Doctors</th>
                    </tr>
                    <tr>
                      <th>Doctor ID</th>
                      <th>Doctors</th>
                      <th>Contact Information</th>
                      <th>Patients</th>
                      <th>Edit Info</th>
                      <th>Remove Doctor</th>
                    </tr>
                    <?php
                    $result = $pdo->query($select_query_doctors);
                    if($result->rowCount() > 0){
                      while($row = $result->fetch()){
                    ?>
                      <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo "<a href='doctors_contant_info.php?docid=" . $row['id'] . "&nm=" . $row['username'] . "&em=" . $row['doc_Email'] . "&phone=".$row['doc_phone']."'>Contact Information</a>"; ?></td>
                        <td><?php echo "<a href='doc_patients.php?docid=" . $row['id'] ."'>Patients</a>"; ?></td>
                        <td><?php echo "<a href='editDoctorForm.php?docid=" . $row['id'] . "&nm=" . $row['username'] . "&em=" . $row['doc_Email'] . "&phone=".$row['doc_phone']."'>Edit</a>"; ?></td>
                        <td><a href="adminremovedoc.php?id= <?php echo $row["id"]; ?>" onclick="return confirm('Are you sure you want to remove the Doctor with ID: ' + <?php echo $row['id']; ?> + '?')" id="remove">Delete</a></td> <!-- removes the patient from the app -->
                      </tr>
                  <?php
                  }
                    unset($result);
                  } else{     // basic error checking
                    echo "No records matching your query were found.";
                  }

                  //display the link of the pages in URL  
                  for($page = 1; $page<= $number_of_page; $page++) {  
                    echo '<a href = "index2.php?page=' . $page . '">' . $page . ' </a>';  
                  }  
                  ?>
                  </table>
                </div>
                <div class="right">
                  <table id="Patients_table" class="w-100">
                    <tr>
                    <input type="text" name="filter-patients" id="filter_Pat_table" onkeyup="filterPatients()" placeholder="Search Patient Name..." class="filter w-100">
                    </tr>
                    <tr>
                      <th colspan="6">Total Patients</th>
                    </tr>
                    <tr>
                      <th>Patient ID</th>
                      <th>Patients</th>
                      <th>Doctor</th>
                      <!-- <th>Date of Birth</th> -->
                      <th>Emails</th>
                      <th>Edit Info</th>
                      <th>Remove Patient</th>
                    </tr>
                    <?php
                    $results = $pdo->query($select_query_total);
                    if($results->rowCount() > 0){
                      while($row = $results->fetch()){
                    ?>

                    <tr>
                      <td><?php echo $row['Patient_id'] ?></td>
                      <td><?php echo $row['Patient_name']; ?></td>
                      <td><?php echo $row['username']; ?></td>
                      <!-- <td><?php echo $row['DOB']; ?></td> -->
                      <td><?php echo $row['Email']; ?></td>
                      <td><?php echo "<a href='editPatientFormAdmin.php?patientid=" . $row['Patient_id'] . "&nm=" . $row['Patient_name'] . "&adr=" . $row['Patient_address'] . "&em=" . $row['Email'] . "&phone=".$row['Phonenum']." &dob=".$row['DOB']."'>Edit</a>"; ?></td>
                      <td><a href="adminremovepat.php?id= <?php echo $row['Patient_id']; ?>&docid= <?php echo $row['id'] ?> " onclick="return confirm('Are you sure you want to remove Patient with ID: ' + <?php echo $row['Patient_id']; ?> + '?')" id="remove">Delete</a></td> <!-- removes the patient from the app -->
                    </tr>
                  <?php
                  }
                  unset($results);
                  } else{     // basic error checking
                    echo "No records matching your query were found.";
                  }
                  ?>
                  </table>
                </div>
              </div>

            <footer id="abso">
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
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>

    <script type="text/javascript">
      function filterPatients() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("filter_Pat_table");
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
      function filterDoctors() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("filter_Doctors_table");
        filter = input.value.toUpperCase();
        table = document.getElementById("Doctors_table");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
          td = tr[i].getElementsByTagName("td")[1]; //0 based counter
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

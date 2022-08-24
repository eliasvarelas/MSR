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
  <link rel="stylesheet" href="basicapp-notnow.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <!-- <script src="searching.js"></script> -->

</head>

<body onload="doc_searching()">

  <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar"> 
      <div class="sidebar-header">
        <h3><a href="admins_menu.php" id="logo">Multiple Sclerosis Registry<a /></h3>
        <strong><a href="menu.php" id="logo">MSR</a></strong>
      </div>

      <ul class="list-unstyled components">
        <li class="">
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
        <li class="active">
            <a href="admin_searching.php" class="dropdown-toggle" ::after>
                <i class="fas fa-search"></i>
                Search Doctors
            </a>
        </li>
        <li>
            <a href="admin_patient_searching.php">
                <i class="fas fa-search"></i>
                Advanced Patient Search
            </a>
        </li> 
        <li>
            <a href="visual_analytics_google_admin.php">
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

      <?php
      $usersid = $_SESSION['user_id'];
      $servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      try { ?>
        <form action="admin_searching.php" method="POST" id="doc_form" onsubmit="patientAppear();return false;">
            <h3>Doctors registered in MS Registry</h3>
            
        <?php 

            echo"<table class='w-100' id='small'>";
            echo"<tr>";
                echo"<th>"."Doctor ID"."</th>";
                // echo"<th>"."Doctors First name"."</th>";
                // echo"<th>"."Doctors Last name"."</th>";
                echo"<th>"."Doctors username"."</th>";
                echo"<th>"."Email"."</th>";
                echo"<th>"."Phone Number"."</th>";
                echo"<th>"."Doctor Registered at:"."</th>";
                // echo"<th>"."Select User:"."</th>";
            echo "</tr>";
                
            $query = "SELECT * FROM users WHERE username != 'admin' ORDER BY id";
            $results_doc = $pdo->query($query);
            if ($results_doc->rowCount() > 0) {
                while ($row_doc = $results_doc->fetch()) { 
                    echo"<tr>";
                        echo "<td>".$row_doc['id']."</td>";
                        // echo"<td>".($row_doc['fname'] ?? "N/A")."</td>";
                        // echo"<td>".($row_doc['lname'] ?? "N/A")."</td>";
                        echo"<td>".($row_doc['username']??"N/A")."</td>";
                        echo"<td>".($row_doc['doc_Email']??"N/A")."</td>";
                        echo"<td>".($row_doc['doc_phone']??"N/A")."</td>";
                        echo"<td>".($row_doc['created_at']??"N/A")."</td>";
                        // echo"<td>"."<input type='checkbox' name='select_doc' id='select_doc' value='$row_doc[id]'>"."</td>";
                    echo"</tr>";                    
                }
            }
            echo"</table>";
        ?> 
            
            <!-- <button type="submit" id="submit_doc" name="docSubmit" onsubmit="patientAppear()">Submit</button> -->
        </form> 
        
        <div id="results" class="search-results">
          <!-- <h3 id="resultheader">Results</h3> -->
          <?php


            if (isset($_POST['docSubmit'])) {
                $doc_entry = $_POST['select_doc'];
                echo $doc_entry; // works fine


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



  <script type="text/javascript"> //doc searching

    function doc_searching(){
        //hides the patients form
        var patients_form = document.getElementById('patients_form');
        patients_form.hidden = true;


        const contentDiv = document.getElementById('content');
        
        var newForm = document.getElementById('doc_form');
        
        // var doc_table = document.createElement('table');
        // doc_table.setAttribute('name','Doc_table_searching');
        // doc_table.setAttribute('id','Doc_table_searching');

        // creating the dropdown with the users
        // var
    }
  </script>

</body>

</html>
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
// TODO: need to make a function that will invert the y and x axis based on the chart type... (hor_bar/vert_bar)
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
  <script src="https://d3js.org/d3.v7.min.js"></script>
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
        <li>
          <a href="/application/searching-bootstrap.php">
            <i class="fas fa-search"></i>
            Advanced Search
          </a>
        </li>
        <li class="active">
          <a href=" ">
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

      <div class="d3-wrapper">

        <form action="visual_analytics.php" method="POST" id="form">
          <table class="table-bordered" id="d3-searching">

            <tr id="type_of_chart_row">
              <!-- select the type of chart you want -->
              <th>Type of Chart</th>
              <td colspan="3" class="tdclass exempt">
                <select id="type_of_chart" name="charts">
                  <option value="Pie_chart">Pie chart</option> <!-- classic pie -->
                  <option value="donut_chart">Donut Chart</option> <!-- pie chart with a whole in the middle -->
                  <option value="vert_bar">Vertical Bar Chart</option> <!-- typical bar graph -->
                  <option value="hor_bar">Horizontal Bar Chart</option> <!-- horizontal bar graph with Y axis as a base -->
                  <option value="line_chart">Line Chart</option> <!-- One line with multiple values -->
                </select>
              </td>
            </tr>

            <tr id="attribute_row">
              <!-- select the attribute for which the chart will be printed -->
              <th>Select an Attribute</th>
              <td colspan="3" class="tdclass exempt">
                <select id="attributes" name="attributes">
                  <option value="Name" id="p_Name">Patient Name</option>
                  <option value="Sex" id="p_Sex">Sex</option>
                  <option value="Age" id="p_Age">Age</option>
                  <option value="Race" id="p_Race">Race</option>
                  <option value="Comorbidities" id="p_Comorbidities">Comorbidities</option>
                  <option value="EDSS" id="p_eddsscore">EDSS Score</option>
                  <option value="Past_medication">Past Medication</option>
                  <option value="Current_medication">Current Medication</option>
                  <option value="Pregnant" id="p_Pregnant">Is Pregnant</option>
                  <option value="Onsetlocalisation" id="p_Onsetlocalisation">Onset Localisation</option>
                  <option value="Smoker" id="p_Smoker">Is a Smoker</option>
                  <option value="onsetsymptoms" id="p_onsetsymptoms">Onset Symptoms</option>
                  <option value="MRIenhancing" id="p_MRIenhancing">MRI Enhancing Lesions</option>
                  <option value="MRInum" id="p_MRInum">MRI Lesion No.</option>
                  <option value="MRIonsetlocalisation" id="p_MRIonsetlocalisation">MRI Onset Localisation</option>
                </select>
              </td>
            </tr>

          </table>
          <button type="submit" name="makeGraph" value="Create Graph">Create Graph</button>

          <!-- <p>want to download the info?</p> -->

          <!-- <button name="json_file" id="json_value" value="json">json</button> -->
        </form>

        <?php

        $usersid = $_SESSION['user_id'];
        $servername = "127.0.0.1";
        $username = "root";
        $password = "bioinformatics";
        $dbname = "BIHElab";
        // get data from the form
        $pdf = $_POST['json_file'];
        $chartType = $_POST['charts'];
        $attribute = $_POST['attributes'];
        $createGraph = $_POST['makeGraph'];

        if (isset($_POST['makeGraph'])) {
          try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($attribute == "Name") {
              $queryparam = "NDS";
            }
            if ($attribute == "Sex") {
              $queryparam = "Sex";
            }
            if ($attribute == "Age") {
              $queryparam = "Age";
              // $sqlqu = "SELECT Patient_name FROM patients WHERE Doctor_id = $usersid";
            }
            if ($attribute == "Race") {
              $queryparam = "Race";
            }
            if ($attribute == "Comorbidities") {
              $queryparam = "Comorbidities";
              // $sql2 = "SELECT Patient_name FROM patients WHERE Doctor_id = $usersid";
            }
            if ($attribute == "EDSS") {
              $queryparam = "eddsscore";
            }
            if ($attribute == "Past_medication") {
              $queryparam = "pastTREATMENT";
            }
            if ($attribute == "Current_medication") {
              $queryparam = "TREATMENT";
            }
            if ($attribute == "Pregnant") {
              $queryparam = "Pregnant";
            }
            if ($attribute == "Onsetlocalisation") {
              $queryparam = "Onsetlocalisation";
            }
            if ($attribute == "Smoker") {
              $queryparam = "smoker";
            }
            if ($attribute == "onsetsymptoms") {
              $queryparam = "onsetsymptoms";
            }
            if ($attribute == "MRIenhancing") {
              $queryparam = "MRIenhancing";
            }
            if ($attribute == "MRInum") {
              $queryparam = "MRInum";
            }
            if ($attribute == "MRIonsetlocalisation") {
              $queryparam = "MRIonsetlocalisation";
            }

            $sql = "SELECT $queryparam FROM MSR";


            $var1 = array();


            $result = $pdo->query($sql);

            if ($result->rowCount() > 1) {
              while ($row = $result->fetch()) {
                $var1[] = $row;
              }
            }
            json_encode($someArray); //transforms the php array in json format


            $fp = fopen('empdata.json', 'w');
            fwrite($fp, json_encode($var1));
            fclose($fp);
          } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
          }
        }


        ?>
        <div id="d3-container">
          <svg width="500" height="400" id="pie"></svg>
          <svg width="500" height="400" id="linechart"></svg>
        </div>

      </div>

      <div class="line" />

      <footer>
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
    //sidebarCollapse
    $(document).ready(function() {
      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
      });
    });
  </script>

  <script src="visual_analytics.js" charset="utf-8"></script>
</body>

</html>
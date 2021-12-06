<?php session_start();
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

  <title>MS Registry Main Menu</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="mainmenu.css">
  <link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">

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
          <a href="">
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
                    <!-- doesnt work yet -->
                    <span>Logout</span>
                  </button>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- main Content -->

      <section class="ftco-section">

        <div class="row">
          <div class="col-md-12">
            <div class="content w-100">
              <div class="calendar-container">
                <div class="calendar">
                  <div class="year-header">
                    <span class="left-button fa fa-chevron-left" id="prev"> </span>
                    <span class="year" id="label"></span>
                    <span class="right-button fa fa-chevron-right" id="next"> </span>
                  </div>
                  <table class="months-table w-100">
                    <tbody>
                      <tr class="months-row">
                        <td class="month ">Jan</td>
                        <td class="month ">Feb</td>
                        <td class="month ">Mar</td>
                        <td class="month ">Apr</td>
                        <td class="month ">May</td>
                        <td class="month ">Jun</td>
                      </tr>
                      <tr class="months-row">
                        <td class="month ">Jul</td>
                        <td class="month ">Aug</td>
                        <td class="month ">Sep</td>
                        <td class="month ">Oct</td>
                        <td class="month ">Nov</td>
                        <td class="month ">Dec</td>
                      </tr>
                    </tbody>
                  </table>

                  <table class="days-table w-100">
                    <td class="day ">Sun</td>
                    <td class="day ">Mon</td>
                    <td class="day ">Tue</td>
                    <td class="day ">Wed</td>
                    <td class="day ">Thu</td>
                    <td class="day ">Fri</td>
                    <td class="day ">Sat</td>
                  </table>
                  <div class="frame">
                    <table class="dates-table w-100">
                      <tbody class="tbody ">
                      </tbody>
                    </table>
                    <button class="button" id="add-button">Add Event</button>
                  </div>

                </div>
              </div>
              <div class="events-container">
              </div>
              <div class="dialog" id="dialog">
                <h2 class="dialog-header"> Add New Event </h2>
                <form class="form" id="form" action="events_form_insert.php" method="post">
                  <div class="form-container" align="center">
                    <label class="form-label" id="valueFromMyButton" for="name">Event name</label>
                    <input class="input" type="text" id="name" name="event_name" maxlength="36">
                    <label class="form-label" id="valueFromMyButton" for="count">Number of people to invite</label>
                    <input class="input" type="number" name="No_of_Persons" id="count" min="0" max="1000000" maxlength="7">
                    <label class="form-label" id="valueFromButton" for="location">Location (Street/Address)</label>
                    <input class="input" type="text" id="street" name="location">
                    <label class="form-label" id="valueFromTheButton" for="name">Emails</label>
                    <input class="input" type="email" id="email" name="email_invites">
                    <input type="button" value="Cancel" class="button" id="cancel-button">
                    <input type="submit" value="OK" class="button button-white" id="ok_button" name="ok_button">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>

      <div class="line"></div>
      <footer>
        <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
        <?php



        ?>
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

  <!-- calendar js -->
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

</body>

</html>
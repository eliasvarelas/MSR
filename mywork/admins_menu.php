<?php session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// assign a variable to each doctor in the db table users
$select_query_total = "SELECT users.username,users.id,patients.Patient_id,patients.Doctor_ID,patients.Patient_name,patients.DOB,patients.Email FROM users,patients WHERE username != 'admin' AND users.id = patients.Doctor_ID ORDER BY users.username";
$select_query_doctors = "SELECT users.username FROM users WHERE username != 'admin' ORDER BY username";

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 18000)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    $scripttimedout = file_get_contents('timeout.js');
    echo "<script>".$scripttimedout."</script>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
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
    <link rel="stylesheet" href="style4.css">

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
                    <a href="" >
                        <i class="fas fa-home"></i>
                        Home
                    </a>

                </li>
                <li>
                    <a href="">
                        <i class="fas fa-folder"></i>
                        Existing Patients
                    </a>


                </li>
                <li>
                    <a href="">
                        <i class="fas fa-user-plus"></i>
                        Add a new Patient
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="fas fa-search"></i>
                        Advanced Search
                    </a>
                </li>
                <li>
                    <a href="">
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
            <!-- main Content -->
            <!-- Loop throught all the users in table users and echo their patients -->
            <div class="split">
              <div>
                <table>
                  <tr>
                    <th>Doctors</th>
                  </tr>
    <?php
                $result = $pdo->query($select_query_doctors);
                if($result->rowCount() > 0){
                  while($row = $result->fetch()){
    ?>
                      <tr>
                        <td><?php echo $row['username']; ?></td>
                      </tr>
    <?php
                }
                  unset($result);
                } else{     // basic error checking
                  echo "No records matching your query were found.";
                }
    ?>
                </table>
              </div>

              <!-- <div class="line"></div> -->
              <div>
                <table>
                  <tr>
                    <th colspan="5">Total Patients</th>
                  </tr>
                  <tr>
                    <th>Doctor</th><th>Patient IDs</th><th>Patients</th><th>Date of Birth</th><th>Emails</th>
                  </tr>
    <?php
                  $results = $pdo->query($select_query_total);
                  if($results->rowCount() > 0){
                    while($row = $results->fetch()){
    ?>

                  <tr>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['Patient_id'] ?></td>
                    <td><?php echo $row['Patient_name']; ?></td>
                    <td><?php echo $row['DOB']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
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

            <div class="line"></div>
            <footer>
              <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
            </footer>
            <div class="line"></div>
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
</body>

</html>

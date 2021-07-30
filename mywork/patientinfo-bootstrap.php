<?php session_start(); ?>
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
                <li>
                    <a href="menu.php">
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
                <li>
                    <a href="/addpatient-bootstrap.php">
                        <i class="fas fa-user-plus"></i>
                        Add a new Patient
                    </a>
                </li>
                <li>
                    <a href="/searching-bootstrap.php">
                        <i class="fas fa-search"></i>
                        Advanced Search
                    </a>
                </li>
                <!-- <li>
                    <a href="#">
                        <i class="fas fa-paper-plane"></i>
                        Contact
                    </a>
                </li> -->
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
                            <li class="navbar-btn">
                                <a class="nav-link" href="/logout.php" id="Logout">
                                  <i class="fas fa-user"></i>
                                  Doctor: <u><?php $user_name = $_SESSION['user'];
                                  echo $user_name; ?></u>
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

              try{ ?>
                <table>    <!-- prints the table with the patients -->
                  <tr>
                    <th>Patient Id</th><th>Patient Name</th><th>Phone Number</th><th>Email</th><th>History</th>
                    <th>Add a Follow Up Visit</th><th>Remove Patient</th>
                  </tr>
                    <?php  $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid"; //filters the patients for the active user/doctor
                    $result = $pdo->query($sql);
                    if($result->rowCount() > 0){
                      while($row = $result->fetch()){?>
                          <tr>
                            <td><?php echo $row['Patient_id']; ?></td>
                            <td><?php echo $row['Patient_name'] ; ?></td>
                            <td><?php echo $row['Phonenum'] ; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td><?php echo "<a href='/previousvisit-bootstrap.php?id=".$row['Patient_id']."'>Previous Visits</a>"; ?></td>
                            <td><?php echo "<a href='/Multiple_Sclerosis_app.php?id=".$row['Patient_id']. "&nm=". $row['Patient_name'] ."'>Add Follow up</a>"; ?></td> <!-- Passes the patients id in the form for minimazing user error -->
                            <td><button id="removeuser" onclick="remove_user"><?php echo "<?id=".$row['Patient_id']."'>Remove Patient</a>"; ?></button></td>  <!-- Removes only the patient with the particular id -->
                          </tr>
                    <?php
                    }
                      unset($result);
                    } else{     // basic error checking
                      echo "No records matching your query were found.";
                    }
              } catch(PDOException $e){
                  die("ERROR: Could not able to execute $sql. " . $e->getMessage());
              }
            ?>

            <header>
              <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
            </header>

            <div class="line"></div>
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript"> //bootstrap sidebar collaplse
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>

    <script>
      document.getElementById('removeuser').onclick = function remove_user() { //a simple function for confirming the removal of a patient
        var r = confirm('Are you Sure?');
        if (r == true) {
          file_get_contents('removeuser.php');
        } else {
          return false;
        }
        // document.getElementById("removeuser").innerHTML = sql;
      }
    </script>


</body>

</html>

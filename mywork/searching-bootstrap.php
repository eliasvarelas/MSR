<?php session_start(); ?>
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
                    <a href="/menu.php" >
                        <i class="fas fa-home"></i>
                        Home
                    </a>

                </li>
                <li>
                    <a href="/patientinfo-bootstrap.php">
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
                <li class="active">
                    <a href="">
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
                                <a class="nav-link" href="#">
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
            $usersid = $_SESSION['user_id'] ;
            $servername = "127.0.0.1";
            $username = "root";
            $password = "bioinformatics";
            $dbname = "BIHElab";

            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          try{ ?>
              <form class="form" action="searching-bootstrap.php" method="post">
                <table>
                  <tr>
                    <th><select class="selection" name="Attributes">
                      <option disabled>Options</option>
                      <option value="ID" id="patientId">Patient ID</option>
                      <option value="Age" id="patientAge">Age</option>
                      <option value="Name" id="patientName">Name</option>
                      <option value="Phone Number" id="patientPhonenum">Phone Number</option>
                      <option value="Email" id="patientEmail">Email</option>
                    </select></th>
                  </tr>
                  <tr>
                    <td><input name="srchoption"></td>
                  </tr>
                </table>
                <input type="submit" name="Searchbtn" value="Search">
              </form>

              <?php
              $option = $_POST['Attributes'];
              $entry = $_POST['srchoption'];
              if (isset($_POST['Searchbtn'])) {
                if ($option == 'ID'){
                  $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Patient_id =$entry";
                  $result = $pdo->query($sql);
                  if ($result->rowCount()>0) {
                    while($row = $result->fetch()){ ?>
                      <table id="standard">
                        <tr>
                          <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th>
                        </tr>
                        <tr>
                          <td> <?php echo $row['Patient_id']; ?> </td><td><?php echo $row['Patient_name']; ?></td>
                          <td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td><td><?php echo $row['Email']; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information.";
                  }
                }
                if ($option == 'Age'){  // add the option about searching with an age limit ex. "Age > 50"
                  $sql = "SELECT * FROM patients WHERE timestampdiff(year,dob,curdate()) > '$entry' AND Doctor_ID = $usersid";
                  $result = $pdo->query($sql);
                  if ($result->rowCount()>0) {
                    while($row = $result->fetch()){ ?>
                      <table id="standard">
                        <tr>
                          <th>Patient Id</th><th>Patient Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td><td><?php echo $row['Phonenum']; ?></td><td><?php echo $row['Email']; ?></td>
                        </tr>
                      </table>
                <?php }
                  } else {
                    echo "No patient exists with this information.";
                  }
                }
                if ($option == 'Name'){
                  $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Patient_name LIKE '$entry%'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount()>0) {
                    while($row = $result->fetch()){ ?>
                      <table id="standard">
                        <tr>
                          <th>Patient Id</th><th>Patient Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td><td><?php echo $row['Phonenum']; ?></td><td><?php echo $row['Email']; ?></td>
                        </tr>
                      </table>
                  <?php }
                  } else {
                    echo "No patient exists with this information.";
                  }
                }
                if ($option == 'Phone Number'){
                  $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Phonenum =$entry";
                  $result = $pdo->query($sql);
                  if ($result->rowCount()>0) {
                    while($row = $result->fetch()){ ?>
                      <table id="standard">
                        <tr>
                          <th>Patient Id</th><th>Patient Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td><td><?php echo $row['Phonenum']; ?></td><td><?php echo $row['Email']; ?></td>
                        </tr>
                      </table>
                    <?php }
                    }
                  } else {
                    echo "No patient exists with this information.";
                  }
                if ($option == 'Email'){
                  $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Email ='$entry'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount()>0) {
                    while($row = $result->fetch()){ ?>
                      <table id="standard">
                        <tr>
                          <th>Patient Id</th><th>Patient Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td>
                          <td><?php echo $row['DOB'] ?></td><td><?php echo $row['Phonenum']; ?></td><td><?php echo $row['Email']; ?></td>
                        </tr>
                      </table>
                    <?php }
                    }
                  } else {
                    echo "No patient exists with this information.";
                  }
              }

            } catch(PDOException $e){
                die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            }
            ?>
            <div class="line"></div>
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
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
         var option = document.getElementsByName('Attributes').addEventListener("change",function changeInputbox() {
          if (option.value === 'ID') {
            document.getElementById('srchoption').type = 'number';
          } else if (option.value === 'Age') {
            document.getElementById('srchoption').type = 'number';
          } else if (option.value === 'Name') {
            document.getElementById('srchoption').type = 'text';
          } else if (option.value === 'Phone Number') {
            document.getElementById('srchoption').type = 'number';
          } else if (option.value === 'Email') {
            document.getElementById('srchoption').type = 'email';
          }
        });
    </script>

</body>

</html>

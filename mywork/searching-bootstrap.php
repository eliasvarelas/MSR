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
                <h5 id="intro"> Please Enter the Name of the Patient You Are Looking For </h5>
                <table>
                  <tr>
                    <th><select class="selection" name="Attributes" id="Attributes">
                      <option disabled>Options</option>
                      <option value="Name" id="p_Name">Name</option>
                      <option value="ID" id="p_Id">Patient ID</option>
                      <option value="Sex" id="p_Sex">Sex</option>
                      <option value="Age" id="p_Age">Age</option>
                      <option value="Race" id="p_Race">Race</option>
                      <option value="PhoneNumber" id="p_Phonenum">Phone Number</option>
                      <option value="Email" id="p_Email">Email</option>
                      <option value="Comorbidities" id="p_Comorbidities">Comorbidities</option>
                      <option value="EDSS" id="p_eddsscore">EDSS Score</option>
                      <option value="Pregnant" id="p_Pregnant">Is Pregnant</option>
                      <option value="Onsetlocalisation" id="p_Onsetlocalisation">Onset Localisation</option>
                      <option value="Smoker" id="p_Smoker">Is a Smoker</option>
                      <option value="onsetsymptoms" id="p_onsetsymptoms">Onset Symptoms</option>
                      <option value="MRIenhancing" id="p_MRIenhancing">MRI Enhancing Lesions</option>
                      <option value="MRInum" id="p_MRInum">MRI Lesion No.</option>
                      <option value="MRIonsetlocalisation" id="p_MRIonsetlocalisation">MRI Onset Localisation</option>
                    </select></th>
                  </tr>
                  <tr>
                    <td><input name="srchoption" id="srchoption" placeholder="Full Name"></td>
                  </tr>
                </table>
                <input type="submit" name="Searchbtn" value="Search">
              </form>
              <div class="line"></div>

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
                    echo "No patient exists with this information. ID";
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
                      <div class="line"></div>
              <?php }
                  } else {
                    echo "No patient exists with this information. Age";
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
                      <div class="line"></div>
                  <?php }
                  } else {
                    echo "No patient exists with this information. Name";
                  }
                }
                if ($option == 'PhoneNumber'){
                  $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Phonenum ='$entry%'";
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
                      <div class="line"></div>
                    <?php }
                    }
                  } else {
                    // echo "No patient exists with this information. Phone";
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
                }
                if ($option == 'Comorbidities'){
                  $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Comorbidities FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Comorbidities = '$entry'";
                  $result = $pdo->query($sql);
                  if ($result->rowCount()>0) {
                    while($row = $result->fetch()){ ?>
                      <table id="standard">
                        <tr>
                          <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>Comorbidities</th>
                        </tr>
                        <tr>
                          <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                          <td><?php echo $row['Email']; ?></td><td><?php echo $row['Comorbidities']; ?></td>
                        </tr>
                      </table>
                    <?php }
                  } else {
                    echo "No patient exists with this information. Comorbidities";
                  }
                }
              }
                if ($option == 'EDSS'){
                $sql = "SELECT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.eddsscore FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.eddsscore = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>EDSS Score 1-10</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['eddsscore']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. EDSS";
                }
              }
                if ($option == 'Pregnant'){
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Pregnant FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Pregnant = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>Is Pregnant? (Y/N)</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['Pregnant']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. Pregnant";
                }
              }
                if ($option == 'Onsetlocalisation'){ // work on the wildcard '%' and create the Enum for the user to pick the values through JS
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Onsetlocalisation FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Onsetlocalisation = '$entry%'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>Onset Localisation</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['Onsetlocalisation']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. Comorbidities";
                }
              }
                if ($option == 'Smoker'){
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.smoker FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.smoker = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>Is a Smoker? (Y/N)</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['smoker']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. Smoker";
                }
              }
                if ($option == 'MRIenhancing'){
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIenhancing FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.MRIenhancing = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>MRI Enhancing Lesions (Yes/No)</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['MRIenhancing']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
                if ($option == 'MRIonsetlocalisation'){ // work on the wildcard '%' and create the Enum for the user to pick the values through JS
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRIonsetlocalisation FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.MRIonsetlocalisation = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>MRI Onset Localisation</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['MRIonsetlocalisation']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
                if ($option == 'onsetsymptoms'){
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.onsetsymptoms FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.onsetsymptoms = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>Onset Symptoms</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['onsetsymptoms']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
                if ($option == 'MRInum'){
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.MRInum FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.MRInum = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>MRI Enhancing Lesions No.</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['MRInum']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
                if ($option == 'Sex'){
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Sex FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Sex = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>Sex</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['Sex']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }
                if ($option == 'Race'){
                $sql = "SELECT DISTINCT patients.Patient_id,patients.Patient_name,patients.DOB,patients.Phonenum,patients.Email,MSR.Race FROM patients,MSR WHERE patients.Patient_id = MSR.NDSnum AND Doctor_ID = $usersid AND MSR.Race = '$entry'";
                $result = $pdo->query($sql);
                if ($result->rowCount()>0) {
                  while($row = $result->fetch()){ ?>
                    <table id="standard">
                      <tr>
                        <th>Patient ID</th><th>Name</th><th>Date of Birth</th><th>Phone Number</th><th>Email</th><th>Race</th>
                      </tr>
                      <tr>
                        <td><?php echo $row['Patient_id']; ?></td><td> <?php echo $row['Patient_name']; ?> </td><td><?php echo $row['DOB']; ?></td><td><?php echo $row['Phonenum']; ?></td>
                        <td><?php echo $row['Email']; ?></td><td><?php echo $row['Race']; ?></td>
                      </tr>
                    </table>
                  <?php }
                } else {
                  echo "No patient exists with this information. MRI enhancing";
                }
              }

            } catch(PDOException $e){
                die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            }
            ?>
            <!-- <div class="line"></div> -->
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
    </script>

    <script type="text/javascript">
    function inputBoxChange() {
      var inputBox = document.getElementById('srchoption');
      var introParagraph = document.getElementById('intro');
      if (this.value == 'ID') {
        inputBox.type = 'number';
        inputBox.setAttribute('placeholder','Patient ID');
        introParagraph.innerHTML = "Enter the ID of the Patient You Are Looking for ";
      } else if (this.value == 'Sex') {
        inputBox.type = 'text'; // print male-female
        inputBox.setAttribute('placeholder','Male or Female');
        introParagraph.innerHTML = "Enter the Sex of the Patient You Are Looking for ";
      } else if (this.value == 'Email') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Email Address');
        introParagraph.innerHTML = "Enter the Email Address You Are Looking for ";
      } else if (this.value == 'Smoker') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Yes / No');
        introParagraph.innerHTML = "Enter if the Patient is a Smoker or Not";
      } else if (this.value == 'Name') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Full Name');
        introParagraph.innerHTML = "Enter the Name of the Patient You Are Looking For";
      } else if (this.value == 'Race') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Race');
        introParagraph.innerHTML = "Enter the Race of the Patient You Are Looking For";
      } else if (this.value == 'Comorbidities') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Comorbidities');
        introParagraph.innerHTML = "Enter Any Comorbidities the Patient You Are Looking For May Have";
      } else if (this.value == 'Pregnant') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Yes / No');
        introParagraph.innerHTML = "Enter if the Patient is Pregnant or Not";
      } else if (this.value == 'Onsetlocalisation') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Onset Localisation');
        introParagraph.innerHTML = "Enter The Onset Localisation of The Patient You Are Looking For";
      } else if (this.value == 'onsetsymptoms') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder',' Onset Symptoms');
        introParagraph.innerHTML = "Enter Any Onset Symptoms of The Patient You Are Looking For";
      } else if (this.value == 'MRIonsetlocalisation') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','MRI Localisation');
        introParagraph.innerHTML = "Enter The MRI Onset Localisation of the Patient You Are Looking For";
      } else if (this.value == 'MRInum') {
        inputBox.type = 'number';
        inputBox.setAttribute('placeholder','MRI Lesions');
        introParagraph.innerHTML = "Enter The Number of MRI Lesions That The Patient You Are Looking For Has";
      } else if (this.value == 'PhoneNumber') {
        inputBox.type = 'number';
        inputBox.setAttribute('placeholder','Phone Number');
        introParagraph.innerHTML = "Enter The Phone Number of The Patient You Are Looking For";
      } else if (this.value == 'MRIenhancing') {
        inputBox.type = 'text';
        inputBox.setAttribute('placeholder','Yes / No');
        introParagraph.innerHTML = "Enter If the Patient Had Enhancing Lesions in His MRI";
      } else if (this.value == 'Age') {
        inputBox.type = 'number';
        inputBox.setAttribute('placeholder','Age');
        introParagraph.innerHTML = "Enter The Age of The Patient You Are Looking For";
      } else if (this.value == 'EDSS') {
        inputBox.type = 'number';
        inputBox.setAttribute('placeholder','EDSS Score');
        introParagraph.innerHTML = "Enter The EDSS Score of The Patient You Are Looking For";
      }
    }
    document.getElementById('Attributes').addEventListener('change', inputBoxChange);
    </script>
</body>

</html>

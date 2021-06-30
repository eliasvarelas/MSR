<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Doctors Menu </title>
  <html lang="en-us">
  <meta charset="utf-8" />
  <style>
    * {
      box-sizing: border-box;
    }
    body{
      background-color: lightblue;
    }
    div.header{
      position: absolute;
      display: block;
      height: 1em;
      margin-left: 30%;
      text-align: center;
      background-color: black;
      color:white;
    }
    nav {
      float: left;
      width: 30%;
      padding: 20px;
      font-family: Arial;
      background-color: yellow;
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      padding:5px;
      text-align:center;
      font-family: arial;
    }
    table{
      margin-bottom: 1.5em;
    }
    th {
      background-color: #2980b9;         /* Title box color */
      color: black;
      margin: auto;
    }
    td {
      background-color: white;
      color: black;
      margin: auto;
    }
    button{
      float: right;
      margin-top: 0.5em;
      margin-right: 0.5em;
    }
    .sidebar {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 12em;
      background-color: lightblue;
      position: absolute;
      text-align: center;
    }
    .sidebar a {
      display: block;
      padding: 16px;
      text-decoration: none;
    }
    .sidebar a.active {
      background-color: lightblue;
      color: white;
    }
    .sidebar a:hover:not(.active) {
      background-color: #3973ac;
      color: white;
    }
    div.box{        /* main content */
      display: block;
      margin-left: 12.5em;
      margin-right: 0em;
      margin-top: 1.5em;
      border-radius: 10px;
      background-color: #f2f2f2;
      text-align: center;
    }
    div.al-center{
      position: absolute;
      margin-left: 10em;
    }
    div.aligner{
      position: relative;
      vertical-align: middle;
    }
    #removeuser a {
      color:red;
    }
    tr:nth-child(even) {
      background-color: #e9e9e9;
      font-family: arial;
    }
    /* Responsive layout - makes the three columns stack on top of each other instead of next to each other on smaller screens (700px wide or less) */
    @media screen and (max-width: 1000px) {
      .column {
        width: 50%;
      }
      .aligner{
        margin-left: 25em;
      }
    }
    @media screen and (max-width: 700px) {
      .column {
        width: 50%;
      }
    }
    @media screen and (max-width: 600px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      .sidebar a {float: left;}
      div.content {margin-left: 0;}
    }
    @media screen and (max-width: 400px) {
      .sidebar a {
        text-align: center;
        float: none;
      }
    }
  </style>
</head>

<body>
  <div class="sidebar">
    <img class="img" src="MSregistry_ionian2_bg_lightblue_small.png" alt="Logo">
    <ul>        <!-- side menu -->
      <li><a href="/doctors_menu_likeopencour.php">Main Menu</a></li>    <!-- Doctors Main menu -->
      <li><a href="/patientsinfo.php">Existing Patients</a></li>  <!-- shows the patients of the active user_id -->
      <li><a href="/addpatient.php">Add a new patient</a></li>  <!-- adds a new patient into the patients table with tha active doctor id -->
      <li><a href="/searching.php">Search Query</a></li>  <!-- Advanced search query via Attributes -->
    </ul>
  </div>
  <div class="box">
    <button type="button" name="Logout" id="logout" class="button"><?php echo "<a href='logout.php'> Logout</a> "; ?></button>
    <h1>Welcome Doctor: <u><?php $user_name = $_SESSION['user'];
    echo $user_name;?></u>!</h1> <!-- prints the active username -->
    <br>
    <br>
      <?php
      $servername = "127.0.0.1";
      $username = "root";
      $password = "bioinformatics";
      $dbname = "BIHElab";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      try{
        $patientID = $_GET["id"]; // passes the id of the patient that was "clicked" in the patientsinfo.php table in order to get the right info
        $sql = "SELECT * FROM MSR WHERE NDSnum = $patientID";
          $result = $pdo->query($sql);
          if($result->rowCount() > 0){
            while($row = $result->fetch()){ //make it with more html for responsiveness
              echo "<table>";  // the MSR table for the particular patient id
                  echo "<tr>";
                    echo "<th> Visit Number</th>";
                    echo "<th>Name & Address</th>";
                    echo "<th>Date</th>";
                    echo "<th>Patient Id</th>";
                    echo "<th>Gender</th>";
                    echo "<th>Age</th>";
                    echo "<th>Race</th>";
                    echo "<th>Comorbidities</th>";
                    echo "<th>MS Type NOW</th>";
                    echo "<th>Conversion to SP</th>";
                    echo "<th>Date of Diagnosis</th>";
                  echo "</tr>";
                  echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['NDS'] . "</td>";
                    echo "<td>" . $row['NDSdate'] . "</td>";
                    echo "<td>" . $row['NDSnum'] . "</td>";
                    echo "<td>" . $row['Sex'] . "</td>";
                    echo "<td>" . $row['Age'] . "</td>";
                    echo "<td>" . $row['Race'] . "</td>";
                    echo "<td>" . $row['Comorbidities'] . "</td>";
                    echo "<td>" . $row['convsprad'] . "</td>";
                    echo "<td>" . $row['convspnum'] . "</td>";
                    echo "<td>" . $row['dateofdia'] . "</td>";
                  echo "</tr>";
                  echo "<tr>";
                    echo "<th>MS Type at Diagnosis</th>";
                    echo "<th>No. of Relapses (RR)</th>";
                    echo "<th>Severity</th>";
                    echo "<th>Date of Past treatment</th>";
                    echo "<th>Past Medication</th>";
                    echo "<th>End of past Medication</th>";
                    echo "<th>Date of Present Treatment</th>";
                    echo "<th>Present Medication</th>";
                    echo "<th>Current EDSS Score</th>";
                    echo "<th>7.5 meters Timed walk & 9-Hole PEG test</th>";
                    echo "<th>Date of EDSS</th>"; //2 outputs
                  echo "</tr>";
                  echo "<tr>";
                    echo "<td>" . $row['dateofdiarad'] . "</td>";
                    echo "<td>" . $row['Noofrelapses'] . "</td>";
                    echo "<td>" . $row['Noofrelapsesrad'] . "</td>";
                    echo "<td>" . $row['pastTREATMENTdate'] . "</td>";
                    echo "<td>" . $row['pastTREATMENT'] . "</td>";
                    echo "<td>" . $row['pastTREATMENTcheck'] . "</td>";
                    echo "<td>" . $row['TREATMENTdate'] . "</td>";
                    echo "<td>" . $row['TREATMENT'] . "</td>";
                    echo "<td>" . $row['eddsscore'] . "</td>";
                    echo "<td>" . $row['edsstime7_5m'] .'<br>'. $row['edsstimePEG'] . "</td>";
                    echo "<td>" . $row['EDSSdate'] . '<br>' .$row['EDSSdaterad'] . "</td>";
                  echo "</tr>";
                  echo "<tr>";
                    echo "<th>Pregnant</th>";
                    echo "<th>Date of Onset</th>";
                    echo "<th>Onset Localisation</th>";
                    echo "<th>Smoker<br>No.cigars/day<br>Smoked Since:</th>"; //3 outputs
                    echo "<th>Onset Symptoms</th>";
                    echo "<th>MRI Onset Localisation</th>";
                    echo "<th>CNS MRI Lesions Y/N </th>";
                    echo "<th>CNS MRI Lesions No.</th>";
                    echo "<th>CNS MRI Location</th>";
                    echo "<th>Person Signing the form</th>";
                    echo "<th>Documented at</th>";
                  echo "</tr>";
                  echo "<tr>";
                    echo "<td>" . $row['Pregnant'] . "</td>";
                    echo "<td>" . $row['onsetdate'] . "</td>";
                    echo "<td>" . $row['Onsetlocalisation'] . "</td>";
                    echo "<td>" . $row['smoker'] . '<br>' . $row['cigars'] . '<br>' . $row['cigardate'] . "</td>";
                    echo "<td>" . $row['onsetsymptoms'] . "</td>";
                    echo "<td>" . $row['MRIonsetlocalisation'] . "</td>";
                    echo "<td>" . $row['MRIenhancing'] . "</td>";
                    echo "<td>" . $row['MRInum'] . "</td>";
                    echo "<td>" . $row['MRIenhancinglocation'] . "</td>";
                    echo "<td>" . $row['signer'] . "</td>";
                    echo "<td>" . $row['reg_date'] . "</td>";
                echo "</tr>";
              }
              echo "</table>";
              // Free result set
              unset($result);
          } else{   // basic error checking
              echo "No records matching your query were found.";
          }
      } catch(PDOException $e){
          die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      ?>
    </article>

  </div>
</body>
</html>

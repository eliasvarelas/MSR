<?php
session_start();?>
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
      background-color: #6699ff;
    }
    header {
      background-color:   #6666ff;
      padding: 30px;
      text-align: center;
      font-size: 40px;
      font-family: Arial;
      color: black;
    }
    nav {
      float: left;
      width: 30%;
      background: #ccddff;
      padding: 20px;
      font-family: Arial;

    }
    article {
      float: left;
      padding: 20px;
      background-color: #1a66ff;
      width: 100%;
      margin: auto;
      font-family: Arial;
      height: auto;
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      padding:5px;
      text-align:center;
      font-family: arial;
    }
    th {
      background-color: #ccddff;         /* Title box color */
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
    }


    .sidebar {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 200px;
      background-color: #99bbff;
      position: fixed;
      overflow: auto;
    }

    .sidebar a {
      display: block;
      color: black;
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
    div.content {
      margin-left: 200px;
      margin-right: 0px;
    }

    /* Responsive layout - makes the three columns stack on top of each other instead of next to each other on smaller screens (600px wide or less) */
    @media screen and (max-width: 600px) {
      .column {
        width: 50%;
      }
    }
    @media screen and (max-width: 700px) {
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
  <header>
    Welcome Doctor: <?php $user_name = $_SESSION['user'];
    echo $user_name;?>!
    <button type="button" name="Logout" id="logout" class="button"><?php echo "<a href='logout.php'> Logout</a> "; ?></button>
  </header>
  <div class="sidebar">
      <ul>
        <li><a href="/doctors_menu.php">Main Menu</a></li>      <!-- Doctors Main menu -->
        <li><a href="/patientsinfo.php">Existing Patients</a></li>       <!-- shows the patients of the active user_id -->
        <li><a href="/addpatient.php">Add a new patient</a></li>      <!-- adds a new patient into the patients table with tha active doctor id -->
        <li><a href=" ">Search Query</a></li>   <!-- Advanced search query via Attributes -->
      </ul>
  </div>
  <div class="content">
    <article>
      <?php
      $usersid = $_SESSION['user_id'] ;
      $servername = "127.0.0.1";
      $username = "root";
      $password = "bioinformatics";
      $dbname = "BIHElab";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // isert a dropdown menu to use as a user input method for searching
      // dynamicly in the sqlquery (ex. Attribute:Sex, Value: Female)
      try{?>
        <form class="form" action="searching.php" method="post">
          <table>
            <tr>
              <th><select class="selection" name="Attributes">
                <option disabled>Attributes</option>
                <option>Patient ID</option>
                <option>Name</option>
                <option>Phone Number</option>
                <option>Email</option>
                <!--<option>Sex</option>
                <option>Age</option>
                <option>Race</option>
                <option>Comorbidities</option>
                <option>Date of Diagnosis</option>
                <option>Onset Date</option>
                <option>Past Treatment</option>
                <option>Present Treatment</option>
                <option>Current EDSS Score</option>
                <option>Pregnancy</option>
                <option>Onset Localisation</option>
                <option>Smoker</option>
                <option>Onset Symptoms</option> -->
              </select> </th>
            </tr>
            <tr>
              <td><input type="text" name="srchoption"></td>
            </tr>
          </table>
          <input type="submit" name="Searchbtn" value="Search">
        </form>

        <?php $option = $_POST['srchoption'];
        if ($option == 'ID'){
          $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Patient_id =$option";
          $result = $pdo->query($sql);
          if ($result->rowCount()>0) {
            while($row = $result->fetch()){
              echo $option;
            }
          } else {
            echo "No patient exists with this information.";
          }
        }
        if ($option == 'Name'){
          $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Patient_name =$option";
          $result = $pdo->query($sql);
          if ($result->rowCount()>0) {
            while($row = $result->fetch()){
              echo $option;
            }
          } else {
            echo "No patient exists with this information.";
          }
        }if ($option == 'Phone Number'){
          $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Phonenum =$option";
          $result = $pdo->query($sql);
          if ($result->rowCount()>0) {
            while($row = $result->fetch()){
              echo $option;
            }
          } else {
            echo "No patient exists with this information.";
          }
        }if ($option == 'Email'){
          $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Email =$option";
          $result = $pdo->query($sql);
          if ($result->rowCount()>0) {
            while($row = $result->fetch()){
              echo $option;
            }
          } else {
            echo "No patient exists with this information.";
          }
        }
      } catch(PDOException $e){
          die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      ?>
    </article>

  </div>
</body>
</html>

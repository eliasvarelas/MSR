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
      border-style: solid;
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
      border-style: double;
      border-width: 0.5em;
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
      border-style: double;
      border-width: 0.5em;
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

    try{ ?>
        <form class="form" action="searching.php" method="post">
          <table>
            <tr>
              <th><select class="selection" name="Attributes">
                <option disabled>Options</option>
                <option value="ID">Patient ID</option>
                <option value="Name">Name</option>
                <option value="Phone Number">Phone Number</option>
                <option value="Email">Email</option>
              </select> </th>
            </tr>
            <tr>
              <td><input type="text" name="srchoption"></td>
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
              if($row = $result->fetch()){ ?>
                <table>
                  <tr>
                    <th>Patient ID</th><th>Name</th><th>Phone Number</th><th>Email</th>
                  </tr>
                  <tr>
                    <td> <?php echo $row['Patient_id']; ?> </td><td><?php echo $row['Patient_name']; ?></td>
                    <td><?php echo $row['Phonenum']; ?></td><td><?php echo $row['Email']; ?></td>
                  </tr>
                </table>
              <?php }
            } else {
              echo "No patient exists with this information.";
            }
          }
          if ($option == 'Name'){
            $sql = "SELECT * FROM patients WHERE Doctor_ID = $usersid AND Patient_name ='$entry'";
            $result = $pdo->query($sql);
            if ($result->rowCount()>0) {
              if($row = $result->fetch()){ ?>
                <table>
                  <tr>
                    <th>Name</th><th>Patient ID</th><th>Phone Number</th><th>Email</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Patient_name']; ?></td><td> <?php echo $row['Patient_id']; ?> </td>
                    <td><?php echo $row['Phonenum']; ?></td><td><?php echo $row['Email']; ?></td>
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
              if($row = $result->fetch()){ ?>
                <table>
                  <tr>
                    <th>Phone Number</th><th>Patient ID</th><th>Name</th><th>Email</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Phonenum']; ?></td><td> <?php echo $row['Patient_id']; ?> </td>
                    <td><?php echo $row['Patient_name']; ?></td><td><?php echo $row['Email']; ?></td>
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
              if($row = $result->fetch()){ ?>
                <table>
                  <tr>
                    <th>Phone Number</th><th>Patient ID</th><th>Name</th><th>Email</th>
                  </tr>
                  <tr>
                    <td><?php echo $row['Email']; ?></td><td> <?php echo $row['Patient_id']; ?> </td>
                    <td><?php echo $row['Patient_name']; ?></td><td><?php echo $row['Phonenum']; ?></td>
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
    </article>

  </div>
</body>
</html>

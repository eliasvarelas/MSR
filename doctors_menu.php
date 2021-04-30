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
      border-right-style: dashed;
    }
    article {
      display: block;
      float: left;
      padding: 20px;
      background-color: #1a66ff;;
      width: 100%;
      margin: auto;
      height: auto;
      font-family: Arial;
      border-style: double;
      border-width: 0.4em;

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
      position: absolute;
      overflow: auto;
      border-style: double;
      border-width: 0.4em;
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
    div {
      margin: auto;
      padding: 0,200px;
      background-color: lightblue;
      text-align: center;
    }
  </style>
</head>

<body>
  <header>
    Welcome Doctor: <?php $user_name = $_SESSION['user'];
    echo $user_name;?>! <!-- prints the active username -->
    <button type="button" name="Logout" id="logout" class="button"><?php echo "<a href='logout.php'> Logout</a> "; ?></button>
  </header>
  <div class="sidebar">
      <ul>
        <li><a href="/doctors_menu.php">Main Menu</a></li>    <!-- Doctors Main menu -->
        <li><a href="/patientsinfo.php">Existing Patients</a></li>  <!-- shows the patients of the active user_id -->
        <li><a href="/addpatient.php">Add a new patient</a></li>  <!-- adds a new patient into the patients table with tha active doctor id -->
        <li><a href="/searching.php">Search Query</a></li>  <!-- Advanced search query via Attributes -->
      </ul>
  </div>
  <div class="content">
    <article>
      <img src="MSregistry_ionian2_bg_lightblue.png" alt="Logo">
      <?php
      //database connection
      $usersid = $_SESSION['user_id'];
      $servername = "127.0.0.1";
      $username = "root";
      $password = "bioinformatics";
      $dbname = "BIHElab";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      //establishing the connection and informing the user of its status
      if (is_null($pdo)) {
          return $pdo -> prepare($query);
          echo"The Connection with the Database has failed \n";
      } else {
          echo "<h1>The Connection with the Database is Active! \n</h1>";
      }
      ?>
      <h4>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</h4>  <!-- Basic information for the app -->
    </article>

  </div>
</body>
</html>

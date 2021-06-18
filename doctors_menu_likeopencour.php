<?php session_start(); ?>  
<!DOCTYPE html>
<html> <!-- try making it look like opencourses -->
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
      margin-top: 0.5em;
      margin-right: 0.5em;
    }
    .sidebar {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 24em;
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
    /* div.content {
      display: block;
      margin-left: 1em;
      margin-right: 0px;
    } */
    article {     /* main content */
      /* display: block;
      float: left;
      width: 100%;
      height: auto;
      font-family: Arial; */
    }
    div.box{        /* main content */
      display: block;
      /* position: float; */
      margin-left: 24em;
      margin-right: 0em;
      margin-top: 1.5em;
      border-radius: 10px;
      background-color: green;
      text-align: center;
    }
    /* Responsive layout - makes the three columns stack on top of each other instead of next to each other on smaller screens (700px wide or less) */
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
    <ul>
      <li><a href="/doctors_menu.php">Main Menu</a></li>    <!-- Doctors Main menu -->
      <li><a href="/patientsinfo.php">Existing Patients</a></li>  <!-- shows the patients of the active user_id -->
      <li><a href="/addpatient.php">Add a new patient</a></li>  <!-- adds a new patient into the patients table with tha active doctor id -->
      <li><a href="/searching.php">Search Query</a></li>  <!-- Advanced search query via Attributes -->
    </ul>
  </div>
    <article>
      <div class="box">
        <button type="button" name="Logout" id="logout" class="button"><?php echo "<a href='logout.php'> Logout</a> "; ?></button>
        <h1>Welcome Doctor: <?php $user_name = $_SESSION['user'];
        echo "<b>".$user_name."</b>";?>! <!-- prints the active username --></h1>

        <br>
        <br>
        <!-- <img src="MSregistry_ionian2_bg_lightblue.png" alt="Logo"> -->
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
      </div>
    </article>
</body>
</html>

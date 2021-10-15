<?php session_start(); ?>
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
      margin-left: 10em;
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
      padding: 10px;
      /* background-color: #30819c; */
      text-decoration: none;
      color: black;
    }
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
      /* position: float; */
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
    #removeuser a {
      color:red;
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
    <ul>        <!-- side menu -->
      <li><a href="/doctors_menu_likeopencour.php">Main Menu</a></li>    <!-- Doctors Main menu -->
      <li><a href="/patientsinfo.php">Existing Patients</a></li>  <!-- shows the patients of the active user_id -->
      <li><a href=" ">Add a new patient</a></li>  <!-- adds a new patient into the patients table with tha active doctor id -->
      <li><a href="/searching.php">Search Query</a></li>  <!-- Advanced search query via Attributes -->
    </ul>

  </div>
  <div class="box">   <!-- main content of the page -->
    <button type="button" name="Logout" id="logout" class="button"><?php echo "<a href='logout.php'> Logout</a> "; ?></button>
    <h1>Welcome Doctor: <u><?php $user_name = $_SESSION['user'];
    echo $user_name;?></u>!</h1> <!-- prints the active username -->
    <br>
    <br>

      <?php
      //database connection
      $servername = "127.0.0.1";
      $username = "root";
      $password = "bioinformatics";
      $dbname = "BIHElab";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $doc = $_SESSION['user_id'];
      //getting the POST data
      $pat_id = $_POST['assignid'];
      $flname = $_POST['flname'];
      $phonenum = $_POST['phone'];
      $email = $_POST['email'];
      $Submit = $_POST['Submit'];

      try{  //using MySQL PDOAttribute for the Exceptions
        $sql = "INSERT INTO patients (Doctor_ID,Patient_id,Patient_name,Phonenum,Email,Submit) VALUES (?,?,?,?,?,?)";

        if(isset($_POST['Submit'])){
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$doc,$pat_id,$flname,$phonenum,$email,$Submit]);

        } else{
          echo "Something went wrong.";
        }

      } catch(PDOException $e){
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      ?>

      <form class="form" action="addpatient.php" method="post"> <!-- basic form to pass the data in the database for the creation of a new patient -->
        <table>
          <tr>
            <th>Assign a Patient ID</th><th>First and Last Name</th><th>Phone Number</th><th>Email</th>
          </tr>
          <tr>
            <td><input type="number" name="assignid"></td><td><input type="text" name="flname"></td>
            <td><input type="number" name="phone"></td><td><input type="email" name="email"></td>
          </tr>
        </table>
        <label for="Submit"> <input type="submit" name="Submit"></label>
      </form>
      <footer>
        <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
      </footer>
  </div>
</body>
</html>

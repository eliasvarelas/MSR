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
    button{
      float: right;
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
      display: block;
      float: left;
      padding: 20px;
      background-color: #6699ff;
      width: 100%;
      margin: auto;
      height: auto;
      font-family: Arial;
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
      padding:5px;
      text-align:center;
      font-family: arial;
    }
    th {
      background-color: #ccddff;              /* Title box color */
      color: black;
      margin: auto;
    }
    td {
      background-color: white;
      color: black;
      margin: auto;
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
      background-color: #1a66ff;

    }
    .form{    /* there is an overflowing-x issue with the form, make it Responsive */
      display: block;
      max-width: 100%;
      margin-right: 1em;
      /* text-align:center; */
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
    /* @media screen and (min-width: 1000px) {     /* Center the table in larger screens 
      .sidebar {
        width: 50%;
        height: auto;
        position: relative;
        float: left;
      }
      .sidebar a {float: left;}
      div.content {
        margin-left: 0%;
        margin-right: 50%;
      }
    } */

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
        <li><a href="/doctors_menu.php">Main Menu</a></li>
        <li><a href="/patientsinfo.php">Existing Patients</a></li>
        <li><a href=" ">Add a new patient</a></li>
        <li><a href="/searching.php">Search Query</a></li>
      </ul>

  </div>
  <div class="content">
    <article>
      <?php

      $servername = "127.0.0.1";
      $username = "root";
      $password = "bioinformatics";
      $dbname = "BIHElab";

      $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $doc = $_SESSION['user_id'];
      //get the POST data
      $pat_id = $_POST['assignid'];
      $flname = $_POST['flname'];
      $phonenum = $_POST['phone'];
      $email = $_POST['email'];
      $Submit = $_POST['Submit'];

      try{
          $sql = "INSERT INTO patients (Doctor_ID,Patient_id,Patient_name,Phonenum,Email,Submit) VALUES (?,?,?,?,?,?)";

          if(isset($_POST['Submit'])){

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$doc,$pat_id,$flname,$phonenum,$email,$Submit]);

            //error handling
            //Fetch the row.
            // $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //If the provided Patient ID already exists - display error.
            // if($row > 0){
            //     echo "That ID already exists!";
            //     die();
            // }

          } else{
              echo "Something went wrong. Sorry.";
          }
      } catch(PDOException $e){
          die("ERROR: Could not able to execute $sql. " . $e->getMessage());
      }
      ?>

      <form class="form" action="addpatient.php" method="post">
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
    </article>

  </div>
</body>
</html>

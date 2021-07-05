<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Doctors Menu</title>
    <style>
      @import url('https://fonts.googleapis.com/css?family=Lexend+Deca&display=swap');

      body {
        margin: 0;
        padding: 0;
        background: linear-gradient(
            #243545,
            #243545 var(--line-offset),
            #dedede var(--line-offset)
        );
        width: 100vw;
        height: 100vh;
        font-family: 'Lexend Deca', sans-serif;
        color: #878787;

        --menu-item-size: 50px;
        --green-color: #329680;
        --blue-color: #099c95;
        --dark-green-color: #175b52;
        --white-color: #FFF;
        --gray-color: #EDEDED;
        --container-width: 700px;
        --container-height: 400px;
        --line-offset: calc((100% - var(--container-height))/ 2 + var(--menu-item-size) + 0.6em);
      }


      .container {
        width: var(--container-width);
        height: var(--container-height);
        margin-left: -350px;
        margin-top: -300px;
        top: 50%;
        left: 50%;
        position: absolute;
        z-index: 1;
      }

      .main-menu {
        display: flex;
        list-style: none;
        margin: auto 0;
        padding: 0.6em 0 0 0;
      }

      .main-menu > li {
        box-sizing: border-box;
        height: var(--menu-item-size);
        width: calc(3.5 * var(--menu-item-size));
        line-height: var(--menu-item-size);
        padding: 0 2em;
        margin: 1px;
        transition: 0.5s linear all;
        background: var(--green-color);
        color: var(--dark-green-color);
        cursor: pointer;
        font-size: 16px;
        user-select: none;
      }

      .main-menu > li:not(.with-submenu) {
        clip-path: polygon(10% 0%, 0% 100%, 95% 100%, 100% 50%, 95% 0%);
        shape-outside: polygon(10% 0%, 0% 100%, 95% 100%, 100% 50%, 95% 0%);
      }

      .main-menu > li:nth-child(2) {
        transform: translateX(-5%);
      }

      .main-menu > li:nth-child(3) {
        transform: translateX(-10%)
      }

      .main-menu > li:nth-child(4) {
        transform: translateX(-15%)
      }

      .main-menu i {
        margin-right: 5px;
      }

      .main-menu > li:hover:not(.active) {
        background: linear-gradient(to right, var(--green-color), var(--blue-color));
        color: var(--white-color);
      }

      .main-menu > li:active:not(.active),
      .main-menu > li:active:not(.with-submenu){
        background: var(--blue-color);
        color: var(--white-color);
      }

      .main-menu > li:hover i:not(li.active) {
        color: #175c58;
      }

      .main-menu .active {
        color: var(--white-color);
        background: var(--blue-color);
        cursor: default;
        text-shadow: 1px 1px 1px var(--dark-green-color);
        font-size: 18px;
      }

      article {
       background: var(--gray-color);
       padding: 1em;
       border-radius: 0 0 5px 5px;
       box-shadow: 5px 5px 5px #CCC;
       position: relative;
       z-index: -1;
      }

      h1 {
        font-size: 115%;
        margin: 1em 2em;
        padding: 0;
        position: relative;
        color: #777;
      }

      .content {
        padding: 0 0 0 3em;
        font-size: 16px;
      }

      .submenu {
        display: none;
        position: absolute;
        z-index: 10;
        list-style: none;
        left: 0;
        margin: 0;
        padding: 0;
        min-width: calc(3.5 * var(--menu-item-size) - 5%);
        box-shadow: 5px 5px 5px #CCC;
      }

      .with-submenu {
        position: relative;
        display: inline-block;
        clip-path: polygon(10% 0%, 0% 100%, 0% 500%, 95% 500%, 95% 100%, 100% 50%, 95% 0%);
        shape-outside: polygon(10% 0%, 0% 100%, 95% 100%, 100% 50%, 95% 0%);
      }

      .with-submenu:hover .submenu {
        display: block;
      }

      .submenu > li {
        background: #dedede;
        border-bottom: 1px solid var(--gray-color);
        color: #777;
        padding: 1.2em 1em;
        transition: 0.3s all linear;
        display: block;
        line-height: 0%;
      }

      .submenu > li:hover {
        background: var(--gray-color)
      }

      .submenu > li:active {
        background: var(--white-color);
      }
      table, th, td {
        border-collapse: collapse;
        border: 1px solid black;
        padding:5px;
        text-align:center;
        font-family: arial;
      }
      th {
        background-color: #2980b9;         /* Title box color */
        color: black;
        margin: auto;
      }
      td {
        background-color: white;
        color: black;
      }

    </style>
  </head>
  <body>
    <div class="container">
           <nav class="menu">
               <ul class="main-menu">
                   <li class="active"><i class="fa fa-home"></i>Main Menu</li>
                    <li>Patients </li>
                   <li>Add Patient</li>
                   <li>Search</li>
               </ul>
           </nav>
           <article>
               <h1>Main Menu</h1>
               <div class="content">

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
                 <!-- <h4>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</h4>  <!-- Basic information for the app --> 
                 <div class="aligner">
                   <table style="align-self: center;">    <!-- prints the table with the patients -->
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
                                   <td><?php echo "<a href='/previousvisits.php?id=".$row['Patient_id']."'>Previous Visits</a>"; ?></td>
                                   <td><?php echo "<a href='/Multiple_Sclerosis_app.php?id=".$row['Patient_id']. "&?nm=". $row['Patient_name'] ."'>Add Follow up</a>"; ?></td> <!-- Passes the patients id in the form for minimazing user error -->
                                   <td><button onclick="remove_user()" id="removeuser"><?php echo "<a href='/removeuser.php?id=".$row['Patient_id']."'>Remove Patient</a>"; ?></button></td>  <!-- Removes only the patient with the particular id -->
                                 </tr>
                               <?php } } ?>
                   </table>
                 </div>
                 <footer>
                   <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
                 </footer>           </article>
       </div>
  </body>
</html>

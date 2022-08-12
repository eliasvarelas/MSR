<?php
  //database connection
  $servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";
  $table = "MSR";

  // get the $_POST info
  $NDS = $_POST['patientName'];
  $patientAddress = $_POST['patientAddress'];
  $NDSdate = $_POST['NDSdate'];
  $NDSnum = $_POST['NDSnum'];
  $Sex = $_POST['Sex'];
  $Age = $_POST['Age'];
  $Race = $_POST['Race'];
  $Comorbidities = $_POST['Comorbidities'];
  $convsprad = $_POST['convsprad'];
  $convspnum = $_POST['convspnum'];
  $dateofdia = $_POST['dateofdia'];
  $dateofdiarad = $_POST['dateofdiarad'];
  $onsetdate = $_POST['onsetdate'];
  $Noofrelapses = $_POST['Noofrelapses'];
  $Noofrelapsesrad = $_POST['Noofrelapsesrad'];
  $pastTREATMENTstart = $_POST['pastTREATMENTstart'];
  $pastTREATMENT = $_POST['pastTREATMENT'];
  $pastTREATMENTdate = $_POST['pastTREATMENTdate'];
  $pastTREATMENTcheck = $_POST['pastTREATMENTcheck'];
  $TREATMENTdate = $_POST['TREATMENTdate'];
  $TREATMENT = $_POST['TREATMENT'];
  $eddsscore = $_POST['eddsscore'];
  $edsstime7_5m = $_POST['edsstime'];
  $edssPEG = $_POST['edsstimePEG'];
  $EDSSdate = $_POST['EDSSdate'];
  $EDSSdaterad = $_POST['EDSSdaterad'];
  $MRIonset = $_POST['MRIonsetlocalisation'];
  $Pregnant = $_POST['Pregnant'];
  $Onsetlocalisation = $_POST['Onsetlocalisation'];
  $smoker = $_POST['smoker'];
  $cigars = $_POST['cigars'];
  $cigardate = $_POST['cigardate'];
  $onsetsymptoms = $_POST['onsetsymptoms'];
  $MRIonsetlocalisation = $_POST['MRIonsetlocalisation'];
  $MRIenhancing = $_POST['MRIenhancing'];
  $MRInum = $_POST['MRInum'];
  $MRIenhancinglocation = $_POST['MRIenhancinglocation'];
  $signer = $_POST['signer'];
  $submit = $_POST['Submit'];

  try {     //Connection!
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully\n";

    //checking that the Connection doesnt return null
  if (is_null($pdo)) {
    return $pdo -> prepare($query);
    echo"Connection is null";
  } else {
    echo "Connection active! \n";
  }
  //cahtching exceptions
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  try {

    
    //get the value of the last visit number
    $query = "SELECT visit_id FROM MSR WHERE NDSnum = $NDSnum";
    $result = $pdo->query($query);
      if ($result->rowCount() > 0) {
        $visit_id = $row['visit_id'];
        if ($visit_id == null) {
          $visit_id = 0;
        }
        $newvisit_id = $visit_id + 1;
      }


    // inserting the data from the form in the MSR table
    $sql = "INSERT INTO $table (visit_id,NDS,NDSdate,NDSnum,`address`,Sex,Age,Race,Comorbidities,convsprad,convspnum, dateofdia,dateofdiarad,
    onsetdate, Noofrelapses,Noofrelapsesrad,pastTREATMENTstart,
    pastTREATMENT,pastTREATMENTdate,pastTREATMENTcheck,TREATMENTdate, TREATMENT, eddsscore,edsstime7_5m,edsstimePEG,
    EDSSdate,Pregnant, Onsetlocalisation, smoker,cigars,cigardate, onsetsymptoms,MRIonsetlocalisation,MRIenhancing,MRInum,MRIenhancinglocation,
    signer,Submit)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";     //using prepared statements for security towards sql injections

    //Execute
  if (isset($_POST["Submit"])) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newvisit_id,$NDS,$NDSdate,$NDSnum,$patientAddress,$Sex,$Age,$Race,$Comorbidities,$convsprad,$convspnum,$dateofdia,
    $dateofdiarad,$onsetdate,$Noofrelapses,$Noofrelapsesrad,$pastTREATMENT,
    $pastTREATMENTdate,$pastTREATMENTcheck,$TREATMENTdate,$TREATMENT,$eddsscore,$edsstime7_5m,$edssPEG,$EDSSdate,$Pregnant,
    $Onsetlocalisation,$smoker,$cigars,$cigardate,$onsetsymptoms,$MRIonsetlocalisation,$MRIenhancing,$MRInum,
    $MRIenhancinglocation,$signer,$Submit]);
    echo "records inserted successfully!!!!!!!!";
  } else {
    echo "Im sorry, there was an error";  // basic error handling
  }

  // if ($sql) {
  //   //Redirect to the Doctors Menu
  //   $script = file_get_contents('redirectMenu.js');
  //   echo "<script>".$script."</script>";
  // } else {
  //   echo "something went wrong";
  // }

  }catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    echo"<div class='error'>";
      die("ERROR: Could not able to execute $sql. " . $e->getMessage());
    echo "</div>";
  }

?>

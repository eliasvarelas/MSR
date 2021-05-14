<?php
  //database connection
  $servername = "127.0.0.1";
  $username = "root";
  $password = "bioinformatics";
  $dbname = "BIHElab";
  $table = "MSR";

  // get the $_POST info
  $NDS = $_POST['NDS'];
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
  $Pregnant = $_POST['Pregnant'];
  $Onsetlocalisation = $_POST['Onsetlocalisation'];
  $smoker = $_POST['smoker'];
  $cigars = $_POST['cigars'];
  $cigardate = $_POST['cigardate'];
  $onsetsymptoms = $_POST['onsetsymptoms'];
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
    // inserting the data from the form in the MSR table
    $sql = "INSERT INTO $table (NDS,NDSdate,NDSnum,Sex,Age,Race,Comorbidities,convsprad,convspnum, dateofdia,dateofdiarad,
    onsetdate, Noofrelapses,Noofrelapsesrad,
    pastTREATMENT,pastTREATMENTdate,pastTREATMENTcheck,TREATMENTdate, TREATMENT, eddsscore,edsstime7_5m,edsstimePEG,
    EDSSdate,
    Pregnant, Onsetlocalisation, smoker,cigars,cigardate, onsetsymptoms,signer,Submit)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";        //using prepared statements for security towards sql injections

    //Execute
  if (isset($_POST["Submit"])) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$NDS,$NDSdate,$NDSnum,$Sex,$Age,$Race,$Comorbidities,$convsprad,$convspnum,$dateofdia,$dateofdiarad,$onsetdate,$Noofrelapses,$Noofrelapsesrad,$pastTREATMENT,
    $pastTREATMENTdate,$pastTREATMENTcheck,$TREATMENTdate,$TREATMENT,$eddsscore,$edsstime7_5m,$edssPEG,$EDSSdate,$Pregnant,$Onsetlocalisation,$smoker,$cigars,$cigardate,$onsetsymptoms,$signer,$Submit]);
    echo "records inserted successfully!!!!!!!!";
  } else {
    echo "Im sorry, there was an error";  // basic error handling
  }

  if ($sql) {
    //Redirect to the Doctors Menu
    $script = file_get_contents('redirectMenu.js');
    echo "<script>".$script."</script>";
  }

  }catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
  }

?>

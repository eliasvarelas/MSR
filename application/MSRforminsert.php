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
    

    //checking that the Connection doesnt return null
  if (is_null($pdo)) {
    // return $pdo -> prepare($query);
    echo"Connection is null";
  } else {
    echo "Connection active! \n";
  }
  //cahtching exceptions
  } catch(PDOException $e) {
    // echo "Connection failed: " . $e->getMessage();
  }

  try {
	  
    // inserting the data from the form in the MSR table
    $sql = "INSERT INTO MSR (NDS,NDSdate,NDSnum,address,Sex,Age,Race,Comorbidities,convsprad,convspnum, 
	dateofdia,dateofdiarad,onsetdate, Noofrelapses,Noofrelapsesrad,pastTREATMENTstart,
    pastTREATMENT,pastTREATMENTdate,pastTREATMENTcheck,TREATMENTdate, TREATMENT, eddsscore,edsstime7_5m,
	edsstimePEG,EDSSdate,Pregnant, Onsetlocalisation, smoker,cigars,cigardate, onsetsymptoms,MRIonsetlocalisation,
	MRIenhancing,MRInum,MRIenhancinglocation,signer,Submit)
    VALUES (:NDS,:NDSdate,:NDSnum,:address,:Sex,:Age,:Race,:Comorbidities,:convsprad,:convspnum,
	:dateofdia,:dateofdiarad,:onsetdate,:Noofrelapses,:Noofrelapsesrad,:pastTREATMENTstart,:pastTREATMENT,
	:pastTREATMENTdate,:pastTREATMENTcheck,:TREATMENTdate,:TREATMENT,:eddsscore,:edsstime7_5m,:edsstimePEG,
	:EDSSdate,:Pregnant,:Onsetlocalisation,:smoker,:cigars,:cigardate,:onsetsymptoms,:MRIonsetlocalisation,:MRIenhancing,:MRInum,:MRIenhancinglocation,
	:signer,:Submit)";     //using prepared statements for security towards sql injections

    //Execute
  if (isset($_POST["Submit"])) {
    $stmt = $pdo->prepare($sql);
	 $stmt->bindValue(":NDS",$NDS);
	 $stmt->bindValue(":NDSdate",$NDSdate);
	 $stmt->bindValue(":NDSnum",$NDSnum);
	 $stmt->bindValue(":address",$patientAddress);
	 $stmt->bindValue(":Sex",$Sex);
	 $stmt->bindValue(":Age",$Age);
	 $stmt->bindValue(":Race",$Race);
	 $stmt->bindValue(":Comorbidities",$Comorbidities);
	 $stmt->bindValue(":convsprad",$convsprad);
	 $stmt->bindValue(":convspnum",$convspnum);
	 $stmt->bindValue(":dateofdia",$dateofdia);
	 $stmt->bindValue(":dateofdiarad",$dateofdiarad);
	 $stmt->bindValue(":onsetdate",$onsetdate);
	 $stmt->bindValue(":Noofrelapses",$Noofrelapses);
	 $stmt->bindValue(":Noofrelapsesrad",$Noofrelapsesrad);
	 $stmt->bindValue(":pastTREATMENTstart",$pastTREATMENTstart);
	 $stmt->bindValue(":pastTREATMENT",$pastTREATMENT);
	 $stmt->bindValue(":pastTREATMENTdate",$pastTREATMENTdate);
	 $stmt->bindValue(":pastTREATMENTcheck",$pastTREATMENTcheck);
	 $stmt->bindValue(":TREATMENTdate",$TREATMENTdate);
	 $stmt->bindValue(":TREATMENT",$TREATMENT);
	 $stmt->bindValue(":eddsscore",$eddsscore);
	 $stmt->bindValue(":edsstime7_5m",$edsstime7_5m);
	 $stmt->bindValue(":edsstimePEG",$edsstimePEG);
	 $stmt->bindValue(":EDSSdate",$EDSSdate);
	 $stmt->bindValue(":Pregnant",$Pregnant);
	 $stmt->bindValue(":Onsetlocalisation",$Onsetlocalisation);
	 $stmt->bindValue(":smoker",$smoker);
	 $stmt->bindValue(":cigars",$cigars);
	 $stmt->bindValue(":cigardate",$cigardate);
	 $stmt->bindValue(":onsetsymptoms",$onsetsymptoms);
	 $stmt->bindValue(":MRIonsetlocalisation",$MRIonsetlocalisation);
	 $stmt->bindValue(":MRIenhancing",$MRIenhancing);
	 $stmt->bindValue(":MRInum",$MRInum);
	 $stmt->bindValue(":MRIenhancinglocation",$MRIenhancinglocation);
	 $stmt->bindValue(":signer",$signer);
	 $stmt->bindValue(":Submit",$Submit);
	  
	  $stmt->execute();
		 
		 
		 
	// $stmt->execute([$NDS,$NDSdate,$NDSnum,$patientAddress,$Sex,$Age,$Race,
	// $Comorbidities,$convsprad,$convspnum,$dateofdia,
	// $dateofdiarad,$onsetdate,$Noofrelapses,$Noofrelapsesrad,$pastTREATMENT,
	// $pastTREATMENTdate,$pastTREATMENTcheck,$TREATMENTdate,$TREATMENT,$eddsscore,$edsstime7_5m,
	// $edssPEG,$EDSSdate,$Pregnant,
	// $Onsetlocalisation,$smoker,$cigars,$cigardate,$onsetsymptoms,$MRIonsetlocalisation,$MRIenhancing,
	// $MRInum,$MRIenhancinglocation,$signer,$Submit]);
    echo "records inserted successfully!!!!!!!!";
  } else {
    echo "Im sorry, there was an error";  // basic error handling
  }

  if ($sql) {
    //Redirect to the Doctors Menu
    $script = file_get_contents('redirectMenu.js');
    echo "<script>".$script."</script>";
  } else {
    echo "something went wrong";
  }

  }catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
    echo"<div class='error'>";
      die("ERROR: Could not able to execute $sql. " . $e->getMessage());
    echo "</div>";
  }

?>

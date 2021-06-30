<?php
$servername = "127.0.0.1";
$username = "root";
$password = "bioinformatics";
$dbname = "BIHElab";

try {     //Connection!
  $conn = new PDO("mysql:host=$servername;dbname=$dbname;", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully<br>";

// sql to create table

  $sql = "CREATE TABLE MSR (
   id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   NDS VARCHAR(50) NOT NULL,
   NDSdate DATE NOT NULL,
   NDSnum INT(20) NOT NULL,
   Sex enum('Male','Female') NOT NULL,
   Age INT(30) NOT NULL,
   Race enum ('American Indian','Asian','Black','Hispanic','Caucasian','Unknown')NOT NULL,
   Comorbidities VARCHAR(1000) NOT NULL,
   convsprad enum('RR','SP','PP','Other') NOT NULL,
   convspnum INT(30),
   dateofdia DATE NOT NULL,
   dateofdiarad enum('RR','SP','PP','Other') NOT NULL,
   onsetdate DATE NOT NULL,
   Noofrelapses INT(50) NOT NULL,
   Noofrelapsesrad enum('Mild','Moderate','Severe') NOT NULL,
   pastTREATMENT enum('Alemtuzumab','Avonex','Betaferon','Copaxone','Extavia','Fingolimod','Mitoxantrone','Natalizumab','Ocrelizumab','Rebif','Tecfidera','Teriflunomide','None') NOT NULL,
   pastTREATMENTdate DATE NOT NULL,
   pastTREATMENTcheck enum('Lack of efficasy','Side effects','Other') NOT NULL,
   TREATMENTdate DATE NOT NULL,
   TREATMENT enum('Alemtuzumab','Avonex','Betaferon','Copaxone','Extavia','Fingolimod','Mitoxantrone','Natalizumab','Ocrelizumab','Rebif','Tecfidera','Teriflunomide','None') NOT NULL,
   eddsscore INT(10) NOT NULL,
   edsstime7_5m time,
   edsstimePEG time,
   EDSSdate DATE NOT NULL,
   Pregnant enum('Yes','No'),
   Onsetlocalisation enum('Spinal','Cortex','Visual','Cerebellar/Brainstem'),
   smoker enum('Yes','No') NOT NULL,
   cigars INT(10),
   cigardate DATE,
   onsetsymptoms enum('Vision','Motor','Sensory','Coordination','Bowel/Bladder','Fatigue','Cognitive','Encephalopathy','Other'),
   MRIonsetlocalisation enum('Spinal','Cortex','Brainstem','Cerebellum','Visual'),
   MRIenhancing enum ('Yes','No') NOT NULL,
   MRInum INT(50),
   MRIenhancinglocation enum('Spinal','Cortex','Brainstem','Cerebellum','Visual'),
   signer VARCHAR(50) NOT NULL,
   Submit TINYINT,
   reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

 // use exec() because no results are returned
 $conn->exec($sql);
 echo "Table created successfully";
} catch(PDOException $e) {
 echo $sql . " \n" . $e->getMessage();
}

$conn = null;         // terminates the connection to the DB
if ($conn==null) {
  echo" connection terminated";
}
else {
  echo" still active";
}

?>

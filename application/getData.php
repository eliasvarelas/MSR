<?php
session_start();
error_reporting(0);
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 18000)) {
  // last request was more than 30 minutes ago
  session_unset();     // unset $_SESSION variable for the run-time
  session_destroy();   // destroy session data in storage
  $scripttimedout = file_get_contents('timeout.js');
  echo "<script>" . $scripttimedout . "</script>";
} 
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$usersid = $_SESSION['user_id'];
$servername = "localhost";
$username = "phpmyadmin";
$password = "root";
$dbname = "MSR";


if( $_SERVER['REQUEST_METHOD']=='POST' && isset(
        $_POST['attributes'],
        $_POST['charts']
    )){
        $pdo = new PDO( "mysql:host=$servername;dbname=$dbname", $username, $password );
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        
        $args=array();
        $attributes = $_POST['attributes'];
        $charts = $_POST['charts'];
        
        /*
            create suitable SQL statement and $args array
            for each $attribute that you wish to plot on a chart.
            
            Use a Prepared statement to mitigate SQL injection attacks.
        */
        switch( $attributes ){
            case 'Patient_name':
                $sql='SELECT `Patient_name` as name, count(*) as number 
                          FROM patients WHERE Doctor_ID = :userid GROUP BY Patient_name';
                $args=array(
                    ':userid'   =>  $usersid
                );
            break;
            
            case 'Patient_id':
                $sql='SELECT `Patient_id` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Patient_name';
                $args=array(':userid'   =>  $usersid);
            break;
            
            case 'Sex':
                $sql='SELECT `Sex` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Sex';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'Race':
                $sql='SELECT `Race` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Race';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'Age':
                $sql='SELECT `Age` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Age';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'Comorbidities':
                $sql='SELECT `Comorbidities` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Comorbidities';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'Email':
                $sql='SELECT `Email` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Email';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'eddsscore':
                $sql='SELECT `eddsscore` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY eddsscore';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'Phonenum':
                $sql='SELECT `Phonenum` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Phonenum';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'onsetsymptoms':
                $sql='SELECT `onsetsymptoms` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY onsetsymptoms';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'Onsetlocalisation':
                $sql='SELECT `Onsetlocalisation` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Onsetlocalisation';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'smoker':
                $sql='SELECT `smoker` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY smoker';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'Pregnant':
                $sql='SELECT `Pregnant` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY Pregnant';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'MRIenhancing':
                $sql='SELECT `MRIenhancing` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY MRIenhancing';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'MRInum':
                $sql='SELECT `MRInum` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY MRInum';
                $args=array(':userid'   =>  $usersid);
            break;
				
			case 'MRIonsetlocalisation':
                $sql='SELECT `MRIonsetlocalisation` as name, count(*) as number 
                          FROM patients JOIN MSR ON patients.Patient_id = MSR.NDSnum 
                          WHERE Doctor_ID = :userid
                          GROUP BY MRIonsetlocalisation';
                $args=array(':userid'   =>  $usersid);
            break;
            
            
        }
        
        if( isset( $sql, $args ) ){
            $stmt=$pdo->prepare( $sql );
            $stmt->execute( $args );
            
            http_response_code( 200 );
            exit( json_encode( $stmt->fetchAll( PDO::FETCH_OBJ ) ) );
        }
        
        # no sql to run...
        http_response_code( 400 );
    }
    
    # only allow POST requests
    http_response_code( 404 );
?>


?>
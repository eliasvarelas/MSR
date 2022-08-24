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
?>

<?php 
		$usersid = $_SESSION['user_id'];
		$servername = "localhost";
		$username = "phpmyadmin";
		$password = "root";
		$dbname = "MSR";
			
	  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      // set the PDO error mode to exception
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>MS Registry Searching Queries</title>

  <!-- Bootstrap CSS CDN -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  <!-- Our Custom CSS -->
  <link rel="stylesheet" href="basicapp-notnow.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  
	<!--Load the AJAX API-->
	<script src="/MSR/application/jquery.js"></script>
    <script src="https://www.google.com/jsapi"></script>
        <script>
            google.load('visualization', '1.0', { 'packages':['corechart'] });
        </script>

	
	<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> -->
	
    


</head>

<body>

  <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
      
            <div class="sidebar-header">
                <h3>Multiple Sclerosis Registry</h3>
                <strong>MSR</strong>
            </div>

            <ul class="list-unstyled components">
                <li class="">
                    <a href="admins_menu.php">
                        <i class="fas fa-home"></i>
                        Admins Page
                    </a>
                </li>

                 <li>
                    <a href="addDoctor.php">
                        <i class="fas fa-user-plus"></i>
                        Add a Doctor
                    </a>
                </li>
                <!--<li>
                    <a href="">
                        <i class="fas fa-user-plus"></i>
                        Add a new Patient
                    </a>
                </li>-->
				<li class="">
					<a href="admin_searching.php" class="dropdown-toggle" ::after>
						<i class="fas fa-search"></i>
						Search Doctors
					</a>
				</li>
				<li class="">
					<a href="admin_patient_searching.php">
						<i class="fas fa-search"></i>
						Advanced Patient Search
					</a>
				</li> 
				<li class="active">
				  <a href="">
					<i class="fas fa-chart-bar"></i>
					Visual Analytics Tool
				  </a>
				</li>
            </ul>
        </nav>

    <!-- Page Content  -->
    <div id="content">

      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

          <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="fas fa-align-left"></i>
            <span>Toggle Sidebar</span>
          </button>
          <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-align-justify"></i>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
              <li class="navbar-nav">
                <a class="nav-link" id="">
                  <i class="fas fa-user"></i>
                  Doctor: <u><?php $user_name = $_SESSION['user'];
									
                              echo $user_name; ?></u>
                </a>
                <a href="logout.php" onclick="return confirm('Are you sure to logout?');">
                  <button type="button" id="logoutBtn" class="navbar-btn btn btn-info">
                    <!-- <i class="fa fa-sign-out"></i> -->
                    <span>Logout</span>
                  </button>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>


      <form name='chart-options' method='POST'>
        <table>
          <tr>
            <th>Type of Chart</th>
            <td colspan='3'>
              <select name='charts'>
                <option value='Pie_chart'>Pie chart
                <option value='Bar_chart'>Bar chart
                <option value='Col_chart'>Column chart
                <option value='Area_chart'>Area
                <option value='Line_chart'>Line chart
              </select>
            </td>
            <th>Select an Attribute</th>
            <td colspan='3'>
              <select name='attributes'>
                <option value='Patient_name'>Name
                <option value='Patient_id'>ID
                <option value='Sex'>Sex
                <option value='Race'>Race
                <option value='Age'>Age
                <option value='Comorbidities'>Comorbidities
                <option value='Email'>Email
                <option value='eddsscore'>EDSS Score
                <option value='Phonenum'>Phone Number
                <option value='onsetsymptoms'>Onset Symptoms
                <option value='Onsetlocalisation'>Onset Localisation
                <option value='smoker'>Smoker
                <option value='Pregnant'>Pregnant
                <option value='MRIenhancing'>MRI Enhanced Lesions
                <option value='MRInum'>MRI Enhanced Lesions Number
                <option value='MRIonsetlocalisation'>MRI Onset Localisation
              </select>
            </td>
          </tr>
        </table>
        <button name='create' type='button' value='Create Graph'>Create Graph</button>
      </form>
      <div id="chart_div"></div>
      <footer>
        <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
      </footer>
    </div>
  </div>
	  
  <!-- Popper.JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  <script type="text/javascript">//sidebarCollapse
    $(document).ready(function() {
      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
      });
    });
  </script>
	  
	  <script>
        
        const q=(e,n=document)=>n.querySelector(e);
        
        const getchart=(type)=>{
            let container=q('#chart_div');
            switch( type ){
                case 'Pie_chart':return new google.visualization.PieChart( container );
                case 'Bar_chart':return new google.visualization.BarChart( container );
                case 'Col_chart':return new google.visualization.ColumnChart( container );
                case 'Area_chart':return new google.visualization.AreaChart( container );
                case 'Line_chart':return new google.visualization.LineChart( container );
                default: return false;
            }           
        };
        
        const drawChart=( json,type )=>{
            let dataTbl=new google.visualization.DataTable();
                dataTbl.addColumn('string', 'Name');
                dataTbl.addColumn('number', 'Number');
                
            Object.keys( json ).forEach(key=>{
            	dataTbl.addRow( [ json[ key ].name,parseFloat( json[ key ].number) ] );
				
            })
            console.log(dataTbl);	
            let args={
                'title':'',
                'width':800,
                'height':400
            };
            let chart=getchart( type );
            if( chart ) chart.draw( dataTbl, args );
            else alert('Error loading Chart');
        }
        
        
        q('button[name="create"]').addEventListener('click',e=>{
            let fd=new FormData( q('form[name="chart-options"]') );
            
            fetch( 'getData_admin.php', { method:'post', body:fd } )
                .then( r=>r.json() )
                .then( json=>{
                    drawChart( json, q('select[name="charts"]').value );
                })
        })
      </script>
	  
	  
</body>

</html>


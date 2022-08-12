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
  <link rel="stylesheet" href="basicapp.css">

  <!-- Font Awesome JS -->
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
  <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
  <!-- <script src="https://d3js.org/d3.v4.min.js"></script> -->
  <!-- <script src="visual_analytics.js" charset="utf-8"></script> -->
  <script src="https://d3js.org/d3.v4.js"></script>

</head>

<body>

  <div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
      <div class="sidebar-header">
        <h3><a href="menu.php" id="logo">Multiple Sclerosis Registry<a /></h3>
        <strong><a href="menu.php" id="logo">MSR</a></strong>
      </div>

      <ul class="list-unstyled components">
        <li>
          <a href="menu.php">
            <i class="fas fa-home"></i>
            Home
          </a>

        </li>
        <li>
          <a href="patientinfo-bootstrap.php">
            <i class="fas fa-folder"></i>
            Existing Patients
          </a>


        </li>
        <!-- <li>
          <a href="editPatientInfo.php">
            <i class="fas fa-edit"></i>
            Edit Patient Info
          </a>
        </li> -->
        <li>
          <a href="addpatient-bootstrap.php">
            <i class="fas fa-user-plus"></i>
            Add a new Patient
          </a>
        </li>
        <li>
          <a href="searching-bootstrap.php">
            <i class="fas fa-search"></i>
            Advanced Search
          </a>
        </li>
        <li class="active">
          <a href=" ">
            <i class="fas fa-chart-bar"></i>
            Visual Analytics Tool D3
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


      <form action="visual_analytics.php" method="POST" id="form">


        <table class="" id="d3-searching">

          <tr id="type_of_chart_row">
            <!-- select the type of chart you want -->
            <th>Type of Chart</th>
            <td colspan="3" class="">
              <select id="type_of_chart" name="charts">
                <option value="Pie_chart">Pie chart</option> <!-- classic pie -->
                <option value="Bar_chart">Bar chart</option> <!-- classic pie -->
              </select>
            </td>
          <!-- </tr> -->

          <!-- <tr id=""> -->
            <!-- select the attribute for which the chart will be printed -->
            <th>Select an Attribute</th>

            <td colspan="3" id="attribute_row">
              <select id="attributes" name="attributes">
                <option value="Sex" id="p_Sex">Sex</option>
              </select>
            </td>

          </tr>
        </table>

        <button type="submit" name="makeGraph" value="Create Graph" id="test" onclick="Graphs()">Create Graph</button>
      
      </form>
      
      <!-- this is supposed to be the spase where the graphs appear -->
      <div class="d3-wrapper">

        
        <div class="border" id="d3-container">
          <p>here</p>
        </div>

      </div>      

      <!-- <div class="line" /> -->
      <footer>
        <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
      </footer>

    </div>
  </div>
  <?php

        $usersid = $_SESSION['user_id'];
        $servername = "127.0.0.1";
        $username = "root";
        $password = "bioinformatics";
        $dbname = "BIHElab";
        // get data from the form
        $createGraph = $_POST['makeGraph'];

        if (isset($_POST['makeGraph'])) {
          try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // making this file a test file, GOAL IS just 1 fully working graph!
            // $sql = "SELECT NDS,Sex FROM MSR";

            // $stmt = $dbh->prepare($sql);
            // $stmt->execute();

            // $result = $pdo->query($sql);

            // if ($result->rowCount() > 0) {
            //   echo "while shoold work";
            //   while ($row = $result->fetch()) {
            //     $var1 = array (
            //       'Patient Name' => $row['NDS'],
            //       'Sex' => $row['Sex']
            //     );
            //   }
            // }
            // $jsonformat = json_encode($var1); //transforms the php array in json format
            // // $out = array_values($var1);
            // echo $jsonformat;

            
            
            $statement = $pdo->prepare("SELECT NDS,Sex FROM MSR");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $json = json_encode($results);
            // echo $json;
            
            $fp = fopen('File.json', 'w');
            fwrite($fp, $json);
            fclose($fp);




          } catch (PDOException $e) {
            echo"<div class='error'>";
              echo $statement . "<br>" . $e->getMessage();
              die("ERROR: Could not able to execute $sql. " . $e->getMessage());
            echo "</div>";
          }
        }


        ?>

  <!-- jQuery CDN - Slim version (=without AJAX) -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
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
  <script type="text/javascript">
    // var option = document.getElementById('test').onclick = function somefun() {
      



function Graphs() {
    var type = document.getElementById('type_of_chart');

    if (type.value == "Pie_chart") {

      d3.request("http://localhost:8000")
        .mimeType("application/json")
        .response(function(xhr) { return JSON.parse(xhr.responseText); });

      
        var width = 960,
        height = 500,
        radius = Math.min(width, height) / 2;
        
        console.log("pie1");
        
        var color = d3.scaleOrdinal()
        .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);
        
        console.log("pie2");
        
        var arc = d3.arc()
        .outerRadius(radius - 10)
        .innerRadius(0);

        console.log("pie3");
        
        // defines wedge size
        var pie = d3.pie()
        .sort(null)
        .value(function(d) { return d.ratio; });
        
        console.log("varpie");
        
        
        var svg = d3.select("#d3-container").append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");
        
        console.log("varsvg - beforeJSON - pie");
        
        d3.json("JSON_File.json", function(error, data) {
          if (error) throw error;
          console.log(data);
        });
      
      console.log("some test");
      
      //* crashes here, "Uncaught (in promise) TypeError: NetworkError when attempting to fetch resource."
      // d3.json("File.json", function(error, data) {
        //     node = data.data[0].ap[0];
        
        //     console.log(data);
        
        
        //     var g = svg.selectAll(".arc")
        //         .data(pie(node))
        //         .enter().append("g")
        //         .attr("class", "arc");
        
        //     g.append("path")
        //         .attr("d", arc)
        //         .style("fill", function(d) { return color(d.data.floor); });
        
        //     g.append("text")
        //         .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
        //         .attr("dy", ".35em")
        //         .style("text-anchor", "middle")
        //         .text(function(d) { return d.data.floor; });
        // });
        
        

        // d3.json("File.json").then(function(data) {
        //   console.log(data);
        // });
        
        
      } else if (type.value == 'Bar_chart') { // needs an edit but works fine
        var width = 960,
        height = 500,
        radius = Math.min(width, height) / 2;

        console.log("bar1");

        var color = d3.scaleOrdinal()
            .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

        console.log("bar2");

        var arc = d3.arc()
            .outerRadius(radius - 10)
            .innerRadius(0);

        console.log("bar3");

        // defines wedge size
        var pie = d3.pie()
            .sort(null)
            .value(function(d) { return d.ratio; });

        console.log("Bar");


        var svg = d3.select("#d3-container").append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        console.log("varsvg - beforeJSON - bar");


        d3.json("File.json", function(error, data) {
            node = data.data[0].ap[0];

            console.log("inJSON - bar");


            var g = svg.selectAll(".arc")
                .data(pie(node))
                .enter().append("g")
                .attr("class", "arc");

            g.append("path")
                .attr("d", arc)
                .style("fill", function(d) { return color(d.data.floor); });

            g.append("text")
                .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
                .attr("dy", ".35em")
                .style("text-anchor", "middle")
                .text(function(d) { return d.data.floor; });
        });
    }
}

// }
</script>
</body>

</html>


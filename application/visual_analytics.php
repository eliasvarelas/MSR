<?php session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 18000)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    $scripttimedout = file_get_contents('timeout.js');
    echo "<script>".$scripttimedout."</script>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
// TODO: need to make a function that will invert the y and x axis based on the chart type... (hor_bar/vert_bar)
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
    <link rel="stylesheet" href="style4.css">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>
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
                <li>
                    <a href="/menu.php" >
                        <i class="fas fa-home"></i>
                        Home
                    </a>

                </li>
                <li>
                    <a href="/patientinfo-bootstrap.php">
                        <i class="fas fa-folder"></i>
                        Existing Patients
                    </a>


                </li>
                <li>
                    <a href="/addpatient-bootstrap.php">
                        <i class="fas fa-user-plus"></i>
                        Add a new Patient
                    </a>
                </li>
                <li>
                    <a href="/searching-bootstrap.php">
                        <i class="fas fa-search"></i>
                        Advanced Search
                    </a>
                </li>
                <li class="active">
                    <a href=" ">
                        <i class="fas fa-paper-plane"></i>
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

            <div class="d3-wrapper">
              <table class="table-bordered" id="d3-searching">
                <tr id="type_of_chart_row"> <!-- select the type of chart you want -->
                  <th>Type of Chart</th>
                  <td colspan="3" class=""><select id="type_of_chart">
                    <option value="Pie_chart">Pie chart</option> <!-- classic pie -->
                    <option value="donut_chart">Donut Chart</option> <!-- pie chart with a whole in the middle -->
                    <option value="vert_bar">Vertical Bar Chart</option>  <!-- typical bar graph -->
                    <option value="hor_bar">Horizontal Bar Chart</option> <!-- horizontal bar graph with Y axis as a base -->
                    <option value="line_chart">Line Chart</option>  <!-- One line with multiple values -->
                  </select></td>
                  <th id="Num_Years_header" hidden>Enter the Time span in Years</th>
                  <th id="Num_Persons_header" hidden>Enter the Number of Persons</th>
                  <th id="Attributes_header" hidden>Choose the Attributes</th>
                  <th id="Medication_header" hidden>Choose the Medication</th>
                </tr>

                <tr id="attribute_row"> <!-- select the attribute for which the chart will be printed -->
                  <th>Select an Attribute</th>
                  <td colspan="3" class=""><select id="attributes">
                    <option value="Name" id="p_Name">Patient Name</option>
                    <option value="Sex" id="p_Sex">Sex</option>
                    <option value="Age" id="p_Age">Age</option>
                    <option value="Race" id="p_Race">Race</option>
                    <option value="Comorbidities" id="p_Comorbidities">Comorbidities</option>
                    <option value="EDSS" id="p_eddsscore">EDSS Score</option>
                    <option value="Past_medication">Past Medication</option>
                    <option value="Current_medication">Current Medication</option>
                    <option value="Pregnant" id="p_Pregnant">Is Pregnant</option>
                    <option value="Onsetlocalisation" id="p_Onsetlocalisation">Onset Localisation</option>
                    <option value="Smoker" id="p_Smoker">Is a Smoker</option>
                    <option value="onsetsymptoms" id="p_onsetsymptoms">Onset Symptoms</option>
                    <option value="MRIenhancing" id="p_MRIenhancing">MRI Enhancing Lesions</option>
                    <option value="MRInum" id="p_MRInum">MRI Lesion No.</option>
                    <option value="MRIonsetlocalisation" id="p_MRIonsetlocalisation">MRI Onset Localisation</option>
                  </select></td>
                </tr>

                <tr id="x_axis_row" hidden> <!-- the x axis for the horizontal bar chart -->
                  <th>X Axis</th>
                  <td colspan="3" class=""><select>
                    <option value="patient_names" hidden>Patient Names</option>
                    <option value="patient_ids" hidden>Patient IDs</option>
                    <option value="Values" selected>Values</option>
                  </select></td>
                </tr>

                <tr id="y_axis_row" hidden> <!-- the y axis for the horizontal bar chart -->
                  <th>Y Axis</th>
                  <td colspan="3" class=""><select id="y_axis_select">
                    <option value="time">Time span</option>
                    <option value="Num_Persons">Number of Persons</option>
                    <option value="Attributes">Attributes</option>
                    <option value="Medication">Medication</option>
                    <!-- <option value=""></option> -->
                  </select></td>
                  <td id="Num_of_persons_on_y" hidden><input id="Num_of_persons_on_y_input" name="Numofper" type="number" placeholder="Enter No. of Persons"></td>
                  <td id="Num_of_years_on_y" hidden><input id="Num_of_years_on_y_input" name="Numofyears" type="text" placeholder="ex. 2010-2021"></td> <!-- not really practical, but ok fo now -->
                  <td id="Attributes_on_y" hidden>
                    <select id="attributes_on_y_select" name="Attribute">
                      <option value="Age">Age</option>
                      <option value="Sex">Sex</option>
                      <option value="Race">Race</option>
                      <option value="Comorbidities">Comorbidities</option>
                      <option value="EDSS">EDSS Score</option>
                      <option value="Past_medication">Past Medication</option>
                      <option value="Current_medication">Current Medication</option>
                      <option value="Pregnant">Is Pregnant</option>
                      <option value="Onsetlocalisation">Onset Localisation</option>
                      <option value="Smoker">Is a Smoker</option>
                      <option value="onsetsymptoms">Onset Symptoms</option>
                      <option value="MRIenhancing">MRI Enhancing Lesions</option>
                      <option value="MRInum">MRI Lesion No.</option>
                      <option value="MRIonsetlocalisation">MRI Onset Localisation</option>
                    </select>
                  </td>
                  <td id="medication_row" hidden> <!-- this will appear if the medication has been selected for the Y axis -->
                    <select id="medication_row_select" name="Meds" multiple> <!-- multiple doesnt fit well... ask waht to do -->
                      <option value="Alemtuzumab">Alemtuzumab</option>
                      <option value="Avonex">Avonex</option>
                      <option value="Betaferon">Betaferon</option>
                      <option value="Copaxone">Copaxone</option>
                      <option value="Extavia">Extavia</option>
                      <option value="Fingolimod">Fingolimod</option>
                      <option value="Mitoxantrone">Mitoxantrone</option>
                      <option value="Natalizumab">Natalizumab</option>
                      <option value="Ocrelizumab">Ocrelizumab</option>
                      <option value="Rebif">Rebif</option>
                      <option value="Tecfidera">Tecfidera</option>
                      <option value="Teriflunomide">Teriflunomide</option>
                      <option value="None">None</option>
                    </select>
                  </td>
                </tr>

                <!-- <tr id="attr_values_row">
                  <th>No. of Ranges</th><td><input name="numofRanges" id="num_of_pie_ranges" type="number"></td>
                  <th>Ranges</th>
                  <td><select>
                    <option value="">value1</option>
                    <option value="">value2</option>
                    <option value="">value3</option>
                  </select></td>
                </tr> -->

              </table>
              <div id="d3-container">

              </div>

            </div>

            <div class="line"></div>

            <footer>
              <p>Application created by the Laboratory of Bioinformatics and Human Electrophysiology of the Ionian University.</p>
            </footer>

        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script type="text/javascript"> //sidebarCollapse
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>

    <script src="visual_analytics.js" charset="utf-8"></script>
</body>

</html>

<?php // session_start and timeout function
session_start();
error_reporting(0);
$patientID = $_GET["id"];   // used to pass the patient id directly in the form
$patientNAME = $_GET["nm"]; // used to pass the pateint name directly in the form
$patientADR = $_GET["adr"]; // used to pass the pateints age directly in the form
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
    <title>Multiple Sclerosis Registry</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <html lang="en-us">
    <meta charset="utf-8" />
    <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet"> -->
    <style>
        :root {
            --page-bg: #d9d9d9;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background-color: white;
            font-family: 'Roboto', sans-serif;
            font-size: 18px;
        }

        /* Classes */

        .split {
            display: flex;
            flex-direction: row;
            margin: 0 auto;
            width: 100%;
        }

        .split>.left,
        .split>.right {
            flex-basis: 100%;
            max-width: 50%;
            width: 0 auto;
        }

        .split>.left+.right {
            /* space between the side-side tables */
            margin-right: 1em;
            flex-basis: 100%;
            max-width: 50%;
            min-height: 100%;
        }

        .right-border {
            margin: 0;
            border-right: 1px solid black;
        }

        .container {
            position: relative;
            margin: 0.5em auto;
            width: min(95%, 70rem);
            padding: 0.5em 1em;
            background-color: white;
            border-radius: 19px;
        }

        .note-wrapper {
            display: block;
            margin-top: 1em;
            padding-top: 2em;
            padding-bottom: 2em;
            text-align: center;
            background-color: #ffff33;
            border-radius: 24px;
        }

        .header-wrapper {
            background-color: #2a7189;
            padding-bottom: 1em;
            padding-top: 0;
            margin-top: 0;
        }

        .header {
            /* table positioning */
            background-color: #3691b0;
            text-align: left;
            margin: auto;
            font-family: arial;
        }

        .w-20 {
            max-width: 20%;
        }

        .w-100 {
            max-width: 100%;
        }

        .lang {
            position: right;
            height: 40px;
            width: 80px;
            box-sizing: border-box;
            padding: 2px;
            border: 1px solid black;
            border-collapse: collapse;
            margin: 1rem 1rem;
        }

        .block {
            padding: 0.5em auto;
            text-align: center;
            margin: 1em;
            background-color: #2a7189;
            border-radius: 19px;
        }

        .block>.borderless {
            border: none;
            border-bottom: 1px black;
        }

        .alligner {
            margin: 2em 1em;
            padding: 0;
            text-align: right;
            min-width: 50%;
            max-width: 90%;
        }

        .text-right {
            padding: 0.5em 0.5em;
            margin-right: 0;
            text-align: right;
        }

        .text-center {
            padding: 0.5em 0.5em;
            text-align: center;
        }

        .text-left {
            text-align: left;
            padding: 0.5em 0.5em;
        }

        .label-header {
            border: 1px solid black;
        }

        /* attributes */

        /* form {
            /* display: table; 
        } */

        p {
            display: table-row;
        }

        label,
        input {
            display: table-cell;
        }

        section {
            padding: 0.5em 0;
        }

        img {
            /*  image alignment for the MS image */
            display: block;
            width: 25em;
            height: 15em;
        }

        h1,
        h2,
        h3,
        h4 {
            text-align: center;
            font-family: 'Roboto', sans-serif;
            text-emphasis: bold;
        }

        input[type=text],
        select {
            border: none;
            box-sizing: border-box;
            border-bottom: 1px solid black;
            font-family: arial;
            padding: 5px 5px;
            margin: 8px 0;
        }

        input[type=date],
        input[type=time] {
            padding: 5px 5px;
            margin: 8px 0;
            box-sizing: border-box;
            font-family: arial;
            width: auto;
        }

        input[type=number] {
            padding: 5px;
            margin: 5px 0;
            box-sizing: border-box;
            font-family: arial;
            width: auto;
        }

        label {
            text-align: left;
            margin-top: max(50%, 5em);
            padding: 5px 5px;
            margin: 8px 0;
            text-align: left;
            min-width: 90%;
        }

        /* ids */

        #purple {
            background-color: #ce99ff;
        }

        #header_container {
            background-color: #3691b0;
        }

        /* Media Queries */

        @media (max-width: 992px) {
            .split {
                display: flex;
                flex-direction: row;
                flex-basis: 100%;
                margin: 0 auto;
                border: none;
            }

            .split>.left+.right {
                margin-left: 0;
                margin-right: 0;
                margin-top: 1em;
                flex-basis: 100%;
                border: 0px;
            }
        }

        @media (max-width: 1200px) {
            .container {
                max-width: 90%;
                min-width: 70%;
            }
        }
    </style>
</head>

<body>


    <!-- Starting the form -->
    <div class="header-wrapper">
        <form target="_blank" action="MSRforminsert.php" method="post">

            <input type="image" class="lang" id="gr" src="Greek_flag.png">
            <!-- redirects the user to the greek form -->

            <div class="container">

                <div class="split ">
                    <div class="left img right-border ">

                        <img src="MSregistry_ionian_new_logo_nobg.png" alt="MSR Ionian University Logo">

                    </div>
                    <div class="right block text-left">

                        <p>
                            <label for="name">Όνομα Ασθενή:</label> <input type="text" name="patientName" placeholder="E.x John Doe" value="<?php echo $patientNAME ?>" required>
                        </p>

                        <p>
                            <label for="address">Διεύθηνση Ασθενή</label> <input type="text" name="patientAddress" placeholder="E.x Alexandras Street 12" value="<?php echo $patientADR; ?>" required>
                        </p>

                        <p>
                            <label for="date">Ημερομηνία</label> <input type="date" name="NDSdate" id="" required>
                        </p>

                        <p>
                            <label for="PatientID">Αριθμός Ταυτότητας/ΑΜΚΑ</label> <input type="number" name="NDSnum" id="" placeholder="Patient ID" value="<?php echo $patientID ?>" required>
                        </p>
                        <p>
                            <label for="PatientSex">Sex</label> <input type="radio" name="Sex" id="Sex" value="Male"> Male <br>
                            <input type="radio" name="Sex" id="" value="Female"> Female
                        </p>
                        <p>
                            <label for="Age">Age</label> <input type="number" name="Age" min="1" max="150" id="Age" placeholder="Age">
                        </p>
                        <p>
                            <label for="Race">Race</label>
                            <select id="Race" name="Race" required>
                                <option value="American Indian">American Indian</option>
                                <option value="Asian">Asian</option>
                                <option value="Black">Black</option>
                                <option value="Hispanic">Hispanic</option>
                                <option value="Caucasian">Caucasian</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </p>
                        <p>
                            <label for="Comorbidities">Comorbidities</label>
                            <input type="text" list="Comorbidities" name="Comorbidities" />
                            <datalist id="Comorbidities">
                                <option value="Diabetes">Diabetes</option>
                                <option value="Obesity">Obesity</option>
                                <option value="Heart Disease">Heart Disease</option>
                                <option value="Renal Failure">Renal Failure</option>
                                <option value="Hepatic Failure">Hepatic Failure</option>
                                <option value="Dyslipidemia">Dyslipidemia</option>
                                <option value="Autoimmune">Autoimmune</option>
                            </datalist>
                        </p>
                    </div>
                </div>
            </div>
    </div>

    <div class="container">
        <h2>TIER 1 - GENERAL INFO</h2>

        <div class="split container block">
            <div class="left right-border text-right ">
                <p>
                    <label for="onsetdate">Date of Onset:</label>
                    <input type="date" name="onsetdate" id="onsetdate" required>
                </p>
                <p>
                    <label for="">MS Type Now:</label>
                    <input type="text" name="convsprad" list="msType_now" placeholder="MS Type now" required>
                    <datalist id="msType_now" name="convsprad">
                        <option value="RR">RR</option>
                        <option value="SP">SP</option>
                        <option value="PP">PP</option>
                        <option value="Other">Other</option>
                    </datalist>
                </p>
                <p>
                    <label for="convsp">Conversion to SP if possible:</label>
                    <input type="text" name="convspnum" placeholder="E.x Possible">
                </p>

                <p>
                    <label for="Noofrelapses">Number of Relapses (RR only) Since last visit/year</label>
                    <input type="number" min="0" name="Noofrelapses" required>
                </p>
                <p>
                    <label for="pastdatestart">PAST Disease Modifying Treatment: Date started:</label>
                    <input type="date" name="pastTREATMENT" id="pastDate" required>
                </p>

                <p>
                    <label for="pastdatestop">PAST Disease Modifying Treatment: Date stopped:</label>
                    <input type="date" name="pastTREATMENTdate" id="pastDateStopped" required>
                </p>
                <p>
                    <label for="pastTREATMENTreason">Reason:</label>
                    <input type="text" name="pastTREATMENTcheck" id="pastTREATMENTcheck" list="pastTREATMENTcheck" placeholder="Reason" required>
                    <datalist id="pastTREATMENTcheck">
                        <option value="Lack of efficasy">Lack of efficasy</option>
                        <option value="Side effects">Side effects</option>
                        <option value="Other">Other</option>
                    </datalist>
                </p>
                <p>
                    <label for="pastDate">PRESENT Disease Modifying Treatment Date</label>
                    <input type="date" name="TREATMENTdate" id="presentdate" required>
                </p>
            </div>

            <div class="right text-right ">
                <p>
                    <label for="dateofdia">Date of Diagnosis</label>
                    <input type="date" name="dateofdia" required>
                </p>

                <p>
                    <label for="dateofdiarads">MS Type at Diagnosis</label>
                    <input type="text" name="dateofdiarad" list="dateofdiarad" placeholder="E.x. RR" required>
                    <datalist id="dateofdiarad">
                        <option value="RR">RR</option>
                        <option value="SP">SP</option>
                        <option value="PP">PP</option>
                        <option value="Other">Other</option>
                    </datalist>
                </p>



                <p>
                    <label for="Severity">Severity</label>
                    <select name="Noofrelapsesrad" id="">
                        <option value="Mild">Mild</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Severe">Severe</option>
                    </select>
                </p>


                <p>
                    <label for="meds">Past Medication:</label>
                    <input type="text" name="pastTREATMENT" list="meds" placeholder="Past Medicin" required>
                    <datalist id="meds">
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
                    </datalist>
                </p>

                <p>
                    <label for="meds">Present Medication:</label>
                    <input type="text" name="TREATMENT" list="meds" placeholder="Present Medicin" required>
                    <datalist id="meds">
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
                    </datalist>
                </p>



                <p>
                    <label for="dateEDSS">Date Current EDSS was Taken</label>
                    <input type="date" name="EDSSdate" id="" required>
                </p>
                <p>
                    <label for="EDSS" class="w-100">Current EDSS Score:</label>
                    <input type="number" name="eddsscore" id="edssscore" min="1" max="10" placeholder="1-10 " required>
                </p>

                <p>
                    <label for="PEGtest">Nine-Hole PEG Test</label>
                    <input type="time" name="edsstimePEG" id="" required>
                </p>
                <p>
                    <!-- change the eddsscore to the correct term -->
                    <label for="timedWalk">7.5 meters Timed Walk</label>
                    <input type="time" name="edsstime" required>
                </p>


            </div>
        </div>

        <h2>TIER 2 - Central Nervous System MRI</h2>

        <!-- <div class="split"> -->
        <div class="block split container text-left">
            <div class="left right-border text-left">
                <p>
                    <label for="MRIonset">CNS MRI Onset Localisation:</label>
                    <!-- <input type="text" name="MRIonsetlocalisation" list="MRIonsetlocalisation" placeholder="E.x. Visual">
                        <datalist id="MRIonsetlocalisation">
                                <option value="Visual">Visual</option>
                                <option value="Spinal">Spinal</option>
                                <option value="Cortex">Cortex</option>
                                <option value="Brainstem">Brainstem</option>
                                <option value="Cerebellum">Cerebellum</option>
                            </datalist> -->
                </p>
                <!-- <label for="" hidden></label> -->
                <p>
                    <label for="Visual">Visual</label>
                    <input type="checkbox" name="Onselocalisation" value="Visual" id="MRIonsetloc2">
                </p>

                <p>
                    <label for="Spinal">Spinal</label>
                    <input type="checkbox" name="Onselocalisation" value="Spinal" id="MRIonsetloc">
                </p>

                <p>
                    <label for="Cortex">Cortex</label>
                    <input type="checkbox" name="Onselocalisation" value="Cortex" id="MRIonsetloc1">
                </p>



                <p>
                    <label for="Cerebellar">Cerebellar</label>
                    <input type="checkbox" name="Onselocalisation" value="Cerebellar" id="MRIonsetloc3">
                </p>

                <p>
                    <label for="Brainstem">Brainstem</label>
                    <input type="checkbox" name="Onselocalisation" value="Brainstem" id="MRIonsetloc4">
                </p>
                <p>


                    <label for="None">None</label>
                    <input type="checkbox" name="Onselocalisation" value="None" id="MRIonsetloc5">
                </p>

            </div>

            <div class="right">
                <legend>
                    <p>
                        <label for="MRIenchancing Lesions">CNS MRI Enhancing Lesions last 12 months:</label>
                        <select name="MRIenhancing" id="MRIenhancing" required>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>

                    </p>
                    <p>
                        <label for="">Number of Lesions:</label> <input name="MRInum" type="number" id="MRInum" placeholder="Number of Lesions">
                    </p>
                    <p>
                        <label for="MRIenchancing Lesions Loc">MRI Enchancing Lesions Location:</label>
                    </p>


                    <p>
                        <label for="Spinal">Spinal</label>
                        <input type="checkbox" name="Onselocalisation" value="Spinal" id="MRIloc">
                    </p>

                    <p>
                        <label for="Cortex">Cortex</label>
                        <input type="checkbox" name="Onselocalisation" value="Cortex" id="MRIloc1">
                    </p>

                    <p>
                        <label for="Visual">Visual</label>
                        <input type="checkbox" name="Onselocalisation" value="Visual" id="MRIloc2">
                    </p>


                    <p>
                        <label for="Cerebellar">Cerebellar</label>
                        <input type="checkbox" name="Onselocalisation" value="Cerebellar" id="MRIloc3">
                    </p>

                    <p>
                        <label for="Brainstem">Brainstem</label>
                        <input type="checkbox" name="Onselocalisation" value="Brainstem" id="MRIloc4">
                    </p>
                </legend>

            </div>

        </div>

        <h2>TIER 3 </h2>

        <div class="split container block">
            <div class="left right-border text-right">

                <p>
                    <label for="Pregnant">Is Patient Pregnant?</label>
                    <select name="Pregnant" id="">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </p>

                <p>
                    <label for="Smoker">Smoker</label>
                    <select name="smoker" id="smoker">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </p>
                <p>
                    <label for="numofCigs">Number of Cigars</label>
                    <input type="number" name="numofcig" id="numofcig" placeholder="Cigars per day">
                </p>
                <p>
                    <label for="lastcig">Smoked Last:</label>
                    <input type="date" name="dateofcig" id="dateofcig">
                </p>


            </div>

            <div class="right  text-right">

                <p>
                    <label for="onsetLocalisation">Onset Localisation</label>
                </p>

                <p>
                    <label for="Spinal">Spinal</label>
                    <input type="checkbox" name="Onselocalisation" value="Spinal">
                </p>

                <p>
                    <label for="Cortex">Cortex</label>
                    <input type="checkbox" name="Onselocalisation" value="Cortex">
                </p>

                <p>
                    <label for="Visual">Visual</label>
                    <input type="checkbox" name="Onselocalisation" value="Visual">
                </p>

                <p>
                    <label for="Cerebellar">Cerebellar</label>
                    <input type="checkbox" name="Onselocalisation" value="Cerebellar">
                </p>

                <p>
                    <label for="Brainstem">Brainstem</label>
                    <input type="checkbox" name="Onselocalisation" value="Brainstem">
                </p>
                <p>
                    <label for="onsetsymptoms">CNS Onset Symptoms</label>
                    <input type="text" name="onsetsymptoms" id="onsetsymptoms" list="onsetsymptoms" placeholder="E.x. Vision">
                    <datalist id="onsetsymptoms">
                        <option value="Vision">Vision</option>
                        <option value="Motor">Motor</option>
                        <option value="Sensory">Sensory</option>
                        <option value="Coordination">Coordination</option>
                        <option value="Bowel">Bowel</option>
                        <option value="Bladder">Bladder</option>
                        <option value="Fatigue">Fatigue</option>
                        <option value="Cognitive">Cognitive</option>
                        <option value="Encephalopathy">Encephalopathy</option>
                        <option value="Other">Other</option>
                    </datalist>
                </p>

            </div>
        </div>




        <!-- </div> -->
        <div class="block container">

            <h3>Person Completing this form:
                <input type="text" name="signer" required>
                <input type="submit" name="Submit" value="Submit" id="subm" required>
            </h3>
            <p id="some">

            </p>
        </div>
    </div>




    <div class="note-wrapper container">
        <strong>By clicking the <i>Reset</i> button any input that you have entered in the form will be erased and will NOT be saved!</strong>
    </div>
    <p>
    <h3>
        Reset the form?
        <input type="reset" name="resetform" id="resetbutton">
    </h3>

    </p>

    </form>

    <script type="text/javascript">
        // redirects to the greek form
        document.getElementById("gr").onclick = function() {
            location.href = "Multiple_Sclerosis_app_gr.php";
        };
    </script>
    <!-- <script type="text/javascript">
        // date validating client-side for pastStarted-pastEnded treatment
        document.getElementById('pastDate').addEventListener("change", function() {
            var inputpastdateStart = this.value;
            var pastdatestart = new Date(inputpastdateStart);
            document.getElementById('datestoped').setAttribute("min", inputpastdateStart);
        });
    </script>  -->
    <script type="text/javascript">
        // dynamicly disabling certain input boxes in the MRI tier
        document.getElementById('MRIenhancing').onchange = function disableInpMRI() {
            if (this.value === 'Yes') {
                document.getElementById('MRInum').disabled = false;
                document.getElementById('MRIloc').disabled = false;
                document.getElementById("MRIloc1").disabled = false;
                document.getElementById("MRIloc2").disabled = false;
                document.getElementById("MRIloc3").disabled = false;
                document.getElementById("MRIloc4").disabled = false;
            } else if (this.value === 'No') {
                document.getElementById("MRInum").disabled = true;
                document.getElementById("MRIloc").disabled = true;
                document.getElementById("MRIloc1").disabled = true;
                document.getElementById("MRIloc2").disabled = true;
                document.getElementById("MRIloc3").disabled = true;
                document.getElementById("MRIloc4").disabled = true;
            }
        }
    </script>
    <script type="text/javascript">
        // dynamicly disabling certain input boxes in the Smoker tier
        document.getElementById('smoker').onchange = function disableInpsmok() {
            if (this.value === 'Yes') {
                document.getElementById('numofcig').disabled = false;
                document.getElementById('dateofcig').disabled = false;
            } else if (this.value === 'No') {
                document.getElementById('numofcig').disabled = true;
                document.getElementById('dateofcig').disabled = true;
            }
        }
    </script>
    <script type="text/javascript">
        // resets the form
        document.getElementById('resetbutton').onclick = function resetForm() {
            var rsbtn = confirm("Are you sure you want to erase the form?");
            if (rsbtn == false) {
                return false;
            } else {
                return true;
            }
        }
    </script>
    <script type="text/javascript">
        //  not Done yet!! calculates the age of the person based on Date of Birth
        // function calcAge() {
        //     var date = new Date();
        //     var day = date.getDate(),
        //         month = date.getMonth() + 1,
        //         year = date.getFullYear();

        //     month = (month < 10 ? "0" : "") + month;
        //     day = (day < 10 ? "0" : "") + day;

        //     var today = year + "/" + month + "/" + day;
        //     var dateOfBirth = document.getElementById('dob');
        //     var Age = today - dateOfBirth;

        //     var ageinputbox = document.getElementById('Age').innerHTML = Age; // make it print the calculated Age on page load
        // }
    </script>
    <script>
        var today = new Date();
        var dateNow = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();

        var currDate = document.getElementById('currDate');
        currDate.innerHTML = dateNow;
    </script>

</body>

</html>
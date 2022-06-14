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

    $doc = $_SESSION['user_id'];
    // query to get the last visit id -> add 1 to the value
    // $visit_num = 0;
    
?>
<!DOCTYPE html>
<html>

<head>
    <title>Multiple Sclerosis Registry</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <html lang="en-us">
    <meta charset="utf-8" />
    <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="form.css">
</head>

<body>


    <!-- Starting the form -->
    <form target="_blank" action="MSRforminsert.php" method="post">
        <div class="header-wrapper">

            <input type="image" class="lang" id="gr" src="Greek_flag.png">
            <!-- redirects the user to the greek form -->

            <div class="container">

                <div class="split ">
                    <div class="left img right-border ">
                        <img src="MSregistry_ionian_new_logo_nobg.png" alt="MSR Ionian University Logo">
                    </div>
                    <div class="right block text-left">

                        <div class="tab-row">
                            <label for="name">Patient Name</label> <input type="text" name="patientName" placeholder="E.x John Doe" value="<?php echo $patientNAME ?>" required>
                        </div>

                        <div class="tab-row">
                            <label for="address">Patient Address</label> <input type="text" name="patientAddress" placeholder="E.x Alexandras Street 12" value="<?php echo $patientADR; ?>" required>
                        </div>

                        <div class="tab-row">
                            <label for="date">Date</label> <input type="date" name="NDSdate" id="date" required>
                        </div>

                        <div class="tab-row">
                            <label for="PatientID">Patient ID</label> <input type="number" name="NDSnum" id="" placeholder="Patient ID" value="<?php echo $patientID ?>" required>
                        </div>
                        <div class="tab-row">
                            <label for="PatientSex">Sex</label> 
                            <input type="radio" name="Sex" id="Sex" value="Male"> Male 
                            <input type="radio" name="Sex" id="" value="Female"> Female
                        </div>
                        <div class="tab-row">
                            <label for="Age">Age</label> <input type="number" name="Age" min="1" max="150" id="Age" placeholder="Age">
                        </div>
                        <div class="tab-row">
                            <label for="Race">Race</label>
                            <select id="Race" name="Race" required>
                                <option value="American Indian">American Indian</option>
                                <option value="Asian">Asian</option>
                                <option value="Black">Black</option>
                                <option value="Hispanic">Hispanic</option>
                                <option value="Caucasian">Caucasian</option>
                                <option value="Unknown">Unknown</option>
                            </select>
                        </div>
                        <div class="tab-row">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h2>TIER 1 - GENERAL INFO</h2>

            <div class="split container block">
                <div class="left right-border text-right ">
                    <div class="tab-row">
                        <!-- <div class="table-column"> -->
                            <label for="onsetdate">Date of Onset:</label>
                        <!-- </div> -->
                        <!-- <div class="table-column"> -->
                            <input type="date" name="onsetdate" id="onsetdate" required>
                        <!-- </div> -->
                    </div>
                    <div class="tab-row">
                        <!-- <div class="table-column"> -->
                            <label for="">MS Type Now:</label>
                        <!-- </div> -->
                        <!-- <div class="table-column"> -->
                            <input type="text" name="convsprad" list="msType_now" placeholder="MS Type now" required>
                            <datalist id="msType_now" name="convsprad">
                                <option value="RR">RR</option>
                                <option value="SP">SP</option>
                                <option value="PP">PP</option>
                                <option value="Other">Other</option>
                            </datalist>
                        <!-- </div> -->
                    </div>
                    <div class="tab-row">
                        <label for="convsp">Conversion to SP if possible:</label>
                        <input type="text" name="convspnum" placeholder="E.x 1">
                    </div>

                    <div class="tab-row">
                        <label for="Noofrelapses">Number of Relapses (RR only) Since last visit/year</label>
                        <input type="number" min="0" name="Noofrelapses" required>
                    </div>
                    <div class="tab-row">
                        <label for="pastdatestart">PAST Disease Modifying Treatment: Date started:</label>
                        <input type="date" name="pastTREATMENT" id="pastDate" required>
                    </div>

                    <div class="tab-row">
                        <label for="pastdatestop">PAST Disease Modifying Treatment: Date stopped:</label>
                        <input type="date" name="pastTREATMENTdate" id="pastDateStopped" required>
                    </div>
                    <div class="tab-row">
                        <label for="pastTREATMENTreason">Reason:</label>
                        <input type="text" name="pastTREATMENTcheck" id="pastTREATMENTcheck" list="pastTREATMENTcheckreason" placeholder="Reason" required>
                        <datalist id="pastTREATMENTcheckreason" name="pastTREATMENTcheck">
                            <option value="Lack of efficasy">Lack of efficasy</option>
                            <option value="Side effects">Side effects</option>
                            <option value="Other">Other</option>
                        </datalist>
                    </div>
                    <div class="tab-row">
                        <label for="pastDate">PRESENT Disease Modifying Treatment Date</label>
                        <input type="date" name="TREATMENTdate" id="presentdate" required>
                    </div>
                </div>

                <div class="right text-right ">
                    <div class="tab-row">
                        <label for="dateofdia">Date of Diagnosis</label>
                        <input type="date" name="dateofdia" required>
                    </div>

                    <div class="tab-row">
                        <label for="dateofdiarads">MS Type at Diagnosis</label>
                        <input type="text" name="dateofdiarad" list="dateofdiarad" placeholder="E.x. RR" required>
                        <datalist id="dateofdiarad">
                            <option value="RR">RR</option>
                            <option value="SP">SP</option>
                            <option value="PP">PP</option>
                            <option value="Other">Other</option>
                        </datalist>
                    </div>



                    <div class="tab-row">
                        <label for="Severity">Severity</label>
                        <select name="Noofrelapsesrad" id="">
                            <option value="Mild">Mild</option>
                            <option value="Moderate">Moderate</option>
                            <option value="Severe">Severe</option>
                        </select>
                    </div>


                    <div class="tab-row">
                        <!-- <div class="table-column"> -->
                            <label for="meds">Past Medication:</label>
                        <!-- </div> -->
                        <!-- <input type="text" name="pastTREATMENT" list="meds" placeholder="Past Medicin" required>
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
                        </datalist> -->
                        <!-- <div class=""> -->
                            <div id="pastMeds" class="dropdown-check-list table-column" tabindex="100">
                                <span class="anchor">Medication</span>
                                <ul class="items">
                                    <li>Alemtuzumab<input type="checkbox" name="pastTREATMENT" value="Alemtuzumab"></li>
                                    <li>Avonex<input type="checkbox" name="pastTREATMENT" value="Avonex"></li>
                                    <li>Betaferon<input type="checkbox" name="pastTREATMENT" value="Betaferon"></li>
                                    <li>Copaxone<input type="checkbox" name="pastTREATMENT" value="Copaxone"></li>
                                    <li>Extavia<input type="checkbox" name="pastTREATMENT" value="Extavia"></li>
                                    <li>Fingolimod<input type="checkbox" name="pastTREATMENT" value="Fingolimod"></li>
                                    <li>Mitoxantrone<input type="checkbox" name="pastTREATMENT" value="Mitoxantrone"></li>
                                    <li>Ocrelizumab<input type="checkbox" name="pastTREATMENT" value="Ocrelizumab"></li>
                                    <li>Rebif<input type="checkbox" name="pastTREATMENT" value="Rebif"></li>
                                    <li>Tecfidera<input type="checkbox" name="pastTREATMENT" value="Tecfidera"></li>
                                    <li>Teriflunomide<input type="checkbox" name="pastTREATMENT" value="Teriflunomide"></li>
                                    <li>None<input type="checkbox" name="pastTREATMENT" value="None"></li>
                                </ul>
                            </div>
                        <!-- </div> -->
                    </div>

                    <div class="tab-row">
                        <label for="meds">Present Medication:</label>
                        <!-- <input type="text" name="TREATMENT" list="meds" placeholder="Present Medicin" required>
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
                        </datalist> -->
                        <div id="presentMeds" class="dropdown-check-list table-column" tabindex="100">
                                <span class="anchor">Medication</span>
                                <ul class="items">
                                    <li>Alemtuzumab<input type="checkbox" name="TREATMENT" value="Alemtuzumab"></li>
                                    <li>Avonex<input type="checkbox" name="TREATMENT" value="Avonex"></li>
                                    <li>Betaferon<input type="checkbox" name="TREATMENT" value="Betaferon"></li>
                                    <li>Copaxone<input type="checkbox" name="TREATMENT" value="Copaxone"></li>
                                    <li>Extavia<input type="checkbox" name="TREATMENT" value="Extavia"></li>
                                    <li>Fingolimod<input type="checkbox" name="TREATMENT" value="Fingolimod"></li>
                                    <li>Mitoxantrone<input type="checkbox" name="TREATMENT" value="Mitoxantrone"></li>
                                    <li>Ocrelizumab<input type="checkbox" name="TREATMENT" value="Ocrelizumab"></li>
                                    <li>Rebif<input type="checkbox" name="TREATMENT" value="Rebif"></li>
                                    <li>Tecfidera<input type="checkbox" name="TREATMENT" value="Tecfidera"></li>
                                    <li>Teriflunomide<input type="checkbox" name="TREATMENT" value="Teriflunomide"></li>
                                    <li>None<input type="checkbox" name="TREATMENT" value="None"></li>
                                </ul>
                            </div>
                    </div>

                    <div class="tab-row">
                        <label for="dateEDSS">Date Current EDSS was Taken</label>
                        <input type="date" name="EDSSdate" id="" required>
                    </div>
                    <div class="tab-row">
                        <label for="EDSS" class="w-100">Current EDSS Score:</label>
                        <input type="number" name="eddsscore" id="edssscore" min="1" max="10" placeholder="1-10 " required>
                    </div>

                    <div class="tab-row">
                        <label for="PEGtest">Nine-Hole PEG Test</label>
                        <input type="time" name="edsstimePEG" id="" required>
                    </div>
                    <div class="tab-row">
                        <!-- change the eddsscore to the correct term -->
                        <label for="timedWalk">7.5 meters Timed Walk</label>
                        <input type="time" name="edsstime" required>
                    </div>


                </div>
            </div>

            <h2>TIER 2 - Central Nervous System MRI</h2>

            <!-- <div class="split"> -->
            <div class="block container text-left">
                <!-- <div class="left right-border text-left"> -->
                    <div class="tab-row">
                        <label for="MRIonset">CNS MRI Onset Localisation:</label>
                        <!-- <input type="text" name="MRIonsetlocalisation" list="MRIonsetlocalisation" placeholder="E.x. Visual">
                            <datalist id="MRIonsetlocalisation">
                                    <option value="Visual">Visual</option>
                                    <option value="Spinal">Spinal</option>
                                    <option value="Cortex">Cortex</option>
                                    <option value="Brainstem">Brainstem</option>
                                    <option value="Cerebellum">Cerebellum</option>
                                </datalist> -->
                                <div id="mriOnset" class="dropdown-check-list table-column" >
                                    <span class="anchor">Localisation</span>
                                    <ul class="items">
                                    <li><input type="checkbox" name="MRIonsetlocalisation" value="Visual" class="exempt" id="MRIonsetloc">Visual</li>
                                    <li><input type="checkbox" name="MRIonsetlocalisation" value="Spinal" class="exempt" id="MRIonsetloc1">Spinal</li>
                                    <li><input type="checkbox" name="MRIonsetlocalisation" value="Cortex" class="exempt" id="MRIonsetloc2">Cortex</li>
                                    <li><input type="checkbox" name="MRIonsetlocalisation" value="Cerebellar" class="exempt" id="MRIonsetloc3">Cerebellar</li>
                                    <li><input type="checkbox" name="MRIonsetlocalisation" value="Brainstem" class="exempt" id="MRIonsetloc4">Brainstem</li>
                                    <li><input type="checkbox" name="MRIonsetlocalisation" value="None" class="exempt" id="MRIonsetloc5">None</li>
                                </ul>
                            </div>
                    </div>
                    <!-- <label for="" hidden></label> -->
                    <!-- <div class="tab-row">
                        <label for="Visual">Visual</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Visual" id="MRIonsetloc2">
                    </div>
                    <div class="tab-row">
                        <label for="Spinal">Spinal</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Spinal" id="MRIonsetloc">
                    </div>
                    <div class="tab-row">
                        <label for="Cortex">Cortex</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Cortex" id="MRIonsetloc1">
                    </div>
                    <div class="tab-row">
                        <label for="Cerebellar">Cerebellar</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Cerebellar" id="MRIonsetloc3">
                    </div>
                    <div class="tab-row">
                        <label for="Brainstem">Brainstem</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Brainstem" id="MRIonsetloc4">
                    </div>
                    <div class="tab-row">
                        <label for="None">None</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="None" id="MRIonsetloc5">
                    </div> -->

                <!-- </div> -->

                <!-- <div class="right"> -->
                        <div class="tab-row">
                            <label for="MRIenchancing Lesions">CNS MRI Enhancing Lesions last 12 months:</label>
                            <select name="MRIenhancing" id="MRIenhancing" required>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>

                        </div>
                        <div class="tab-row">
                            <label for="">Number of Lesions:</label> <input name="MRInum" type="number" id="MRInum" placeholder="Number of Lesions">
                        </div>
                        <div class="tab-row">
                            <label for="MRIenchancing Lesions Loc">MRI Enchancing Lesions Location:</label>
                            <div id="MRIlesions" class="dropdown-check-list table-column" tabindex="100">
                                <span class="anchor">Locations</span>
                                <ul class="items">
                                    <li><input type="checkbox" name="MRIenhancinglocation" value="Visual" id="MRIloc">Visual</li>
                                    <li><input type="checkbox" name="MRIenhancinglocation" value="Spinal"  id="MRIloc1">Spinal</li>
                                    <li><input type="checkbox" name="MRIenhancinglocation" value="Cortex"  id="MRIloc2">Cortex</li>
                                    <li><input type="checkbox" name="MRIenhancinglocation" value="Cerebellar"  id="MRIloc3">Cerebellar</li>
                                    <li><input type="checkbox" name="MRIenhancinglocation" value="Brainstem"  id="MRIloc4">Brainstem</li>
                                </ul>
                            </div>
                        </div>


                        <!-- <div class="tab-row">
                            <label for="Spinal">Spinal</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Spinal" id="MRIloc">
                        </div>

                        <div class="tab-row">
                            <label for="Cortex">Cortex</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Cortex" id="MRIloc1">
                        </div>

                        <div class="tab-row">
                            <label for="Visual">Visual</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Visual" id="MRIloc2">
                        </div>


                        <div class="tab-row">
                            <label for="Cerebellar">Cerebellar</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Cerebellar" id="MRIloc3">
                        </div>

                        <div class="tab-row">
                            <label for="Brainstem">Brainstem</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Brainstem" id="MRIloc4">
                        </div> -->
                    

                <!-- </div> -->

            </div>

            <h2>TIER 3 </h2>

            <div class="split container block">
                <div class="left right-border text-right">

                    <div class="tab-row">
                        <label for="Pregnant">Is Patient Pregnant?</label>
                        <select name="Pregnant" id="">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>

                    <div class="tab-row">
                        <label for="Smoker">Smoker</label>
                        <select name="smoker" id="smoker">
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="tab-row">
                        <label for="numofCigs">Number of Cigars</label>
                        <input type="number" name="cigars" id="numofcig" placeholder="Cigars per day">
                    </div>
                    <div class="tab-row">
                        <label for="lastcig">Smoked Last:</label>
                        <input type="date" name="cigardate" id="dateofcig">
                    </div>


                </div>

                <div class="right  text-right">

                    <!-- <div class="tab-row">
                        <label for="onsetLocalisation">Onset Localisation</label>
                    </div> -->

                    <div class="tab-row">
                            <label for="MRIenchancing Lesions Loc">Onset Localisation:</label>
                            <div id="onsetLoc" class="dropdown-check-list table-column" tabindex="100">
                                    <span class="anchor">Locations</span>
                                    <ul class="items">
                                    <li>Visual<input type="checkbox" name="MRIenhancinglocation" value="Visual" class="exempt" id="MRIloc"></li>
                                    <li>Spinal<input type="checkbox" name="MRIenhancinglocation" value="Spinal" class="exempt" id="MRIloc1"></li>
                                    <li>Cortex<input type="checkbox" name="MRIenhancinglocation" value="Cortex" class="exempt" id="MRIloc2"></li>
                                    <li>Cerebellar<input type="checkbox" name="MRIenhancinglocation" value="Cerebellar" class="exempt" id="MRIloc3"></li>
                                    <li>Brainstem<input type="checkbox" name="MRIenhancinglocation" value="Brainstem" class="exempt" id="MRIloc4"></li>
                                    <!-- <li><input type="checkbox" name="MRIenhancinglocation" value="None" class="exempt" id="MRIloc">None</li> -->
                                </ul>
                            </div>
                        </div>

                    <!-- <div class="tab-row">
                        <label for="Spinal">Spinal</label>
                        <input type="checkbox" name="Onselocalisation" value="Spinal">
                    </div>

                    <div class="tab-row">
                        <label for="Cortex">Cortex</label>
                        <input type="checkbox" name="Onselocalisation" value="Cortex">
                    </div>

                    <div class="tab-row">
                        <label for="Visual">Visual</label>
                        <input type="checkbox" name="Onselocalisation" value="Visual">
                    </div>

                    <div class="tab-row">
                        <label for="Cerebellar">Cerebellar</label>
                        <input type="checkbox" name="Onselocalisation" value="Cerebellar">
                    </div>

                    <div class="tab-row">
                        <label for="Brainstem">Brainstem</label>
                        <input type="checkbox" name="Onselocalisation" value="Brainstem">
                    </div> -->
                    <div class="tab-row">
                        <label for="onsetsymptoms">CNS Onset Symptoms</label>
                        <input type="text" name="onsetsymptoms" id="onsetsymptoms" list="onsetsymptomslist" placeholder="E.x. Vision">
                        <datalist id="onsetsymptomslist" name="onsetsymptoms">
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
                    </div>

                </div>
            </div>

            <div class="block container">

                <h3>Person Completing this form:
                    <input type="text" name="signer" required>
                    <input type="submit" name="Submit" value="Submit" id="subm" required>
                </h3>
                <!-- <p id="some">

                </div> -->
            </div>
        </div>

        <div class="note-wrapper container">
            <strong>By clicking the <i>Reset</i> button any input that you have entered in the form will be erased and will NOT be saved!</strong>
            <br>
            <input type="reset" name="resetform" id="resetbutton">
        </div>    
        

    </form>

    <script type="text/javascript">
        // redirects to the greek form
        document.getElementById("gr").onclick = function() {
            location.href = "Multiple_Sclerosis_app_gr.php";
        };
    
        // // date validating client-side for pastStarted-pastEnded treatment
        // document.getElementById('pastDate').addEventListener("change", function() {
        //     var inputpastdateStart = this.value;
        //     var pastdatestart = new Date(inputpastdateStart);
        //     document.getElementById('datestoped').setAttribute("min", inputpastdateStart);
        // });
    
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
        
        // resets the form
        document.getElementById('resetbutton').onclick = function resetForm() {
            var rsbtn = confirm("Are you sure you want to erase the form?");
            if (rsbtn == false) {
                return false;
            } else {
                return true;
            }
        }

        // js for the dropdowns with the checkboxes

        // dropdown with past medication
        var checkList = document.getElementById('pastMeds');
        checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
            if (checkList.classList.contains('visible'))
                checkList.classList.remove('visible');
            else
                checkList.classList.add('visible');
        }
        // dropdown with present medication
        var checkList2 = document.getElementById('presentMeds');
        checkList2.getElementsByClassName('anchor')[0].onclick = function(evt) {
            if (checkList2.classList.contains('visible'))
                checkList2.classList.remove('visible');
            else
                checkList2.classList.add('visible');
        }
        // dropdown with MRI onset locations
        var checkList3 = document.getElementById('mriOnset');
        checkList3.getElementsByClassName('anchor')[0].onclick = function(evt) {
            if (checkList3.classList.contains('visible'))
                checkList3.classList.remove('visible');
            else
                checkList3.classList.add('visible');
        }
        // dropdown with MRI lesion locations
        var checkList4 = document.getElementById('MRIlesions');
        checkList4.getElementsByClassName('anchor')[0].onclick = function(evt) {
            if (checkList4.classList.contains('visible'))
                checkList4.classList.remove('visible');
            else
                checkList4.classList.add('visible');
        }
        // dropdown with onset locations
        var checkList5 = document.getElementById('onsetLoc');
        checkList5.getElementsByClassName('anchor')[0].onclick = function(evt) {
            if (checkList5.classList.contains('visible'))
                checkList5.classList.remove('visible');
            else
                checkList5.classList.add('visible');
        }
    
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
</body>
</html>
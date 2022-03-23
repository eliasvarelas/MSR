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
    <link rel="stylesheet" href="form.css">
</head>

<body>


    <!-- Starting the form -->
    <form target="_blank" action="MSRforminsert.php" method="post">
        <div class="header-wrapper">

            <input type="image" class="lang" id="eng" src="English_flag.png">
            <!-- redirects the user to the greek form -->

            <div class="container">

                <div class="split ">
                    <div class="left img right-border ">
                        <img src="MSregistry_ionian_new_logo_nobg.png" alt="MSR Ionian University Logo">
                    </div>
                    <div class="right block text-left">

                        <p>
                            <label for="name">'Ονομα Ασθενή</label> <input type="text" name="patientName" placeholder="E.x John Doe" value="<?php echo $patientNAME ?>" required>
                        </p>

                        <p>
                            <label for="address">Διεύθυνση Ασθενή</label> <input type="text" name="patientAddress" placeholder="E.x Alexandras Street 12" value="<?php echo $patientADR; ?>" required>
                        </p>

                        <p>
                            <label for="date">Ημερομηνία</label> <input type="date" name="NDSdate" id="date" required>
                        </p>

                        <p>
                            <label for="PatientID">ΑΜΚΑ</label> <input type="number" name="NDSnum" id="" placeholder="Patient ID" value="<?php echo $patientID ?>" required>
                        </p>
                        <p>
                            <label for="PatientSex">Φύλο</label> 
                            <input type="radio" name="Sex" id="Sex" value="Male"> Αρσενικό 
                            <input type="radio" name="Sex" id="" value="Female"> Θυληκό
                        </p>
                        <p>
                            <label for="Age">Ηλικία</label> <input type="number" name="Age" min="1" max="150" id="Age" placeholder="Age">
                        </p>
                        <p>
                            <label for="Race">Φυλή</label>
                            <select id="Race" name="Race" required>
                                <option value="American Indian">Ιθαγενής Αμερικάνος</option>
                                <option value="Asian">Ασιάτης</option>
                                <option value="Black">Έγχρωμος</option>
                                <option value="Hispanic">Ισπνόφωνος</option>
                                <option value="Caucasian">Καυκάσιος</option>
                                <option value="Unknown">Άγνωστο</option>
                            </select>
                        </p>
                        <p>
                            <label for="Comorbidities">Συννοσηρότητες</label>
                            <input type="text" list="Comorbidities" name="Comorbidities" />
                            <datalist id="Comorbidities">
                                <option value="Diabetes">Διαβήτης</option>
                                <option value="Obesity">Παχυσαρκία</option>
                                <option value="Heart Disease">Καρδιακά Νοσήματα</option>
                                <option value="Renal Failure">Ρυνική Ανεπάρκια</option>
                                <option value="Hepatic Failure">Ηπατική Ανεπάρκια</option>
                                <option value="Dyslipidemia">Δυσλιπιδαιμία</option>
                                <option value="Autoimmune">Αυτοάνοσα</option>
                            </datalist>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h2>TIER 1 - Γενικές Πληροφορίες</h2>

            <div class="split container block">
                <div class="left right-border text-right ">
                    <p>
                        <label for="onsetdate">Ημερομηνία Έναρξης:</label>
                        <input type="date" name="onsetdate" id="onsetdate" required>
                    </p>
                    <p>
                        <label for="">Τύπος MS Τώρα:</label>
                        <input type="text" name="convsprad" list="msType_now" placeholder="MS Type now" required>
                        <datalist id="msType_now" name="convsprad">
                            <option value="RR">RR</option>
                            <option value="SP">SP</option>
                            <option value="PP">PP</option>
                            <option value="Other">Άλλο</option>
                        </datalist>
                    </p>
                    <p>
                        <label for="convsp">Μετατροπή σε SP (αν γίνεται):</label>
                        <input type="text" name="convspnum" placeholder="E.x 1">
                    </p>

                    <p>
                        <label for="Noofrelapses">Αριθμός Υποτροπιάσεων (μονο RR) απο την τελευταία επίσκεψη/έτος</label>
                        <input type="number" min="0" name="Noofrelapses" required>
                    </p>
                    <p>
                        <label for="pastdatestart">Ημερομηνία Έναρξης Παλιάς Φαρμακευτικής Αγωγής:</label>
                        <input type="date" name="pastTREATMENT" id="pastDate" required>
                    </p>

                    <p>
                        <label for="pastdatestop">Ημερομηνία Διακοπής Παλιάς Φαρμακευτικής Αγωγής:</label>
                        <input type="date" name="pastTREATMENTdate" id="pastDateStopped" required>
                    </p>
                    <p>
                        <label for="pastTREATMENTreason">Λόγος Διακοπής:</label>
                        <input type="text" name="pastTREATMENTcheck" id="pastTREATMENTcheck" list="pastTREATMENTcheckreason" placeholder="Reason" required>
                        <datalist id="pastTREATMENTcheckreason" name="pastTREATMENTcheck">
                            <option value="Lack of efficasy">Ελλείψη Αποτελεσματικότητας</option>
                            <option value="Side effects">Παρενέργειες</option>
                            <option value="Other">Άλλο</option>
                        </datalist>
                    </p>
                    <p>
                        <label for="pastDate">Ημερομηνία Παρούσας Φαρμακευτικής Αγωγής:</label>
                        <input type="date" name="TREATMENTdate" id="presentdate" required>
                    </p>
                </div>

                <div class="right text-right ">
                    <p>
                        <label for="dateofdia">Ημερομηνία Διάγνωσης</label>
                        <input type="date" name="dateofdia" required>
                    </p>

                    <p>
                        <label for="dateofdiarads">Τύπος MS κατα την Διάγνωση</label>
                        <input type="text" name="dateofdiarad" list="dateofdiarad" placeholder="E.x. RR" required>
                        <datalist id="dateofdiarad">
                            <option value="RR">RR</option>
                            <option value="SP">SP</option>
                            <option value="PP">PP</option>
                            <option value="Other">Άλλο</option>
                        </datalist>
                    </p>



                    <p>
                        <label for="Severity">Σοβαρότητα</label>
                        <select name="Noofrelapsesrad" id="">
                            <option value="Mild">Ήπια</option>
                            <option value="Moderate">Μέτρια</option>
                            <option value="Severe">Σοβαρή</option>
                        </select>
                    </p>


                    <p>
                        <label for="meds">Παλιά Φαρμακευτική Αγωγή:</label>
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
                            <option value="None">Καμία</option>
                        </datalist>
                    </p>

                    <p>
                        <label for="meds">Παρούσα Φαρμακευτική Αγωγή:</label>
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
                            <option value="None">Καμία</option>
                        </datalist>
                    </p>

                    <p>
                        <label for="dateEDSS">Ημερομηνία Υπολογισμού Τωρινού EDSS</label>
                        <input type="date" name="EDSSdate" id="" required>
                    </p>
                    <p>
                        <label for="EDSS" class="w-100">Τωρινός Βαθμός EDSS:</label>
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

            <h2>TIER 2 - Μαγνητική Τομογραφία Κεντρικού Νευρικού Συστήματος</h2>

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
                    <p>
                        <label for="Visual">Οπτικό</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Visual" id="MRIonsetloc2">
                    </p>
                    <p>
                        <label for="Spinal">Νωτιαίος</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Spinal" id="MRIonsetloc">
                    </p>
                    <p>
                        <label for="Cortex">Φλοιός</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Cortex" id="MRIonsetloc1">
                    </p>
                    <p>
                        <label for="Cerebellar">Παρεγκεφαλικός</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Cerebellar" id="MRIonsetloc3">
                    </p>
                    <p>
                        <label for="Brainstem">Εγκεφαλικό Επεισόδιο</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="Brainstem" id="MRIonsetloc4">
                    </p>
                    <p>
                        <label for="None">Κανένα</label>
                        <input type="checkbox" name="MRIonsetlocalisation" value="None" id="MRIonsetloc5">
                    </p>

                </div>

                <div class="right">
                        <p>
                            <label for="MRIenchancing Lesions">ΚΝΣ MRI Επιδείνωση Βλαβών στους Τελευταίους 12 Μήνες:</label>
                            <select name="MRIenhancing" id="MRIenhancing" required>
                                <option value="Yes">Ναι</option>
                                <option value="No">Όχι</option>
                            </select>

                        </p>
                        <p>
                            <label for="">Αριθμός Βλαβών:</label> <input name="MRInum" type="number" id="MRInum" placeholder="Number of Lesions">
                        </p>
                        <p>
                            <label for="MRIenchancing Lesions Loc">MRI Τοποθεσία Επιδεινούμενων Βλαβών:</label>
                        </p>


                        <p>
                            <label for="Spinal">Νωτιαίος</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Spinal" id="MRIloc">
                        </p>

                        <p>
                            <label for="Cortex">Φλοιός</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Cortex" id="MRIloc1">
                        </p>

                        <p>
                            <label for="Visual">Οπτικός</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Visual" id="MRIloc2">
                        </p>


                        <p>
                            <label for="Cerebellar">Παρεγκεφαλικός</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Cerebellar" id="MRIloc3">
                        </p>

                        <p>
                            <label for="Brainstem">Εγκεφαλικό Επεισόδιο</label>
                            <input type="checkbox" name="MRIenhancinglocation" value="Brainstem" id="MRIloc4">
                        </p>
                    

                </div>

            </div>

            <h2>TIER 3 </h2>

            <div class="split container block">
                <div class="left right-border text-right">

                    <p>
                        <label for="Pregnant">Είναι Έγγυος;</label>
                        <select name="Pregnant" id="">
                            <option value="Yes">Ναι</option>
                            <option value="No">Όχι</option>
                        </select>
                    </p>

                    <p>
                        <label for="Smoker">Είναι Καπνιστής;</label>
                        <select name="smoker" id="smoker">
                            <option value="Yes">Ναι</option>
                            <option value="No">Όχι</option>
                        </select>
                    </p>
                    <p>
                        <label for="numofCigs">Αριθμός Τσιγάρων / Μέρα</label>
                        <input type="number" name="cigars" id="numofcig" placeholder="Cigars per day">
                    </p>
                    <p>
                        <label for="lastcig">Τελευταία Φορά Κάπνισε:</label>
                        <input type="date" name="cigardate" id="dateofcig">
                    </p>


                </div>

                <div class="right  text-right">

                    <p>
                        <label for="onsetLocalisation">Εντοπισμός Έναρξης</label>
                    </p>

                    <p>
                        <label for="Spinal">Νωτιαίος</label>
                        <input type="checkbox" name="Onselocalisation" value="Spinal">
                    </p>

                    <p>
                        <label for="Cortex">Φλοιός</label>
                        <input type="checkbox" name="Onselocalisation" value="Cortex">
                    </p>

                    <p>
                        <label for="Visual">Οπτικά</label>
                        <input type="checkbox" name="Onselocalisation" value="Visual">
                    </p>

                    <p>
                        <label for="Cerebellar">Παρεγκεφαλικός</label>
                        <input type="checkbox" name="Onselocalisation" value="Cerebellar">
                    </p>

                    <p>
                        <label for="Brainstem">Εγκεφαλικό Επεισόδιο</label>
                        <input type="checkbox" name="Onselocalisation" value="Brainstem">
                    </p>
                    <p>
                        <label for="onsetsymptoms">ΚΝΣ Συμπτώματα Έναρξης</label>
                        <input type="text" name="onsetsymptoms" id="onsetsymptoms" list="onsetsymptomslist" placeholder="E.x. Vision">
                        <datalist id="onsetsymptomslist" name="onsetsymptoms">
                            <option value="Vision">Οπτικά</option>
                            <option value="Motor">Κινητικά</option>
                            <option value="Sensory">Αισθητήριος</option>
                            <option value="Coordination">Συντονισμός</option>
                            <option value="Bowel/Bladder">Έντερο/Κύστη</option>
                            <option value="Fatigue">Κόπωση</option>
                            <option value="Cognitive">Γνωστικά</option>
                            <option value="Encephalopathy">Εγκεφαλοπάθεια</option>
                            <option value="Other">Άλλο</option>
                        </datalist>
                    </p>

                </div>
            </div>

            <div class="block container">

                <h3>Υπογράφων Φόρμας:
                    <input type="text" name="signer" required>
                    <input type="submit" name="Submit" value="Submit" id="subm" required>
                </h3>
            </div>
        </div>

        <div class="note-wrapper container">
            <strong>Πατώντας το κουμπί <i>Reset</i> όλα τα δεδομένα που έχετε εισάγει στην φόρμα θα διαγραφούν χωρίς να αποθηκευτούν!</strong>
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
        document.getElementById("eng").onclick = function() {
            location.href = "Multiple_Sclerosis_app.php";
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
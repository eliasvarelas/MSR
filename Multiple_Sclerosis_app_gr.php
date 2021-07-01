<?php
session_start();
$patientID = $_GET["id"];   // used to pass the patient id directly in the form
$patientNAME = $_GET["nm"]; // used to pass the pateint name directly in the form
?>
<!DOCTYPE html>
<html>
<head>
  <title>Multiple Sclerosis Registry</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <html lang="en-us">
  <meta charset="utf-8" />
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
    }

    .split{
      display: flex;
      flex-direction: row;
      margin: 0 auto;

    }
    .split > * {
      flex-basis: 100%;
    }
    .split > * + * {      /* space between the side-side tables */
      margin-left:1em;
    }
    @media (max-width: 40em){   /* some responsiveness for laptop-desktop screens */
      .split{
        flex-direction: column;
        flex-basis: 100%;
      }
      .split > * + * {
        margin-left:1em;
        margin-right:1em;
        margin-top: 1em;
      }
    }

    .container{
      margin: 0  auto;
      width: min(90%, 70rem);
    }
    section{
      padding: 0.5em 0;
    }

    table {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;
      border: 1px solid #ddd;
      padding: 16px;
      font-family: arial;
      min-height:100%;      /* kinda helps, but not the ideal solution, maybe use ids */
    }

    th, td {
      padding: 16px;
      height: auto;
    }



    th, td {
      border: 1px solid black;
      border-collapse: collapse;
      padding:5px;
      text-align:center;
      font-family: arial;


    }

    th {
      background-color: #6699ff;              /* Title box color */
      color: black;
      margin: auto;
    }
    /* table positioning... in development */

    .header {
      background-color: #ffffff;
      text-align: left;
      padding-left: 10px;
      padding-right: 10px;
      width: 80%;
      margin: auto;
      font-family: arial;
    }

    img {                   /*  image alignment for the MS image */
      display: block;
      margin-right: auto;
      width: 30%;
    }
    .lang {
      position: right;
      height:40px;
      width:80px;
      box-sizing:border-box;
      padding: 2px;
      border: 2px solid black;
      border-collapse: collapse;
      margin: 1rem 1rem;
    }

    input[type=date], input[type=number] {
      padding: 5px 5px;
      margin: 8px 0;
      box-sizing: border-box;
      font-family: arial;
    }

    input[type=text] {
      border: none;
      border-bottom: 1px solid black;
      font-family: arial;
      padding: 5px 5px;
      margin: 8px 0;
    }
    textarea {
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      resize: none;
      font-family: arial;
      width: auto;
    }
    tr:nth-child(even) {
      background-color: #ffffff;
      font-family: arial;
    }
    #purple{
      background-color: #b366ff;
    }

  </style>
</head>

<body>
  <input type="image" class="lang" id="eng" src="England.png">
  <script type="text/javascript">
    document.getElementById("eng").onclick = function () {
      location.href = "Multiple_Sclerosis_app.php";
    };
  </script>

  <form target="_blank" action="MSRforminsert.php" method="post" class="header">        <!-- action="form.php"-->

    <img src="MSregistryionian2.png" alt="Intro" style="float:left;">
    <br>
    <p style="font-family: arial;">Όνομα & Διεύθυνση:<br> <textarea name="NDS" rows="5" cols="40" name="NDS" autofocus></textarea> <br> </p>
    <div class="container">
      <table style="width:100%;">
        <tr>
          <th>Ημερομηνία: <input type="date" name="NDSdate"></th><th>ΑΜΚΑ: <input type="number" min=0 name="NDSnum"></th>
        </tr>
      </table>
      <table style="width:100%;">
        <tr>
          <th colspan="2">Φύλο</th><th>Ηλικία</th><th>Φυλή</th><th>Συννοσηρότητες</th>
        </tr>
        <tr>
          <td> Αρσενικό<br><input type="radio" name="Sex" value="Male"></td><td>Θηλυκό<br><input type="radio" name="Sex" value="Female"></td>
          <td> <input type="number" name="Age" min="1" max="150"></td>  <td><select id="Race" name="Race" required>
              <option value="American Indian">American Indian</option>
              <option value="Asian">Asian</option>
              <option value="Black">Black</option>
              <option value="Hispanic">Hispanic</option>
              <option value="Caucasian" selected>Caucasian</option>
              <option value="Unknown">Unknown</option>
            </select>
            </td>
            <td><input type="text" list="Comorbidities" name="Comorbidities"/>
            <datalist id="Comorbidities">
              <option value="Diabetes">Διαβήτης</option>
              <option value="Obesity">Παχυσαρκία</option>
              <option value="Heart Disease">Heart Disease</option>
            </datalist></td>
        </tr>
      </table>
    </div>

    <br>

    <h4 style="text-align:center; font-family: arial;">ΚΑΤΗΓΟΡΙΑ 1 ΟΛΑ ΠΡΕΠΕΙ ΝΑ ΣΥΜΠΛΗΡΩΘΟΥΝ</h4>

                      <!-- all the tables, not yet organized to fit in to a single page,prob css -->
    <section id="mstype">
      <div class="container">
        <div class="split">
          <div>
            <table>
              <tr>
                <th colspan="4">ΤΥΠΟΣ MS ΤΩΡΑ</th>
              </tr>
              <tr>
                <td><label for="RR">RR</label><br><input type="radio" id="RR" name="convsprad" value="RR" required></td>
                <td><label for="SP">SP</label><br><input type="radio" id="SP" name="convsprad" value="SP" required></td>
                <td><label for="PP">PP</label><br><input type="radio" id="PP" name="convsprad" value="PP" required></td>
                <td><label for="Other">Άλλο</label><br><input type="radio" id="Other" name="convsprad" value="Other" required></td>
              </tr>
              <tr>
                <th colspan="4">ΜΕΤΑΤΡΟΠΗ ΣΕ SP (αν είναι δυνατόν)<input type="text" name="convspnum" ></th>  <!-- check about the convsp data type -->
              </tr>
            </table>
          </div>
          <div>
            <table>     <!-- right hand side -->
              <tr>
                <th>Ημερομηνία Διάγνωσης</th><th colspan="4">Τύπος MS στην Διάγνωση</th>
              </tr>
              <tr>
                <td><input type="date" name="dateofdia" required></td>
                <td><label for="RR">RR</label><br><input type="radio" id="RR" name="dateofdiarad" value="RR" required></td>
                <td><label for="SP">SP</label><br><input type="radio" id="SP" name="dateofdiarad" value="SP" required></td>
                <td><label for="PP">PP</label><br><input type="radio" id="PP" name="dateofdiarad" value="PP" required></td>
                <td><label for="Other">Άλλο</label><br><input type="radio" id="Other" name="dateofdiarad" value="Other" required></td>
              </tr>
              <tr>
                <th >Ημερομηνία της έναρξης:</th><td colspan="4"><input type="date" name="onsetdate"></td>
             </tr>
            </table>
          </div>
        </div>
      </div>
    </section>
         <br>
    <section>
      <div class="container">
        <table>
          <tr>
            <th>Αριθμός Υποτροπιάσεων (ΜΟΝΟ RR)<br>(απο την τελευταία επίσκεψη/χρόνο)</th><th colspan="3">Σοβαρότητα</th>
          </tr>
          <tr>
            <td><input type="number" min="0" name="Noofrelapses" required></td>
            <td><label for="Mild">Ήπια</label><br><input type="radio" id="Mild" name="Noofrelapsesrad" value="Mild" required></td>
            <td><label for="Moderate">Μέτρια</label><br><input type="radio" id="Moderate" name="Noofrelapsesrad" value="Moderate" required></td>
            <td><label for="Severe">Σοβαρή</label><br><input type="radio" id="Severe" name="Noofrelapsesrad" value="Severe" required></td>
          </tr>
        </table>
      <br>
        <table>
          <tr>
            <th colspan="7">ΠΑΡΕΛΘΟΝΤΙΚΗ Θεραπεία τροποποίησης ασθένειας :(tick) Ημερομηνία έναρξης: <input type="date" name="pastTREATMENT" id="pastDate" required></th>    <!-- whole width -->
          </tr>
          <tr>
            <td>Alemtuzumab<br><input type="checkbox" name="pastTREATMENT" value="Alemtuzumab" ></td><td>Avonex<br><input type="checkbox" name="pastTREATMENT" value="Avonex"></td>
            <td>Betaferon<br><input type="checkbox" name="pastTREATMENT" value="Betaferon"></td><td>Copaxone<br><input type="checkbox" name="pastTREATMENT" value="Copaxone"></td>
            <td>Extavia<br><input type="checkbox" name="pastTREATMENT" value="Extavia"></td><td>Fingolimod<br><input type="checkbox" name="pastTREATMENT" value="Fingolimod"></td>
            <td>Mitoxantrone<br><input type="checkbox" name="pastTREATMENT" value="Mitoxantrone"></td>
          </tr>
          <tr>
            <td>Natalizumab<br><input type="checkbox" name="pastTREATMENT" value="Natalizumab"></td><td>Ocrelizumab<br><input type="checkbox" name="pastTREATMENT" value="Ocrelizumab"></td>
            <td>Rebif<br><input type="checkbox" name="pastTREATMENT" value="Rebif"></td><td>Tecfidera<br><input type="checkbox" name="pastTREATMENT" value="Tecfidera"></td>
            <td>Teriflunomide<br><input type="checkbox" name="pastTREATMENT" value="Teriflunomide"></td><td colspan="2">Καμία<br><input type="checkbox" name="pastTREATMENT" value="None"></td>
          </tr>
          <tr>
            <th>Ημερομηνία τερματισμού:</th><td><input type="date" name="pastTREATMENTdate" id="datestoped"></td><th>Λόγος</th>
            <td colspan="2"><label for="Lack of efficasy">Έλλειψη Αποτελεσματικότητας</label><br><input type="checkbox" id="Lack of efficasy" name="pastTREATMENTcheck" value="Lack of efficasy"></td>
            <td><label for="Side effects">Παρενέργειες</label><br><input type="checkbox" id="Side effects" name="pastTREATMENTcheck" value="Side effects"></td>
            <td><label for="Other">Άλλο</label><br><input type="checkbox" id="Other" name="pastTREATMENTcheck" value="Other"></td>
          </tr>
        </table>
      <br>
        <table>
          <tr>
            <th colspan="7">Παρούσα Θεραπεία τροποποίησης ασθένειας:(tick)  Ημερομηνία έναρξης: <input type="date" name="TREATMENTdate" id="presentdate" required></th>    <!-- whole width -->
          </tr>
          <tr>
            <td>Alemtuzumab<br><input type="checkbox" name="TREATMENT" value="Alemtuzumab"></td><td>Avonex<br><input type="checkbox" name="TREATMENT" value="Avonex" ></td>
            <td>Betaferon<br><input type="checkbox" name="TREATMENT" value="Betaferon" ></td><td>Copaxone<br><input type="checkbox" name="TREATMENT" value="Copaxone"></td>
            <td>Extavia<br><input type="checkbox" name="TREATMENT" value="Extavia" ></td><td>Fingolimod<br><input type="checkbox" name="TREATMENT" value="Fingolimod"></td>
            <td>Mitoxantrone<br><input type="checkbox" name="TREATMENT"value="Mitoxantrone" ></td>
          </tr>
          <tr>
            <td>Natalizumab<br><input type="checkbox" name="TREATMENT" value="Natalizumab"></td><td>Ocrelizumab<br><input type="checkbox" name="TREATMENT" value="Ocrelizumab"></td>
            <td>Rebif<br><input type="checkbox" name="TREATMENT" value="Rebif"></td><td>Tecfidera<br><input type="checkbox" name="TREATMENT" value="Tecfidera"></td>
            <td>Teriflunomide<br><input type="checkbox" name="TREATMENT" value="Teriflunomide"></td><td colspan="2">Καμία<br><input type="checkbox" name="TREATMENT" value="None"></td>
          </tr>
        </table>
      </div>
    </section>
      <br>
    <section>
      <div class="container">
        <div class="split">
          <div>
            <table>
              <tr>
                <th colspan="2">Τρέχων βαθμός EDSS (1-10): <input type="number" min="1" max="10" name="eddsscore"required></th>    <!-- left hand side, right next to the following table -->
              </tr>
              <tr>
                <td>7,5 Μέτρα Χρονομετρημένο Περπάτημα</td>
                <td>Χρόνος: <input type="time" name="edsstime" required></td>
              </tr>
              <tr>
                <td id="purple">Nine-Hole PEG Test</td><td>Χρόνος: <input type="time"></td>
              </tr>
            </table>
          </div>
          <div>
            <table>
              <tr>
                <td id="purple" colspan="3"> Ημερομηνία που λήφθηκε το EDSS : <input type="date" name="EDSSdate" required></td>
              </tr>
              <!-- <tr>
                <td><label for="Self Estimated">Αυτοεκτιμώμενος</label><br><input type="radio" id="Self Estimated" name="EDSSdaterad" value="Self Estimated" required></td>
                <td><label for="Trundle wheel">Εξέταση Κυλιόμενου Τροχού</label><br><input type="radio" id="Trundle wheel" name="EDSSdaterad" value="Trundle wheel" required></td>
                <td><label for="Treadmill">Ηλεκτρικός Διάδρομος</label><br><input type="radio" id="Treadmill" name="EDSSdaterad" value="Treadmill" required></td>
              </tr> -->
            </table>
          </div>
        </div>
      </div>
    </section>

    <section>
      <h3 style="text-align:center;">ΚΑΤΗΓΟΡΙΑ 2</h3>
      <div class="container">
        <div class="split">
          <div>
            <table>
              <tr>
                <th colspan="5">CNS MRI Onset Localisation/ΚΝΣ Τοποθεσία αρχικής MRI</th>
              </tr>
              <tr>
                <td>Spinal<br><input type="checkbox" name="MRIonsetlocalisation" value="Spinal"></td>
                <td>Cortex<br><input type="checkbox" name="MRIonsetlocalisation" value="Cortex"></td>
                <td>Brainstem<br><input type="checkbox" name="MRIonsetlocalisation" value="Brainstem"></td>
                <td>Cerebellum<br><input type="checkbox" name="MRIonsetlocalisation" value="Cerebellum"></td>
                <td>Visual<br><input type="checkbox" name="MRIonsetlocalisation" value="Spinal"></td>
              </tr>
            </table>
          </div>
          <div>
            <table>
              <tr>
                <th colspan="5">Οι βλάβες που ενισχύουν τη μαγνητική τομογραφία του ΚΝΣ τους τελευταίους 12 μήνες</th>
              </tr>
              <tr>
                <!-- <td colspan="2">Yes <input type="radio" value="Yes" name="MRIenhancing" id="MRIenhancing" checked><br>No<input type="radio" value="No" name="MRIenhancing"></td> -->
                <td colspan="2"><select id="MRIenhancing" name="MRIenhancing"><option value="Yes">Ναι</option><option value="No">Όχι</option> </select></td>
                <td colspan="3">Αριθμός: <input name="MRInum" type="number" id="MRInum"></td>
              </tr>
              <tr>
                <th colspan="5">Τοποθεσία</th>
              </tr>
              <tr>
                <td>Νωτιαίος<br><input type="checkbox" name="MRIenhancinglocation" value="Spinal" id="MRIloc"></td>
                <td>Φλοιός<br><input type="checkbox" name="MRIenhancinglocation" value="Cortex" id="MRIloc1"></td>
                <td>Εγκεφαλικό<br><input type="checkbox" name="MRIenhancinglocation" value="Brainstem" id="MRIloc2"></td>
                <td>Παρεγκεφαλικό<br><input type="checkbox" name="MRIenhancinglocation" value="Cerebellum" id="MRIloc3"></td>
                <td>Οπτικό<br><input type="checkbox" name="MRIenhancinglocation" value="Visual" id="MRIloc4"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>

    </section>

  <h4 style="text-align:center; font-family: arial;">ΚΑΤΗΓΟΡΙΑ 3 <br> </h4>

    <section>
      <div class="container">
        <div class="split">
          <div>
            <table>
              <tr>
                <th colspan="2">Είναι ο ασθενής έγγυος;</th>
              </tr>
              <tr>
                <td>Ναι<br><input type="radio" name="Pregnant"></td><td>Όχι<br><input type="radio" name="Pregnant" value="Pregnant"></td>
              </tr>
            </table>
          </div>
          <div>
            <table>
              <tr>
                <th colspan="2">Εντοπισμός Έναρξης στο ΚΝΣ<br></th>
              </tr>
              <tr>
                <td>Νωτιαίος <br><input type="checkbox" name="Onsetlocalisation" value="Spinal"></td><td>Φλοιός<br><input type="checkbox" name="Onsetlocalisation" value="Cortex"></td>
              </tr>
              <tr>
                <td>Οπτικός<br><input type="checkbox" name="Onsetlocalisation" value="Visual"></td><td>Παρεγκεφαλικός/Εγκεφαλικό<br><input type="checkbox" name="Onsetlocalisation" value="Cerebellar/Brainstem"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="container">
        <div class="split">
          <div>
            <table>
              <tr>
                <th>Καπνιστής</th>
                <td><select name="smoker" id="smoker"><option value="Yes">Ναι</option><option value="No">Όχι</option></td>
              </tr>
              <tr>
                <td>Αριθμός τσιγάρων ανα μέρα:</td><td colspan="2"><input type="number" min="0" name="cigars" id="numofcig" value="0"></td>
              </tr>
              <tr>
                <td>Κάπνισε τελευταία φορά:</td><td colspan="2"><input type="date" name="cigardate" id="dateofcig"></td>
              </tr>
            </table>
          </div>
          <div>
            <table>
              <tr>
                <th colspan="3">Συμπτώματα έναρξης </th>
              </tr>
              <tr>
                <td>Όραση<br><input type="checkbox" name="onsetsymptoms" value="Vision"></td><td>Κινητικό<br><input type="checkbox" name="onsetsymptoms" value="Motor"></td><td>Αισθητήριος<br><input type="checkbox" name="onsetsymptoms" value="Sensory"></td>
              </tr>
              <tr>
                <td>Συντονισμός<br><input type="checkbox" name="onsetsymptoms" value="Coordination"></td><td>Έντερο/Κύστη<br><input type="checkbox" name="onsetsymptoms" value="Bowel/Bladder"></td><td>Κόποση<br><input type="checkbox" name="onsetsymptoms" value="Fatigue"></td>
              </tr>
              <tr>
                <td>Γνωστική<br><input type="checkbox" name="onsetsymptoms" value="Cognitive"></td><td>Εγκεφαλοπάθεια<br><input type="checkbox" name="onsetsymptoms" value="Encephalopathy"></td><td>Άλλο<br><input type="checkbox" name="onsetsymptoms" value="Other"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>

  <h3 style="text-align:center;">Άτομο που συμπληρώνει την φόρμα:<input type="text" name="signer" required> <input type="submit" name="Submit" value="Submit" id="subm"required> </h3>
  </form>

  <script type="text/javascript"> // date validating client-side for pastStarted-pastEnded treatment
    document.getElementById('pastDate').addEventListener("change", function() {
      var inputpastdateStart = this.value;
      var pastdatestart = new Date(inputpastdateStart);
      document.getElementById('datestoped').setAttribute("min",inputpastdateStart);
    });
  </script>
  <script type="text/javascript"> // date validating client-side for past-present treatment
    document.getElementById('datestoped').addEventListener("change", function() {
      var inputpastdateStop = this.value;
      var pastdatestop = new Date(inputpastdateStop);
      document.getElementById('presentdate').setAttribute("min",inputpastdateStop);
    });
  </script>
  <script type="text/javascript"> // dynamicly disabling certain input boxes in the MRI tier
    document.getElementById('MRIenhancing').onchange = function disableInpMRI() {
      if (this.value === 'Yes') {
        document.getElementById('MRInum').disabled = false;
        document.getElementById('MRIloc').disabled = false;
        document.getElementById("MRIloc1").disabled = false;
        document.getElementById("MRIloc2").disabled = false;
        document.getElementById("MRIloc3").disabled = false;
        document.getElementById("MRIloc4").disabled = false;
      }
      else if (this.value === 'No') {
        document.getElementById("MRInum").disabled = true;
        document.getElementById("MRIloc").disabled = true;
        document.getElementById("MRIloc1").disabled = true;
        document.getElementById("MRIloc2").disabled = true;
        document.getElementById("MRIloc3").disabled = true;
        document.getElementById("MRIloc4").disabled = true;
      }
    }
    </script>
  <script type="text/javascript">// dynamicly disabling certain input boxes in the Smoker tier
    document.getElementById('smoker').onchange = function disableInpsmok() {
      if (this.value === 'Yes') {
        document.getElementById('numofcig').disabled = false;
        document.getElementById('dateofcig').disabled = false;
      }
      else if (this.value === 'No') {
        document.getElementById('numofcig').disabled = true;
        document.getElementById('dateofcig').disabled = true;
      }
    }
  </script>
</body>
</html>

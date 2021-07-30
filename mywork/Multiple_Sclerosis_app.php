<?php
session_start();
$patientID = $_GET["id"];   // used to pass the patient id directly in the form
$patientNAME = $_GET["nm"]; // used to pass the pateint name directly in the form
$patientDOB = $_GET["DOB"]; // used to pass the pateints age directly in the form
?>
<!DOCTYPE html>
<html>
<head>
  <title>Multiple Sclerosis Registry</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <html lang="en-us">
  <meta charset="utf-8" />
  <!-- <script src="functions.js" charset="utf-8"></script> -->
  <style>
    /*   make it responsive (the right way) without messing up the table possitioning   */
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
        width: 100%;
      }
      .split > * {
        flex-basis: 100%;
        max-width: 100%;
      }
      .split > * + * {      /* space between the side-side tables */
        margin-left:1em;
        margin-right:0;
      }
      @media (max-width: 600px){
        .split{
          display: flex;
          flex-direction: column;
          flex-basis: 100%;
          margin: 0 auto;
        }
        .split > * + * {
          margin-left:0;
          margin-right:0;
          margin-top: 1em;
          flex-basis: 100%;
        }
      }
      .container{
        position:relative;
        margin: 0  auto;
        width: min(95%, 70rem);
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
        min-height:100%;
        max-width: 100%;
        word-break: break-all;
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
        background-color: #7386D5;              /* Title box color */
        color: black;
        margin: auto;
      }
      /* table positioning */
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
      h3,h4 {
        text-align: center;
        font-family: Arial;
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
        background-color: white;
        font-family: arial;
      }
      #purple{
        background-color: #ce99ff;
      }
      .note-wrapper{
        display: block;
        margin-top: 1em;
        margin-left: 1em;
        margin-right: 1em;
        /* margin-bottom: 1em; */
        padding-top: 2em;
        padding-bottom: 2em;
        text-align: center;
        background-color: #ffff33;
        border-radius: 24px;
      }
      .important{
        font-weight: bold;
        /* color: red; */
      }
  </style>
</head>

<body>
  <input type="image" class="lang" id="gr" src="gr.png">    <!-- redirects the user to the greek form -->
  <script type="text/javascript">
    document.getElementById("gr").onclick = function() {
      location.href = "Multiple_Sclerosis_app_gr.php";
    };
  </script>

    <!-- Starting the form -->
   <form target="_blank" action="MSRforminsert.php" method="post" class="header"> <!-- currently the form gets only the patients ID passed directly -->
    <img src="MSregistryionian2.png" alt="MSR ionian university" style="float:left;">    <!--picture with the logo of the laboratory and the university  -->
    <br>
    <p> Name & Address:<br><textarea name="NDS" rows="5" cols="40" name="NDS"> <?php echo $patientNAME?> </textarea>  <!-- gets the info, but doesnt print them in the boxes --> <br> </p>
    <div class="container">
      <table>
        <tr>
          <th>Date: <input id="ndsdate" type="date" name="NDSdate"></th><th>Study ID: <input type="number" min=0 name="NDSnum" value="<?php echo $patientID?>"></th>
        </tr>
      </table>
      <table> <!-- style width 100% -->
        <tr>
          <th colspan="2">Gender</th><th>Age</th><th>Race</th><th>Comorbidities</th>
        </tr>
        <tr>
          <td> Male<br><input type="radio" name="Sex" value="Male" required></td><td>Female<br><input type="radio" name="Sex" value="Female" required></td>
          <td> <input type="number" name="Age" min="1" max="150" id="Age"></td>
          <td><select id="Race" name="Race" required>
            <option value="American Indian">American Indian</option>
            <option value="Asian">Asian</option>
            <option value="Black">Black</option>
            <option value="Hispanic">Hispanic</option>
            <option value="Caucasian">Caucasian</option>
            <option value="Unknown">Unknown</option>
          </select>
          </td>
            <td>
              <input type="text" list="Comorbidities" name="Comorbidities"/>
              <datalist id="Comorbidities">
                <option value="Diabetes">Diabetes</option>
                <option value="Obesity">Obesity</option>
                <option value="Heart Disease">Heart Disease</option>
                <option value="Renal Failure">Renal Failure</option>
                <option value="Hepatic Failure">Hepatic Failure</option>
                <option value="Dyslipidemia">Dyslipidemia</option>
                <option value="Autoimmune">Autoimmune</option>
              </datalist>
            </td>
        </tr>
      </table>
    </div>

    <br>
    <h3>TIER 1 All MUST BE FILLED IN</h3>
    <!-- all the tables organized to fit in to a single page -->
    <section>
      <div class="container">
        <div class="split">
          <div>
            <table>
              <tr>
                <th colspan="4">MS TYPE NOW</th>
              </tr>
              <tr>
                <td><label for="RR">RR</label><br><input type="radio" id="RR" name="convsprad" value="RR" required></td>
                <td><label for="SP">SP</label><br><input type="radio" id="SP" name="convsprad" value="SP" required></td>
                <td><label for="PP">PP</label><br><input type="radio" id="PP" name="convsprad" value="PP" required></td>
                <td><label for="Other">Other</label><br><input type="radio" id="Other" name="convsprad" value="Other" required></td>
              </tr>
              <tr>
                <th colspan="4">CONVERSION TO SP (if possible)<input type="number" name="convspnum" ></th>
              </tr>
            </table>
          </div>

          <div>
            <table>       <!-- right hand side -->
              <tr>
                <th>Date of Diagnosis</th><th colspan="4">MS Type at Diagnosis</th>
              </tr>
              <tr>
                <td><input type="date" name="dateofdia" id="dateofdia" required></td>
                <td><label for="RR">RR</label><br><input type="radio" id="RR" name="dateofdiarad" value="RR" required></td>
                <td><label for="SP">SP</label><br><input type="radio" id="SP" name="dateofdiarad" value="SP" required></td>
                <td><label for="PP">PP</label><br><input type="radio" id="PP" name="dateofdiarad" value="PP" required></td>
                <td><label for="Other">Other</label><br><input type="radio" id="Other" name="dateofdiarad" value="Other" required></td>
              </tr>
              <tr>
                <th >Date of Onset:</th><td colspan="4"><input type="date" name="onsetdate" id="dateonset"></td>
             </tr>
            </table>
          </div>
        </div>
      </div>
    </section>
    <br>
    <section>
      <div class="container">
        <div class="split">
          <div>
            <table>  <!-- center -->
              <tr>
                <th>No. of Relapses(RR only)<br>(since last visit/year)</th>
              </tr>
              <tr>
                <td><input type="number" min="0" name="Noofrelapses" required></td>
              </tr>
            </table>
          </div>
          <div>
            <table>
              <tr>
                <th colspan="3">Severity</th>
              </tr>
              <tr>
                <td><label for="Mild">Mild</label><br><input type="radio" id="Mild" name="Noofrelapsesrad" value="Mild" required></td>
                <td><label for="Moderate">Moderate</label><br><input type="radio" id="Moderate" name="Noofrelapsesrad" value="Moderate" required></td>
                <td><label for="Severe">Severe</label><br><input type="radio" id="Severe" name="Noofrelapsesrad" value="Severe" required></td>
              </tr>
            </table>
          </div>
        </div>
        <br>
          <table>   <!-- center -->
            <tr>
              <th colspan="7">PAST Disease modifying treatment:(tick) Date Started: <input type="date" name="pastTREATMENT" id="pastDate" required></th>    <!-- whole width -->
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
              <td>Teriflunomide<br><input type="checkbox" name="pastTREATMENT" value="Teriflunomide"></td><td colspan="2">None<br><input type="checkbox" name="pastTREATMENT" value="None"></td>
            </tr>
            <tr>
              <th>Date stopped:</th><td><input type="date" name="pastTREATMENTdate" id="datestoped"></td><th>Reason</th>
              <td colspan="2"><label for="Lack of efficasy">Lack of efficasy</label><br><input type="checkbox" id="Lack of efficasy" name="pastTREATMENTcheck" value="Lack of efficasy"></td>
              <td><label for="Side effects">Side effects</label><br><input type="checkbox" id="Side effects" name="pastTREATMENTcheck" value="Side effects"></td>
              <td><label for="Other">Other</label><br><input type="checkbox" id="Other" name="pastTREATMENTcheck" value="Other"></td>
            </tr>
          </table>


        <br>

          <table>   <!-- center -->
            <tr>
              <th colspan="7">PRESENT Disease modifying treatment:(tick)  Date Started: <input type="date" name="TREATMENTdate" id="presentdate" required></th>    <!-- whole width -->
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
              <td>Teriflunomide<br><input type="checkbox" name="TREATMENT" value="Teriflunomide"></td><td colspan="2">None<br><input type="checkbox" name="TREATMENT" value="None"></td>
            </tr>
          </table>
      </div>
    </section>
        <br>
    <section>
      <div class="container">
        <div class="split">
          <div>
            <table> <!-- left hand side, right next to the following table -->
              <tr>
                <th colspan="2">Current EDSS Score (1-10): <input type="number" min="1" max="10" name="eddsscore"required></th>
              </tr>
              <tr>
                <td id="purple">7,5 meters Timed Walk</td>
                <td id="purple">Time: <input type="time" name="edsstime"><br>
              </tr>
              <tr>
                <td id="purple">Nine-Hole PEG Test</td><td id="purple">Time: <input type="time" name="edsstimePEG"></td>
              </tr>
            </table>
          </div>
            <br>
          <div>
            <table> <!-- right hand side -->
              <tr>
                <th colspan="3"> Date EDSS was taken: <input type="date" name="EDSSdate" required></th>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>

    <section>
      <h3>TIER 2 MRI ALL MUST BE COMPLETED</h3>
      <div class="container">
        <div class="split">
          <div>
            <table>
              <tr>
                <th colspan="5">CNS MRI Onset Localisation</th>
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
                <th colspan="5">CNS MRI enhancing lesions last 12 months</th>
              </tr>
              <tr>
                <!-- <td colspan="2">Yes <input type="radio" value="Yes" name="MRIenhancing" id="MRIenhancing" checked><br>No<input type="radio" value="No" name="MRIenhancing"></td> -->
                <td colspan="2"><select id="MRIenhancing" name="MRIenhancing"><option value="Yes">Yes</option><option value="No">No</option> </select></td>
                <td colspan="3">Number: <input name="MRInum" type="number" id="MRInum"></td>
              </tr>
              <tr>
                <th colspan="5">Location</th>
              </tr>
              <tr>
                <td>Spinal<br><input type="checkbox" name="MRIenhancinglocation" value="Spinal" id="MRIloc"></td>
                <td>Cortex<br><input type="checkbox" name="MRIenhancinglocation" value="Cortex" id="MRIloc1"></td>
                <td>Brainstem<br><input type="checkbox" name="MRIenhancinglocation" value="Brainstem" id="MRIloc2"></td>
                <td>Cerebellum<br><input type="checkbox" name="MRIenhancinglocation" value="Cerebellum" id="MRIloc3"></td>
                <td>Visual<br><input type="checkbox" name="MRIenhancinglocation" value="Visual" id="MRIloc4"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>

    </section>

  <h3>TIER 3 <br> </h3>

    <section>
      <div class="container">
        <div class="split">
          <div>
            <table>           <!-- left hand side -->
              <tr>
                <th colspan="2">Patient is pregnant</th>
              </tr>
              <tr>
                <td>Yes<br><input type="radio" name="Pregnant" value="Yes"></td><td>No<br><input type="radio" name="Pregnant" value="No"></td>
              </tr>
            </table>
          </div>
          <br>
          <div>
            <table>           <!-- right hand side -->
              <tr>
                <th colspan="2"> CNS Onset Localisation<br></th>
              </tr>
              <tr>
                <td>Spinal<br><input type="checkbox" name="Onsetlocalisation" value="Spinal"></td><td>Cortex<br><input type="checkbox" name="Onsetlocalisation" value="Cortex"></td>
              </tr>
              <tr>
                <td>Visual<br><input type="checkbox" name="Onsetlocalisation" value="Visual"></td><td>Cerebellar/Brainstem<br><input type="checkbox" name="Onsetlocalisation" value="Cerebellar/Brainstem"></td>
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
            <table>           <!-- left hand side -->
              <tr>
                <th>Smoker</th><!--<td>Yes<br><input type="radio" name="smoker" value="Yes" id="smokeryes" ></td><td>No<br><input type="radio" name="smoker" value="No" id="smokern" checked></td>-->
                <td><select name="smoker" id="smoker"><option value="Yes">Yes</option><option value="No">No</option></td>
              </tr>
              <tr>
                <td>No. per day:</td><td colspan="2"><input type="number" min="0" name="cigars" value="0" id="numofcig" ></td>
              </tr>
              <tr>
                <td>Smoked since:</td><td colspan="2"><input type="date" name="cigardate" id="dateofcig" ></td>
              </tr>
            </table>
          </div>

          <br>

          <div>
            <table>                 <!-- right hand side -->
              <tr>
                <th colspan="3">Onset Symptoms</th>
              </tr>
              <tr>
                <td>Vision<br><input type="checkbox" name="onsetsymptoms" value="Vision"></td><td>Motor<br><input type="checkbox" name="onsetsymptoms" value="Motor"></td><td>Sensory<br><input type="checkbox" name="onsetsymptoms" value="Sensory"></td>
              </tr>
              <tr>
                <td>Coordination<br><input type="checkbox" name="onsetsymptoms" value="Coordination"></td><td>Bowel/Bladder<br><input type="checkbox" name="onsetsymptoms" value="Bowel/Bladder"></td><td>Fatigue<br><input type="checkbox" name="onsetsymptoms" value="Fatigue"></td>
              </tr>
              <tr>
                <td>Cognitive<br><input type="checkbox" name="onsetsymptoms" value="Cognitive"></td><td>Encephalopathy<br><input type="checkbox" name="onsetsymptoms" value="Encephalopathy"></td><td>Other<br><input type="checkbox" name="onsetsymptoms" value="Other"></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>
  <br>

  <h3>Person Completing this form:<input type="text" name="signer" required> <input type="submit" name="Submit" value="Submit" id="subm"required> </h3>

  <div class="note-wrapper">
    <p><strong>By clicking the <i>Reset</i> button any input that you have entered in the form will be erased and will NOT be saved!</strong></p>
  </div>
  <h3> Reset the form? <br><input type="reset" name="resetform" id="resetbutton" class="important"></h3>
  <input type="date" id="dob" value="<?php echo $patientDOB;?>" hidden>
  </form>

  <script type="text/javascript"> // date validating client-side for pastStarted-pastEnded treatment
    document.getElementById('pastDate').addEventListener("change", function() {
      var inputpastdateStart = this.value;
      var pastdatestart = new Date(inputpastdateStart);
      document.getElementById('datestoped').setAttribute("min",inputpastdateStart);
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
  <script type="text/javascript">
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
    function calcAge() {
      var date = new Date();
      var day = date.getDate(),
          month = date.getMonth() + 1,
          year = date.getFullYear();

      month = (month < 10 ? "0" : "") + month;
      day = (day < 10 ? "0" : "") + day;

      var today = year + "/" + month + "/" + day;
      var dateOfBirth = document.getElementById('dob');
      var Age = today - dateOfBirth;

      var ageinputbox = document.getElementById('Age').innerHTML = Age;  // make it print the calculated Age on page load
    }
  </script>

</body>
</html>

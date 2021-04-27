<?php
session_start();
$patientID = $_GET["id"];
$patientNAME = $_GET["nm"];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Multiple Sclerosis Registry</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <html lang="en-us">
  <meta charset="utf-8" />
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

      }
      .split > * {
        flex-basis: 100%;
      }
      .split > * + * {      /* space between the side-side tables */
        margin-left:1em;
        margin-right:1em;
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
    <input type="image" class="lang" id="gr" src="gr.png">
    <script type="text/javascript">
      document.getElementById("gr").onclick = function () {
          location.href = "Multiple_Sclerosis_app_gr.html";
      };
    </script>

                <!-- Starting the form -->
   <form target="_blank" action="MSRforminsert.php" method="post" class="header">
     <img src="MSregistryionian2.png" alt="MSR ionian university" style="float:left;">    <!--picture with the logo of the laboratory and the university  -->
    <br>
    <p style="font-family: arial;"> Name & Address:<br> <textarea name="NDS" rows="5" cols="40" name="NDS" value="<?php echo $patientNAME; ?>" autofocus></textarea>
      <br> </p>
    <table style="width:100%;">
      <tr>
        <th>Date: <input type="date" name="NDSdate"  max="" ></th><th>Study ID: <input type="number" min=0 name="NDSnum" value="<?php echo $patientID?>"></th>
      </tr>
    </table>
    <table style="width:100%;">
      <tr>
        <th colspan="2">Gender</th><th>Age</th><th>Race</th><th>Comorbidities</th>
      </tr>
      <tr>
        <td> Male<br><input type="radio" name="Sex" value="Male" required></td><td>Female<br><input type="radio" name="Sex" value="Female" required></td>
        <td> <input type="number" name="Age" min="1"></td>
        <td><select id="Race" name="Race" required>
          <option value="American Indian">American Indian</option>
          <option value="Asian">Asian</option>
          <option value="Black">Black</option>
          <option value="Hispanic">Hispanic</option>
          <option value="Caucasian">Caucasian</option>
          <option value="Unknown">Unknown</option>
        </select>
      </td><td><select name="Comorbidities" name="Comorbidities" required> <!-- want to make a dropdown with a freetext as a last input -->
        <option value="Diabetes">Diabetes</option>
        <option><input type="text" name="Comorbidities"></option>
      </tr>
    </table>
    <br>
    <h4 style="text-align:center; font-family: arial;">TIER 1 All MUST BE FILLED IN</h4>

                                          <!-- all the tables organized to fit in to a single page -->
    <section id="mstype">
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
                <th colspan="4">CONVERSION TO SP (if possible)<input type="text" name="convspnum" ></th>
              </tr>
            </table>
          </div>

          <div>
            <table>       <!-- right hand side -->
              <tr>
                <th>Date of Diagnosis</th><th colspan="4">MS Type at Diagnosis</th>
              </tr>
              <tr>
                <td><input type="date" name="dateofdia" required></td>
                <td><label for="RR">RR</label><br><input type="radio" id="RR" name="dateofdiarad" value="RR" required></td>
                <td><label for="SP">SP</label><br><input type="radio" id="SP" name="dateofdiarad" value="SP" required></td>
                <td><label for="PP">PP</label><br><input type="radio" id="PP" name="dateofdiarad" value="PP" required></td>
                <td><label for="Other">Other</label><br><input type="radio" id="Other" name="dateofdiarad" value="Other" required></td>
              </tr>
              <tr>
                <th >Date of Onset:</th><td colspan="4"><input type="date" name="onsetdate"></td>
             </tr>
            </table>
          </div>
        </div>
      </div>
    </section>

         <br>

    <section>
      <div class="container">
          <table>  <!-- center -->
            <tr>
              <th>No. of Relapses(RR only)<br>(since last visit/year)</th><th colspan="3">Severity</th>
            </tr>
            <tr>
              <td><input type="number" min="0" name="Noofrelapses" required></td>
              <td><label for="Mild">Mild</label><br><input type="radio" id="Mild" name="Noofrelapsesrad" value="Mild" required></td>
              <td><label for="Moderate">Moderate</label><br><input type="radio" id="Moderate" name="Noofrelapsesrad" value="Moderate" required></td>
              <td><label for="Severe">Severe</label><br><input type="radio" id="Severe" name="Noofrelapsesrad" value="Severe" required></td>
            </tr>
          </table>
        <br>
          <table>   <!-- center -->
            <tr>
              <th colspan="7">PAST Disease modifying treatment:(tick) Date Started: <input type="date" name="pastTREATMENT" required></th>    <!-- whole width -->
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
              <th>Date stopped:</th><td><input type="date" name="pastTREATMENTdate" ></td><th>Reason</th>
              <td colspan="2"><label for="Lack of efficasy">Lack of efficasy</label><br><input type="checkbox" id="Lack of efficasy" name="pastTREATMENTcheck" value="Lack of efficasy"></td>
              <td><label for="Side effects">Side effects</label><br><input type="checkbox" id="Side effects" name="pastTREATMENTcheck" value="Side effects"></td>
              <td><label for="Other">Other</label><br><input type="checkbox" id="Other" name="pastTREATMENTcheck" value="Other"></td>
            </tr>
          </table>

        <br>

          <table>   <!-- center -->
            <tr>
              <th colspan="7">PRESENT Disease modifying treatment:(tick)  Date Started: <input type="date" name="TREATMENTdate" required></th>    <!-- whole width -->
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
                <td>7,5 meters Timed Walk</td>
                <td>Time: <input type="time" name="edsstime" required><br>
              </tr>
              <tr>
                <td id="purple">Nine-Hole PEG Test</td><td>Time: <input type="time" name="edsstimePEG"></td>
              </tr>
            </table>
          </div>
            <br>
          <div>
            <table> <!-- right hand side -->
              <tr>
                <td id="purple" colspan="3"> Date EDSS was taken: <input type="date" name="EDSSdate" required></td>
              </tr>
              <!-- <tr>
                <td><label for="Self Estimated">Self Estimated</label><br><input type="radio" id="Self Estimated" name="EDSSdaterad" value="Self Estimated" required></td>
                <td><label for="Trundle wheel">Trundle wheel</label><br><input type="radio" id="Trundle wheel" name="EDSSdaterad" value="Trundle wheel" required></td>
                <td><label for="Treadmill">Treadmill</label><br><input type="radio" id="Treadmill" name="EDSSdaterad" value="Treadmill" required></td>
              </tr> -->
            </table>
          </div>
        </div>
      </div>
    </section>

  <h4 style="text-align:center; font-family: arial;">TIER 2 <br> </h4>

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
                <th>Smoker</th><td>Yes<br><input type="radio" name="smoker" value="Yes"></td><td>No<br><input type="radio" name="smoker" value="No"></td>
              </tr>
              <tr>
                <td>No. per day:</td><td colspan="2"><input type="number" min="0" name="cigars" value="0"></td>
              </tr>
              <tr>
                <td>Smoked since:</td><td colspan="2"><input type="date" name="cigardate"></td>
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

  <h3 style="text-align:center;">Person Completing this form:<input type="text" name="signer" required> <input type="submit" name="Submit" value="Submit" id="subm"required> </h3>
  </form>


</body>
</html>

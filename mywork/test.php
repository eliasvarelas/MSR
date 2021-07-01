<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>some title</title>
    <script src="functions2.js" charset="utf-8"></script>
    <style>
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
    </style>
  </head>
  <body>
    <form class="" action="test.html" method="post">
      <table>
        <tr>
          <th colspan="5">CNS MRI enhancing lesions last 12 months</th>
        </tr>
        <tr>
          <!-- <td colspan="2">Yes <input type="radio" value="Yes" name="MRIenhancing" id="MRIenhancing" checked><br>No<input type="radio" value="No" name="MRIenhancing"></td> -->
          <td colspan="2"><select id="MRIenhancing"><option value="Yes">Yes</option><option value="No">No</option> </select></td>
          <td colspan="3">Number: <input name="MRInum" type="number" id="MRInum"></td>
        </tr>
      </table>
    </form>
    <script type="text/javascript">

      document.getElementById('MRIenhancing').onchange = function disableInpMRI() {
        if (this.value === 'Yes') {
          document.getElementById('MRInum').disabled = false;
        }
        else if (this.value === 'No') {
          document.getElementById("MRInum").disabled = true;
        }
      }

    </script>
  </body>
</html>

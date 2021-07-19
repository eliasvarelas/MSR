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
          <th> <select class="" name="some" id="Attributes">
            <option value="num" >THIS SHOULD BE A NUMBER </option>
            <option value="radio" >This shoyld be a radio</option>
            <option value="text" >this should be a text</option>
          </select> </th>
        </tr>
        <tr>
          <td><input type="text" name="button" id="button"></td>
        </tr>
      </table>
    </form>
    <script type="text/javascript">
      function addressChange() {
        var inputBox = document.getElementById('button');
        // this.value == 'radio' ? inputBox.type = 'radio' : inputBox.type = 'text';
        if (this.value == 'num') {
          inputBox.type = 'number';
        } else if (this.value == 'radio') {
          inputBox.type = 'radio';
        } else if (this.value == 'text') {
          inputBox.type = 'text';
        }
      }
      document.getElementById('Attributes').addEventListener('change', addressChange);
    </script>
  </body>
</html>

function setcurrentDate(){
  var date = new Date();
  var day = date.getDate(),
      month = date.getMonth() + 1,
      year = date.getFullYear();

  month = (month < 10 ? "0" : "") + month;
  day = (day < 10 ? "0" : "") + day;

  var today = day + "/" + month + "/" + year,
  document.getElementById('ndsdate').value = today;
}
function setMaxDate(){
  var dtToday = new Date();
  var month = dtToday.getMonth() + 1;     // getMonth() is zero-based
  var day = dtToday.getDate();
  var year = dtToday.getFullYear();
  if(month < 10)
      month = '0' + month.toString();
  if(day < 10)
      day = '0' + day.toString();

  var pastDate = year + '-' + month + '-' + day;
  $('#pastDate').attr('max', pastDate);
}

document.getElementById('MRIenhancing').onchange = function disableInpMRI() {
  if (this.value === 'Yes') {
    document.getElementById('MRInum').disabled = false;
    document.getElementById('MRIloc').disabled = false;
  }
  else if (this.value === 'No') {
    document.getElementById("MRInum").disabled = true;
  }
}


document.getElementById('smoker').onchange = disableInpsmok() {
  if (this.value === 'No') {
    document.getElementById('numofcig').disabled = true;
    document.getElementById('dateofcig').disabled = true;
  } else {
    document.getElementById('numofcig').disabled = false;
    document.getElementById('dateofcig').disabled = false;
  }
}

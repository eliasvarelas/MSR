// test file
document.getElementById('MRIenhancing').onchange = function disableInpMRI() {
  if (this.value === 'Yes') {
    document.getElementById('MRInum').disabled = false;
  }
  else if (this.value === 'No') {
    document.getElementById("MRInum").disabled = true;
  }
}

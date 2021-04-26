function customconfirm() {
  this.render = function(dialog,op,id){
    // var winW = window.innerWidth;
    // var winH
    dialogoverlay.style.display = "block";
    document.getElementById('dialogboxhead').innerHTML = "Confirm";
    document.getElementById('dialogboxbody').innerHTML = dialog;
    document.getElementById('dialogboxfoot').innerHTML = '<button onclick='Confirm.yes(\''+op+'\',\''+id+'\')">Yes</button> <button onclick="Confirm.no()">No</button>';
    this.no = function(){
      document.getElementById('dialogbox').style.display = "none";
      document.getElementById('dialogoverlay').style.display = "none";
    }
    this.yes = function(){
      if (op == "delete_patient") {
        deletePatient(id);
      }
      document.getElementById('dialogbox').style.display = "none";
      document.getElementById('dialogoverlay').style.display = "none";
    }
  }
}
var Confirm = new customconfirm();

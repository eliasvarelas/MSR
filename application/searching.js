var inputBox = document.getElementById('inputBox').hidden = false;
Sex_td_male.hidden = true;
Sex_td_female.hidden = true;

var sele = document.getElementById('selectth').onchange = function inputBoxChange() {

    // get all the elements from the DOM
    var srchoption = document.getElementById('srchoption');
    var introParagraph = document.getElementById('intro');
    var attr = document.getElementById('Attributes');
    var inputBox = document.getElementById('inputBox');
    var Comorbidities_td = document.getElementById('Race_td');
    var Sex_td_male = document.getElementById('Sex_td_male');
    var Sex_td_female = document.getElementById('Sex_td_female');
    var Comorbidities_td = document.getElementById('Comorbidities_td');
    var Pregnant_Smoker_td = document.getElementById('Pregnant_Smoker_td');
    var onsetsymptoms_td = document.getElementById('onsetsymptoms_td');
    var MRIonsetlocalisation_td = document.getElementById('MRIonsetlocalisation_td');
    var Onsetlocalisation_td = document.getElementById('Onsetlocalisation_td');
    var MRIenhancing_td = document.getElementById('MRIenhancing_td');
    var mriRadio = document.getElementById('MRIenhancing_radio');
    var MRIenhancing_tr = document.getElementById('MRIenhancing_tr');
    var MRIenhancing_td_extented = document.getElementById('MRIenhancing_td_extented');
    var MRIenhancing_num = document.getElementById('MRIenhancing_num');
    var MRIenhancing_list = document.getElementById('MRIenhancing_list');
    var email_tr = document.getElementById('Email_tr');
    var email_td = document.getElementById('Email_td');

    if (attr.value == 'ID') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', " Patient ID");
        introParagraph.innerHTML = "Enter the ID of the Patient You Are Looking for ";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'Sex') {
        introParagraph.innerHTML = "Enter the Sex of the Patient You Are Looking for ";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = false;
        Sex_td_female.hidden = false;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

    } else if (attr.value == 'Smoker') {
        introParagraph.innerHTML = "Enter if the Patient is a Smoker or Not";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = false;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

    } else if (attr.value == 'Name') {
        srchoption.type = 'text';
        srchoption.setAttribute('placeholder', " Full Name");
        introParagraph.innerHTML = "Enter the Name of the Patient You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

    } else if (attr.value == 'Race') {
        introParagraph.innerHTML = "Enter the Race of the Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = false;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

    } else if (attr.value == 'Comorbidities') {
        introParagraph.innerHTML = "Enter Any Comorbidities the Patient You Are Looking For May Have";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = false;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

    } else if (attr.value == 'Pregnant') {
        introParagraph.innerHTML = "Enter if the Patient is Pregnant or Not";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = false;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

    } else if (attr.value == 'Onsetlocalisation') {
        introParagraph.innerHTML = "Enter The Onset Localisation of The Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = false;

    } else if (attr.value == 'onsetsymptoms') {
        introParagraph.innerHTML = "Enter Any Onset Symptoms of The Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = false;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'MRIonsetlocalisation') {
        introParagraph.innerHTML = "Enter The MRI Onset Localisation of the Patient You Are Looking For";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = false;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'MRInum') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' MRI Lesions');
        introParagraph.innerHTML = "Enter The Number of MRI Lesions That The Patient You Are Looking For Has";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'PhoneNumber') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' Phone Number');
        introParagraph.innerHTML = "Enter The Phone Number of The Patient You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'MRIenhancing') {
        introParagraph.innerHTML = "Enter If the Patient Had Enhancing Lesions in His MRI";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = false;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'Age') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' Age > than');
        introParagraph.innerHTML = "Enter The Lower Age Threshold of The Patients You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'EDSS') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' EDSS Score');
        srchoption.setAttribute('maxlength', '2');
        introParagraph.innerHTML = "Enter The EDSS Score of The Patient You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;


    } else if (attr.value == 'Agesmaller') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' Age < than');
        introParagraph.innerHTML = "Enter The Higher Age Threshold of The Patients You Are Looking For";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;

    } else if (attr.value == 'MRInum') {
        srchoption.type = 'number';
        srchoption.setAttribute('placeholder', ' No. of Lesions');
        introParagraph.innerHTML = "Enter the No. of Lesions the Patient You Are Looking For Had";

        inputBox.hidden = false;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;
        MRIenhancing_td.hidden = true;
    } else if (attr.value == 'Email') {
        srchoption.type = 'email';
        srchoption.setAttribute('placeholder', ' Email');
        introParagraph.innerHTML = "Enter the Email of the Patient You Are Looking for";

        inputBox.hidden = true;
        Race_td.hidden = true;
        Sex_td_male.hidden = true;
        Sex_td_female.hidden = true;
        Comorbidities_td.hidden = true;
        Pregnant_Smoker_td.hidden = true;
        onsetsymptoms_td.hidden = true;
        MRIonsetlocalisation_td.hidden = true;
        Onsetlocalisation_td.hidden = true;
        MRIenhancing_td.hidden = true;
        email_tr.hidden = false;
        email_td.hidden = false;
    }
}

function addRow() {
    // hide the button
    document.getElementById('new_row_btn').hidden = true;

    // counters to keep track of the previous attributes clicked


    // **  counter that finds the attributed that was selected on the first round
    var prevAtt = document.getElementById('Attributes');
    var prevAttval = prevAtt.options[prevAtt.selectedIndex].value;
    // works fine

    var table = document.getElementById("searching_query_table");

    // creates the row for the inputbox header
    var hrow = table.insertRow(2);

    // formats the table a bit nicer
    var col = document.getElementById('selectth');
    col.setAttribute('colspan', '2');

    var col1 = document.getElementById('inputBox');
    col1.setAttribute('colspan', '2');

    //creates a new tb row that needs to have the next header, and a new row that will have the input field
    var headCell = hrow.insertCell(0);
    var headerCell = document.createElement('th');
    headCell.id = 'newhCell';
    // creates the header for the second info box
    // headCell.innerHTML = "More Info"; 

    // create the row for the inputbox
    var crow = table.insertRow(3);

    // create the second input field
    var cell = crow.insertCell(0);
    cell.id = 'newInputCell';
    var newInputBox = document.createElement('input');



    var select = document.createElement('select');
    select.setAttribute('id', 'newSelect');
    select.setAttribute('name', 'newAttributes');


    // the options in the new select attributes row
    var op1 = document.createElement('option');
    op1.value = 'Options';
    op1.innerHTML = 'Select an Option from Below';

    select.appendChild(op1);

    var op2 = document.createElement('option');
    op2.value = 'Name';
    op2.setAttribute('id', 'newInput');
    op2.innerHTML = 'Name';
    select.appendChild(op2);

    var op3 = document.createElement('option');
    op3.value = 'ID';
    op3.setAttribute('id', 'newInput');
    op3.innerHTML = 'Patient ID';
    select.appendChild(op3);

    var op4 = document.createElement('option');
    op4.value = 'Sex';
    op4.setAttribute('id', 'newInput');
    op4.innerHTML = 'Sex';
    select.appendChild(op4);

    var op5 = document.createElement('option');
    op5.value = 'Email';
    op5.setAttribute('id', 'newInput');
    op5.innerHTML = 'Patient Email';
    select.appendChild(op5);

    var op6 = document.createElement('option');
    op6.value = 'Age';
    op6.setAttribute('id', 'newInput');
    op6.innerHTML = 'Age >';
    select.appendChild(op6);

    var op7 = document.createElement('option');
    op7.value = 'Agesmaller';
    op7.setAttribute('id', 'newInput');
    op7.innerHTML = 'Age <';
    select.appendChild(op7);

    var op8 = document.createElement('option');
    op8.value = 'Race';
    op8.setAttribute('id', 'newInput');
    op8.innerHTML = 'Race';
    select.appendChild(op8);

    var op9 = document.createElement('option');
    op9.value = 'PhoneNumber';
    op9.setAttribute('id', 'newInput');
    op9.innerHTML = 'Phone Number';
    select.appendChild(op9);

    var op10 = document.createElement('option');
    op10.value = 'Comorbidities';
    op10.setAttribute('id', 'newInput');
    op10.innerHTML = 'Comorbidities';
    select.appendChild(op10);

    var op11 = document.createElement('option');
    op11.value = 'EDSS';
    op11.setAttribute('id', 'newInput');
    op11.innerHTML = 'EDSS Score';
    select.appendChild(op11);

    var op12 = document.createElement('option');
    op12.value = 'Pregnant';
    op12.setAttribute('id', 'newInput');
    op12.innerHTML = 'Is Pregnant';
    select.appendChild(op12);

    var op13 = document.createElement('option');
    op13.value = 'Onsetlocalisation';
    op13.setAttribute('id', 'newInput');
    op13.innerHTML = 'Onset Localisation';
    select.appendChild(op13);

    var op14 = document.createElement('option');
    op14.value = 'Smoker';
    op14.setAttribute('id', 'newInput');
    op14.innerHTML = 'Is a Smoker';
    select.appendChild(op14);

    var op15 = document.createElement('option');
    op15.value = 'onsetsymptoms';
    op15.setAttribute('id', 'newInput');
    op15.innerHTML = 'Onset Symptoms';
    select.appendChild(op15);

    var op16 = document.createElement('option');
    op16.value = 'MRIenhancing';
    op16.setAttribute('id', 'newInput');
    op16.innerHTML = 'MRI Enhancing Lesions';
    select.appendChild(op16);

    var op17 = document.createElement('option');
    op17.value = 'MRInum';
    op17.setAttribute('id', 'newInput');
    op17.innerHTML = 'MRI Lesion No.';
    select.appendChild(op17);

    var op18 = document.createElement('option');
    op18.value = 'MRIonsetlocalisation';
    op18.setAttribute('id', 'newInput');
    op18.innerHTML = 'MRI Onset Localisation';
    select.appendChild(op18);

    //**  add the functionality to perform queries either with the AND portal or the OR portal
    // var queryHeadCell = hrow.insertCell(1);
    // var queryHead = document.createElement('th');
    // queryHead.innerHTML = 'Add with AND / OR';
    // queryHead.classList.toggle('text-center');
    // // queryHeadCell.appendChild(queryHead);
    // hrow.appendChild(queryHead);

    // var queryCell = crow.insertCell(1);
    // var andorSelect = document.createElement('select');
    // andorSelect.setAttribute('name','querySelector');
    // andorSelect.setAttribute('selected',true);
    // andorSelect.setAttribute('id','querySelector');

    // var queryOpt = document.createElement('option');
    // queryOpt.value = 'AND';
    // queryOpt.innerHTML = 'AND';
    // andorSelect.appendChild(queryOpt);

    // var queryOpt1 = document.createElement('option');
    // queryOpt1.value = 'OR';
    // queryOpt1.innerHTML = 'OR';
    // andorSelect.appendChild(queryOpt1);

    // queryCell.appendChild(andorSelect);

    select.addEventListener("change", function changeSelect() {
        //todo needs to remove the fields of the previous boxes after changing to a new select value
        // todo if the select.value!= something, somethingInputBox=hidden try changing the newInputBox for a new var in each if statement...



        if (select.value == 'Name') {
            var newNameBox = document.createElement('input');
            newNameBox.type = "text";
            newNameBox.setAttribute('name', 'newName');
            newNameBox.setAttribute('placeholder', 'Name');
            newNameBox.setAttribute('id', 'newNameBox');
            cell.appendChild(newNameBox);

        } else if (select.value == 'ID') {
            var newIdBox = document.createElement('input');
            newIdBox.type = "text";
            newIdBox.setAttribute('name', 'newID');
            newIdBox.setAttribute('placeholder', 'Patient ID');
            newIdBox.setAttribute('id', 'newIdBox');
            cell.appendChild(newIdBox);
        } else if (select.value == 'Sex') {
            var newMaleRadio = document.createElement('input');
            newMaleRadio.type = "radio";
            newMaleRadio.setAttribute('name', 'newSex');
            newMaleRadio.setAttribute('id', 'newMaleRadio');
            // creates a label for the button
            var sexLabel = document.createElement('label');
            sexLabel.setAttribute('for', 'Male');
            sexLabel.setAttribute('id', 'sexLabel');
            sexLabel.innerHTML = ": Male";
            newMaleRadio.setAttribute('value', 'Male');
            // creates the new radio button for the female value
            var newFemaleRadio = document.createElement('input');
            newFemaleRadio.type = "radio";
            newFemaleRadio.setAttribute('name', 'newSex');
            newFemaleRadio.setAttribute('value', 'Female');
            newFemaleRadio.setAttribute('id', 'newFemaleRadio');
            // creates a label for the button
            var sexLabel1 = document.createElement('label');
            sexLabel1.setAttribute('for', 'Female');
            sexLabel1.setAttribute('id', 'sexLabel1');
            sexLabel1.innerHTML = ": Female";

            // appends the elements to the table cell
            cell.appendChild(newMaleRadio);
            cell.appendChild(sexLabel);
            cell.appendChild(newFemaleRadio);
            cell.appendChild(sexLabel1);

        } else if (select.value == 'Email') {
            var newEmailBox = document.createElement('input');
            newEmailBox.type = "email";
            newEmailBox.setAttribute('name', 'newEmail')
            newEmailBox.setAttribute('placeholder', 'Email');
            newEmailBox.setAttribute('id', 'newEmailBox');
            cell.appendChild(newEmailBox);
        } else if (select.value == 'Age') {
            var newAgeBox = document.createElement('input');
            newAgeBox.type = "number";
            newAgeBox.setAttribute('name', 'newAge')
            newAgeBox.setAttribute('placeholder', 'Age greater than');
            newAgeBox.setAttribute('id', 'newAgeBox');
            cell.appendChild(newAgeBox);
        } else if (select.value == 'Agesmaller') {
            var newAgeSmallerBox = document.createElement('input');
            newAgeSmallerBox.type = "number";
            newAgeSmallerBox.setAttribute('name', 'newAgesmaller')
            newAgeSmallerBox.setAttribute('placeholder', 'Age smaller than');
            newAgeSmallerBox.setAttribute('id', 'newAgeSmallerBox');
            cell.appendChild(newAgeSmallerBox);
        } else if (select.value == 'Race') { //** it prints the values by the buttons with some style 
            var newRaceBox = document.createElement('input');
            newRaceBox.type = "radio";
            newRaceBox.setAttribute('name', 'newRace')
            newRaceBox.setAttribute('value', 'Caucasian');
            newRaceBox.setAttribute('id', 'newRaceBox');
            var raceLabel = document.createElement('label');
            raceLabel.setAttribute('for', 'Caucasian');
            raceLabel.setAttribute('id', 'raceLabel');
            raceLabel.innerHTML = ": Caucasian";
            cell.appendChild(newRaceBox);
            cell.appendChild(raceLabel);

            var newRacebox1 = document.createElement('input');
            newRacebox1.type = "radio";
            newRacebox1.setAttribute('name', 'newRace');
            newRacebox1.setAttribute('value', 'American Indian');
            newRacebox1.setAttribute('id', 'newRaceBox1');
            var raceLabel1 = document.createElement('label');
            raceLabel1.setAttribute('for', 'American Indian');
            raceLabel1.setAttribute('id', 'raceLabel1');
            raceLabel1.innerHTML = ": American Indian";
            cell.appendChild(newRacebox1);
            cell.appendChild(raceLabel1);

            var newRacebox2 = document.createElement('input');
            newRacebox2.type = "radio";
            newRacebox2.setAttribute('name', 'newRace');
            newRacebox2.setAttribute('value', 'Asian');
            newRacebox2.setAttribute('id', 'newRaceBox2');
            var raceLabel2 = document.createElement('label');
            raceLabel2.setAttribute('for', 'Asian');
            raceLabel2.setAttribute('id', 'raceLabel2');
            raceLabel2.innerHTML = ": Asian";
            cell.appendChild(newRacebox2);
            cell.appendChild(raceLabel2);


            var newRacebox3 = document.createElement('input');
            newRacebox3.type = "radio";
            newRacebox3.setAttribute('name', 'newRace');
            newRacebox3.setAttribute('value', 'Black');
            newRacebox3.setAttribute('id', 'newRaceBox3');
            var raceLabel3 = document.createElement('label');
            raceLabel3.setAttribute('for', 'Black');
            raceLabel3.setAttribute('id', 'raceLabel3');
            raceLabel3.innerHTML = ": Black";
            cell.appendChild(newRacebox3);
            cell.appendChild(raceLabel3);

            var newRacebox4 = document.createElement('input');
            newRacebox4.type = "radio";
            newRacebox4.setAttribute('name', 'newRace');
            newRacebox4.setAttribute('value', 'Hispanic');
            newRacebox4.setAttribute('id', 'newRaceBox4');
            var raceLabel4 = document.createElement('label');
            raceLabel4.setAttribute('for', 'Hispanic');
            raceLabel4.setAttribute('id', 'raceLabel4');
            raceLabel4.innerHTML = ": Hispanic";
            cell.appendChild(newRacebox4);
            cell.appendChild(raceLabel4);

            var newRacebox5 = document.createElement('input');
            newRacebox5.type = "radio";
            newRacebox5.setAttribute('name', 'newRace');
            newRacebox5.setAttribute('value', 'Unknown');
            newRacebox5.setAttribute('id', 'newRaceBox5');
            var raceLabel5 = document.createElement('label');
            raceLabel5.setAttribute('for', 'Unknown');
            raceLabel5.setAttribute('id', 'raceLabel5');
            raceLabel5.innerHTML = ": Unknown";
            cell.appendChild(newRacebox5);
            cell.appendChild(raceLabel5);
        } else if (select.value == 'PhoneNumber') {
            var newPhonenumBox = document.createElement('input');
            newPhonenumBox.type = "text";
            newPhonenumBox.setAttribute('name', 'newPhonenum')
            newPhonenumBox.setAttribute('placeholder', 'Phone Number');
            newPhonenumBox.setAttribute('id', 'newPhonenumBox');
            cell.appendChild(newPhonenumBox);

        } else if (select.value == 'Comorbidities') {
            var newComorbiditiesBox = document.createElement('input');
            newComorbiditiesBox.type = "checkbox";
            newComorbiditiesBox.setAttribute('name', 'newComorbidities')
            newComorbiditiesBox.setAttribute('name', 'newComorbidities')
            newComorbiditiesBox.setAttribute('value', 'Diabetes');
            cell.appendChild(newComorbiditiesBox);

            var comorLabel = document.createElement('label');
            comorLabel.setAttribute('for', 'Diabetes');
            comorLabel.setAttribute('id', 'comorLabel');
            comorLabel.innerHTML = ": Diabetes";
            cell.appendChild(comorLabel);

            var newComorBox1 = document.createElement('input');
            newComorBox1.type = "checkbox";
            newComorBox1.setAttribute('name', 'newComorbidities');
            newComorBox1.setAttribute('value', 'Obesity');
            cell.appendChild(newComorBox1);

            var comorLabel1 = document.createElement('label');
            comorLabel1.setAttribute('for', 'Obesity');
            comorLabel1.setAttribute('id', 'comorLabel1');
            comorLabel1.innerHTML = ": Obesity";
            cell.appendChild(comorLabel1);

            var newComorBox2 = document.createElement('input');
            newComorBox2.type = "checkbox";
            newComorBox2.setAttribute('name', 'newComorbidities');
            newComorBox2.setAttribute('value', 'Heart Disease');
            cell.appendChild(newComorBox2);

            var comorLabel2 = document.createElement('label');
            comorLabel2.setAttribute('for', 'Heart Disease');
            comorLabel2.setAttribute('id', 'comorLabel2');
            comorLabel2.innerHTML = ": Heart Disease";
            cell.appendChild(comorLabel2);

            var newComorBox3 = document.createElement('input');
            newComorBox3.type = "checkbox";
            newComorBox3.setAttribute('name', 'newComorbidities');
            newComorBox3.setAttribute('value', 'Renal Failure');
            cell.appendChild(newComorBox3);

            var comorLabel3 = document.createElement('label');
            comorLabel3.setAttribute('for', 'Renal Failure');
            comorLabel3.setAttribute('id', 'comorLabel3');
            comorLabel3.innerHTML = ": Renal Failure";
            cell.appendChild(comorLabel3);

            var newComorBox4 = document.createElement('input');
            newComorBox4.type = "checkbox";
            newComorBox4.setAttribute('name', 'newComorbidities');
            newComorBox4.setAttribute('value', 'Dyslipidemia');
            cell.appendChild(newComorBox4);

            var comorLabel4 = document.createElement('label');
            comorLabel4.setAttribute('for', 'Dyslipidemia');
            comorLabel4.setAttribute('id', 'comorLabel4');
            comorLabel4.innerHTML = ": Dyslipidemia";
            cell.appendChild(comorLabel4);

            var newComorBox5 = document.createElement('input');
            newComorBox5.type = "checkbox";
            newComorBox5.setAttribute('name', 'newComorbidities');
            newComorBox5.setAttribute('value', 'Autoimmune');
            cell.appendChild(newComorBox5);

            var comorLabel5 = document.createElement('label');
            comorLabel5.setAttribute('for', 'Autoimmune');
            comorLabel5.setAttribute('id', 'comorLabel5');
            comorLabel5.innerHTML = ": Autoimmune";
            cell.appendChild(comorLabel5);

        } else if (select.value == 'EDSS') {
            var newBox = document.createElement('input');
            newBox.type = "number";
            newBox.setAttribute('name', 'newEDSS');
            newBox.setAttribute('placeholder', '1-10');
            newBox.setAttribute('id', 'newBox');
            cell.appendChild(newBox);
            // cell.removeChild(newComorBox1);
            // cell.removeChild(newComorBox2);
            // cell.removeChild(newComorBox3);
            // cell.removeChild(newComorBox4);
            // cell.removeChild(newComorBox5);

        } else if (select.value == 'Pregnant') {
            var newPregnantBox = document.createElement('input');
            newPregnantBox.type = "radio";
            newPregnantBox.setAttribute('name', 'newPregnant');
            newPregnantBox.setAttribute('value', 'Yes');
            newPregnantBox.setAttribute('id', 'newPregnantBox');
            cell.appendChild(newPregnantBox);

            var pregnantLabel = document.createElement('label');
            pregnantLabel.setAttribute('for', 'Yes');
            pregnantLabel.setAttribute('id', 'pregnantLabel');
            pregnantLabel.innerHTML = ": Yes";
            cell.appendChild(pregnantLabel);

            var newPregnantBox1 = document.createElement('input');
            newPregnantBox1.type = "radio";
            newPregnantBox1.setAttribute('name', 'newPregnant');
            newPregnantBox1.setAttribute('value', 'No');
            newPregnantBox1.setAttribute('id', 'newPregnantBox1');
            cell.appendChild(newPregnantBox1);

            var pregnantLabel1 = document.createElement('label');
            pregnantLabel1.setAttribute('for', 'No');
            pregnantLabel1.setAttribute('id', 'pregnantLabel1');
            pregnantLabel1.innerHTML = ": No";
            cell.appendChild(pregnantLabel1);

        } else if (select.value == 'Onsetlocalisation') {
            var newOnsetBox = document.createElement('input');
            newOnsetBox.type = "checkbox";
            newOnsetBox.setAttribute('name', 'newOnsetlocalisation');
            newOnsetBox.setAttribute('value', 'Spinal');
            newOnsetBox.setAttribute('id', 'newOnsetBox');
            cell.appendChild(newOnsetBox);

            var onsetLabel = document.createElement('label');
            onsetLabel.setAttribute('for', 'Spinal');
            onsetLabel.setAttribute('id', 'onsetLabel');
            onsetLabel.innerHTML = ": Spinal";
            cell.appendChild(onsetLabel);

            var newOnsetBox1 = document.createElement('input');
            newOnsetBox1.type = "checkbox";
            newOnsetBox1.setAttribute('name', 'newOnsetlocalisation');
            newOnsetBox1.setAttribute('value', 'Cortex');
            newOnsetBox1.setAttribute('id', 'newOnsetBox1');
            cell.appendChild(newOnsetBox1);

            var onsetLabel1 = document.createElement('label');
            onsetLabel1.setAttribute('for', 'Cortex');
            onsetLabel1.setAttribute('id', 'onsetLabel1');
            onsetLabel1.innerHTML = ": Cortex";
            cell.appendChild(onsetLabel1);

            var newOnsetBox2 = document.createElement('input');
            newOnsetBox2.type = "checkbox";
            newOnsetBox2.setAttribute('name', 'newOnsetlocalisation');
            newOnsetBox2.setAttribute('value', 'Brainstem');
            newOnsetBox2.setAttribute('id', 'newOnsetBox2');
            cell.appendChild(newOnsetBox2);

            var onsetLabel2 = document.createElement('label');
            onsetLabel2.setAttribute('for', 'Brainstem');
            onsetLabel2.innerHTML = ": Brainstem";
            cell.appendChild(onsetLabel2);

            var newOnsetBox3 = document.createElement('input');
            newOnsetBox3.type = "checkbox";
            newOnsetBox3.setAttribute('name', 'newOnsetlocalisation');
            newOnsetBox3.setAttribute('value', 'Cerebellum');
            newOnsetBox3.setAttribute('id', 'newOnsetBox3');
            cell.appendChild(newOnsetBox3);

            var onsetLabel3 = document.createElement('label');
            onsetLabel3.setAttribute('for', 'Cerebellum');
            onsetLabel3.setAttribute('id', 'onsetLabel3');
            onsetLabel3.innerHTML = ": Cerebellum";
            cell.appendChild(onsetLabel3);

            var newOnsetBox4 = document.createElement('input');
            newOnsetBox4.type = "checkbox";
            newOnsetBox4.setAttribute('name', 'newOnsetlocalisation');
            newOnsetBox4.setAttribute('value', 'Visual');
            newOnsetBox4.setAttribute('id', 'newOnsetBox4');
            cell.appendChild(newOnsetBox4);

            var onsetLabel4 = document.createElement('label');
            onsetLabel4.setAttribute('for', 'Visual');
            onsetLabel4.setAttribute('id', 'onsetLabel4');
            onsetLabel4.innerHTML = ": Visual";
            cell.appendChild(onsetLabel4);
        } else if (select.value == 'Smoker') {
            var newSmokerbox = document.createElement('input');
            newSmokerbox.type = "radio";
            newSmokerbox.setAttribute('name', 'newSmoker');
            newSmokerbox.setAttribute('value', 'Yes');
            znewSmokerbox.setAttribute('id', 'newSmokerBox');
            cell.appendChild(newSmokerbox);

            var smokerLabel = document.createElement('label');
            smokerLabel.setAttribute('for', 'Yes');
            smokerLabel.setAttribute('id', 'smokerLabel');
            smokerLabel.innerHTML = ": Yes";
            cell.appendChild(smokerLabel);

            var newSmokerbox1 = document.createElement('input');
            newSmokerbox1.type = "radio";
            newSmokerbox1.setAttribute('name', 'newSmoker');
            newSmokerbox1.setAttribute('value', 'No');
            znewSmokerbox.setAttribute('id', 'newSmokerBox');
            cell.appendChild(newSmokerbox1);

            var smokerLabel1 = document.createElement('label');
            smokerLabel1.setAttribute('for', 'No');
            smokerLabel1.setAttribute('id', 'smokerLabel1');
            smokerLabel1.innerHTML = ": No";
            cell.appendChild(smokerLabel1);

        } else if (select.value == 'onsetsymptoms') {
            var newOnsetsymptomsbox = document.createElement('input');
            newOnsetsymptomsbox.type = "checkbox";
            newOnsetsymptomsbox.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox.setAttribute('value', 'Vision');
            newOnsetsymptomsbox.setAttribute('id', 'newOnsetsymptomsbox');
            cell.appendChild(newOnsetsymptomsbox);

            var onsympLabel = document.createElement('label');
            onsympLabel.setAttribute('for', 'Vision');
            onsympLabel.setAttribute('id', 'onsympLabel');
            onsympLabel.innerHTML = ": Vision";
            cell.appendChild(onsympLabel);

            var newOnsetsymptomsbox1 = document.createElement('input');
            newOnsetsymptomsbox1.type = "checkbox";
            newOnsetsymptomsbox1.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox1.setAttribute('value', 'Motor');
            newOnsetsymptomsbox1.setAttribute('id', 'newOnsetsymptomsbox1');
            cell.appendChild(newOnsetsymptomsbox1);

            var onsympLabel1 = document.createElement('label');
            onsympLabel1.setAttribute('for', 'Motor');
            onsympLabel1.setAttribute('id', 'onsympLabel1');
            onsympLabel1.innerHTML = ": Motor";
            cell.appendChild(onsympLabel1);

            var newOnsetsymptomsbox2 = document.createElement('input');
            newOnsetsymptomsbox2.type = "checkbox";
            newOnsetsymptomsbox2.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox2.setAttribute('value', 'Sensory');
            newOnsetsymptomsbox2.setAttribute('id', 'newOnsetsymptomsbox2');
            cell.appendChild(newOnsetsymptomsbox2);

            var onsympLabel2 = document.createElement('label');
            onsympLabel2.setAttribute('for', 'Sensory');
            onsympLabel2.innerHTML = ": Sensory";
            cell.appendChild(onsympLabel2);

            var newOnsetsymptomsbox3 = document.createElement('input');
            newOnsetsymptomsbox3.type = "checkbox";
            newOnsetsymptomsbox3.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox3.setAttribute('value', 'Coordination');
            newOnsetsymptomsbox3.setAttribute('id', 'newOnsetsymptomsbox3');
            cell.appendChild(newOnsetsymptomsbox3);

            var onsympLabel3 = document.createElement('label');
            onsympLabel3.setAttribute('for', 'Coordination');
            onsympLabel3.innerHTML = ": Coordination";
            cell.appendChild(onsympLabel3);

            var newOnsetsymptomsbox4 = document.createElement('input');
            newOnsetsymptomsbox4.type = "checkbox";
            newOnsetsymptomsbox4.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox4.setAttribute('value', 'Bowel/Bladder');
            newOnsetsymptomsbox4.setAttribute('id', 'newOnsetsymptomsbox4');
            cell.appendChild(newOnsetsymptomsbox4);

            var onsympLabel4 = document.createElement('label');
            onsympLabel4.setAttribute('for', 'Bowel/Bladder');
            onsympLabel4.innerHTML = ": Bowel/Bladder";
            cell.appendChild(onsympLabel4);

            var newOnsetsymptomsbox5 = document.createElement('input');
            newOnsetsymptomsbox5.type = "checkbox";
            newOnsetsymptomsbox5.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox5.setAttribute('value', 'Fatigue');
            newOnsetsymptomsbox5.setAttribute('id', 'newOnsetsymptomsbox5');
            cell.appendChild(newOnsetsymptomsbox5);

            var onsympLabel5 = document.createElement('label');
            onsympLabel5.setAttribute('for', 'Fatigue');
            onsympLabel5.innerHTML = ": Fatigue";
            cell.appendChild(onsympLabel5);

            var newOnsetsymptomsbox6 = document.createElement('input');
            newOnsetsymptomsbox6.type = "checkbox";
            newOnsetsymptomsbox6.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox6.setAttribute('value', 'Cognitive');
            newOnsetsymptomsbox6.setAttribute('id', 'newOnsetsymptomsbox6');
            cell.appendChild(newOnsetsymptomsbox6);

            var onsympLabel6 = document.createElement('label');
            onsympLabel6.setAttribute('for', 'Cognitive');
            onsympLabel6.innerHTML = ": Cognitive";
            cell.appendChild(onsympLabel6);

            var newOnsetsymptomsbox7 = document.createElement('input');
            newOnsetsymptomsbox7.type = "checkbox";
            newOnsetsymptomsbox7.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox7.setAttribute('value', 'Encephalopathy');
            newOnsetsymptomsbox7.setAttribute('id', 'newOnsetsymptomsbox7');
            cell.appendChild(newOnsetsymptomsbox7);

            var onsympLabel7 = document.createElement('label');
            onsympLabel7.setAttribute('for', 'Encephalopathy');
            onsympLabel7.innerHTML = ": Encephalopathy";
            cell.appendChild(onsympLabel7);

            var newOnsetsymptomsbox8 = document.createElement('input');
            newOnsetsymptomsbox8.type = "checkbox";
            newOnsetsymptomsbox8.setAttribute('name', 'newOnsetsymptoms');
            newOnsetsymptomsbox8.setAttribute('value', 'Other');
            newOnsetsymptomsbox8.setAttribute('id', 'newOnsetsymptomsbox8');
            cell.appendChild(newOnsetsymptomsbox8);

            var onsympLabel8 = document.createElement('label');
            onsympLabel8.setAttribute('for', 'Other');
            onsympLabel8.innerHTML = ": Other";
            cell.appendChild(onsympLabel8);

        } else if (select.value == 'MRIenhancing') {
            var newMRIenhBox = document.createElement('input');
            newMRIenhBox.type = "radio";
            newMRIenhBox.setAttribute('name', 'newMRIenhancing');
            newMRIenhBox.setAttribute('value', 'Yes');
            newMRIenhBox.setAttribute('id', 'newMRIenhBox');
            cell.appendChild(newMRIenhBox);

            var mrienhLabel = document.createElement('label');
            mrienhLabel.setAttribute('for', 'Yes');
            mrienhLabel.setAttribute('id', 'mrienhLabel');
            mrienhLabel.innerHTML = ": Yes";
            cell.appendChild(mrienhLabel);

            var newMRIenhBox1 = document.createElement('input');
            newMRIenhBox1.type = "radio";
            newMRIenhBox1.setAttribute('name', 'newMRIenhancing');
            newMRIenhBox1.setAttribute('value', 'No');
            newMRIenhBox1.setAttribute('id', 'newMRIenhBox1');
            cell.appendChild(newMRIenhBox1);

            var mrienhLabel1 = document.createElement('label');
            mrienhLabel1.setAttribute('for', 'No');
            mrienhLabel1.setAttribute('id', 'mrienhLabel1');
            mrienhLabel1.innerHTML = ": No";
            cell.appendChild(mrienhLabel1);

        } else if (select.value == 'MRInum') {
            var newMRInumBox = document.createElement('input');
            newMRInumBox.type = "number";
            newMRInumBox.setAttribute('name', 'newMRInum');
            newMRInumBox.setAttribute('placeholder', 'MRI Lesion No.');
            newMRInumBox.setAttribute('id', 'newMRInumBox');
            cell.appendChild(newMRInumBox);
        } else if (select.value == 'MRIonsetlocalisation') {
            var newMRIonsetBox = document.createElement('input');
            newMRIonsetBox.type = "checkbox";
            newMRIonsetBox.setAttribute('name', 'newMRIonsetlocalisation');
            newMRIonsetBox.setAttribute('value', 'Visual');
            cell.appendChild(newMRIonsetBox);

            var mrionsetLabel = document.createElement('label');
            mrionsetLabel.setAttribute('for', 'Visual');
            mrionsetLabel.setAttribute('id', 'mrionsetLabel');
            mrionsetLabel.innerHTML = ": Visual";
            cell.appendChild(mrionsetLabel);

            var newMRIonsetBox1 = document.createElement('input');
            newMRIonsetBox1.type = "checkbox";
            newMRIonsetBox1.setAttribute('name', 'newMRIonsetlocalisation');
            newMRIonsetBox1.setAttribute('value', 'Spinal');
            cell.appendChild(newMRIonsetBox1);

            var mrionsetLabel1 = document.createElement('label');
            mrionsetLabel1.setAttribute('for', 'Spinal');
            mrionsetLabel1.setAttribute('id', 'mrionsetLabel1');
            mrionsetLabel1.innerHTML = ": Spinal";
            cell.appendChild(mrionsetLabel1);

            var newMRIonsetBox4 = document.createElement('input');
            newMRIonsetBox4.type = "checkbox";
            newMRIonsetBox4.setAttribute('name', 'newMRIonsetlocalisation');
            newMRIonsetBox4.setAttribute('value', 'Cortex');
            cell.appendChild(newMRIonsetBox4);

            var mrionsetLabel4 = document.createElement('label');
            mrionsetLabel4.setAttribute('for', 'Cortex');
            mrionsetLabel4.setAttribute('id', 'mrionsetLabel4');
            mrionsetLabel4.innerHTML = ": Cortex";
            cell.appendChild(mrionsetLabel4);

            var newMRIonsetBox2 = document.createElement('input');
            newMRIonsetBox2.type = "checkbox";
            newMRIonsetBox2.setAttribute('name', 'newMRIonsetlocalisation');
            newMRIonsetBox2.setAttribute('value', 'Brainstem');
            cell.appendChild(newMRIonsetBox2);

            var mrionsetLabel2 = document.createElement('label');
            mrionsetLabel2.setAttribute('for', 'Brainstem');
            mrionsetLabel2.setAttribute('id', 'mrionsetLabel2');
            mrionsetLabel2.innerHTML = ": Brainstem";
            cell.appendChild(mrionsetLabel2);

            var newMRIonsetBox3 = document.createElement('input');
            newMRIonsetBox3.type = "checkbox";
            newMRIonsetBox3.setAttribute('name', 'newMRIonsetlocalisation');
            newMRIonsetBox3.setAttribute('value', 'Cerebellum');
            cell.appendChild(newMRIonsetBox3);

            var mrionsetLabel3 = document.createElement('label');
            mrionsetLabel3.setAttribute('for', 'Cerebellum');
            mrionsetLabel3.setAttribute('id', 'mrionsetLabel3');
            mrionsetLabel3.innerHTML = ": Cerebellum";
            cell.appendChild(mrionsetLabel3);
        } else {
            // newRacebox1.style.display = 'none';
            // newRacebox2.style.display = 'none';
            // newRacebox3.style.display = 'none';
            // newRacebox4.style.display = 'none';

            // newRacebox1.remove();
            // newRacebox2.remove();
            // newRacebox3.remove();
            // newRacebox4.remove();
            // cell.removeChild(newRaceBox);
            // cell.removeChild(newRaceBox1);
            // cell.removeChild(newRaceBox2);
            // cell.removeChild(newRaceBox3);
            // cell.removeChild(newRaceBox4);
        }

        // removes the elements that are not supposed to appear in the second row
        if (select.value !== 'Race') { //! this is buggy...
            document.getElementById('newRaceBox').style.display = 'none';
            document.getElementById('raceLabel').style.display = 'none';
            document.getElementById('newRaceBox1').style.display = 'none';
            document.getElementById('raceLabel1').style.display = 'none';
            document.getElementById('newRaceBox2').style.display = 'none';
            document.getElementById('raceLabel2').style.display = 'none';
            document.getElementById('newRaceBox3').style.display = 'none';
            document.getElementById('raceLabel3').style.display = 'none';
            document.getElementById('newRaceBox4').style.display = 'none';
            document.getElementById('raceLabel4').style.display = 'none';
            document.getElementById('newRaceBox5').style.display = 'none';
            document.getElementById('raceLabel5').style.display = 'none';
            console.log('Value !== Race'); //not neccesary
            // return;
        } else if (select.value !== 'Sex') {
            document.getElementById('newMaleRadio').style.display = 'none';
            document.getElementById('newFemaleRadio').style.display = 'none';
            document.getElementById('sexLabel').style.display = 'none';
            document.getElementById('sexLabel1').style.display = 'none';
        } else if (select.value !== 'Name') {
            var testing = document.getElementById('newNameBox');
            testing.setAttribute('hidden', 'true');
            console.log('name');
        } else if (select.value !== 'ID') {
            document.getElementById('newIdBox').style.display = 'none';
            console.log('id');
        } else if (select.value !== 'Email') {
            document.getElementById('newEmailBox').style.display = 'none';
            console.log('email');
        } else if (select.value !== 'Age') {
            document.getElementById('newAgeBox').style.display = 'none';
            console.log('age');
        } else if (select.value !== 'Phonenumber') {
            document.getElementById('newPhonenumBox').style.display = 'none';
            console.log('phonenum');
        } else if (select.value !== 'Agesmaller') {
            document.getElementById('newAgeSmallerBox').style.display = 'none';
            console.log('age<');
        } else if (select.value !== 'Comorbidities') {
            document.getElementsByName('newComorbidities').style.display = 'none';
            document.getElementById('comorLabel1').style.display = 'none';
            document.getElementById('comorLabel').style.display = 'none';
            document.getElementById('comorLabel2').style.display = 'none';
            document.getElementById('comorLabel3').style.display = 'none';
            document.getElementById('comorLabel4').style.display = 'none';
            document.getElementById('comorLabel5').style.display = 'none';
            console.log('Comorbidities');
        } else if (select.value !== 'EDSS') {
            document.getElementById('newBox').style.display = 'none';
        } else if (select.value !== 'Pregnant') {
            document.getElementById('pregnantLabel').style.display = 'none';
            document.getElementById('pregnantLabel1').style.display = 'none';
            document.getElementById('newPregnantBox').style.display = 'none';
            document.getElementById('newPregnantBox1').style.display = 'none';
        } else if (select.value !== 'Onsetlocalisation') {
            document.getElementById('newOnsetBox1').style.display = 'none';
            document.getElementById('newOnsetBox2').style.display = 'none';
            document.getElementById('newOnsetBox3').style.display = 'none';
            document.getElementById('newOnsetBox4').style.display = 'none';
            document.getElementById('onsetLabel1').style.display = 'none';
            document.getElementById('onsetLabel2').style.display = 'none';
            document.getElementById('onsetLabel3').style.display = 'none';
            document.getElementById('onsetLabel4').style.display = 'none';
        } else if (select.value !== 'Smoker') {
            smokerLabel.setAttribute('for', 'Yes');
            document.getElementById('newSmokerBox').style.display = 'none';
            document.getElementById('newSmokerBox1').style.display = 'none';
            document.getElementById('smokerLabel').style.display = 'none';
            document.getElementById('smokerLabel1').style.display = 'none';
        } else if (select.value !== 'onsetsymptoms') {
            document.getElementById('newOnsetsymptomsbox').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox1').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox2').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox3').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox4').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox5').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox6').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox7').style.display = 'none';
            document.getElementById('newOnsetsymptomsbox8').style.display = 'none';


            document.getElementById('onsympLabel').style.display = 'none';
            document.getElementById('onsympLabel1').style.display = 'none';
            document.getElementById('onsympLabel2').style.display = 'none';
            document.getElementById('onsympLabel3').style.display = 'none';
            document.getElementById('onsympLabel4').style.display = 'none';
            document.getElementById('onsympLabel5').style.display = 'none';
            document.getElementById('onsympLabel6').style.display = 'none';
            document.getElementById('onsympLabel7').style.display = 'none';
            document.getElementById('onsympLabel8').style.display = 'none';
        } else if (select.value !== 'MRIenhancing') {
            document.getElementById('newMRIenhBox').style.display = 'none';
            document.getElementById('newMRIenhBox1').style.display = 'none';

            document.getElementById('mrienhLabel').style.display = 'none';
            document.getElementById('mrienhLabel1').style.display = 'none';
        } else if (select.value !== 'MRInum') {
            document.getElementById('newMRInumBox').style.display = 'none';
        } else if (select.value !== 'MRIonsetlocalisation') {
            document.getElementsByName('newMRIonsetlocalisation').style.display = 'none';
            document.getElementsByName('mrionsetLabel').style.display = 'none';
            document.getElementsByName('mrionsetLabel1').style.display = 'none';
            document.getElementsByName('mrionsetLabel2').style.display = 'none';
            document.getElementsByName('mrionsetLabel3').style.display = 'none';
            document.getElementsByName('mrionsetLabel4').style.display = 'none';
        }

    });



    headCell.appendChild(select);




    //disable the element selected in the first attributes selection
    // ** works perfectly
    var op = document.getElementById("newSelect").getElementsByTagName("option");
    for (var i = 0; i < op.length; i++) {
        if (op[i].value == prevAttval) {
            op[i].selected = false;
            op[i].disabled = true;
        }
    }





}
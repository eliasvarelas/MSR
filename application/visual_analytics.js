// now it prints the standart chart based on the value of the html form... good progress

// document.getElementById('type_of_chart').onchange = function axisAppear() {
//   if (this.value == 'vert_bar' ||  this.value == 'line_chart') {
//     var y_axis_row = document.getElementById('y_axis_row').hidden = false;
//     var x_axis_row = document.getElementById('x_axis_row').hidden = false;
//     // var x_axis_row_on_ver_bar = document.getElementById('x_axis_row_on_ver_bar').hidden = false;
//     // var y_axis_row_on_ver_bar = document.getElementById('y_axis_row_on_ver_bar').hidden = false;
//     var attr_values_row = document.getElementById('attr_values_row').hidden = true;
//     var Num_of_years_on_y = document.getElementById('Num_of_years_on_y').hidden = false;
//     var Num_Years_header = document.getElementById('Num_Years_header').hidden = false;
//
//
//   } else if (this.value == 'hor_bar') {
//
//     var y_axis_row = document.getElementById('y_axis_row').hidden = false;
//     // var x_axis_row_on_ver_bar = document.getElementById('x_axis_row_on_ver_bar').hidden = true;
//     // var y_axis_row_on_ver_bar = document.getElementById('y_axis_row_on_ver_bar').hidden = true;
//     var x_axis_row = document.getElementById('x_axis_row').hidden = false;
//     var attr_values_row = document.getElementById('attr_values_row').hidden = true;
//     var Num_of_years_on_y = document.getElementById('Num_of_years_on_y').hidden = false;
//     var Num_Years_header = document.getElementById('Num_Years_header').hidden = false;
//
//     document.getElementById('y_axis_select').onchange = function getValue() { //gets value of the y axis inputs
//
//       if (y_axis_select.value == 'Num_Persons') {
//         var Num_Persons_header = document.getElementById('Num_Persons_header').hidden = false;
//         var Num_of_persons_on_y = document.getElementById('Num_of_persons_on_y').hidden = false;
//         var attributes_on_y = document.getElementById('Attributes_on_y').hidden = true;
//         var Attributes_header = document.getElementById('Attributes_header').hidden = true;
//         var medication_row = document.getElementById('medication_row').hidden = true;
//         var Medication_header = document.getElementById('Medication_header').hidden = true;
//         var Num_of_years_on_y = document.getElementById('Num_of_years_on_y').hidden = true;
//         var Num_Years_header = document.getElementById('Num_Years_header').hidden = true;
//
//       } else if (y_axis_select.value == 'Attributes') {
//         var Num_Persons_header = document.getElementById('Num_Persons_header').hidden = true;
//         var Num_of_persons_on_y = document.getElementById('Num_of_persons_on_y').hidden = true;
//         var Attributes_on_y = document.getElementById('Attributes_on_y').hidden = false;
//         var Attributes_header = document.getElementById('Attributes_header').hidden = false;
//         var medication_row = document.getElementById('medication_row').hidden = true;
//         var Medication_header = document.getElementById('Medication_header').hidden = true;
//         var Num_of_years_on_y = document.getElementById('Num_of_years_on_y').hidden = true;
//         var Num_Years_header = document.getElementById('Num_Years_header').hidden = true;
//
//       } else if (y_axis_select.value == 'Medication') {
//         var Num_Persons_header = document.getElementById('Num_Persons_header').hidden = true;
//         var Num_of_persons_on_y = document.getElementById('Num_of_persons_on_y').hidden = true;
//         var Attributes_on_y = document.getElementById('Attributes_on_y').hidden = true;
//         var Attributes_header = document.getElementById('Attributes_header').hidden = true;
//         var medication_row = document.getElementById('medication_row').hidden = false;
//         var Medication_header = document.getElementById('Medication_header').hidden = false;
//         var Num_of_years_on_y = document.getElementById('Num_of_years_on_y').hidden = true;
//         var Num_Years_header = document.getElementById('Num_Years_header').hidden = true;
//
//       } else if (y_axis_select.value == 'years') {
//         var Num_Persons_header = document.getElementById('Num_Persons_header').hidden = true;
//         var Num_of_persons_on_y = document.getElementById('Num_of_persons_on_y').hidden = true;
//         var Attributes_on_y = document.getElementById('Attributes_on_y').hidden = true;
//         var Attributes_header = document.getElementById('Attributes_header').hidden = true;
//         var medication_row = document.getElementById('medication_row').hidden = true;
//         var Medication_header = document.getElementById('Medication_header').hidden = true;
//         var Num_of_years_on_y = document.getElementById('Num_of_years_on_y').hidden = false;
//         var Num_Years_header = document.getElementById('Num_Years_header').hidden = false;
//       }
//     }
//
//   } else if (this.value == 'Pie_chart' || this.value == 'donut_chart') {
//     var yAxis = document.getElementById('y_axis_row').hidden = true;
//     var xAxis = document.getElementById('x_axis_row').hidden = true;
//     var pievalues = document.getElementById('attr_values_row').hidden = false;
//     var Num_Persons_header = document.getElementById('Num_Persons_header').hidden = true;
//     var Num_of_persons_on_y = document.getElementById('Num_of_persons_on_y').hidden = true;
//     var Attributes_on_y = document.getElementById('Attributes_on_y').hidden = true;
//     var Attributes_header = document.getElementById('Attributes_header').hidden = true;
//     var medication_row = document.getElementById('medication_row').hidden = true;
//     var Medication_header = document.getElementById('Medication_header').hidden = true;
//     var Num_of_years_on_y = document.getElementById('Num_of_years_on_y').hidden = true;
//     var Num_Years_header = document.getElementById('Num_Years_header').hidden = true;
//   }
// }

var option = document.getElementById('type_of_chart').onchange = function somefun() {
  var attribute = document.getElementById('')
    if (this.value == "vert_bar" ) {
        const data = [
            { name: 'John', score: 80 },
            { name: 'Simon', score: 76 },
            { name: 'Samantha', score: 100 },
            { name: 'Patrick', score: 82 },
            { name: 'Mary', score: 90 },
            { name: 'Christina', score: 75 },
            { name: 'Michael', score: 86 },
        ];
        console.log(option.value);

        const width = 900;
        const height = 450;
        const margin = { top: 50, bottom: 50, left: 50, right: 50 };

        const svg = d3.select('#d3-container')
            .append('svg')
            .attr('width', width - margin.left - margin.right)
            .attr('height', height - margin.top - margin.bottom)
            .attr("viewBox", [0, 0, width, height]);

        const x = d3.scaleBand()
            .domain(d3.range(data.length))
            .range([margin.left, width - margin.right])
            .padding(0.1)

        const y = d3.scaleLinear()
            .domain([0, 100])
            .range([height - margin.bottom, margin.top])

        svg
            .append("g")
            .attr("fill", 'royalblue')
            .selectAll("rect")
            .data(data.sort((a, b) => d3.descending(a.score, b.score)))
            .join("rect")
            .attr("x", (d, i) => x(i))
            .attr("y", d => y(d.score))
            .attr('title', (d) => d.score)
            .attr("class", "rect")
            .attr("height", d => y(0) - y(d.score))
            .attr("width", x.bandwidth());

        function yAxis(g) {
            g.attr("transform", `translate(${margin.left}, 0)`)
                .call(d3.axisLeft(y).ticks(null, data.format))
                .attr("font-size", '20px')
        }

        function xAxis(g) {
            g.attr("transform", `translate(0,${height - margin.bottom})`)
                .call(d3.axisBottom(x).tickFormat(i => data[i].name))
                .attr("font-size", '20px')
        }

        svg.append("g").call(xAxis);
        svg.append("g").call(yAxis);
        svg.node();


    } else {
        console.log('Hidden');
    }
}

// document.getElementById('form').addEventListener;

// var option = document.getElementById('type_of_chart').onchange = function names() {

//     // var attribute = document.getElementById('attributes').value;

//     if (option.value == "vert_bar") {
//         // const data = [
//         //     { name: 'John', Patient_id: 1 },
//         //     { name: 'Simon', Patient_id: 2 },
//         //     { name: 'Samantha', Patient_id: 3 },
//         //     { name: 'Patrick', Patient_id: 4 },
//         //     { name: 'Mary', Patient_id: 5 },
//         //     { name: 'Christina', Patient_id: 6 },
//         //     { name: 'Michael', Patient_id: 7 },
//         // ];
//         var data = d3.json("/empdata.json", function(data) {
//             console.log(data);

//         });

//         const width = 900;
//         const height = 450;
//         const margin = { top: 50, bottom: 50, left: 50, right: 50 };

//         const svg = d3.select('#d3-container')
//             .append('svg')
//             .attr('width', width - margin.left - margin.right)
//             .attr('height', height - margin.top - margin.bottom)
//             .attr("viewBox", [0, 0, width, height]);

//         const x = d3.scaleBand()
//             .domain(d3.range(data.length))
//             .range([margin.left, width - margin.right])
//             .padding(0.1)

//         const y = d3.scaleLinear()
//             .domain([0, 310])
//             .range([height - margin.bottom, margin.top])

//         // function setColor() {    //create a function that gets the colorInput and processes it as a graph
//         //     var fillColor = document.getElementById('colorInput');
//         //     fillColor.value = "#9933ff";
//         // }

//         svg
//             .append("g")
//             .attr("fill", 'blue')
//             .selectAll("rect")
//             .data(data.sort((a, b) => d3.descending(a.Patient_id, b.Patient_id)))
//             .join("rect")
//             .attr("x", (d, i) => x(i))
//             .attr("y", d => y(d.Patient_id))
//             .attr('title', (d) => d.Patient_id)
//             .attr("class", "rect")
//             .attr("height", d => y(0) - y(d.Patient_id))
//             .attr("width", x.bandwidth());

//         function yAxis(g) {
//             g.attr("transform", `translate(${margin.left}, 0)`)
//                 .call(d3.axisLeft(y).ticks(null, data.format))
//                 .attr("font-size", '20px')
//         }

//         function xAxis(g) {
//             g.attr("transform", `translate(0,${height - margin.bottom})`)
//                 .call(d3.axisBottom(x).tickFormat(i => data[i].Patient_name))
//                 .attr("font-size", '20px')
//         }

//         svg.append("g").call(xAxis);
//         svg.append("g").call(yAxis);
//         svg.node();


//         // }
//         // if (option.value = "Pie_chart") {
//         //     const dataPie = [{ name: "Alex", share: 20.70 },
//         //         { name: "Shelly", share: 30.92 },
//         //         { name: "Clark", share: 15.42 },
//         //         { name: "Matt", share: 13.65 },
//         //         { name: "Jolene", share: 19.31 }
//         //     ];

//         //     const width = 900;
//         //     const height = 450;
//         //     const margin = { top: 50, bottom: 50, left: 50, right: 50 };

//         //     const svg2 = d3.select('#d3-container')
//         //         .append('svg')
//         //         .attr('width', width - margin.left - margin.right)
//         //         .attr('height', height - margin.top - margin.bottom)
//         //         .attr("viewBox", [0, 0, width, height]);

//         //     var g = svg2.append("g")
//         //         .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

//         //     var ordScale = d3.scaleOrdinal()
//         //         .domain(dataPie)
//         //         .range(['#ffd384', '#94ebcd', '#fbaccc', '#d3e0ea', '#fa7f72']);

//         //     var pie = d3.pie().value(function(d) {
//         //         return d.share;
//         //     });

//         //     var arc = g.selectAll("arc")
//         //         .data(pie(dataPie))
//         //         .enter();

//         //     var path = d3.arc()
//         //         .outerRadius(radius)
//         //         .innerRadius(0);

//         //     arc.append("path")
//         //         .attr("d", path)
//         //         .attr("fill", function(d) { return ordScale(d.dataPie.name); });

//         //     var label = d3.arc()
//         //         .outerRadius(radius)
//         //         .innerRadius(0);

//         //     arc.append("text")
//         //         .attr("transform", function(d) {
//         //             return "translate(" + label.centroid(d) + ")";
//         //         })
//         //         .text(function(d) { return d.dataPie.name; })
//         //         .style("font-family", "arial")
//         //         .style("font-size", 15);

//     } else {
//         console.log('Hidden');

//     }
// }


// -------------------- Scripts from the testing Repo!!!! --------------------


var option = document.getElementById('type_of_chart').onchange = function somefun() {
    if (this.value == "vert_bar") {
        // const data = [
        //     { name: 'John', score: 80 },
        //     { name: 'Simon', score: 76 },
        //     { name: 'Samantha', score: 90 },
        //     { name: 'Patrick', score: 82 },
        //     { name: 'Mary', score: 90 },
        //     { name: 'Christina', score: 75 },
        //     { name: 'Michael', score: 86 },
        // ];

        var data = d3.json("/application/empdata.json", function(data) {
            console.log(data);
        });
        let sort = function(data) {
            console.log(`Passed in value is: ${data}`);
            player_data.then(function(data) {
                let new_data = data.slice().sort((a, b) => d3.descending(+a[data], +b[val]));
                console.log(new_data);
                return new_data;
            });
        };

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
            // .data(data.sort((a, b) => d3.descending(a.score, b.score)))
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


    }
    if (this.value == "Pie_chart") {
        // Step 3
        var svg = d3.select("svg"),
            width = svg.attr("width"),
            height = svg.attr("height"),
            radius = 200;

        // Step 1        
        var data = [{ name: "Alex", share: 20.70 },
            { name: "Shelly", share: 30.92 },
            { name: "Clark", share: 15.42 },
            { name: "Matt", share: 13.65 },
            { name: "Jolene", share: 19.31 }
        ];

        var g = svg.append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        // Step 4
        var ordScale = d3.scaleOrdinal()
            .domain(data)
            .range(['#ffd384', '#94ebcd', '#fbaccc', '#d3e0ea', '#fa7f72']);

        // Step 5
        var pie = d3.pie().value(function(d) {
            return d.share;
        });

        var arc = g.selectAll("arc")
            .data(pie(data))
            .enter();

        // Step 6
        var path = d3.arc()
            .outerRadius(radius)
            .innerRadius(0);

        arc.append("path")
            .attr("d", path)
            .attr("fill", function(d) { return ordScale(d.data.name); });

        // Step 7
        var label = d3.arc()
            .outerRadius(radius)
            .innerRadius(0);

        arc.append("text")
            .attr("transform", function(d) {
                return "translate(" + label.centroid(d) + ")";
            })
            .text(function(d) { return d.data.name; })
            .style("font-family", "arial")
            .style("font-size", 15);
    }
    if (this.value == "Restart") {
        var elem = document.getElementsByTagName("svg");
        elem.remove();

    }
    if (this.value == "line_chart") {
        var dataset1 = [
            [1, 1],
            [12, 20],
            [24, 36],
            [32, 50],
            [40, 70],
            [50, 100],
            [55, 106],
            [65, 123],
            [73, 130],
            [78, 134],
            [83, 136],
            [89, 138],
            [100, 140]
        ];

        // Step 3
        var svg = d3.select("#linechart"),
            margin = 200,
            width = svg.attr("width") - margin, //300
            height = svg.attr("height") - margin //200

        // Step 4 
        var xScale = d3.scaleLinear().domain([0, 100]).range([0, width]),
            yScale = d3.scaleLinear().domain([0, 200]).range([height, 0]);

        var g = svg.append("g")
            .attr("transform", "translate(" + 100 + "," + 100 + ")");

        // Step 5
        // Title
        svg.append('text')
            .attr('x', width / 2 + 100)
            .attr('y', 100)
            .attr('text-anchor', 'middle')
            .style('font-family', 'Helvetica')
            .style('font-size', 20)
            .text('Line Chart');

        // X label
        svg.append('text')
            .attr('x', width / 2 + 100)
            .attr('y', height - 15 + 150)
            .attr('text-anchor', 'middle')
            .style('font-family', 'Helvetica')
            .style('font-size', 12)
            .text('Independant');

        // Y label
        svg.append('text')
            .attr('text-anchor', 'middle')
            .attr('transform', 'translate(60,' + height + ')rotate(-90)')
            .style('font-family', 'Helvetica')
            .style('font-size', 12)
            .text('Dependant');

        // Step 6
        g.append("g")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(xScale));

        g.append("g")
            .call(d3.axisLeft(yScale));

        // Step 7
        svg.append('g')
            .selectAll("dot")
            .data(dataset1)
            .enter()
            .append("circle")
            .attr("cx", function(d) { return xScale(d[0]); })
            .attr("cy", function(d) { return yScale(d[1]); })
            .attr("r", 3)
            .attr("transform", "translate(" + 100 + "," + 100 + ")")
            .style("fill", "#CC0000");

        // Step 8        
        var line = d3.line()
            .x(function(d) { return xScale(d[0]); })
            .y(function(d) { return yScale(d[1]); })
            .curve(d3.curveMonotoneX)

        svg.append("path")
            .datum(dataset1)
            .attr("class", "line")
            .attr("transform", "translate(" + 100 + "," + 100 + ")")
            .attr("d", line)
            .style("fill", "none")
            .style("stroke", "#CC0000")
            .style("stroke-width", "2");

    }
    if (this.value == "donut_chart") {
        var pie = d3.pie()
            .sort(null)
            .value(d => d.population);

        var arc = d3.arc()
            .innerRadius(0)
            .outerRadius(Math.min(width, height) / 2 - 1);

        var arcLabel = function() {
            const radius = Math.min(width, height) / 2 * 0.8;
            return d3.arc().innerRadius(radius).outerRadius(radius);
        }

        d3.tsv("/data/population-data.tsv", function(error, data) {
            if (error) throw error;
            data.forEach(function(d) {
                console.log(d);
                //     d.date = parseDate(d.date);
            });
            var color = d3.scaleOrdinal()
                .domain(data.map(d => d.age))
                .range(d3.quantize(t => d3.interpolateSpectral(t * 0.8 + 0.1), data.length).reverse())
            const arcs = pie(data);
            svg.append("g")
                .attr("stroke", "white")
                .selectAll("path")
                .data(arcs)
                .enter().append("path")
                .attr("fill", d => color(d.data.age))
                .attr("d", arc)
                .append("title")
                .text(d => `${d.data.age}: ${d.data.population.toLocaleString()}`);

            svg.append("g")
                .attr("font-family", "sans-serif")
                .attr("font-size", 12)
                .attr("text-anchor", "middle")
                .selectAll("text")
                .data(arcs)
                .enter().append("text")
                .attr("transform", d => `translate(${arcLabel().centroid(d)})`)
                .call(text => text.append("tspan")
                    .attr("y", "-0.4em")
                    .attr("font-weight", "bold")
                    .text(d => d.data.age))
                .call(text => text.filter(d => (d.endAngle - d.startAngle) > 0.25).append("tspan")
                    .attr("x", 0)
                    .attr("y", "0.7em")
                    .attr("fill-opacity", 0.7)
                    .text(d => d.data.population.toLocaleString()));

        });
    } else {
        console.log('Hidden');

    }
}
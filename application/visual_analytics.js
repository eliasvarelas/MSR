// var option = document.getElementById('test').onclick = function somefun() {



function Graphs() {
    var type = document.getElementById('type_of_chart');
    if (type.value == "Pie_chart") {

        var width = 960,
            height = 500,
            radius = Math.min(width, height) / 2;

        console.log("pie1");

        var color = d3.scaleOrdinal()
            .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

        console.log("pie2");

        var arc = d3.arc()
            .outerRadius(radius - 10)
            .innerRadius(0);

        console.log("pie3");

        // defines wedge size
        var pie = d3.pie()
            .sort(null)
            .value(function(d) { return d.ratio; });

        console.log("varpie");


        var svg = d3.select("#d3-container").append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        console.log("varsvg - beforeJSON - pie");

        //* crashes here, "Uncaught (in promise) TypeError: NetworkError when attempting to fetch resource."
        d3.json("File.json", function(error, data) {
            node = data.data[0].ap[0];

            console.log(data);


            var g = svg.selectAll(".arc")
                .data(pie(node))
                .enter().append("g")
                .attr("class", "arc");

            g.append("path")
                .attr("d", arc)
                .style("fill", function(d) { return color(d.data.floor); });

            g.append("text")
                .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
                .attr("dy", ".35em")
                .style("text-anchor", "middle")
                .text(function(d) { return d.data.floor; });
        });
    } else if (type.value == 'Bar_chart') { // needs an edit but works fine
        var width = 960,
            height = 500,
            radius = Math.min(width, height) / 2;

        console.log("bar1");

        var color = d3.scaleOrdinal()
            .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

        console.log("bar2");

        var arc = d3.arc()
            .outerRadius(radius - 10)
            .innerRadius(0);

        console.log("bar3");

        // defines wedge size
        var pie = d3.pie()
            .sort(null)
            .value(function(d) { return d.ratio; });

        console.log("Bar");


        var svg = d3.select("#d3-container").append("svg")
            .attr("width", width)
            .attr("height", height)
            .append("g")
            .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

        console.log("varsvg - beforeJSON - bar");


        d3.json("File.json", function(error, data) {
            node = data.data[0].ap[0];

            console.log("inJSON - bar");


            var g = svg.selectAll(".arc")
                .data(pie(node))
                .enter().append("g")
                .attr("class", "arc");

            g.append("path")
                .attr("d", arc)
                .style("fill", function(d) { return color(d.data.floor); });

            g.append("text")
                .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
                .attr("dy", ".35em")
                .style("text-anchor", "middle")
                .text(function(d) { return d.data.floor; });
        });
    }
}

// }
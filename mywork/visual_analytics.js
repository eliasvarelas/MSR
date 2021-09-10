

      chart = {
        const svg = d3.create("svg")
            .attr("viewBox", [0, 0, width, height]);

        svg.append("g")
            .attr("fill", color)
          .selectAll("rect")
          .data(data)
          .join("rect")
            .attr("x", (d, i) => x(i))
            .attr("y", d => y(d.value))
            .attr("height", d => y(0) - y(d.value))
            .attr("width", x.bandwidth());

        svg.append("g")
            .call(xAxis);

        svg.append("g")
            .call(yAxis);

        return svg.node();
        }

        data = Array(26) [
          0: Object {name: "E", value: 0.12702}
          1: Object {name: "T", value: 0.09056}
          2: Object {name: "A", value: 0.08167}
          3: Object {name: "O", value: 0.07507}
          4: Object {name: "I", value: 0.06966}
          5: Object {name: "N", value: 0.06749}
          6: Object {name: "S", value: 0.06327}
          7: Object {name: "H", value: 0.06094}
          8: Object {name: "R", value: 0.05987}
          9: Object {name: "D", value: 0.04253}
          10: Object {name: "L", value: 0.04025}
          11: Object {name: "C", value: 0.02782}
          12: Object {name: "U", value: 0.02758}
          13: Object {name: "M", value: 0.02406}
          14: Object {name: "W", value: 0.0236}
          15: Object {name: "F", value: 0.02288}
          16: Object {name: "G", value: 0.02015}
          17: Object {name: "Y", value: 0.01974}
          18: Object {name: "P", value: 0.01929}
          19: Object {name: "B", value: 0.01492}
          20: Object {name: "V", value: 0.00978}
          21: Object {name: "K", value: 0.00772}
          22: Object {name: "J", value: 0.00153}
          23: Object {name: "X", value: 0.0015}
          24: Object {name: "Q", value: 0.00095}
          25: Object {name: "Z", value: 0.00074}
          format: "%"
          y: "↑ Frequency"
        ]

      data = Object.assign(d3.sort(await FileAttachment("alphabet.csv").csv({typed: true}), d => -d.frequency).map(({letter, frequency}) => ({name: letter, value: frequency})), {format: "%", y: "↑ Frequency"});

      x = ƒ(i);

      x = d3.scaleBand()
    .domain(d3.range(data.length))
    .range([margin.left, width - margin.right])
    .padding(0.1);

    y = d3.scaleLinear()
    .domain([0, d3.max(data, d => d.value)]).nice()
    .range([height - margin.bottom, margin.top]);

    xAxis = g => g
    .attr("transform", `translate(0,${height - margin.bottom})`)
    .call(d3.axisBottom(x).tickFormat(i => data[i].name).tickSizeOuter(0))


    yAxis = g => g
    .attr("transform", `translate(${margin.left},0)`)
    .call(d3.axisLeft(y).ticks(null, data.format))
    .call(g => g.select(".domain").remove())
    .call(g => g.append("text")
        .attr("x", -margin.left)
        .attr("y", 10)
        .attr("fill", "currentColor")
        .attr("text-anchor", "start")
        .text(data.y));


    color = "steelblue";

    height = 500;

    margin = ({top: 30, right: 0, bottom: 30, left: 0});

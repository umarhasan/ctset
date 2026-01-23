let amChartsInstances = {}; // keep track of charts separately

function drawPie(chartId, data) {
    // Dispose only this chart if exists
    if (amChartsInstances[chartId]) {
        amChartsInstances[chartId].dispose();
    }

    am4core.useTheme(am4themes_animated);
    let chart = am4core.create(chartId, am4charts.PieChart);
    chart.data = data;

    let series = chart.series.push(new am4charts.PieSeries());
    series.dataFields.value = "value";
    series.dataFields.category = "name";
    series.labels.template.fontSize = 12;
    series.ticks.template.disabled = true;

    chart.legend = new am4charts.Legend();
    amChartsInstances[chartId] = chart;
}

function drawBar(chartId, data) {
    if (amChartsInstances[chartId]) {
        amChartsInstances[chartId].dispose();
    }

    am4core.useTheme(am4themes_animated);
    let chart = am4core.create(chartId, am4charts.XYChart);
    chart.data = data;

    let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "name";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.labels.template.rotation = -45;

    let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    let series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "value";
    series.dataFields.categoryX = "name";
    series.name = "Value";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = 0.8;

    let columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 2;
    columnTemplate.strokeOpacity = 1;

    amChartsInstances[chartId] = chart;
}

function fillTop5(tableBodyId, data) {
    let html = '';
    data.forEach((d, i) => {
        html += `
            <tr>
                <td>${i + 1}</td>
                <td>${d.name}</td>
                <td>${d.type}</td>
                <td>${d.value}</td>
            </tr>
        `;
    });
    document.getElementById(tableBodyId).innerHTML = html;
}

function loadPerformanceData(url, period = 'all') {
    fetch(`${url}?period=${period}`)
        .then(res => res.json())
        .then(res => {
            if (!res.chart_data || !Array.isArray(res.chart_data)) {
                console.error('No chart data received');
                return;
            }
            // draw Pie and Bar
            drawPie('cicuPieChartDiv', res.chart_data);
            drawBar('cicuBarChartDiv', res.chart_data);
            fillTop5('top5Body', res.chart_data);
        })
        .catch(err => console.error(err));
}

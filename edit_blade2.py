import sys

with open('resources/views/index.blade.php', 'r') as f:
    content = f.read()

script_replacement = '''<script>
    const revenueCategories = @json($revenueSummary->pluck('label'));
    const revenueData = @json($revenueSummary->pluck('total'));
    
    const ordersCategories = @json($ordersOverview->pluck('label'));
    const ordersData = @json($ordersOverview->pluck('count'));

    const revenueWithOrderOptions = {
        series: [
            { name: "Revenue", data: revenueData }
        ],
        chart: { height: 300, type: "line", toolbar: { show: false }, zoom: { enabled: false }, parentHeightOffset: 0 },
        colors: ["#663ffa"],
        stroke: { curve: "smooth", width: [2] },
        markers: { size: 0 },
        grid: { borderColor: "#f1f3fa", yaxis: { lines: { show: true } }, xaxis: { lines: { show: false } } },
        xaxis: { 
            categories: revenueCategories, 
            axisBorder: { show: false }, 
            axisTicks: { show: false }, 
            labels: { style: { fontSize: "12px", colors: "#4d5761" } } 
        },
        yaxis: { 
            labels: { style: { fontSize: "12px", colors: "#4d5761" }, formatter: function(e) { return "Rs. " + e.toLocaleString(); } } 
        },
        legend: { position: "top", horizontalAlign: "right", fontSize: "12px", markers: { width: 10, height: 10, radius: 6 } },
        dataLabels: { enabled: false },
        tooltip: { shared: true, intersect: false, y: { formatter: function(e) { return "Rs. " + e.toLocaleString(); } } }
    };

    if(document.querySelector("#revenue_summary")) {
        const revenueWithOrderChart = new ApexCharts(document.querySelector("#revenue_summary"), revenueWithOrderOptions);
        revenueWithOrderChart.render();
    }

    const columnChartDatabaseOptions = {
        chart: { height: 250, type: "bar", toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 2, columnWidth: "30%", horizontal: true, dataLabels: { position: "top" } } },
        dataLabels: { enabled: true, formatter: function(e) { return e; }, offsetX: 25, style: { fontSize: "12px", colors: ["#304758"] } },
        colors: ["#5d7186"],
        legend: { show: true, horizontalAlign: "center", offsetX: 0, offsetY: -5 },
        series: [{ name: "Total Orders", data: ordersData }],
        xaxis: { 
            categories: ordersCategories, 
            position: "bottom", 
            labels: { offsetY: 0, style: { fontSize: "12px", colors: "#4d5761" } }, 
            axisBorder: { show: true }, 
            axisTicks: { show: true }, 
            tooltip: { enabled: true, offsetY: -10 } 
        },
        yaxis: { axisBorder: { show: true }, axisTicks: { show: true }, labels: { show: true, style: { fontSize: "12px", colors: "#4d5761" } } },
        grid: { row: { colors: ["transparent", "transparent"], opacity: 0.2 }, borderColor: "#f1f3fa" }
    };

    if(document.querySelector("#datalabels-column2")) {
        const columnChartDatabaseChart = new ApexCharts(document.querySelector("#datalabels-column2"), columnChartDatabaseOptions);
        columnChartDatabaseChart.render();
    }
    
    // For basic heatmap just render static as they are fast moving items mockup
    function generateData(count, yrange) {
        let i = 0;
        let series = [];
        while (i < count) {
            let x = (i + 1).toString();
            let y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
            series.push({ x: x, y: y });
            i++;
        }
        return series;
    }
    const basicHeatmapOptions = {
        chart: { toolbar: { show: false }, height: 250, type: "heatmap" },
        dataLabels: { enabled: false },
        colors: ["#53389f"],
        series: [
            { name: "Food", data: generateData(7, { min: 0, max: 90 }) },
            { name: "Beverage", data: generateData(7, { min: 0, max: 90 }) },
            { name: "Snack", data: generateData(7, { min: 0, max: 90 }) },
        ],
        xaxis: { type: "category" },
        yaxis: { labels: { style: { fontSize: "11px" } } }
    };
    if(document.querySelector("#basic-heatmap")) {
        const basicHeatmapChart = new ApexCharts(document.querySelector("#basic-heatmap"), basicHeatmapOptions);
        basicHeatmapChart.render();
    }
</script>'''

content = content.replace('<script src="{{ asset(\'assets/js/pages/dashboard.js\') }}"></script>', script_replacement)

with open('resources/views/index.blade.php', 'w') as f:
    f.write(content)
print('JS updated.')

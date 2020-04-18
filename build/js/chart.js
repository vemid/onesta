/**
 *
 */
function renderDoughnut(dataElementName, legendElementName, url) {

    var dataElement = document.getElementById(dataElementName);
    var legendElement = $("#" + legendElementName);

    $.ajax({
        url: url,
        dataType: "json",
        method: "get",
        success: function (doughnutData) {

            var doughnutOptions = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 2,
                percentageInnerCutout: 45, // This is 0 for Pie charts
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false,
                responsive: true
            };

            var pie_ctx = dataElement.getContext("2d");
            window.myPie = new Chart(pie_ctx).Doughnut(doughnutData, doughnutOptions);
            legendElement.html(myPie.generateLegend());
        }
    });
}

/**
 *
 * @param {string} dataElementName
 * @param {string} legendElementName
 * @param {string} url
 */
function renderBar(dataElementName, legendElementName, url) {

    var dataElement = document.getElementById(dataElementName);
    var legendElement = $("#" + legendElementName);

    $.ajax({
        url: url,
        dataType: "json",
        method: "get",
        success: function (barData) {

            barOptions = {
                scaleBeginAtZero: true,
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                barShowStroke: true,
                barStrokeWidth: 2,
                barValueSpacing: 5,
                barDatasetSpacing: 1,
                responsive: true,
                multiTooltipTemplate: "<%if (label){%><%}%><%= value %>"
            };

            var ctx = dataElement.getContext("2d");
            window.myLine = new Chart(ctx).Bar(barData, barOptions);
            legendElement.html(myLine.generateLegend());
        }
    });
}

/**
 * 
 * @param {string} dataElementName
 * @param {string} legendElementName
 * @param {string} url
 */
function renderLine(dataElementName, legendElementName, url) {

    var dataElement = document.getElementById(dataElementName);
    var legendElement = $("#" + legendElementName);

    $.ajax({
        url: url,
        dataType: "json",
        method: "get",
        success: function (lineData) {

            var lineOptions = {
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                bezierCurve: true,
                bezierCurveTension: 0,
                pointDot: true,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: false,
                responsive: true,
            };

            var ctx = dataElement.getContext("2d");
            window.myLine = new Chart(ctx).Line(lineData, lineOptions);
            legendElement.html(myLine.generateLegend());
        }
    });

}

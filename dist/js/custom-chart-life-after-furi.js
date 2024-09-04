/******/ (() => { // webpackBootstrap
/*!*****************************************************!*\
  !*** ./src/scripts/custom-chart-life-after-furi.js ***!
  \*****************************************************/
// Outputs pie chart for Life After FURI block.
// See templates/blocks/about-life-after-furi.php
// Block markup produces var segementsArray, used twice in the initialization script below.

google.charts.load('current', {
  packages: ['corechart']
});
google.charts.setOnLoadCallback(mmmDonut);
function mmmDonut() {
  var data = google.visualization.arrayToDataTable(segmentsArray.segments);
  var options = {
    pieHole: 0.6,
    pieStartAngle: 160,
    colors: segmentsArray.colors,
    chartArea: {
      width: '100%',
      height: '100%'
    },
    legend: {
      position: 'right',
      alignment: 'center',
      textStyle: {
        color: '#222222',
        fontSize: 24,
        fontWeight: 700
      }
    }
  };
  var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
  chart.draw(data, options);
}
/******/ })()
;
//# sourceMappingURL=custom-chart-life-after-furi.js.map
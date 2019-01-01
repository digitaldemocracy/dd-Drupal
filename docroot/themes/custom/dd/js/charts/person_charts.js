/* eslint-disable strict */
setupGoogleCharts();
var google;

function cc_toggle_visibility(id) {
  var e = document.getElementById(id);
  if (e.style.display === 'block') {
    e.style.display = 'none';
  }
  else {
    drawGoogleCharts();
    e.style.display = 'block';
  }
}

function newGoogleTable(str) {
  var table = new google.visualization.DataTable();
  table.addColumn('string');
  table.addColumn('number', 'Average Percent Participation all ' + str);
  table.addColumn({type: 'string', role: 'annotation'});
  table.addColumn({type: 'string', role: 'tooltip'});

  return table;
}

function setupGoogleCharts() {
  google.load('visualization', '1', {
    callback: drawGoogleCharts,
    packages: ['corechart', 'bar']
  });
}

function drawGoogleCharts() {
  var local = JSON.parse(jQuery('#personParticipationData').html().replace(';', ''));
  var part = local['participation'];

  var partTableS = newGoogleTable('hearings');
  if (part) {
    var avgHearingPrc = Math.round(parseFloat(part['LegAvgPercentParticipation']) * 10000.0) / 100.0;
    partTableS.addRow([part['first'].substr(0, 1) + '. ' + part['last'], avgHearingPrc, avgHearingPrc + '%', avgHearingPrc + '%']);
  }

  var smallChartOptions = {
    bars: 'horizontal',
    vAxis: {format: 'percent'},
    width: 256,
    height: partTableS.getNumberOfRows() * 40 + 40,
    fontSize: 12,
    legend: {position: 'none'},
    tooltip: {isHtml: true},
    explorer: {actions: ['dragToZoom', 'rightClickToReset'], maxZoomIn: '0.05'},
    crosshair: {trigger: 'selection'},
    colors: ['#1b9e77', '#d95f02'],
    backgroundColor: {
      fill: '#EEE'
    },
    chartArea: {top: '5%', left: 65, width: '55%', height: '90%'},
    annotations: {
      alwaysOutside: true,
      textStyle: {
        fontSize: 12,
        auraColor: 'none'
      }
    }
  };

  var partSmallChart;

  partSmallChart = new google.visualization.BarChart(document.getElementById('partSmall'));
  partSmallChart.draw(partTableS, smallChartOptions);
}

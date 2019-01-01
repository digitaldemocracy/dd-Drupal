function get_align_data(alignment_percentage) {
  //console.log(alignment_percentage);

  return google.visualization.arrayToDataTable([
    ['Label', 'Value'],
    ['Alignment', parseFloat(alignment_percentage) * 100]
  ]);
}

function drawAlignmentChart() {
  var charts = document.getElementsByClassName('chart-div');
  for (i = 0; i < charts.length; i++) {
    var chart = new google.visualization.Gauge(charts[i]);
    var data = get_align_data(charts[i].dataset.alignment);

    var options = {
      width: 120, height: 120,
      redFrom: 0, redTo: 25,
      greenFrom: 75, greenTo: 100,
      yellowFrom: 25, yellowTo: 75,
      minorTicks: 5
    };

    var formatter = new google.visualization.NumberFormat(
      {suffix: '%', pattern: '#'}
    );

    formatter.format(data, 1);

    chart.draw(data, options);
  }
}

google.charts.load('current', {'packages':['gauge']});

jQuery( document ).ready(function() {
  google.charts.setOnLoadCallback(drawAlignmentChart);
});


/* eslint-disable strict */
var google;
setupGoogleCharts();

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
  table.addColumn('number', 'Percent Participation this ' + str);
  table.addColumn({type: 'string', role: 'annotation'});
  table.addColumn('number', 'Average Percent Participation all ' + str);
  table.addColumn({type: 'string', role: 'annotation'});

  return table;
}

function setupGoogleCharts() {
  google.load('visualization', '1', {callback: drawGoogleCharts, packages: ['corechart', 'bar']});
}

function drawGoogleCharts() {
  var partTableS = newGoogleTable('Hearing');
  var partTableL = newGoogleTable('Hearing');
  var billTablesS = [];
  var billTablesL = [];
  var bills = [];

  var hid = document.URL.split('/hearing/')[1];
  if (isNaN(hid)) {
    hid = hid.split('?')[0];
  }

  // Get Participation Overview.
  // Perform these as blocking calls for now.
  jQuery.ajax({
    dataType: 'json',
    async: false,
    url: '/hearing-participation-overview/json?hid=' + hid,
    success: function (partOverview) {
      jQuery.each(partOverview, function (key, part) {
        var hearingPrc = Math.round(part.LegHearingPercentWord * 10000.0) / 100.0;
        var avgHearingPrc = Math.round(part.LegAvgPercentParticipation * 10000.0) / 100.0;
        partTableS.addRow([part.name_last_first, hearingPrc, hearingPrc + '%', avgHearingPrc, avgHearingPrc + '%']);
        partTableL.addRow([part.name_last_first, hearingPrc, hearingPrc + '%', avgHearingPrc, avgHearingPrc + '%']);
      });
    }
  });
  // Build Bill Charts

  // Get list of bills for hearing.
  jQuery.ajax({
    dataType: 'json',
    async: false,
    url: '/hearing-bills-list/json/' + hid,
    success: function (bills_data) {
      var bills = bills_data;

      jQuery.each(bills, function (cur_bill_index, bill) {
        // Get bill data for bid
        jQuery.ajax({
          dataType: 'json',
          async: false,
          url: '/hearing-participation-bills/json/' + hid + '/' + bill.bid,
          success: function (billStats) {
            var cur_bill_stat_index = 0;
            jQuery.each(billStats, function (key, billStat) {
              if (cur_bill_stat_index === 0) {
                // Setup table on first iteration.
                billTablesS[cur_bill_index] = newGoogleTable('Bill');
                billTablesL[cur_bill_index] = newGoogleTable('Bill');
              }
              var billPrc = Math.round(10000.0 * billStat.LegBillPercentWord) / 100.0;
              billTablesS[cur_bill_index].addRow([billStat.first.substr(0, 1) + ' ' + billStat.last, billPrc, billPrc + '%', 0, '']);
              billTablesL[cur_bill_index].addRow([billStat.name_first_last, billPrc, billPrc + '%', 0, '']);
              cur_bill_stat_index++;
            });
          }
        });
      });
    }
  });

  var smallChartOptions = {
    bars: 'horizontal',
    vAxis: {format: 'percent'},
    width: 225,
    height: partTableS.getNumberOfRows() * 40 + 40,
    fontSize: 12,
    legend: {position: 'none'},
    tooltip: {showColorCode: true, trigger: 'both'},
    explorer: {actions: ['dragToZoom', 'rightClickToReset'], maxZoomIn: '0.05'},
    crosshair: {trigger: 'selection'},
    colors: ['#1b9e77', '#d95f02'],
    backgroundColor: {
      fill: '#EEE'
    },
    chartArea: {top: '5%', left: 75, width: '45%', height: '90%'},
    annotations: {
      alwaysOutside: true,
      textStyle: {
        fontSize: 12,
        auraColor: 'none'
      }
    }
  };

  var largeChartOptions = {
    title: 'Participation compared to other Speakers',
    vAxis: {format: 'percent'},
    bars: 'horizontal',
    width: 620,
    height: partTableL.getNumberOfRows() * 70 + 50,
    fontSize: 16,
    legend: {position: 'none'},
    colors: ['#1b9e77', '#d95f02'],
    backgroundColor: {
      fill: '#EEE'
    },
    chartArea: {left: 200, width: '60%', height: '90%'},
    annotations: {
      alwaysOutside: true,
      textStyle: {
        fontSize: 16,
        auraColor: 'none'
      }
    }

  };

  var partSmallChart;
  var partLargeChart;

  partSmallChart = new google.visualization.BarChart(document.getElementById('partSmall'));
  partSmallChart.draw(partTableS, smallChartOptions);

  partLargeChart = new google.visualization.BarChart(document.getElementById('partLarge'));
  partLargeChart.draw(partTableL, largeChartOptions);

  for (var i = 0; i < bills.length; i++) {
    var smallBill = new google.visualization.BarChart(document.getElementById('billSmall-' + bills[i].bid));
    smallChartOptions.height = billTablesS[i].getNumberOfRows() * 40 + 40;
    smallBill.draw(billTablesS[i], smallChartOptions);

    var largeBill = new google.visualization.BarChart(document.getElementById('billLarge-' + bills[i].bid));
    largeChartOptions.height = billTablesL[i].getNumberOfRows() * 70 + 50;
    largeBill.draw(billTablesL[i], largeChartOptions);
  }
}


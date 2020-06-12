google.load("visualization", "1.1", {
  packages: ["bar", "corechart", "line"]
});

var charttype = 'pie';
var threed = true;
var stacking = false;
var chartdata = function($t) {
  var data = $t[0].config.chart.data;
  
  if (typeof chartdata_processor === 'function') {
    data = chartdata_processor(data);
  }
  
  return data;
};

var chartdata_processor = function(data) {
        console.log(data);

        return data;
};

function drawChart() {
  var obj = $('#chart')[0];
  var rawdata = chartdata($('#table'));
  if (rawdata.length < 2) {
    return;
  }
  var data = google.visualization.arrayToDataTable(rawdata);
  var thischart = charttype;
  var thisthreed = threed;
  var thisstacking = stacking;

  var numofcols = rawdata[1].length;
  if ((numofcols > 2 && (charttype == 'pie' || charttype == 'pie3d')) || (numofcols > 5 && (charttype == 'bar' || charttype == 'column'))) {
    thischart = 'line';
    thisthreed = false;
    thisstacking = false;
  }

  var options = {
    title: 'Company Performance',
    chart: {
      title: 'Company Performance'
    },
    hAxis: {
      title: 'Year',
      titleTextStyle: {
        color: '#333'
      }
    },
    vAxis: {},
    is3D: thisthreed,
    isStacked: thisstacking,
    pieSliceText: 'percentage',
    width: 900,
    height: 500
  };

  var chart;
  if (thischart == 'column') {
    chart = new google.charts.Bar(obj);
  } else if (thischart == 'bar') {
    options.hAxis = {};
    options.vAxis = {
      title: 'Year',
      titleTextStyle: {
        color: 'red'
      },
      minValue: 0
    };
    chart = new google.visualization.BarChart(obj);
  } else if (thischart == 'area') {
    chart = new google.visualization.AreaChart(obj);
  } else if (thischart == 'line') {
    chart = new google.charts.Line(obj);
  } else if (thischart == 'columnChart') {
    options.isStacked = true;
    chart = new google.visualization.ColumnChart(obj);
  } else {
    chart = new google.visualization.PieChart(obj);
  }

  chart.draw(data, options);
}

$(document).ready(function () {
  $('#chartSelect').change(function() {
    if ($(this).is(':checked')) {
      $('#chart-container').slideDown();
    } else {
      $('#chart-container').slideUp();
    }
  });
  
  $('#chart-container i').click(function(e) {
    if ($(e.target).hasClass('disabled')) {
      return true;
    }
    
    $('#chart-container i').removeClass('active');
    $(this).addClass('active');

    if ($(e.target).hasClass('fa-cube')) {
      stacking = false;
      threed = true;
      charttype = 'pie';
    }

    if ($(e.target).hasClass('fa-pie-chart')) {
      stacking = false;
      threed = false;
      charttype = 'pie';
    }

    if ($(e.target).hasClass('fa-line-chart')) {
      stacking = false;
      threed = false;
      charttype = 'line';
    }

    if ($(e.target).hasClass('fa-area-chart')) {
      stacking = false;
      threed = false;
      charttype = 'area';
    }

    if ($(e.target).hasClass('fa-bar-chart')) {
      stacking = false;
      threed = false;
      charttype = 'column';
    }

    if ($(e.target).hasClass('fa-tasks fa-rotate-90')) {
      charttype = 'columnChart';
      stacking = true;
      threed = false;
    }

    if ($(e.target).hasClass('fa-tasks fa-rotate-180')) {
      stacking = true;
      threed = false;
      charttype = 'bar';
    }
    
    if ($(e.target).hasClass('fa-align-left')) {
      stacking = false;
      threed = false;
      charttype = 'bar';
    }
    
    drawChart();
  });

  $('#table').on('filterEnd sortEnd columnUpdate pagerComplete tablesorter-initialized', function(e) {
    var t = this;
    setTimeout(function() {
      if (t.hasInitialized) {
        $(t).trigger('chartData');
        drawChart();

        if (typeof t.config.chart !== 'undefined') {
          var cols =  t.config.chart.data[0].length;

          if (cols > 2) {
            $('#chartbar').find('.fa-cube, .fa-pie-chart').addClass('disabled');
            if ($('#chartbar').find('.fa-cube, .fa-pie-chart').hasClass('active')) {
              $('#chartbar').find('.fa-cube, .fa-pie-chart').removeClass('active');
              $('#chartbar').find('.fa-line-chart').addClass('active');
            }
          } else {
            $('#chartbar').find('.fa-cube, .fa-pie-chart').removeClass('disabled');
            if (charttype == 'pie') {
              $('#chartbar').find('.active').removeClass('active');
              if (threed === true) {
                $('#chartbar').find('.fa-cube').addClass('active');
              } else {
                $('#chartbar').find('.fa-pie-chart').addClass('active');
              }
            }
          }
        }
      }
    }, 10);
  });

  $("#table")
  .tablesorter({
    debug: false,
    theme: 'blue',
    sortList: [
      [0, 0]
    ],
    widgets: ['pager', 'zebra', 'filter', 'cssStickyHeaders', 'columnSelector', 'chart'],
    widgetOptions: {
      columnSelector_container: '#columnSelector',
      // jQuery selector string of an element used to reset the filters
      filter_reset : 'button.reset',

      // Reset filter input when the user presses escape - normalized across browsers
      filter_resetOnEsc : true,
      cssStickyHeaders_filteredToTop: false,
      pager_selectors: {
        container: '#pager'
      },
      pager_output: 'Showing {startRow} to {endRow} of {filteredRows} results',
      pager_size: 20,
      chart_incRows: 'visible',
      chart_useSelector: true
    }
  });
});
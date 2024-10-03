<?php
$stsum = json_decode($index_dataset);
$newlen = array_sum($stsum);

if ($newlen == 0) {
?>
    <style>
        .img-style {
            height: 60%;
            width: 50%;
            text-align: center;
            margin: auto;
            margin-left: 23%;
            margin-top: 2%;
        }

        .head-text {
            font-family: "Catamaran";
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
            color: #2A2E36;
            text-transform: inherit;
            margin-bottom: 8px;
            text-align: center;
        }

        .sub-head {
            font-family: "Catamaran";
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
            color: #2A2E36;
            text-transform: inherit;
            text-align: center;
        }
    </style>
    <div id='container'>
        <div id='performance_comparison_by_division' style='min-width: 100%; height: 300px; background:white;'>
            <img src="<?= base_url(); ?>assets/images/empty.jpeg" class="img-style" />
            <br>
            <div class="head-text">No data found for selected filters</div>
            <div class="sub-head">Please retry with some other filters</div>
        </div>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>
    <div id='performance_comparison_by_division' style='min-width: 100%; height: 300px;'></div>
    <div id='performance_comparison_by_division'></div>
    <div class="clearfix"></div>
    </div>
    <script>
        var indexLabel = <?php echo $index_label; ?>;
        var indexData = <?php echo $index_dataset; ?>;
        var dcount = <?php echo $dcount; ?>;
        $(document).ready(function() {
            Highcharts.SVGRenderer.prototype.symbols.download = function(x, y, w, h) {
                var path = [
                    // Arrow stem
                    'M', x + w * 0.5, y,
                    'L', x + w * 0.5, y + h * 0.7,
                    // Arrow head
                    'M', x + w * 0.3, y + h * 0.5,
                    'L', x + w * 0.5, y + h * 0.7,
                    'L', x + w * 0.7, y + h * 0.5,
                    // Box
                    'M', x, y + h * 0.9,
                    'L', x, y + h,
                    'L', x + w, y + h,
                    'L', x + w, y + h * 0.9
                ];
                return path;
            };
            (function(H) {

                //internal functions
                function stopEvent(e) {
                    if (e) {
                        if (e.preventDefault) {
                            e.preventDefault();
                        }
                        if (e.stopPropagation) {
                            e.stopPropagation();
                        }
                        e.cancelBubble = true;
                    }
                }

                //the wrap
                H.wrap(H.Chart.prototype, 'render', function(proceed) {
                    var chart = this,
                        mapNavigation = chart.options.mapNavigation;

                    proceed.call(chart);

                    // Add the mousewheel event
                    H.addEvent(chart.container, document.onmousewheel === undefined ? 'DOMMouseScroll' : 'mousewheel', function(event) {

                        var delta, extr, step, newMin, newMax, axis = chart.xAxis[0];

                        e = chart.pointer.normalize(event);
                        // Firefox uses e.detail, WebKit and IE uses wheelDelta
                        delta = e.detail || -(e.wheelDelta / 50);
                        delta = delta < 0 ? -1 : 1;

                        if (chart.isInsidePlot(e.chartX - chart.plotLeft, e.chartY - chart.plotTop)) {
                            extr = axis.getExtremes();
                            step = (extr.max - extr.min) / 5 * delta;
                            axis.setExtremes(extr.min + step, extr.max + step, true, false);
                        }

                        stopEvent(event); // Issue #5011, returning false from non-jQuery event does not prevent default
                        return false;
                    });
                });
            }(Highcharts));
            Highcharts.chart('performance_comparison_by_division', {
                chart: {
                    type: 'bar',
                    marginTop: 50,
                    height: 300,
                    spacingBottom: 25,
                    events: {
                        load: function() {
                            this.renderer.image('<?= base_url(); ?>assets/images/poweredby-awarathon-logo.png', this.chartWidth / 2 - 24, this.chartHeight - 16, 80, 10).add();
                        }
                    }

                },
                title: {

                    text: 'Competency by divisions',
                    align: 'left',
                    verticalAlign: 'top',
                    y: 15,
                    'style': {
                        'fontSize': '12px',
                        'fontFamily': 'Catamaran',
                        'display': 'none',

                    }
                },
                subtitle: {
                    text: '',
                    align: 'left',
                    verticalAlign: 'top',
                    y: 10,
                    'style': {
                        'fontSize': '12px',
                        'fontFamily': 'Catamaran',
                    },
                },
                xAxis: {
                    categories: <?php echo $index_label; ?>,
                    title: {
                        text: 'Divisions'
                    },
                    min: 0,
                    max: (dcount > '5' ? '4' : dcount - 1),
                    scrollbar: {
                        enabled: (dcount > '5' ? true : false)
                    },
                    labels: {
                        formatter: function() {
                            if (typeof this.value !== 'number') {
                                return this.value;
                            }
                        }
                    },
                    events: {
                        afterSetExtremes: function() {
                            var xAxis = this,
                                numberOfPoints = xAxis.series[0].points.length - 1,
                                minRangeValue = xAxis.getExtremes().min,
                                maxRangeValue = xAxis.getExtremes().max;

                            if (minRangeValue < 0) {
                                xAxis.setExtremes(null, xAxis.options.max);
                            } else if (maxRangeValue > numberOfPoints) {
                                xAxis.setExtremes(numberOfPoints - xAxis.options.max, numberOfPoints);
                            }
                        }
                    }
                },
                yAxis: [{
                    
                    title: {
                        text: <?php echo $report_title; ?>,
                        align: 'high',
                        y: 10,
                        'style': {
                            'fontSize': '12px',
                            'fontFamily': 'Catamaran',
                        },
                    },
                    labels: {
                        formatter: function() {
                            return this.value;
                        },
                        overflow: 'justify'
                    }
                }, {
                    linkedTo: 0,
                    title: {
                        text: '% Scores',
                        align: 'middle',
                        y: 10,

                    },
                    offset: 0
                }],
                tooltip: {
                    valueSuffix: ''
                },
                legend: {
                    enabled: false,
                },
                // credits: {
                //   text: 'Powered by Awarathon',
                //   href: '',
                //   }
                credits: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        pointWidth: 15 //width of the column bars irrespective of the chart size
                    }
                },
                series: [{
                    type: 'bar',
                    name: 'Avg',
                    data: <?php echo isset($index_dataset) ? $index_dataset : 0; ?>,
                    color: '#dbc3c3',
                }],
                exporting: {
                    chartOptions: {
                        plotOptions: {
                            series: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        },
                        subtitle: {
                            text: 'Competency by divisions',
                            align: 'left',
                            verticalAlign: 'top',
                            y: 10,
                            'style': {
                                'fontSize': '12px',
                                'fontFamily': 'Catamaran',
                                'color': 'black',
                            },
                        },
                        yAxis: [{
                            title: {
                                text: <?php echo $report_title; ?>,
                                align: 'high',
                                y: 10,
                                'style': {
                                    'fontSize': '12px',
                                    'fontFamily': 'Catamaran',
                                },
                            }
                        }, {
                            linkedTo: 0,
                            title: {
                                text: '% Scores',
                                align: 'middle',

                            },
                            offset: 0
                        }],

                    },

                    csv: {
                        columnHeaderFormatter: function(item, key) {
                            if (!key) {
                                return 'Division Name'
                            }
                            return false
                        }

                    },
                    filename: 'Competency by divisions ' + <?php echo $report_title ?> + '',

                    buttons: {
                        contextButton: {

                            symbol: 'download',
                            'stroke-width': 1,
                            symbolStroke: "#004369",
                            menuItems: ['printChart', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadCSV', 'downloadXLS']
                        }
                    },
                    enableImages: true

                }
            });

        });
    </script>
<?php } ?>
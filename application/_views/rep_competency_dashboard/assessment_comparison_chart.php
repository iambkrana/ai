<!-- Assessment comparision chart started by Patel Rudra -->
<?php
$ass = json_decode($ass_names);
$trainer_name = json_decode($trainee_name);
$stsum1 = json_decode($less_than_range);
$stsum2 = json_decode($second_range);
$stsum3 = json_decode($third_range);
$stsum4 = json_decode($forth_range);
$stsum5 = json_decode($fifth_range);
$stsum6 = json_decode($above_range_final);
$newlen1 = array_sum($stsum1);
$newlen2 = array_sum($stsum2);
$newlen3 = array_sum($stsum3);
$newlen4 = array_sum($stsum4);
$newlen5 = array_sum($stsum5);
$newlen6 = array_sum($stsum6);
$Company_id = json_decode($Company_id);
$e_id = json_decode($e_id);

if ($newlen1 == 0 && $newlen2 == 0 && $newlen3 == 0 && $newlen4 == 0 && $newlen5 == 0 && $newlen6 == 0) {
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
            font-family: 'Catamaran';
            font-size: 16px;
            font-weight: 600;
            line-height: 24px;
            color: #2A2E36;
            text-transform: inherit;
            margin-bottom: 8px;
            text-align: center;
        }

        .sub-head {
            font-family: 'Catamaran';
            font-size: 12px;
            font-weight: 400;
            line-height: 16px;
            color: #2A2E36;
            text-transform: inherit;
            text-align: center;
        }
    </style>
    <div id='container'>
        <div id='assessment_comparison' style='min-width: 100%; height: 300px; background:white;'>
            <img src="<?= base_url(); ?>/assets/images/empty.jpeg" class="img-style" />
            <br>
            <div class="head-text">No data found for selected filters</div>
            <div class="sub-head">Please retry with some other filters</div>
        </div>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>
    <?php
    $base_url = base_url();
    ?> <div id='assessment_comparison' style='border:1px solid #d4d4d4;padding: 5px; height: 300px;'>
    </div>
    <div id='assessment_comparison' style='min-width: 100%; height: 300px;'></div>
    <div id='assessment_comparison'></div>
    <div class="clearfix"></div>
    </div>
    <script>
        var trainee_name = '<?php echo str_replace('\\"', '', $trainer_name); ?>';
        var Ncount = <?php echo $Ncount; ?>;
        var Company_id = <?php echo $Company_id; ?>;
        var e_id = <?php echo $e_id; ?>;
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

            Highcharts.chart('assessment_comparison', {

                chart: {
                    type: 'column',
                    height: 300,
                    marginTop: 50,
                    spacingBottom: 25,
                    // borderWidth: 1,
                    events: {
                        load: function() {
                            this.renderer.image('https://ai.awarathon.com/assets/images/poweredby-awarathon-logo.png', this.chartWidth / 2 - 24, this.chartHeight - 12, 80, 10)
                                .add();
                        }
                    }
                },
                title: {
                    text: trainee_name,
                    align: 'left',
                    verticalAlign: 'top',
                    y: 10,
                    'style': {
                        'fontSize': '12px',
                        'fontFamily': 'Catamaran',

                    },
                    enabled: false
                },
                xAxis: {
                    allowDecimals: false,
                    categories: <?php echo $assessment_names; ?>,
                    title: {
                        text: 'Assessment names'
                    },
                    // overflow: 'justify',
                    // min: 0,
                    // max: (Ncount > '5' ? '4' : Ncount - 1),
                    // scrollbar: {
                    //     enabled: (Ncount > '5' ? true : false)
                    // },
                    // events: {
                    //     afterSetExtremes: function() {
                    //         var xAxis = this,
                    //             numberOfPoints = xAxis.series[0].points.length - 1,
                    //             minRangeValue = xAxis.getExtremes().min,
                    //             maxRangeValue = xAxis.getExtremes().max;

                    //         if (minRangeValue < 0) {
                    //             xAxis.setExtremes(null, xAxis.options.max);
                    //         } else if (maxRangeValue > numberOfPoints) {
                    //             xAxis.setExtremes(numberOfPoints - xAxis.options.max, numberOfPoints);
                    //         }
                    //     }
                    // }
                },

                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false,
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Assessment score'
                    },
                    stackLabels: {
                        enabled: true
                    }
                },
                // legend: {
                //     align: 'left',
                //     x: 70,
                //     verticalAlign: 'top',
                //     y: 70,
                //     floating: true,
                //     backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                //     borderColor: '#CCC',
                //     borderWidth: 1,
                //     shadow: false
                // },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    series: {
                        pointWidth: 14
                    },
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                if (this.y > 0)
                                    return this.y;
                            }
                        }
                    },
                },
                series: [{
                        name: trainee_name,
                        data: <?php echo $less_than_range; ?>,
                        color: '#357045',

                    }, {
                        name: trainee_name,
                        data: <?php echo $second_range; ?>,
                        color: '#03fc07',

                    },
                    {
                        name: trainee_name,
                        data: <?php echo $third_range; ?>,
                        color: '#2cab8f',

                    },
                    {
                        name: trainee_name,
                        data: <?php echo $forth_range; ?>,
                        color: '#b81c29',

                    },
                    {
                        name: trainee_name,
                        data: <?php echo $fifth_range; ?>,
                        color: '#e626f0',

                    },
                    {
                        name: trainee_name,
                        data: <?php echo $above_range_final; ?>,
                        color: '#dade10',

                    },
                ],
                exporting: {
                    chartOptions: {
                        title: {
                            text: 'Assessment comparision',
                            align: 'left',
                            verticalAlign: 'top',
                            x: 5,
                            y: 10,
                            'style': {
                                'fontSize': '12px',
                                'fontFamily': 'Catamaran',
                                'color': 'black',
                            },
                        },
                        xAxis: {
                            scrollbar: {
                                enabled: false
                            },
                        },

                        plotOptions: {
                            series: {
                                dataLabels: {
                                    enabled: true

                                }
                            }

                        },
                        subtitle: {
                            text: trainee_name + '_' + Company_id + '_' + e_id,
                            align: 'left',
                            verticalAlign: 'top',
                            x: 128,
                            y: 10,
                            'style': {
                                'fontSize': '10px',
                                'fontFamily': 'Catamaran',
                                'color': 'black',
                            },
                        },

                        // legend: {
                        //     enabled: false,
                        //     itemMarginTop: 0,
                        //     verticalAlign: 'top',
                        //     itemMarginTop: -15,
                        //     y: 28,
                        // },
                    },
                    csv: {
                        columnHeaderFormatter: function(item, key) {
                            if (!key) {
                                return 'Assessment Name'
                            }
                            return false
                        }
                    },
                    filename: 'Asessment comparision ' + '_' + trainee_name + '_' + Company_id + '_' + e_id,
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
<?php }
?>
<!-- Assessment comparision chart ended by Patel Rudra -->
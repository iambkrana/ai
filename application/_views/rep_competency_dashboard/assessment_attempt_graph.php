<?php
$ass = json_decode($assessment_names);
$trainer_name = $trainee_name;
$stsum1 = json_decode($assessment_attempts);
$newlen1 = array_sum($stsum1);
$Company_id = json_decode($Company_id);
$e_id = json_decode($e_id);

if ($newlen1 == 0) {
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
        <div id='assessment_attempt' style='min-width: 100%; height: 300px; background:white;'>
            <img src="<?= base_url(); ?>/assets/images/empty.jpeg" class="img-style" />
            <br>
            <div class="head-text">No data found for selected filters</div>
            <div class="sub-head">Please retry with some other filters</div>
        </div>
        <div class="clearfix"></div>
    </div>
<?php } else { ?>
    <?php
    $assessment_attempts;
    $assessment_attempts = str_replace('"', "", $assessment_attempts);
    $assessment_attempts;
    $trainer_name;
    $trainer_name = str_replace('"', "", $trainer_name);
    $trainer_name;
    ?>
    <?php

    $base_url = base_url();
    ?> <div id='assessment_attempt' style='border:1px solid #d4d4d4;padding: 5px; height: 300px;'>
    </div>
    <div id='assessment_attempt' style='min-width: 100%; height: 300px;'></div>
    <div id='assessment_attempt'></div>
    <div class="clearfix"></div>
    </div>
    <script>
        var trainee_name = '<?php echo str_replace('\\"', '', $trainer_name); ?>';
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

            Highcharts.chart('assessment_attempt', {
                chart: {
                    type: 'line',
                    height: 300,
                    marginBottom: 80,
                    marginTop: 95,

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
                    enabled: true
                },
                xAxis: {
                    allowDecimals: false,
                    categories: <?php echo $assessment_names; ?>,
                    title: {
                        text: 'Assessment names'
                    },
                },

                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false,
                },
                yAxis: {
                    title: {
                        text: 'No. attempts'
                    },
                },
                plotOptions: {
                    series: {
                        marker: {
                            enabled: false
                        },
                    }
                },
                series: [{
                    name: '<?php echo "attempts"; ?>',
                    data:  <?php echo $assessment_attempts ?>,
                }, ],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                },
                exporting: {
                    chartOptions: {
                        title: {
                            text: 'Assessment attempt of',
                            align: 'left',
                            verticalAlign: 'top',
                            y: 4,
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
                            text: trainee_name + ' ' + Company_id + ' ' + e_id,
                            align: 'left',
                            verticalAlign: 'top',
                            x: 117,
                            y: 4,
                            'style': {
                                'fontSize': '10px',
                                'fontFamily': 'Catamaran',
                                'color': 'black',
                            },
                        },
                    },
                    csv: {
                        columnHeaderFormatter: function(item, key) {
                            if (!key) {
                                return 'Assessment Name'
                            }
                            return false
                        }
                    },
                    filename: 'Assessment attempt ' + '_' + trainee_name + '_' +Company_id+ '_' +e_id,
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
<!-- Assessment comparison chart created by Patel Rudra -->
<!-- Rep spider chart created by Patel Rudra -->
<?php

$ass = json_decode($ass_names);
$trainer_name = json_decode($trainer_name);
$Company_id = json_decode($Company_id);
$e_id = json_decode($e_id);

$newlen1 = array_sum(json_decode($assessment_average));

if ($newlen1 == 0) {
    ?>
<div id='container'>
    <div id='rep_spider_chart' style='min-width: 100%; height: 300px; background:white;'>
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
?> <div id='rep_spider_chart' style='border:1px solid #d4d4d4;padding: 5px; height: 300px;'>
</div>
<div id='rep_spider_chart' style='min-width: 100%; height: 300px;'></div>
<div id='rep_spider_chart'></div>
<div class="clearfix"></div>
</div>
<script>
        var trainee_name = '<?php echo str_replace('\\"', '', $trainer_name); ?>';
        var assessment_average = <?php echo $assessment_average; ?>;
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

            Highcharts.chart('rep_spider_chart', {

                chart: {
                    polar: true,
                    type: 'line',

                    height: 300,
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
                pane: {
                    size: '60%'
                },

                xAxis: {
                    categories: <?= $ass_names; ?>,
                    tickmarkPlacement: 'on',
                    lineWidth: 0
                },

                yAxis: {
                    allowDecimals: false,
                    // minTickInterval: 1,
                    gridLineInterpolation: 'polygon',
                    lineWidth: 0,
                    min: 0,
                    startOnTick: true,
                    labels: {
                        enabled: true
                    }
                },

                tooltip: {
                    shared: true,
                    pointFormat: '<span style="color:{series.color}">{series.name}: <b>{point.y:,.2f} </b><br/>'
                },

                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },

                series: [{
                    name: trainee_name,
                    data: assessment_average,
                    pointPlacement: 'on',
                }],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                align: 'center',
                                verticalAlign: 'bottom',
                                layout: 'horizontal'
                            },
                            pane: {
                                size: '70%'
                            }
                        }
                    }]
                },
                exporting: {
                    chartOptions: {
                        title: {
                            text: 'Rep spider ',
                            align: 'left',
                            verticalAlign: 'top',
                            x:5,
                            y:10,
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
                            x: 57,
                            y: 10,
                            'style': {
                                'fontSize': '10px',
                                'fontFamily': 'Catamaran',
                                'color': 'black',
                            },
                        },
                        
                        legend: {
                            enabled: false,
                            itemMarginTop: 0,
                            verticalAlign: 'top',
                            itemMarginTop: -15,
                            y: 28,
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
                    filename: 'Rep spider ' + '_' + trainee_name + '_' +Company_id+ '_' +e_id,
                    buttons: {
                        contextButton: {
                            symbol: 'download',
                            symbolStroke: "#004369",
                            menuItems: ['printChart', 'downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
}); 
});
</script>
<?php } 
?>
<!-- Rep spider chart ended by Patel Rudra -->
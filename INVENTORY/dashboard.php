<?php
session_start();

if (!isset($_SESSION['users'])) {
    header('location: login.php');
    exit(); // Add exit to prevent further execution
}

$user = $_SESSION['users'];

// get graph data order vise status
include('database/po_status_pie_graph.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Inventory Management System</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <script src="https://kit.fontawesome.com/74f27641c2.js" crossorigin="anonymous"></script>
</head>
<body>
<div id="dashboardMainContainer">
    <?php include("partials/sidebar.php") ?>
    <div class="dashboard_content_container" id="dashboard_content_container">
        <?php include("partials/topnav.php") ?>
        <div class="dashboard_content">
            <div class="dashboard_content_main" id="contentMain">
                <div class="col50">
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <p class="highcharts-description">
                            Breakdown of Purchase Order by Status.
                        </p>
                    </figure>
                </div>
<!--
                <div class="col50">
                    <figure class="highcharts-figure">
                        <div id="containerBarChart"></div>
                        <p class="highcharts-description">
                            Breakdown of Purchase Order by Status.
                        </p>
                    </figure>
                </div>
-->
            </div>
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    var graphData = <?= json_encode($results) ?>;

    // Data retrieved from https://netmarketshare.com
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Purchase Order By Status',
            align: 'left'
        },
        tooltip: {
            pointFormatter: function () {
                var point = this,
                    series = point.series;
                return '<b>$(point.name)</b>: ${point.y}';
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.y}'
                }
            }
        },
        series: [{
            name: 'Status',
            colorByPoint: true,
            data: graphData
        }]
    });
</script>
<script src="Javascript/script.js"></script>
</body>
</html>

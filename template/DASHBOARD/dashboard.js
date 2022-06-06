/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

$(function () {

    'use strict';

    // Make the dashboard widgets sortable Using jquery UI
    $('.connectedSortable').sortable({
        containment: $('section.content'),
        placeholder: 'sort-highlight',
        connectWith: '.connectedSortable',
        handle: '.box-header, .nav-tabs',
        forcePlaceholderSize: true,
        zIndex: 999999
    });
    $('.connectedSortable .box-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move');



    /* jQueryKnob */
    $('.knob').knob();


    // The Calender
    $('#calendar').datepicker();

    // SLIMSCROLL FOR CHAT WIDGET
    $('#chat-box').slimScroll({
        height: '250px'
    });

    /* Morris.js Charts */
    // Sales chart

    $.ajax({
        type: 'POST',
        url: "././module/RPTChart/data.php",
        dataType: 'json',
        beforeSend: function () {
            $('#Chart_graph').loading();
        },
        data: {
        },
        success: function (data) {
            //console.log(data)
            $('#Chart_graph').loading("stop");

            var area = new Morris.Area({
                element: 'revenue-chart',
                resize: true,
                data: data,
                xkey: 'y',
                ykeys: ['item1'],
                labels: ['ยอดขาย'],
                lineColors: ['#a0d0e0', '#3c8dbc'],
                hideHover: 'auto'
            });
            var line = new Morris.Line({
                element: 'line-chart',
                resize: true,
                data: data,
                xkey: 'y',
                ykeys: ['item1'],
                labels: ['ยอดขาย'],
                lineColors: ['#efefef'],
                lineWidth: 2,
                hideHover: 'auto',
                gridTextColor: '#fff',
                gridStrokeWidth: 0.4,
                pointSize: 4,
                pointStrokeColors: ['#efefef'],
                gridLineColor: '#efefef',
                gridTextFamily: 'Open Sans',
                gridTextSize: 10
            });

        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
    // Donut Chart
    var donut = new Morris.Donut({
        element: 'sales-chart',
        resize: true,
        colors: ['#3c8dbc', '#f56954', '#00a65a'],
        data: [
            {label: 'Download Sales', value: 12},
            {label: 'In-Store Sales', value: 30},
            {label: 'Mail-Order Sales', value: 20}
        ],
        hideHover: 'auto'
    });

    // Fix for charts under tabs
    $('.box ul.nav a').on('shown.bs.tab', function () {
        area.redraw();
        donut.redraw();
        line.redraw();
    });



});

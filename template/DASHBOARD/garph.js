
//createGarphTab("Line_Chart");
//createGarphTab("Donut_Chart");
//createGarphTab("Bar_chart");

getGarpList();
AllSellSummaryGarphLineChart();


function createGarphTab(text_node, chart_type_id, tab_active) {
    var tab_garph = document.getElementById("tab_garph");
    var tab_select_garph = document.createElement("li");
    var text_node_tab_select_garph = document.createTextNode(text_node);
    var a_node_table_select_garph = document.createElement("a");
    var span_node_table_select_garph = document.createElement("span");
    a_node_table_select_garph.setAttribute("href", chart_type_id);
    a_node_table_select_garph.setAttribute("data-toggle", "tab");
    span_node_table_select_garph.setAttribute("class", "graph_description");
    span_node_table_select_garph.appendChild(text_node_tab_select_garph);
    a_node_table_select_garph.appendChild(span_node_table_select_garph);
    tab_select_garph.appendChild(a_node_table_select_garph);
    tab_select_garph.setAttribute("class", tab_active);
    tab_garph.appendChild(tab_select_garph);
}

// เลือก Select box Graph List // 
function getGarpList() {
    $.ajax({
        type: 'POST',
        url: "././module/DASHBOARD/graph.php",
        dataType: 'json',
        beforeSend: function () {
           // $('#Chart_graph').loading();
        },
        data: {
            load: 'getGraphList',
        },
        success: function (data) {
            //document.getElementById("graph_description").innerHTML = data[0].graph_areachart;
            var select, i, option;
            select = document.getElementById('graph_list');
            for (i = 0; i < data.length; i++) {
                option = document.createElement('option');
                option.text = data[i].garph_name;
                option.value = data[i].id;
                select.add(option, data[1]);
            }
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

function AllSellSummaryGarphLineChart() {
    $.ajax({
        type: 'POST',
        url: "././module/DASHBOARD/graph.php",
        dataType: 'json',
        beforeSend: function () {
            // $('#Chart_graph').loading();
        },
        data: {
            load: "AllSellSummaryGarphLineChart",
        },
        success: function (data) {
            //$('#Chart_graph').loading("stop");
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
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

function selectGarphGroup() {
    var id_graph = document.getElementById("graph_list").value;
    $.ajax({
        type: 'POST',
        url: "././module/DASHBOARD/graph.php",
        dataType: 'json',
        beforeSend: function () {
           // $('#Chart_graph').loading();
        },
        data: {
            load: 'selectGarphGroup',
            id_graph: id_graph
        },
        success: function (data) {
            document.querySelectorAll('[data-toggle="tab"]').innerHTML = '';
            console.log(data);
            createGarphTab("Area Chart", "#revenue-chart", "active");
            createGarphTab("Sales Chart", "#sales-chart", "");


            //document.getElementById("graph_description").innerHTML = data[0].graph_areachart;

        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });

}





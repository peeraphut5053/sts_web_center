$(".txtNumuric").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode >= 35 && e.keyCode <= 40)) {
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});
function NumberFormat(num, dec) {
    return  num.toFixed(dec).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
function number_check(e) {
    if ((e.keyCode == 46 || e.keyCode >= 48) && (e.keyCode <= 57))
    {
    } else
    {
        alert("please input number and dot");
        return  false;
    }
}

function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function RenderDataTable() {
//    TableId , ArrParams , ArrDataColumns
    //======== Table ==========
//    var TarGetTableId = "tblModal";
    //======== Param ==========
//    var ArrThisId = thisid.split("!!");
//    var CoNum = ArrThisId[1];
//    var CoLine = ArrThisId[2];
    //========================

//    $.ajax({
//        type: 'GET',
//        url: "././module/CER_DO/data.php",
//        contentType: "text/plain",
//        dataType: 'json',
//        beforeSend: function () {
//            
//        },
//        data: {
//            load: 'MultiLot',
//            CoNum: CoNum,
//            CoLine: CoLine,
////                Item : Item
//        },
//        success: function (data) {
//            $("#" + TarGetTableId).DataTable().clear();
//            $('#' + TarGetTableId).dataTable({
//                fixedHeader: true,
//                "aaData": data,
//                "columns": [
//                    {"data": "Loc"},
//                    {"data": "Qty", "className": "text-right"},
//                ],
//                'dom': "<'row'<'col-md-5'B><'col-md-7 text-right col-flt-page'lf>>" +
//                        "<'row'<'col-md-12'tr>>" +
//                        "<'row'<'col-md-5 text-left'i><'col-sm-7 text-right'p>>",
//                buttons: [
//                    {
//                        extend: 'print',
//                        text: "<i class='fa fa-print'></i>&nbsp;Print",
//                        title: ''
//                    },
//                    {
//                        extend: 'excel',
//                        text: "<i class='fa fa-file-excel'></i>&nbsp;Excel",
//                        title: ''
//                    },
//                ]
//            });
//
//            $('#' + TarGetTableId).loading("stop");
//
//        },
//        error: function (e) {
//            console.log("There was an error with your request...");
//            console.log("error: " + JSON.stringify(e));
//        }
//    });
}
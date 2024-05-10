//================= Get date Today =========================
function GetToday() {
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var thisDay = d.getFullYear() + '-' +
            (month < 10 ? '0' : '') + month + '-' +
            (day < 10 ? '0' : '') + day;
    return thisDay;
}

function GetTodayTime() {
    var d = new Date();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var thisDay = d.getFullYear() + '-' +
            (month < 10 ? '0' : '') + month + '-' +
            (day < 10 ? '0' : '') + day;
    return thisDay + ' 00:00:00';
}


function addCommas(num) {
    var str = num.toString().split('.');
    if (str[0].length >= 4) {
        str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
    }
    return str.join('.');
}

// function formatMoney(number, decPlaces, decSep, thouSep) {
//     decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
//             decSep = typeof decSep === "undefined" ? "." : decSep;
//     thouSep = typeof thouSep === "undefined" ? "," : thouSep;
//     var sign = number < 0 ? "-" : "";
//     var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
//     var j = (j = i.length) > 3 ? j % 3 : 0;

//     return sign +
//             (j ? i.substr(0, j) + thouSep : "") +
//             i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
//             (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
// }

function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces;
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;

    // แบ่งเลขเป็นส่วนจำนวนเต็มและทศนิยม
    var parts = Number(number).toFixed(decPlaces).split('.');
    var integerPart = parts[0];
    var decimalPart = decSep + (parts.length > 1 ? parts[1] : '00').padEnd(decPlaces, '0').substring(0, decPlaces);

    // ใส่ comma เข้าไปในส่วนของจำนวนเต็ม
    var integerWithCommas = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thouSep);

    // รวมจำนวนเต็มกับทศนิยมเข้าด้วยกันและคืนค่า
    return (number < 0 ? "-" : "") + integerWithCommas + decimalPart;
}





function formatQcTest(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 5 : decPlaces,
            decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;

    return sign +
            (j ? i.substr(0, j) + thouSep : "") +
            i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
            (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}

function formatDate(date) {
    if (date != null) {
        date = date.date.substring(0, 10)
    } else {
        date = '-';
    }
    return date;
}

function GetCurrDateTime() {
    var d = new Date();
    var GetTime = d.getHours() + "" + d.getMinutes() + "" + d.getSeconds();
    var month = d.getMonth() + 1;
    var day = d.getDate();

    var GetDate = d.getFullYear() + '' +
            (month < 10 ? '0' : '') + month + '' +
            (day < 10 ? '0' : '') + day;
    return GetDate + "_" + GetTime;
}


function GetYearMonth(IdYear, IdMonth) {
    var currentYear = (new Date).getFullYear();
    var currentMonth = (new Date).getMonth();
    var ss = 0;
    var sss = "";
    var i = 0;
    for (i = 0; i < 10; i++) {
        sss = "";
        ss = parseInt(currentYear) - i;
        currentYear == ss ? sss = "selected" : sss = "";
        $("#" + IdYear).append("<option " + sss + " value='" + ss.toString() + "' >" + ss.toString() + "</optio>");
    }
    var monName = "";
    for (i = 1; i <= 12; i++) {
        sss = "";
        currentMonth == i ? sss = "selected" : sss = "";

        if (i == 1) {
            monName = "January";
        } else if (i == 2) {
            monName = "Febuary";
        } else if (i == 3) {
            monName = "March";
        } else if (i == 4) {
            monName = "April";
        } else if (i == 5) {
            monName = "May";
        } else if (i == 6) {
            monName = "June";
        } else if (i == 7) {
            monName = "July";
        } else if (i == 8) {
            monName = "August";
        } else if (i == 9) {
            monName = "September";
        } else if (i == 10) {
            monName = "October";
        } else if (i == 11) {
            monName = "November";
        } else if (i == 12) {
            monName = "December";
        }
        $("#" + IdMonth).append("<option " + sss + " value='" + i + "' >" + monName + "</optio>");
    }
}

function GetUserProp(PropGet) {
    //<input id='user_prop' type='hidden' login_username='admin' login_user_id='225' />
    if (PropGet == "login_username") {
        var login_username = $("#user_prop").attr("login_username")
        PropValue = login_username;
    }

    if (PropGet == "DATABASE") {
        var DATABASE = $("#user_prop").attr("DATABASE")
        PropValue = DATABASE;
    }
    return PropValue;
}


// GetSelectOption for create select box by append value 
/*
 var IdSelectbox = "selDep";
 var url = "././module/USER_MNG/data.php";
 var load = "GetDepartmentAll";
 var valueId = "dep_id";
 var valueShow = "dep_name";
 */

function GetSelectOption(IdSelectbox, url, load, valueId, valueShow, selectName) {
    var IdSelect = "#" + IdSelectbox;
    $.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        beforeSend: function () {
        },
        data: {
            load: load,
        },
        success: function (data) {
            $(IdSelect).empty();
            $(IdSelect).append("<option selected value=''>--- SELECT " + selectName + " ---</option>");
            var selected = "";
            $.each(data, function (index, row) {
                $(IdSelect).append("<option " + selected + "  value='" + row[valueId] + "'>" + "[" + row[valueId] + "]" + row[valueShow] + "</option>");
            });
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

function SetDataPostElement(load) {
    var formElements = new Array();
    var valElements = new Array();
    var i = 0;
    $("input:text,select").each(function () {
        formElements.push($(this)[0].id);
    });
    do {
        valElements.push([formElements[i], $("#" + formElements[i]).val()]);
        i = i + 1;
    } while (i < formElements.length);
    valElements.push(["load", load]);

    var entries = new Map(valElements);
    var obj = Object.fromEntries(entries);
    return obj;
}



function SearchDataFn(tblReportId, btnSearch, tablecolumsArr, excelName, postval) {
    var btnSearch = this.btnSearch;
    var ModuleName = $("#CurrentPage").val();
    var urlModule = "././module/" + ModuleName + "/data.php";
    var datapost = SetDataPostElement(postval);
    
    var OldHtml = $(btnSearch).html();
    $.ajax({
        type: 'GET',
        url: urlModule,
        dataType: 'json',
        beforeSend: function () {
            $(btnSearch).html("<i class='fa fa-spinner fa-spin'></i>");
            $(tblReportId).loading();
        },
        data: datapost,
        success: function (data) {
            console.log(data)
            $(tblReportId).DataTable().clear();
            $(tblReportId).dataTable({
                scrollY: 400,
                "scrollX": true,
                "aaData": data,
                "paging": false,
                "searching": false,
                "initComplete": function (settings, json) {
                },
                fixedHeader: true,
                destroy: true,
                'processing': true,
                "columns": tablecolumsArr,
                'dom': "<'row'<'col-md-5'B><'col-md-7 text-right col-flt-page'lf>>" +
                        "<'row'<'col-md-12'tr>>" +
                        "<'row'<'col-md-5 text-left'i><'col-sm-7 text-right'p>>",
                buttons: [
                    {
                        extend: 'print',
                        text: "<i class='fa fa-print'></i>&nbsp;Print",
                        title: '',
                    },
                    {
                        extend: 'excel',
                        text: "<i class='fa fa-file-excel'></i>&nbsp;Excel",
                        title: 'รายงาน ' + excelName + ' ' + GetCurrDateTime()
                    },
                ],
            });
            $(tblReportId).loading("stop");
            $(btnSearch).html(OldHtml);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}



function SearchDataFnPackingReport(tblReportId, btnSearch, tablecolumsArr, excelName, postval) {
    var btnSearch = this.btnSearch;
    var ModuleName = $("#CurrentPage").val();
    var urlModule = "././module/" + ModuleName + "/data.php";
    var datapost = SetDataPostElement(postval);
    $.ajax({
        type: 'GET',
        url: urlModule,
        dataType: 'json',
        data: datapost,
        success: function (data) {
            console.log(tablecolumsArr)
            console.log(data)
            let total_bundle = 0;
            let total_pcs = 0;

            for (i = 0; i < data.length; i++) {

                let pcs_qty = data[i]["pcs_qty"].split(";");
                let resuult_pcs_qty = "";
                for (j = 0; j < pcs_qty.length; j++) {
                    resuult_pcs_qty = pcs_qty[j]
                    if (j > 0) {
                        resuult_pcs_qty = resuult_pcs_qty + "<hr>" + pcs_qty[j - 1];
                    }
                }

                let bundle = data[i]["bundle"].split(";");
                let resuult_bundle = "";
                for (k = 0; k < bundle.length; k++) {
                    resuult_bundle = bundle[k]
                    total_bundle = total_bundle + parseInt(bundle[k]);

                    if (k > 0) {
                        resuult_bundle = resuult_bundle + "<hr>" + bundle[k - 1];
                    }
                }

                let qty = data[i]["qty"].split(";");
                let resuult_qty = "";
                for (k = 0; k < qty.length; k++) {
                    resuult_qty = qty[k]
                    total_pcs = total_pcs + parseInt(qty[k].replace(/,/g, ''), 10) ;
                    if (k > 0) {
                        resuult_qty = resuult_qty + "<hr>" + qty[k - 1];
                    }
                }

                let LotNO = "";
                var ret1 = data[i]["MinBundleNo"];
                var ret2 = data[i]["MaxBundleNo"];
                var ret3 = data[i]["PCSnoBundle"];
                var nobundle = ""
                if (ret3 > 0) {
                    nobundle = "<hr>" + data[i]["MaxBundleNo"];
                    ret2 = ret2 - 1
                }
                LotNO = ret1 + "-" + ret2 + nobundle;
                $(tblReportId).append(`<tr>
                                            <td style='text-align:center'> ${i + 1}</td>
                                            <td style='text-align:center'> ${data[i]["Uf_NPS"]}</td>
                                            <td style='text-align:center;font-size:9px'> ${data[i]["Uf_spec"]}</td>
                                            <td style='text-align:center;font-size:9px'> ${data[i]["Uf_Grade"]}</td>
                                            <td style='text-align:center;font-size:9px'> ${data[i]["Uf_Schedule"]}</td>
                                            <td style='text-align:center'> ${data[i]["Uf_length"]}</td>
                                            <td style='text-align:center'> ${data[i]["Uf_TypeEnd"]}</td>
                                            <td style='text-align:center'> ${LotNO} </td>
                                            <td style='text-align:center'> ${data[i]["ref_num"]}</td>
                                            <td style='text-align:right'> ${resuult_pcs_qty}</td>
                                            <td style='text-align:right'> ${resuult_bundle}</td>
                                            <td style='text-align:right'> ${resuult_qty}</td>
                                            <td style='text-align:right'> ${data[i]["Total_PCS"]}</td>
                                        </tr>`)

            }
            $(tblReportId).append(`<tr>
                                        <td colspan='9'></td>
                                        <td style='text-align:right'>${total_bundle}</td>
                                        <td style='text-align:right'></td>
                                        <td style='text-align:right'>${total_pcs}</td>
                                        
                                    </tr>`)
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}


function SearchDataFnFormingReport(tblReportId, btnSearch, tablecolumsArr, excelName, postval) {
    var btnSearch = this.btnSearch;
    var ModuleName = $("#CurrentPage").val();
    var urlModule = "././module/" + ModuleName + "/data.php";
    var datapost = SetDataPostElement(postval);
    $.ajax({
        type: 'GET',
        url: urlModule,
        dataType: 'json',
        data: datapost,
        success: function (data) {
            console.log(tablecolumsArr)
            console.log(data)
            let total_bundle = 0;
            let total_pcs = 0;

            for (i = 0; i < data.length; i++) {

               
                $(tblReportId).append(`<tr>
                                            <td style='text-align:center'> ${i + 1}</td>
                                            
                                        </tr>`)

            }
            $(tblReportId).append(`<tr>
                                        <td colspan='8'></td>
                                        <td style='text-align:right'>${total_bundle}</td>
                                        <td style='text-align:right'></td>
                                        <td style='text-align:right'>${total_pcs}</td>
                                        
                                    </tr>`)
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}


function SearchDataFooterFn(tblReportId, btnSearch, tablecolumsArr, excelName, postval) {
    var btnSearch = this.btnSearch;
    var ModuleName = $("#CurrentPage").val();
    var urlModule = "././module/" + ModuleName + "/data.php";
    var datapost = SetDataPostElement(postval);
    var OldHtml = $(btnSearch).html();
    $.ajax({
        type: 'POST',
        url: urlModule,
        dataType: 'json',
        beforeSend: function () {
            $(btnSearch).html("<i class='fa fa-spinner fa-spin'></i>");
            $(tblReportId).loading();
        },
        data: datapost,
        success: function (data) {
            console.log(data)
            $(tblReportId).DataTable().clear();
            $(tblReportId).dataTable({
                scrollY: 400,
                "scrollX": true,
                "aaData": data,
                "paging": false,
                "searching": false,
                "initComplete": function (settings, json) {
                },
                destroy: true,
                'processing': true,
                "columns": tablecolumsArr,
                'dom': "<'row'<'col-md-5'B><'col-md-7 text-right col-flt-page'lf>>" +
                        "<'row'<'col-md-12'tr>>" +
                        "<'row'<'col-md-5 text-left'i><'col-sm-7 text-right'p>>",
                buttons: [
                    {
                        extend: 'print',
                        text: "<i class='fa fa-print'></i>&nbsp;Print",
                        title: '',
                    },
                    {
                        extend: 'excel',
                        text: "<i class='fa fa-file-excel'></i>&nbsp;Excel",
                        title: 'รายงาน ' + excelName + ' ' + GetCurrDateTime()
                    },
                ],
                "footerCallback": function () {
                    var api = this.api();
                    footerCallback(api)
                },
            });
            $(tblReportId).loading("stop");
            $(btnSearch).html(OldHtml);
        },
        error: function (e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });

}



function RowFooterBuild(tblReport) {
    var RowFooterBuild = "";
    var ColBuild = "";
    var ColCount = $(tblReport).find("tr th").length;
    var i = 0;
    RowFooterBuild = "<tfoot><tr>!!ColBuild!!</tr></tfoot>";
    for (i = 1; i <= ColCount; i++) {
        ColBuild += "<td></td>";
    }
    $(tblReport).append(RowFooterBuild.replace("!!ColBuild!!", ColBuild));
}

function ColumnSumVal(col, api) {
    var intVal = function (i) {
        return typeof i === 'string' ?
                i.replace(/[\$,]/g, '') * 1 :
                typeof i === 'number' ?
                i : 0;
    };
    var total = api
            .column(col)
            .data()
            .reduce(function (a, b) {
                return intVal(a) + intVal(b);
            }, 0);
    $(api.column(col).footer()).html(addCommas(total.toFixed(2)));
}




function getScript(url, callback) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url;
    script.onload = callback;
    script.onreadystatechange = function () {
        if (this.readyState == 'complete') {
            callback();
        }
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}



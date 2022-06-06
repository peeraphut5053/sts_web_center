//
//var tablesToExcel = (function () {
//    var uri = 'data:application/vnd.ms-excel;base64,'
//            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>'
//            , templateend = '</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>'
//            , body = '<body>'
//            , tablevar = '<table>{table'
//            , tablevarend = '}</table>', bodyend = '</body></html>'
//            , worksheet = '<x:ExcelWorksheet><x:Name>'
//            , worksheetend = '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>'
//            , worksheetvar = '{worksheet'
//            , worksheetvarend = '}'
//            , base64 = function (s) {
//                return window.btoa(unescape(encodeURIComponent(s)))
//            }
//    , format = function (s, c) {
//        return s.replace(/{(\w+)}/g, function (m, p) {
//            return c[p];
//        })
//    }
//    , wstemplate = ''
//            , tabletemplate = '';
//
//    return function (table, name, filename) {
//        var tables = table;
//
//        for (var i = 0; i < tables.length; ++i) {
//            wstemplate += worksheet + worksheetvar + i + worksheetvarend + worksheetend;
//            tabletemplate += tablevar + i + tablevarend;
//        }
//
//        var allTemplate = template + wstemplate + templateend;
//        var allWorksheet = body + tabletemplate + bodyend;
//        var allOfIt = allTemplate + allWorksheet;
//
//        var ctx = {};
//        for (var j = 0; j < tables.length; ++j) {
//            ctx['worksheet' + j] = name[j];
//        }
//
//        for (var k = 0; k < tables.length; ++k) {
//            var exceltable;
//            if (!tables[k].nodeType)
//                exceltable = document.getElementById(tables[k]);
//            ctx['table' + k] = exceltable.innerHTML;
//        }
//
//        var a = document.createElement('a');
//        a.href = uri + base64(format(allOfIt, ctx));
//        a.download = filename;
//        a.click();
//    }
//})();


var xport = {
    _fallbacktoCSV: true,
    toXLS: function (tableId, filename) {
        this._filename = (typeof filename == 'undefined') ? tableId : filename;

        //var ieVersion = this._getMsieVersion();
        //Fallback to CSV for IE & Edge
        if ((this._getMsieVersion() || this._isFirefox()) && this._fallbacktoCSV) {
            return this.toCSV(tableId);
        } else if (this._getMsieVersion() || this._isFirefox()) {
            alert("Not supported browser");
        }

        //Other Browser can download xls
        var htmltable = document.getElementById(tableId);
        var html = htmltable.outerHTML;

        this._downloadAnchor("data:application/vnd.ms-excel" + encodeURIComponent(html), 'xls');
    },
    toCSV: function (tableId, filename) {
        this._filename = (typeof filename === 'undefined') ? tableId : filename;
        // Generate our CSV string from out HTML Table
        var csv = this._tableToCSV(document.getElementById(tableId));
        // Create a CSV Blob
        var blob = new Blob([csv], {type: "text/csv"});

        // Determine which approach to take for the download
        if (navigator.msSaveOrOpenBlob) {
            // Works for Internet Explorer and Microsoft Edge
            navigator.msSaveOrOpenBlob(blob, this._filename + ".csv");
        } else {
            this._downloadAnchor(URL.createObjectURL(blob), 'csv');
        }
    },
    _getMsieVersion: function () {
        var ua = window.navigator.userAgent;

        var msie = ua.indexOf("MSIE ");
        if (msie > 0) {
            // IE 10 or older => return version number
            return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
        }

        var trident = ua.indexOf("Trident/");
        if (trident > 0) {
            // IE 11 => return version number
            var rv = ua.indexOf("rv:");
            return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
        }

        var edge = ua.indexOf("Edge/");
        if (edge > 0) {
            // Edge (IE 12+) => return version number
            return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
        }

        // other browser
        return false;
    },
    _isFirefox: function () {
        if (navigator.userAgent.indexOf("Firefox") > 0) {
            return 1;
        }

        return 0;
    },
    _downloadAnchor: function (content, ext) {
        var anchor = document.createElement("a");
        anchor.style = "display:none !important";
        anchor.id = "downloadanchor";
        document.body.appendChild(anchor);

        // If the [download] attribute is supported, try to use it

        if ("download" in anchor) {
            anchor.download = this._filename + "." + ext;
        }
        anchor.href = content;
        anchor.click();
        anchor.remove();
    },
    _tableToCSV: function (table) {
        // We'll be co-opting `slice` to create arrays
        var slice = Array.prototype.slice;

        return slice
                .call(table.rows)
                .map(function (row) {
                    return slice
                            .call(row.cells)
                            .map(function (cell) {
                                return '"t"'.replace("t", cell.textContent);
                            })
                            .join(",");
                })
                .join("\r\n");
    }
};



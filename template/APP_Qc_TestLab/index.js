 table = $('#example').DataTable({
                responsive: true,
                columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -2 }
        ],
                paging: true,
                searching: false,
                //            processing: true,        
                dom: 'Bfrtip',
                buttons: ['colvis', { extend: 'excel', exportOptions: { columns: ':visible' }, title: $("#ctl00_ContentPlaceHolder1_lblPageName").text(),
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        var numrows = 7;
                        debugger;
                        var mergeCells = $('mergeCells', sheet);
                        mergeCells[0].appendChild(_createNode(sheet, 'mergeCell', {
                            attr: {
                                ref: 'A1:E1'
                            }
                        }));

                        mergeCells.attr('count', mergeCells.attr('count') + 1);


                        function _createNode(doc, nodeName, opts) {
                            var tempNode = doc.createElement(nodeName);

                            if (opts) {
                                if (opts.attr) {
                                    $(tempNode).attr(opts.attr);
                                }

                                if (opts.children) {
                                    $.each(opts.children, function (key, value) {
                                        tempNode.appendChild(value);
                                    });
                                }

                                if (opts.text !== null && opts.text !== undefined) {
                                    tempNode.appendChild(doc.createTextNode(opts.text));
                                }
                            }

                            return tempNode;
                        }

                        var clR = $('row', sheet);
                        //update Row
                        clR.each(function () {
                            var attr = $(this).attr('r');
                            var ind = parseInt(attr);
                            ind = ind + numrows;
                            $(this).attr("r", ind);
                        });

                        // Create row before data
                        $('row c ', sheet).each(function (index) {
                            var attr = $(this).attr('r');

                            var pre = attr.substring(0, 1);
                            var ind = parseInt(attr.substring(1, attr.length));
                            ind = ind + numrows;
                            $(this).attr("r", pre + ind);
                        });

                        function Addrow(index, data) {
                            var row = sheet.createElement('row');
                            row.setAttribute("r", index);
                            for (i = 0; i < data.length; i++) {
                                var key = data[i].key;
                                var value = data[i].value;

                                var c = sheet.createElement('c');
                                // c.setAttribute("t", "inlineStr");
                                c.setAttribute("s", "0");
                                c.setAttribute("r", key + index);

                                var is = sheet.createElement('is');
                                var t = sheet.createElement('t');
                                var text = sheet.createTextNode(value)

                                t.appendChild(text);
                                is.appendChild(t);
                                c.appendChild(is);
                                row.appendChild(c);
                            }

                            return row;
                        }

                        var Comp = $("#lblCompany").text();
                        var City = $("#lblCity").text()
                        var Address = $("#lblAddress").text();
                        var mobile = $("#lblPhone").text();
                        var Phone = parseInt(mobile);
                        var Mail = $("#lblMail").text();
                        debugger;
                        var GSTIN = $("#lblGSTIN").text();
                        var col1 = '';
                        if (Mail != null && Mail != undefined && Mail != '') {
                            col1 += Comp + '' + City + '' + Address + '' + mobile + '' + Phone + '' + Mail + '' + GSTIN + '';
                        }
                        else {
                            col1 += Comp + '' + City + '' + Address + '' + mobile + '' + Phone + '' + GSTIN + '';
                        }
                        if (screenid == "5.9.4") {
                            var fromdate = " ";
                        }
                        else
                            var fromdate = "Duartion From " + $("#ctl00_ContentPlaceHolder1_Calender1_txtfrom").val() + " To " + $("#ctl00_ContentPlaceHolder1_Calender1_txtto").val() + " ";

                        var Title = $("#ctl00_ContentPlaceHolder1_lblPageName").text();

                        var r1 = Addrow(1, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }, { key: 'F', value: Comp }, { key: 'G', value: '' }, { key: 'H', value: '' }, { key: 'I', value: '' }, { key: 'J', value: ''}]);
                        var r2 = Addrow(2, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }, { key: 'F', value: City }, { key: 'G', value: '' }, { key: 'H', value: '' }, { key: 'I', value: '' }, { key: 'J', value: ''}]);
                        var r3 = Addrow(3, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }, { key: 'F', value: Address }, { key: 'G', value: '' }, { key: 'H', value: '' }, { key: 'I', value: '' }, { key: 'J', value: ''}]);
                        var r4 = Addrow(4, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }, { key: 'F', value: Phone }, { key: 'G', value: '' }, { key: 'H', value: '' }, { key: 'I', value: '' }, { key: 'J', value: ''}]);
                        var r5 = Addrow(5, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }, { key: 'F', value: Mail }, { key: 'G', value: '' }, { key: 'H', value: '' }, { key: 'I', value: '' }, { key: 'J', value: ''}]);
                        var r6 = Addrow(6, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }, { key: 'F', value: GSTIN }, { key: 'G', value: '' }, { key: 'H', value: '' }, { key: 'I', value: '' }, { key: 'J', value: ''}]);
                        var r7 = Addrow(7, [{ key: 'A', value: '' }, { key: 'B', value: '' }, { key: 'C', value: '' }, { key: 'D', value: '' }, { key: 'E', value: '' }, { key: 'F', value: fromdate }, { key: 'G', value: '' }, { key: 'H', value: '' }, { key: 'I', value: '' }, { key: 'J', value: ''}]);

                        //sheet.childNodes[0].childNodes[1].innerHTML = r1 + r2 + r3 + r4 + r5 + r6 + r7 + sheet.childNodes[0].childNodes[1].innerHTML;

                        var sheetData = sheet.getElementsByTagName('sheetData')[0];
                        sheetData.insertBefore(r7, sheetData.childNodes[0]);
                        sheetData.insertBefore(r6, sheetData.childNodes[0]);
                        sheetData.insertBefore(r5, sheetData.childNodes[0]);
                        sheetData.insertBefore(r4, sheetData.childNodes[0]);
                        sheetData.insertBefore(r3, sheetData.childNodes[0]);
                        sheetData.insertBefore(r2, sheetData.childNodes[0]);
                        sheetData.insertBefore(r1, sheetData.childNodes[0]);

                    }, footer: true
                },
            { extend: 'pdf', exportOptions: { columns: ':visible' }, title: $("#ctl00_ContentPlaceHolder1_lblPageName").text(), customize: function (doc) {
                doc.styles.title = {
                    color: 'black',
                    fontSize: '12',
                    background: 'lightgreen',
                    alignment: 'center'
                }
                var textdoc = "";
                debugger;
                if ($("#lblCompany").text() != "") {

                    textdoc = $("#lblCompany").text() + " \n ";
                    $("#lblCompany").attr('style', 'margin-bottom:5px');
                }
                if ($("#lblCity").text() != "") {

                    textdoc = textdoc + $("#lblCity").text() + " \n ";
                    $("#lblCity").attr('style', 'margin-bottom:5px');
                }
                if ($("#lblAddress").text() != "") {

                    textdoc = textdoc + $("#lblAddress").text() + ",\n ";
                    $("#lblAddress").attr('style', 'margin-bottom:5px');
                }
                if ($("#lblPhone").text() != "") {

                    textdoc = textdoc + $("#lblPhone").text() + ",\n ";
                    $("#lblPhone").attr('style', 'margin-bottom:5px');
                }
                if ($("#lblMail").text() != "") {

                    textdoc = textdoc + $("#lblMail").text() + ",\n ";
                    $("#lblMail").attr('style', 'margin-bottom:5px');
                }
                if ($("#lblGSTIN").text() != "") {
                    textdoc = textdoc + " GSTIN NO : " + $("#lblGSTIN").text() + ",\n ";
                }

                debugger;
                textdoc = textdoc + fromdate + " \n ";
                doc.content.splice(0, 0, {
                    alignment: 'center',
                    text: textdoc,
                    height: '50px',
                    style: 'line-height:30px'


                    //text: "My Online Software Pvt Ltd   Hyderabad"                                 
                });
            }, footer: true
            }]
            });
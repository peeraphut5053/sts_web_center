$('xf', xlsx.xl['styles.xml']).length
$('cellXfs xf', xlsx.xl['styles.xml']).length


customize: function( xlsx ) {
  // see built in styles here: https://datatables.net/reference/button/excelHtml5
  // take a look at "buttons.html5.js", search for "xl/styles.xml"
  //styleSheet.childNodes[0].childNodes[0] ==> number formats  <numFmts count="6"> </numFmts>
  //styleSheet.childNodes[0].childNodes[1] ==> fonts           <fonts count="5" x14ac:knownFonts="1"> </fonts>
  //styleSheet.childNodes[0].childNodes[2] ==> fills           <fills count="6"> </fills>
  //styleSheet.childNodes[0].childNodes[3] ==> borders         <borders count="2"> </borders>
  //styleSheet.childNodes[0].childNodes[4] ==> cell style xfs  <cellStyleXfs count="1"> </cellStyleXfs>
  //styleSheet.childNodes[0].childNodes[5] ==> cell xfs        <cellXfs count="67"> </cellXfs>
  //on the last line we have the 67 currently built in styles (0 - 66), see link above
   
      var sSh = xlsx.xl['styles.xml'];
      var lastXfIndex = $('cellXfs xf', sSh).length - 1;
   
      var n1 = '<numFmt formatCode="##0.0000%" numFmtId="300"/>';
      var s1 = '<xf numFmtId="300" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/>';
      var s2 = '<xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1">'+
                  '<alignment horizontal="center"/></xf>';
      sSh.childNodes[0].childNodes[0].innerHTML += n1;
      sSh.childNodes[0].childNodes[1].innerHTML += s1 + s2;
   
      var fourDecPlaces = lastXfIndex + 1;
      var greyBoldCentered = lastXfIndex + 2;
   
      var sheet = xlsx.xl.worksheets['sheet1.xml'];
      //numeric columns except for rate get thousand separators
      $('row c[r^="F"]', sheet).attr( 's', '64' );
  //                $('row c[r^="G"]', sheet).attr( 's', '60' );  //% 1 dec. place
      $('row c[r^="G"]', sheet).attr( 's', fourDecPlaces );  //% 4 decimal places, as added above
      $('row c[r^="I"]', sheet).attr( 's', '64' );
      $('row c[r^="J"]', sheet).attr( 's', '64' );
      $('row c[r^="K"]', sheet).attr( 's', '64' );
      $('row c[r^="L"]', sheet).attr( 's', '64' );
  //                $('row c', sheet).attr( 's', '25' ); //for all rows
      $('row:eq(0) c', sheet).attr( 's', greyBoldCentered );  //grey background bold and centered, as added above
      $('row:eq(1) c', sheet).attr( 's', '7' );  //grey background bold
  },






var sSh = xlsx.xl['styles.xml'];
//below could be replaced with use of .innerHtml but that doesn't work in IE
var newPercentageFormat =sSh.childNodes[0].childNodes[0].childNodes[3].cloneNode(false);
newPercentageFormat.setAttribute('formatCode','##0.00%');
newPercentageFormat.setAttribute('numFmtId','180');
sSh.childNodes[0].childNodes[0].appendChild(newPercentageFormat);
 
$(sSh).find('numFmts').attr('count', '7');
$(sSh).find('xf[numFmtId="9"]').attr('numFmtId', '180');



 //Get the built-in styles
                                //refer buttons.html5.js "xl/styles.xml" for the XML structure
                                var styles = xlsx.xl['styles.xml'];

                                //Create our own style to use the "Text" number format with id: 49
                                var style = '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyNumberFormat="1"/>';
                                // Add new node and update counter
                                el = $('cellXfs', styles);
                                el.append(style).attr('count', parseInt(el.attr('count'))+1);
                                // Index of our new style
                                var styleIdx = $('xf', el).length - 1;

                                //Apply new style to the first (A) column
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                //Set new style default for the column (optional)
                                $('col:eq(0)', sheet).attr('style', styleIdx);
                                //Apply new style to the existing rows of the first column ('A')
                                //Skipping the header row
                                $('row:gt(0) c[r^="E"]', sheet).attr('s', styleIdx);
                            },
                            exportOptions: {
                                format: {
                                    body: function(data, row, column, node) {
                                        return column === 0 ? "\0" + data : data;

                                    }}



     // var sSh = xlsx.xl['styles.xml'];
                // var lastXfIndex = $('xf', sSh).length - 1;
            
                // var n1 = '<numFmt numFmtId="169" formatCode="#,##0.00;(#,##0.00)"/>';
                // var s1 = '<xf numFmtId="169" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/>';
                // var s2 = '<xf numFmtId="0" fontId="2" fillId="2" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyAlignment="1">'+
                //             '<alignment horizontal="center"/></xf>';
                // sSh.childNodes[0].childNodes[0].innerHTML += n1;
                // sSh.childNodes[0].childNodes[5].innerHTML += n1+s1+s2;
            
                // var fourDecPlaces = lastXfIndex + 1;
            //     var greyBoldCentered = lastXfIndex + 2;
            
                // var sheet = xlsx.xl.worksheets['sheet1.xml'];
            //     //numeric columns except for rate get thousand separators
                // $('row c[r^="F"]', sheet).attr( 's', '61' );
            // //                $('row c[r^="G"]', sheet).attr( 's', '60' );  //% 1 dec. place
                // $('row c[r^="Y"]', sheet).attr( 's', fourDecPlaces );  //% 4 decimal places, as added above
            //     $('row c[r^="I"]', sheet).attr( 's', '64' );
            //     $('row c[r^="J"]', sheet).attr( 's', '64' );
            //     $('row c[r^="K"]', sheet).attr( 's', '64' );
            //     $('row c[r^="L"]', sheet).attr( 's', '64' );
            // //                $('row c', sheet).attr( 's', '25' ); //for all rows
            //     $('row:eq(0) c', sheet).attr( 's', greyBoldCentered );  //grey background bold and centered, as added above
            //     $('row:eq(1) c', sheet).attr( 's', '7' );  //grey background bold*******



            //     var sSh = xlsx.xl['styles.xml'];
            //     var lastXfIndex = $('cellXfs xf', sSh).length - 1;
            //     var fourDecPlaces = lastXfIndex + 1;
            //     var greyBoldCentered = lastXfIndex + 2;
            //     var twoDecPlacesBold = lastXfIndex + 3;

            //     var sheet = xlsx.xl.worksheets['sheet1.xml'];

            // //two decimal places columns         
            //     var twoDecPlacesCols = ['L', 'AA', 'AC',];           
            //     for ( i=0; i < twoDecPlacesCols.length; i++ ) {
            //         $('row c[r^='+twoDecPlacesCols[i]+']', sheet).attr( 's', '68' );
            //     }
            //     $('row c[r^="Y"]', sheet).attr( 's', fourDecPlaces );  //% 4 decimal places, as added above
            // //                $('row c', sheet).attr( 's', '25' ); //for all rows
            //     $('row:eq(0) c', sheet).attr( 's', greyBoldCentered );  //grey background bold and centered, as added above
            //     $('row:eq(1) c', sheet).attr( 's', '7' );  //grey background bold

                // var styleSheet = xlsx.xl['styles.xml'];
                // var n1 = '<numFmt formatCode="$ #,##0.00" numFmtId=""/>';
                // var nFmt = '<numFmt  formatCode="0.0000"/>';
                // var s1 = '<xf numFmtId="" borderId="1" fillId="4" fontId="2" applyBorder="1" applyFill="1" applyFont="1" />';
                // styleSheet.childNodes[0].childNodes[0].innerHTML = styleSheet.childNodes[0].childNodes[0].innerHTML + n1;
                // styleSheet.childNodes[0].childNodes[1].innerHTML = styleSheet.childNodes[0].childNodes[1].innerHTML + s1;

            // var sheet = xlsx.xl.worksheets['sheet1.xml'];
            // var styles = xlsx.xl['styles.xml'];
            // var numrows = 1;
            // var clR = $('row', sheet, styles);


            // //update Row
            // clR.each(function () {
            //     var attr = $(this).attr('r');
            //     var ind = parseInt(attr);
            //     ind = ind + numrows;
            //     $(this).attr("r", ind);
            // });

            // // Create row before data
            // $('row c ', sheet,styles).each(function () {
            //     var attr = $(this).attr('r');
            //     var pre = attr.substring(0, 1);
            //     var ind = parseInt(attr.substring(1, attr.length));
            //     ind = ind + numrows;
            //     $(this).attr("r", pre + ind);
            // });

            // function Addrow(index, data) {
            //     var msg = '<row r="' + index + '">'
            //     for (var i = 0; i < data.length; i++) {
            //         var key = data[i].key;
            //         var value = data[i].value;
            //         msg += '<c t="inlineStr" r="' + key + index + '">';
            //         msg += '<is>';
            //         msg += '<t>' + value + '</t>';
            //         msg += '</is>';
            //         msg += '</c>';
            //     }
            //     msg += '</row>';
            //     return msg;
            // }

            // var d = new Date();
            // let dateNow = `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`
            // var r1 = Addrow(1, [{key: 'A', value: ` Report ( เรียกรายงาน ณ วันที่ ${dateNow}) `}]);
            // //var r2 = Addrow(2, [{key: 'A', value: "222"}]);
            // //  var nFmt = Addrow(2 [{key: 'Y', value:  '" formatCode="0.0000"/>'}]);

            // sheet.childNodes[0].childNodes[1].innerHTML = r1 + sheet.childNodes[0].childNodes[1].innerHTML;



                
            // Get the built-in styles
            // refer buttons.html5.js "xl/styles.xml" for the XML structure
            // var styles = xlsx.xl['styles.xml'];

            // Create a custom number format
            // Get the available id for the custom number format
            // var numFmtId = getMaxValue($('numFmts numFmt', styles), 'numFmtId') + 1
            // //XML Node: Custom number format
            
            // // Add new node
            // var el = $('numFmts', styles);
            // el.append(nFmt).attr('count', parseInt(el.attr('count'))+1);
            // //Create our own style to use the custom number format above
            // //XML Node: Custom style for the timestamp column
            // var style = '<xf numFmtId="' + numFmtId + '" fontId="0" fillId="0" borderId="0" applyFont="1" applyFill="1" applyBorder="1" xfId="0" applyNumberFormat="1"/>';
            // // Add new node
            // el = $('cellXfs', styles);
            // el.append(style).attr('count', parseInt(el.attr('count'))+1);
            // // Index of our new style
            // var styleIdx = $('xf', el).length - 1;

            // //Apply new style to the first (A) column
            // var sheet = xlsx.xl.worksheets['sheet1.xml'];
            // //Set new style default for the column (optional)
            // $('col:eq(0)', sheet).attr('style', styleIdx);
            // //Apply new style to the existing rows of the first column ('A'), skipping header row
            // $('row:gt(0) c[r^="Y"]', sheet).attr('s', styleIdx);
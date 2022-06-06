
function GetBarType(barcode) {
    return new Promise(resolve => {
        $.ajax({
            type: 'GET',
            url: url,
            contentType: "text/plain",
            dataType: 'json',
            data: {
                load: "GetBarType",
                barcode: barcode,
            },
            success: function (data) {
                resolve(data);
            },
        });
    });
}


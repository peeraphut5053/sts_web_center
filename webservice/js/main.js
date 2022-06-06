function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}
function FormatNumber(number, dc) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    var ret = parseFloat(parts.join("."));
    return ret.toFixed(dc);
}
function addCommas(num) {

    var str = num.toString().split('.');
    if (str[0].length >= 4) {
        str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
    }
    return str.join('.');
}

function GenLoadingMessage(msgIn) {
    var msg = '<h1><i class="fa fa-circle-notch fa-spin"></i></h1><h4><b> ... ' + msgIn + ' ... </b></h4>';
    return msg;
}



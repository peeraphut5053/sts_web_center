$('.AllowFloatNumber').keypress(function(event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
});

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

function GenLoadingMessage(msgIn) {
    var msg = '<h1><i class="fa fa-circle-notch fa-spin"></i></h1><h4><b> ... ' + msgIn + ' ... </b></h4>';
    return msg;
}

function MAIN_ToggleLoading(panelId, ToggleState) {
    if (ToggleState == "ON") {
        $(panelId).LoadingOverlay("show", {
            image: "",
            fontawesome: "fa fa-circle-o-notch fa-spin"
        });
    } else {
        $(panelId).LoadingOverlay("hide", true);
    }
}

function MAIN_Confirm(p_title, p_content, p_iconfa) {
    $.confirm({
        title: '<i class="fa ' + p_iconfa + '"></i>&nbsp;' + p_title,
        content: p_content,
        buttons: {
            confirm: function() {
                return true;
            },
            cancel: function() {
                return false;
            },
        }
    });
}

function MAIN_FormValidate() {
    var Result = [];
    var BlankCount = 0;
    var Msg = "<b>กรุณากรอกข้อมูล(<font color='red'>*</font>)ให้ครบ</b><br>";
    var thisId = "";
    var thisVal = "";
    $(".require").each(function() {
        thisId = $(this).attr("id");
        thisVal = $(this).val();
        if (thisVal == "") {
            BlankCount++;
            Msg += "- " + $(this).attr("validate_text") + "<br>";
        }
    });
    Result.push(BlankCount);
    Result.push(Msg);
    return Result;
}

function MAIN_GetAjaxJSON(AjaxUrl, AjaxDataJson, DivIdLoading) {
    return $.ajax({
        type: 'POST',
        url: AjaxUrl,
        data: AjaxDataJson,
        dataType: "json",
        beforeSend: function() {
            MAIN_ToggleLoading(DivIdLoading, "ON");
        },
        async: false,
        cache: false,
        success: function(data) {
            MAIN_ToggleLoading(DivIdLoading, "OFF");
        },
        error: function(e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}

function MAIN_GetAjaxJSON_Txt(AjaxUrl, AjaxDataJson, DivIdLoading) {
    return $.ajax({
        type: 'POST',
        url: AjaxUrl,
        data: AjaxDataJson,
        beforeSend: function() {
            MAIN_ToggleLoading(DivIdLoading, "ON");
        },
        async: false,
        cache: false,
        success: function(data) {
            MAIN_ToggleLoading(DivIdLoading, "OFF");
        },
        error: function(e) {
            console.log("There was an error with your request...");
            console.log("error: " + JSON.stringify(e));
        }
    });
}
    function GetYearMonth(IdSelYear, IdSelMonth) {
        var date = new Date();
        var currentYear = (new Date).getFullYear();
        var currentMonth = (new Date).getMonth();
        var ss = 0;
        var sss = "";
        var i = 0;
        for (i = 0; i < 10; i++) {
            sss = "";
            ss = parseInt(currentYear) - i;
            currentYear == ss ? sss = "selected" : sss = "";
            $("#" + IdSelYear).append("<option " + sss + " value='" + ss.toString() + "' >" + ss.toString() + "</optio>");
        }
        var Mon = "";
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



            $("#" + IdSelMonth).append("<option " + sss + " value='" + i + "' >" + monName + "</optio>");
        }


    }
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

let sts_chemical = [
    {"data": {sts_c: "sts_c", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_c';
            let valdata = data.sts_c;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_si: "sts_si", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_si';
            let valdata = data.sts_si;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_mn: "sts_mn", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_mn';
            let valdata = data.sts_mn;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_p: "sts_p", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_p';
            let valdata = data.sts_p;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_s: "sts_s", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_s';
            let valdata = data.sts_s;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_cu: "sts_cu", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_cu';
            let valdata = data.sts_cu;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_v: "sts_v", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_v';
            let valdata = data.sts_v;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_ni: "sts_ni", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_ni';
            let valdata = data.sts_ni;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_cr: "sts_cr", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_cr';
            let valdata = data.sts_cr;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_cr: "sts_mo", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_mo';
            let valdata = data.sts_mo;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_ti: "sts_ti", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_ti';
            let valdata = data.sts_ti;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_nb: "sts_nb", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_nb';
            let valdata = data.sts_nb;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_al: "sts_al", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_al';
            let valdata = data.sts_al;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_b: "sts_b", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_b';
            let valdata = data.sts_b;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_co: "sts_co", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_co';
            let valdata = data.sts_co;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_pb: "sts_pb", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_pb';
            let valdata = data.sts_pb;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_fe: "sts_fe", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_fe';
            let valdata = data.sts_fe;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_ts: "sts_ts", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_ts';
            let valdata = data.sts_ts;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_ts: "sts_ys", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_ys';
            let valdata = data.sts_ys;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sts_el: "sts_el", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sts_el';
            let valdata = data.sts_el;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
]
let sup_chemical = [
    {"data": {sup_c: "sup_c", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_c';
            let valdata = data.sup_c;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_si: "sup_si", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_si';
            let valdata = data.sup_si;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_mn: "sup_mn", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_mn';
            let valdata = data.sup_mn;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_p: "sup_p", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_p';
            let valdata = data.sup_p;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_s: "sup_s", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_s';
            let valdata = data.sup_s;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_cu: "sup_cu", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_cu';
            let valdata = data.sup_cu;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_v: "sup_v", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_v';
            let valdata = data.sup_v;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_ni: "sup_ni", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_ni';
            let valdata = data.sup_ni;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_cr: "sup_cr", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_cr';
            let valdata = data.sup_cr;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_cr: "sup_mo", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_mo';
            let valdata = data.sup_mo;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_ti: "sup_ti", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_ti';
            let valdata = data.sup_ti;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_nb: "sup_nb", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_nb';
            let valdata = data.sup_nb;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_al: "sup_al", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_al';
            let valdata = data.sup_al;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_b: "sup_b", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_b';
            let valdata = data.sup_b;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_co: "sup_co", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_co';
            let valdata = data.sup_co;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_pb: "sup_pb", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_pb';
            let valdata = data.sup_pb;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_fe: "sup_fe", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_fe';
            let valdata = data.sup_fe;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_ts: "sup_ts", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_ts';
            let valdata = data.sup_ts;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_ts: "sup_ys", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_ys';
            let valdata = data.sup_ys;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
    {"data": {sup_el: "sup_el", sts_no: "sts_no"},
        "className": "text-left",
        "render": function (data, type, row, meta) {
            let col_name = 'sup_el';
            let valdata = data.sup_el;
            let iddata = `${col_name}_${data.sts_no}`;
            ((valdata == null) ? valdata = "" : valdata = valdata)
            let ret = `<span hidden>${formatQcTest(valdata) }</span><input type='text' class='input-text-value' id='${iddata}' onchange="UpdateQcTestLab('${iddata}','${col_name}');" value='${formatQcTest(valdata)}' />`;
            return ret;
        }
    },
]
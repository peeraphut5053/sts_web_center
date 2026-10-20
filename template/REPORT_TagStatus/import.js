(function () {
    var columns = ['id', 'NC_QTY', 'QC_loc', 'QA_recordDate', 'Tag_Status', 'Issue', 'Minor_cause', 'Main_cause', 'Detail'];
    var rows = [];
    var busy = false;
    var version = 0;
    function message(text) { $('#tagImportMessage').text(text); }
    $('#tagImportModal').on('hide.bs.modal', function (event) {
        if (busy) event.preventDefault();
    }).on('shown.bs.modal', function () {
        $('#tagImportFile').focus();
    });
    $('#tagImportFile').on('change', function () {
        var token = ++version;
        rows = [];
        $('#tagImportSave').prop('disabled', true);
        $('#tagImportPreview').empty();
        var file = this.files[0];
        if (!file) return;
        if (!/\.(xlsx|xls)$/i.test(file.name) || file.size > 10 * 1024 * 1024) {
            message('กรุณาเลือกไฟล์ .xlsx หรือ .xls ขนาดไม่เกิน 10 MB'); return;
        }
        message('กำลังอ่านไฟล์...');
        var reader = new FileReader();
        reader.onerror = function () { message('อ่านไฟล์ไม่สำเร็จ'); };
        reader.onload = function (event) {
            if (token !== version) return;
            try {
                var book = XLSX.read(new Uint8Array(event.target.result), { type: 'array' });
                var sheet = XLSX.utils.sheet_to_json(book.Sheets[book.SheetNames[0]], { header: 1, defval: '', raw: true });
                var headers = (sheet.shift() || []).map(function (value) { return String(value).trim().toLowerCase(); });
                var indexes = columns.map(function (name) {
                    var index = headers.indexOf(name.toLowerCase());
                    if (index < 0 || headers.lastIndexOf(name.toLowerCase()) !== index) throw new Error('หัวคอลัมน์ขาดหรือซ้ำ: ' + name);
                    return index;
                });
                var ids = Object.create(null);
                sheet.forEach(function (cells, index) {
                    if (cells.every(function (value) { return value === ''; })) return;
                    var row = {};
                    columns.forEach(function (name, col) {
                        var value = cells[indexes[col]];
                        if (name === 'QA_recordDate' && typeof value === 'number') {
                            var date = XLSX.SSF.parse_date_code(value, { date1904: !!(book.Workbook && book.Workbook.WBProps && book.Workbook.WBProps.date1904) });
                            if (!date) throw new Error('วันที่ไม่ถูกต้อง แถว ' + (index + 2));
                            value = date.y + '-' + ('0' + date.m).slice(-2) + '-' + ('0' + date.d).slice(-2);
                        }
                        row[name] = value == null ? '' : String(value).trim();
                    });
                    if (!/^\d+$/.test(row.id) || ids[row.id]) throw new Error('id ไม่ถูกต้องหรือซ้ำ แถว ' + (index + 2));
                    ids[row.id] = true;
                    rows.push(row);
                });
                if (!rows.length || rows.length > 1000) throw new Error('ต้องมีข้อมูล 1–1,000 แถว');
                var table = $('<table class="table table-bordered table-condensed"><thead><tr></tr></thead><tbody></tbody></table>');
                columns.forEach(function (name) { $('<th>').text(name).appendTo(table.find('thead tr')); });
                rows.forEach(function (row) {
                    var tr = $('<tr>').appendTo(table.find('tbody'));
                    columns.forEach(function (name) { $('<td>').text(row[name]).appendTo(tr); });
                });
                $('#tagImportPreview').append(table);
                message('พร้อมอัปเดต ' + rows.length + ' แถว (แสดงข้อมูลครบทุกแถว)');
                $('#tagImportSave').prop('disabled', false);
            } catch (error) { rows = []; message(error.message); }
        };
        reader.readAsArrayBuffer(file);
    });
    $('#tagImportSave').on('click', function () {
        if (busy || !rows.length) return;
        busy = true;
        $('#tagImportModal [data-dismiss="modal"]').prop('disabled', true);
        $('#tagImportSave, #tagImportFile').prop('disabled', true);
        message('กำลังตรวจสอบและอัปเดต กรุณารอสักครู่...');
        $.ajax({
            url: '././module/REPORT_TagStatus/import.php', type: 'POST', dataType: 'json',
            data: { rows: JSON.stringify(rows) }
        }).done(function (result) {
            if (!result.success) { message(result.error || 'อัปเดตไม่สำเร็จ'); return; }
            rows = [];
            message('อัปเดตสำเร็จ ' + result.updated + ' แถว');
            $('#tagImportFile').val('');
            $('#tagImportPreview').empty();
            if (hasSearchFilter(getSearchFilters())) ReportAllSearch();
        }).fail(function () {
            rows = [];
            message('ไม่ได้รับผลยืนยันจากเซิร์ฟเวอร์ กรุณาค้นหาตรวจสอบข้อมูลก่อนเลือกไฟล์ใหม่');
        }).always(function () {
            busy = false;
            $('#tagImportModal [data-dismiss="modal"]').prop('disabled', false);
            $('#tagImportFile').prop('disabled', false);
            $('#tagImportSave').prop('disabled', !rows.length);
        });
    });
})();

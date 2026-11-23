(function () {
    var columns = ['id', 'NC_QTY', 'QC_loc', 'QA_recordDate', 'Tag_Status', 'Issue', 'Minor_cause', 'Main_cause', 'Detail'];
    var rows = [];
    var busy = false;
    var version = 0;
    var book = null;
    var sheetSelect = $('#tagImportSheet');
    function clearPreview() {
        rows = [];
        $('#tagImportSave').prop('disabled', true);
        $('#tagImportPreview').empty();
    }
    function clearWorkbook() {
        book = null;
        clearPreview();
        sheetSelect.empty().prop('disabled', true);
        $('#tagImportSheetField').prop('hidden', true);
    }
    function message(text) { $('#tagImportMessage').text(text); }
    $('#tagImportModal').on('hide.bs.modal', function (event) {
        if (busy) event.preventDefault();
    }).on('shown.bs.modal', function () {
        $('#tagImportFile').focus();
    });
    $('#tagImportFile').on('change', function () {
        var token = ++version;
        clearWorkbook();
        var file = this.files[0];
        if (!file) { message('เลือกไฟล์เพื่อแสดงตัวอย่างก่อนอัปเดต'); return; }
        if (!/\.(xlsx|xls)$/i.test(file.name) || file.size > 10 * 1024 * 1024) {
            message('กรุณาเลือกไฟล์ .xlsx หรือ .xls ขนาดไม่เกิน 10 MB'); return;
        }
        message('กำลังอ่านไฟล์...');
        var reader = new FileReader();
        reader.onerror = function () { if (token === version) message('อ่านไฟล์ไม่สำเร็จ'); };
        reader.onload = function (event) {
            if (token !== version) return;
            try {
                book = XLSX.read(new Uint8Array(event.target.result), { type: 'array' });
                if (!book.SheetNames.length) throw new Error('ไม่พบชีตในไฟล์');
                book.SheetNames.forEach(function (name) {
                    $('<option>').val(name).text(name).appendTo(sheetSelect);
                });
                $('#tagImportSheetField').prop('hidden', false);
                sheetSelect.prop('disabled', book.SheetNames.length < 2).val(book.SheetNames[0]);
                previewSheet(book.SheetNames[0]);
            } catch (error) { clearWorkbook(); message(error.message); }
        };
        reader.readAsArrayBuffer(file);
    });
    function renderPreview(headers, data) {
        var table = $('<table class="table table-bordered table-condensed"><thead><tr></tr></thead><tbody></tbody></table>');
        headers.forEach(function (name) { $('<th>').text(name).appendTo(table.find('thead tr')); });
        data.forEach(function (cells) {
            var tr = $('<tr>').appendTo(table.find('tbody'));
            headers.forEach(function (_, index) {
                $('<td>').text(cells[index] == null ? '' : cells[index]).appendTo(tr);
            });
        });
        $('#tagImportPreview').append(table);
    }

    function previewSheet(sheetName) {
        clearPreview();
        if (!book || !book.Sheets[sheetName]) return;
        try {
            var sheet = XLSX.utils.sheet_to_json(book.Sheets[sheetName], { header: 1, defval: '', raw: true });
            var rawHeaders = sheet.shift() || [];
            var headers = rawHeaders.map(function (value) { return String(value).trim().toLowerCase(); });
            var missing = [];
            var duplicates = [];
            var indexes = columns.map(function (name) {
                var key = name.toLowerCase();
                var index = headers.indexOf(key);
                if (index < 0) missing.push(name);
                else if (headers.lastIndexOf(key) !== index) duplicates.push(name);
                return index;
            });
            if (missing.length || duplicates.length) {
                var sample = sheet.slice(0, 1000);
                var width = sample.reduce(function (max, cells) {
                    return Math.max(max, cells.length);
                }, rawHeaders.length);
                var displayHeaders = Array.from({ length: Math.min(width, 50) }, function (_, index) {
                    return String(rawHeaders[index] == null ? '' : rawHeaders[index]).trim() || 'คอลัมน์ ' + (index + 1);
                });
                if (displayHeaders.length) renderPreview(displayHeaders, sample);

                var problems = [];
                if (missing.length) problems.push('ขาด: ' + missing.join(', '));
                if (duplicates.length) problems.push('ซ้ำ: ' + duplicates.join(', '));
                var limit = sheet.length > sample.length || width > displayHeaders.length
                    ? ' · แสดงตัวอย่างสูงสุด 1,000 แถว และ 50 คอลัมน์'
                    : '';
                message('ชีต ' + sheetName + ' · ' + problems.join(' · ') + ' · ยังอัปเดตไม่ได้' + limit);
                return;
            }
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
            renderPreview(columns, rows.map(function (row) {
                return columns.map(function (name) { return row[name]; });
            }));
            message('ชีต ' + sheetName + ' · พร้อมอัปเดต ' + rows.length + ' แถว (แสดงข้อมูลครบทุกแถว)');
            $('#tagImportSave').prop('disabled', false);
        } catch (error) { clearPreview(); message('ชีต ' + sheetName + ' · ' + error.message); }
    }
    sheetSelect.on('change', function () {
        if (!busy) previewSheet(this.value);
    });
    $('#tagImportSave').on('click', function () {
        if (busy || !rows.length) return;
        busy = true;
        $('#tagImportModal [data-dismiss="modal"]').prop('disabled', true);
        $('#tagImportSave, #tagImportFile, #tagImportSheet').prop('disabled', true);
        message('กำลังตรวจสอบและอัปเดต กรุณารอสักครู่...');
        $.ajax({
            url: '././module/REPORT_TagStatus/import.php', type: 'POST', dataType: 'json',
            data: { rows: JSON.stringify(rows) }
        }).done(function (result) {
            if (!result.success) { message(result.error || 'อัปเดตไม่สำเร็จ'); return; }
            clearWorkbook();
            message('อัปเดตสำเร็จ ' + result.updated + ' แถว');
            $('#tagImportFile').val('');
            if (hasSearchFilter(getSearchFilters())) ReportAllSearch();
        }).fail(function () {
            clearWorkbook();
            $('#tagImportFile').val('');
            message('ไม่ได้รับผลยืนยันจากเซิร์ฟเวอร์ กรุณาค้นหาตรวจสอบข้อมูลก่อนเลือกไฟล์ใหม่');
        }).always(function () {
            busy = false;
            $('#tagImportModal [data-dismiss="modal"]').prop('disabled', false);
            $('#tagImportFile').prop('disabled', false);
            sheetSelect.prop('disabled', !book || book.SheetNames.length < 2);
            $('#tagImportSave').prop('disabled', !rows.length);
        });
    });
})();

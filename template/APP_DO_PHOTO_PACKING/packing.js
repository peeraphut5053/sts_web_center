(function ($) {
    'use strict';
    var root = $('#packing-app');
    var base = 'module/APP_DO_PHOTO_PACKING/';
    var placeholder = 'template/APP_DO_PHOTO_PACKING/add-image.png';
    var loaded = {};
    var activeTag = '';
    var busy = false;
    var table = null;
    var modalPhoto = null;
    var user = typeof GetUserProp === 'function' ? GetUserProp('login_username') : '';

    function text(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (char) {
            return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[char];
        });
    }
    function photoUrl(filename) { return base + 'view_file.php?file=' + encodeURIComponent(filename); }
    function status(message, kind) {
        $('#packing-status').prop('hidden', !message).removeClass('is-error is-loading').addClass(kind || '').text(message || '');
    }
    function setBusy(value) {
        busy = value;
        root.toggleClass('is-busy', value);
        root.find('.packing-tabs button, #btnSearch, #packing-clear-tag, #packing-modal-file').prop('disabled', value);
        $('#packing-delete-image').prop('disabled', value || !modalPhoto || !modalPhoto.filename);
        root.find('[data-slot]').prop('disabled', value || !activeTag);
    }
    async function request(action, params) {
        var form = new URLSearchParams(Object.assign({load: action}, params || {}));
        var response = await fetch(base + 'data.php', {method: 'POST', body: form});
        var result = await response.json();
        if (!response.ok || (result && result.success === false)) throw new Error(result.message || 'Request failed');
        return result;
    }
    function errorMessage(error) {
        status(error.message || 'Request failed', 'is-error');
        if ($('#ProductModal').hasClass('in')) $('#packing-modal-feedback').text(error.message || 'Request failed').prop('hidden', false);
    }
    function renderUpload(row) {
        row = row || {};
        $('#LOTtxt').val(row.lot || '');
        $('#remark').val(row.remark || '').prop('disabled', !activeTag);
        ['file_name_1', 'file_name_2'].forEach(function (slot) {
            $('#' + slot).attr('src', row[slot] ? photoUrl(row[slot]) : placeholder);
        });
        root.find('[data-slot]').prop('disabled', !activeTag || busy);
    }
    async function searchTag() {
        if (busy) return;
        var tag = $('#tag_id').val().trim();
        if (!tag || tag === activeTag) return;
        setBusy(true);
        status('Loading Tag ' + tag + '...', 'is-loading');
        try {
            var rows = await request('SearchTagDetail', {tag_id: tag, create_by: user});
            if (!Array.isArray(rows) || !rows.length) throw new Error('Tag barcode not found');
            activeTag = tag;
            $('#tag_id').prop('readonly', true);
            renderUpload(rows[0]);
            status('Tag ' + tag);
        } catch (error) {
            activeTag = '';
            $('#tag_id').prop('readonly', false);
            renderUpload();
            errorMessage(error);
        } finally { setBusy(false); }
    }
    async function upload(file, tag, slot) {
        if (busy || !file) return;
        if (!tag) { status('กรุณาสแกน Tag Barcode', 'is-error'); return; }
        setBusy(true);
        status('Uploading ' + file.name + '...', 'is-loading');
        $('#packing-modal-feedback').prop('hidden', true);
        try {
            var form = new FormData();
            form.set('tag_id', tag); form.set('slot', slot); form.set('create_by', user); form.set('file', file);
            var response = await fetch(base + 'upload.php', {method: 'POST', body: form});
            var result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Upload failed');
            if (activeTag === tag) $('#' + slot).attr('src', photoUrl(result.filename));
            if (modalPhoto && modalPhoto.tag === tag && modalPhoto.slot === slot) {
                modalPhoto.filename = result.filename;
                $('#modal_img').attr('src', photoUrl(result.filename)).prop('hidden', false);
                $('#packing-modal-empty').prop('hidden', true);
                $('#packing-delete-image').prop('disabled', false);
            }
            if (table) await searchList(true);
            status(result.warning || 'อัปโหลดสำเร็จ', result.warning ? 'is-error' : '');
        } catch (error) { errorMessage(error); }
        finally { setBusy(false); root.find('input[type=file]').val(''); }
    }
    function filters() {
        return {tag_id: ($('#tag_id_list').val() || '').trim(), sts_no: ($('#sts_no_list').val() || '').trim(), size_list: ($('#size_list').val() || '').trim(), job: ($('#job').val() || '').split('+')[0].trim()};
    }
    function hasFilters(params) {
        if (Object.keys(params).some(function (key) { return params[key] !== ''; })) return true;
        status('กรุณาระบุ Job, Tag, STS NO หรือ Size อย่างน้อย 1 ช่อง', 'is-error');
        $('#job').trigger('focus');
        return false;
    }
    function photoCell(row, slot) {
        var filename = row[slot] || '';
        return '<button type="button" class="packing-thumb-button" data-photo-slot="' + slot + '" data-tag="' + text(row.tag_id) + '" data-file="' + text(filename) + '" aria-label="Open ' + slot + ' for Tag ' + text(row.tag_id) + '"><img class="packing-thumb" alt="' + slot + '" src="' + (filename ? photoUrl(filename) : placeholder) + '"></button>';
    }
    function initList() {
        table = $('#tblReport').DataTable({
            data: [], paging: false, searching: false, scrollY: '48vh', scrollCollapse: true, scrollX: true, autoWidth: false,
            language: {emptyTable: 'กรุณาระบุเงื่อนไขอย่างน้อย 1 ช่อง แล้วกดค้นหา', zeroRecords: 'ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา'},
            columns: [
                {data: null, width: '54%', render: function (row, type) {
                    if (type !== 'display') return row.tag_id;
                    return '<strong>Tag ' + text(row.tag_id) + '</strong>' + [['lot', row.lot], ['sts no', row.sts_no], ['create_by', row.create_by], ['remark', row.remark]].map(function (item) {
                        return '<div class="packing-detail-line">' + item[0] + ' : <strong>' + text(item[1]) + '</strong></div>';
                    }).join('');
                }},
                {data: null, orderable: false, className: 'text-center', render: function (row, type) { return type === 'display' ? photoCell(row, 'file_name_1') : (row.file_name_1 || ''); }},
                {data: null, orderable: false, className: 'text-center', render: function (row, type) { return type === 'display' ? photoCell(row, 'file_name_2') : (row.file_name_2 || ''); }},
                {data: 'tag_id', orderable: false, className: 'text-center', render: function (tag, type) { return type === 'display' ? '<button type="button" class="packing-delete-tag" data-delete-tag="' + text(tag) + '"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</button>' : tag; }}
            ]
        });
    }
    async function searchList(background) {
        if (busy && !background) return;
        var params = filters();
        if (!hasFilters(params)) return;
        if (!background) { setBusy(true); status('Loading...', 'is-loading'); }
        try {
            var rows = await request('GetImgTagUpload', params);
            table.settings()[0].oLanguage.sEmptyTable = 'ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา';
            table.clear().rows.add(rows || []).draw();
            table.columns.adjust();
            if (!background) status('');
        } catch (error) { if (background) throw error; errorMessage(error); }
        finally { if (!background) setBusy(false); }
    }
    async function switchTab(name) {
        if (busy) return;
        $('#ProductModal').modal('hide');
        root.find('.packing-content').prop('hidden', true);
        $('#content_' + name).prop('hidden', false);
        root.find('.packing-tabs button').removeClass('active').attr('aria-selected', 'false');
        $('#tab_' + name).addClass('active').attr('aria-selected', 'true');
        status('');
        if (!loaded[name]) {
            setBusy(true);
            try {
                var response = await fetch(base + 'render.php', {method: 'POST', body: new URLSearchParams({pageHtml: 'content_' + name})});
                if (!response.ok) throw new Error('Cannot load page');
                $('#content_' + name).html(await response.text());
                loaded[name] = true;
                if (name === 'upload') { $('#username').val(user); activeTag = ''; }
                else initList();
            } catch (error) { errorMessage(error); }
            finally { setBusy(false); }
            if (name === 'upload') { $('#tag_id').trigger('focus'); await searchTag(); }
        }
        if (name === 'list' && table) table.columns.adjust();
    }

    root.on('click', '.packing-tabs button', function () { switchTab(this.id === 'tab_list' ? 'list' : 'upload'); });
    root.on('submit', '#packing-tag-form', function (event) { event.preventDefault(); searchTag(); });
    root.on('change', '#tag_id', searchTag);
    root.on('click', '#packing-clear-tag', async function () {
        if (busy) return;
        setBusy(true);
        try { await request('clearTagBarcode'); activeTag = ''; $('#tag_id').val('').prop('readonly', false).trigger('focus'); renderUpload(); status(''); }
        catch (error) { errorMessage(error); }
        finally { setBusy(false); }
    });
    root.on('change', '[data-slot]', function () { upload(this.files[0], activeTag, $(this).attr('data-slot')); });
    root.on('change', '#remark', async function () {
        if (!activeTag || busy) return;
        setBusy(true);
        try { await request('updateRemark', {tag_id: activeTag, remark: this.value}); status('บันทึกสำเร็จ'); }
        catch (error) { errorMessage(error); }
        finally { setBusy(false); }
    });
    root.on('submit', '#packing-search-form', function (event) { event.preventDefault(); searchList(false); });
    root.on('click', '#btnPrint', function () {
        var params = filters();
        if (!hasFilters(params)) return;
        window.open('template/APP_DO_PHOTO_PACKING/print_tag_list.html?' + new URLSearchParams(Object.assign(params, {size: params.size_list})), '_blank');
    });
    root.on('click', '[data-photo-slot]', function () {
        if (busy) return;
        modalPhoto = {tag: $(this).attr('data-tag'), slot: $(this).attr('data-photo-slot'), filename: $(this).attr('data-file')};
        $('#packing-modal-title').text('Tag ' + modalPhoto.tag + ' / Image ' + modalPhoto.slot.slice(-1));
        $('#packing-modal-feedback').prop('hidden', true);
        $('#modal_img').attr('src', modalPhoto.filename ? photoUrl(modalPhoto.filename) : placeholder).prop('hidden', !modalPhoto.filename);
        $('#packing-modal-empty').prop('hidden', !!modalPhoto.filename);
        $('#packing-delete-image').prop('disabled', !modalPhoto.filename);
        $('#ProductModal').modal('show');
    });
    root.on('change', '#packing-modal-file', function () { if (modalPhoto) upload(this.files[0], modalPhoto.tag, modalPhoto.slot); });
    root.on('click', '#packing-delete-image', async function () {
        if (busy || !modalPhoto || !modalPhoto.filename || !confirm('ยืนยันการลบรูปภาพ?')) return;
        setBusy(true);
        try {
            await request('DeleteImg', {tag_id: modalPhoto.tag, file_name_x: modalPhoto.slot});
            if (activeTag === modalPhoto.tag) $('#' + modalPhoto.slot).attr('src', placeholder);
            $('#ProductModal').modal('hide');
            await searchList(true);
            status('ลบสำเร็จ');
        } catch (error) { errorMessage(error); }
        finally { setBusy(false); }
    });
    root.on('click', '[data-delete-tag]', async function () {
        if (busy || !confirm('คุณต้องการที่จะลบ Tag id นี้หรือไม่?')) return;
        var tag = $(this).attr('data-delete-tag');
        setBusy(true);
        try {
            await request('DeleteTagId', {tag_id: tag});
            if (activeTag === tag) { activeTag = ''; $('#tag_id').val('').prop('readonly', false); renderUpload(); }
            await searchList(true); status('ลบสำเร็จ');
        } catch (error) { errorMessage(error); }
        finally { setBusy(false); }
    });
    root[0].addEventListener('error', function (event) {
        if (event.target.tagName === 'IMG' && event.target.getAttribute('src') !== placeholder) {
            event.target.alt = 'Photo unavailable';
            event.target.src = placeholder;
        }
    }, true);
    switchTab('upload');
})(jQuery);

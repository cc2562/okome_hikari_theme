(function ($) {
    $(document).ready(function () {
        function openFormModal(title, fields, onSubmit) {
            var modal = document.getElementById('rice-editor-modal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'rice-editor-modal';
                modal.style.position = 'fixed';
                modal.style.inset = '0';
                modal.style.display = 'none';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.style.background = 'rgba(0,0,0,0.45)';
                modal.style.zIndex = '9999';
                var panel = document.createElement('div');
                panel.style.background = '#fff';
                panel.style.color = '#000';
                panel.style.width = '360px';
                panel.style.maxWidth = '90%';
                panel.style.borderRadius = '8px';
                panel.style.boxShadow = '0 10px 20px rgba(0,0,0,.2)';
                panel.innerHTML = '<div id="rice-editor-modal-title" style="padding:12px;font-weight:600;"></div>' +
                    '<div id="rice-editor-modal-body" style="padding:12px;"></div>' +
                    '<div style="padding:12px;display:flex;justify-content:flex-end;gap:8px;">' +
                    '<button id="rice-editor-modal-cancel" type="button" style="padding:6px 12px;border-radius:6px;">取消</button>' +
                    '<button id="rice-editor-modal-ok" type="button" style="padding:6px 12px;background:#1677ff;color:#fff;border-radius:6px;">插入</button>' +
                    '</div>';
                modal.appendChild(panel);
                document.body.appendChild(modal);
                modal.addEventListener('click', function (e) { if (e.target.id === 'rice-editor-modal') modal.style.display = 'none'; });
                document.getElementById('rice-editor-modal-cancel').addEventListener('click', function () { modal.style.display = 'none'; });
            }
            document.getElementById('rice-editor-modal-title').textContent = title || '';
            var body = document.getElementById('rice-editor-modal-body');
            body.innerHTML = '';
            var inputs = {};
            fields.forEach(function (f) {
                var wrap = document.createElement('div');
                var label = document.createElement('label');
                label.textContent = f.label || '';
                label.style.display = 'block';
                label.style.marginBottom = '6px';
                if (f.required) {
                    var req = document.createElement('span');
                    req.textContent = '（必填）';
                    req.style.color = '#d93025';
                    req.style.marginLeft = '6px';
                    label.appendChild(req);
                }
                wrap.appendChild(label);
                var input = document.createElement('input');
                input.type = f.type || 'text';
                input.id = f.id;
                input.style.width = '100%';
                input.style.padding = '8px';
                input.style.border = '1px solid #ddd';
                input.style.borderRadius = '6px';
                if (f.placeholder) input.placeholder = f.placeholder;
                wrap.appendChild(input);
                body.appendChild(wrap);
                inputs[f.id] = input;
            });
            modal.style.display = 'flex';
            setTimeout(function () { inputs[fields[0].id].focus(); }, 0);
            var ok = document.getElementById('rice-editor-modal-ok');
            ok.onclick = function () {
                var values = {};
                fields.forEach(function (f) { values[f.id] = (inputs[f.id].value || '').trim(); });
                for (var i = 0; i < fields.length; i++) {
                    var f = fields[i];
                    if (f.required && !values[f.id]) { inputs[f.id].focus(); return; }
                }
                if (typeof onSubmit === 'function') onSubmit(values, modal);
            };
        }
        function insertAtCursor(text) {
            var textarea = document.getElementById('text');
            if (!textarea) return;
            if (typeof textarea.setRangeText === 'function') {
                var start = textarea.selectionStart;
                textarea.setRangeText(text);
                textarea.selectionStart = start + text.length;
                textarea.selectionEnd = textarea.selectionStart;
                textarea.focus();
            } else {
                textarea.value = (textarea.value || '') + text;
                textarea.focus();
            }
        }
        function wrapSelection(prefix, suffix) {
            var textarea = document.getElementById('text');
            if (!textarea) return;
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            if (start == null || end == null) return;

            var sel = textarea.value.substring(start, end);
            var wrapped;

            // 如果没有选中文本，直接插入标签对
            if (end <= start || sel.length === 0) {
                wrapped = (prefix || '') + (suffix || '');
            } else {
                // 如果有选中文本，用标签包裹选中的文本
                wrapped = (prefix || '') + sel + (suffix || '');
            }

            if (typeof textarea.setRangeText === 'function') {
                textarea.setRangeText(wrapped);
                // 如果没有选中文本，将光标放在标签中间
                if (end <= start || sel.length === 0) {
                    textarea.selectionStart = start + (prefix || '').length;
                    textarea.selectionEnd = textarea.selectionStart;
                } else {
                    textarea.selectionStart = start + wrapped.length;
                    textarea.selectionEnd = textarea.selectionStart;
                }
                textarea.focus();
            } else {
                textarea.value = textarea.value.slice(0, start) + wrapped + textarea.value.slice(end);
                // 如果没有选中文本，将光标放在标签中间
                if (end <= start || sel.length === 0) {
                    textarea.selectionStart = start + (prefix || '').length;
                    textarea.selectionEnd = textarea.selectionStart;
                } else {
                    textarea.selectionStart = start + wrapped.length;
                    textarea.selectionEnd = textarea.selectionStart;
                }
                textarea.focus();
            }
        }
        function addButton(def) {
            var el = $(`<li class="wmd-button" id="${def.id}" title="${def.title}">${def.title}</li>`);
            el.on('click', function () {
                if (def.modal === false && typeof def.action === 'function') {
                    def.action();
                } else {
                    openFormModal(def.title, def.fields, function (values, modal) {
                        var text = def.build(values);
                        insertAtCursor(text);
                        modal.style.display = 'none';
                    });
                }
            });
            $('#wmd-button-row').append(el);
        }

        // 等待编辑器加载完成
        function initEditorButtons() {
            var buttonRow = $('#wmd-button-row');
            if (buttonRow.length === 0) {
                // 如果按钮容器不存在，延迟重试
                setTimeout(initEditorButtons, 100);
                return;
            }

            // 如果按钮已经添加过，不再重复添加
            if ($('#wmd-meting-button').length > 0) {
                return;
            }

            var buttons = [
                {
                    id: 'wmd-meting-button',
                    title: '插入在线音乐',
                    fields: [
                        { id: 'musicId', label: '音乐ID', type: 'text', required: true },
                        { id: 'containerId', label: '容器ID', type: 'text', required: true }
                    ],
                    build: function (v) { return `[meting_single id="${v.containerId}"]${v.musicId}[/meting_single]`; }
                },
                {
                    id: 'wmd-meting-list-button',
                    title: '歌单',
                    fields: [
                        { id: 'listId', label: '歌单ID', type: 'text', required: true },
                        { id: 'containerId', label: '容器ID', type: 'text', required: true }
                    ],
                    build: function (v) { return `[meting_list id="${v.containerId}"]${v.listId}[/meting_list]`; }
                },
                {
                    id: 'wmd-local-music-button',
                    title: '本地音乐',
                    fields: [
                        { id: 'containerId', label: '容器ID', type: 'text', required: true },
                        { id: 'musicUrl', label: '音乐URL', type: 'text', required: true },
                        { id: 'musicArtist', label: '音乐作者', type: 'text', required: false },
                        { id: 'musicTitle', label: '音乐标题', type: 'text', required: true },
                        { id: 'musicCover', label: '音乐封面', type: 'text', required: false }
                    ],
                    build: function (v) { return `[aplayer id="${v.containerId}" url="${v.musicUrl}" artist="${v.musicArtist}" pic="${v.musicCover}"]${v.musicTitle}[/aplayer]`; }
                },
                {
                    id: 'wmd-notice-button',
                    title: '提示',
                    modal: false,
                    action: function () { wrapSelection('[notice]', '[/notice]'); }
                },
            ];
            buttons.forEach(addButton);
        }

        // 初始化按钮
        initEditorButtons();
    });

})(jQuery);

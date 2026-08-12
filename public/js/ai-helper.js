$(function () {

    // ─── Language list ────────────────────────────────────────────────────────
    var LANGUAGES = [
        'Arabic', 'Chinese (Simplified)', 'Chinese (Traditional)', 'Dutch',
        'English', 'French', 'German', 'Greek', 'Hebrew', 'Hindi', 'Indonesian', 'Italian',
        'Japanese', 'Korean', 'Malay', 'Persian', 'Polish', 'Portuguese',
        'Russian', 'Spanish', 'Swahili', 'Thai', 'Turkish', 'Ukrainian',
        'Urdu', 'Vietnamese'
    ];

    // ─── Build picker once, append to body ───────────────────────────────────
    var $picker = $(
        '<div id="ai-lang-picker" style="display:none;position:absolute;z-index:9999;' +
            'background:#fff;border:1px solid #ccc;border-radius:6px;padding:10px;' +
            'box-shadow:0 4px 12px rgba(0,0,0,.15);min-width:210px;">' +
            '<div style="margin-bottom:6px;font-size:12px;color:#555;font-weight:600;">Select target language</div>' +
            '<select id="ai-lang-select" class="form-control form-control-sm" style="margin-bottom:8px;">' +
                LANGUAGES.map(function (l) { return '<option value="' + l + '">' + l + '</option>'; }).join('') +
            '</select>' +
            '<input id="ai-lang-custom" type="text" class="form-control form-control-sm" ' +
                'placeholder="Or type any language..." style="margin-bottom:8px;">' +
            '<button id="ai-lang-confirm" class="btn btn-accent btn-sm" style="width:100%;">' +
                '<i class="fas fa-globe"></i> Translate' +
            '</button>' +
        '</div>'
    );

    $('body').append($picker);

    // Close picker on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#ai-lang-picker, .ai-action[data-type="translate"]').length) {
            $picker.hide();
        }
    });

    // ─── Main click handler ───────────────────────────────────────────────────
    $(document).on('click', '.ai-action', function (e) {
        e.stopPropagation();

        var btn       = $(this);
        var card      = btn.closest('.question-card');
        var type      = btn.data('type');
        var answer    = $.trim(card.find('.answer').val());
        var limitObj  = getLimit(card.data('limit'));
        var statusBox = card.find('.ai-status');
        var explainBox = card.find('.ai-explain-output');

        statusBox.hide();

        // Guard: polish/translate need existing text
        if ((type === 'polish' || type === 'translate') && !answer) {
            showStatus(statusBox, '⚠️ Please write a description before using this action.', 'error');
            return;
        }

        // Translate: show language picker positioned below the button
        if (type === 'translate') {
            var offset = btn.offset();
            $picker.css({ top: offset.top + btn.outerHeight() + 6, left: offset.left });
            $picker.show();
            // Stash context for the confirm handler
            $picker.data({ card: card, btn: btn, answer: answer, limitObj: limitObj, statusBox: statusBox });
            return;
        }

        // Build draft_answer prompt from form context
        var text = answer;
        if (type === 'draft_answer') {
            var sponsorId  = $('#id_charity_id').val();
            var fundId     = $('#id_fund_id').val();
            var categoryId = $('#category').val();
            var details = [
                sponsorId  != 0 && ('Sponsor: '       + $('#id_charity_id option:selected').text()),
                fundId     != 0 && ('Fund: '           + $('#id_fund_id option:selected').text()),
                categoryId != 0 && ('Ticket Type: '    + $('#category option:selected').text()),
                $('#title').val() && ('Ticket Subject: ' + $('#title').val())
            ].filter(Boolean);

            text = details.length
                ? 'Generate a clear and professional draft description. Return only the answer.\nDetails: ' + details.join(', ')
                : 'Generate a general professional draft description for a support ticket. Return only the answer.';
        }

        fireRequest(type, text, null, limitObj, card, btn, statusBox, explainBox);
    });

    // ─── Translate confirm ────────────────────────────────────────────────────
    $(document).on('click', '#ai-lang-confirm', function () {
        var lang = $.trim($('#ai-lang-custom').val()) || $('#ai-lang-select').val();
        if (!lang) return;

        var card       = $picker.data('card');
        var btn        = $picker.data('btn');
        var answer     = $picker.data('answer');
        var limitObj   = $picker.data('limitObj');
        var statusBox  = $picker.data('statusBox');
        var explainBox = card.find('.ai-explain-output');

        $picker.hide();
        $('#ai-lang-custom').val('');

        showStatus(statusBox, '⏳ Translating to ' + lang + '...', 'loading');
        fireRequest('translate', answer, lang, limitObj, card, btn, statusBox, explainBox);
    });

    // ─── AJAX core ───────────────────────────────────────────────────────────
    function fireRequest(type, text, language, limitObj, card, btn, statusBox, explainBox) {

        var statusMessages = {
            explain_question : '⏳ Explaining the question...',
            draft_answer     : '⏳ Generating draft response...',
            polish           : '⏳ Improving your response...'
        };

        if (type !== 'translate') {
            type === 'explain_question'
                ? explainBox.html(statusMessages[type]).show()
                : showStatus(statusBox, statusMessages[type], 'loading');
        }

        btn.css('pointer-events', 'none');

        var data = {
            _token : $('meta[name="csrf-token"]').attr('content'),
            type   : type,
            text   : text
        };

        if (limitObj) {
            data.limit_type  = limitObj.type;
            data.limit_value = limitObj.value;
        }

        if (type === 'translate' && language) {
            data.language = language;
        }

        $.post('/ai/process', data)
            .done(function (res) {
                if (type === 'explain_question') {
                    showExplain(explainBox, res.result);
                } else {
                    card.find('.answer').summernote('code', res.result);
                    hideStatus(statusBox);
                }
            })
            .fail(function () {
                type === 'explain_question'
                    ? showExplain(explainBox, '❌ Failed to explain question.')
                    : showStatus(statusBox, '❌ Action failed. Please try again.', 'error');
            })
            .always(function () {
                btn.css('pointer-events', 'auto');
            });
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    function getLimit(limitText) {
        if (!limitText) return null;
        limitText = limitText.toLowerCase();
        return {
            type  : limitText.includes('word') ? 'words' : 'chars',
            value : parseInt(limitText) || 0
        };
    }

    function showStatus(box, msg, type) {
        box.removeClass('loading error').addClass(type || 'loading').html(msg).show();
    }

    function hideStatus(box) {
        box.hide().removeClass('loading error');
    }

    function showExplain(box, msg) {
        box.html(msg).show();
    }

});

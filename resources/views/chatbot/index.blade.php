@extends($layout)

@section('content')
<div class="container-fluid" style="padding: 24px 16px;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

        @if ($fallbackState)
        <div class="alert alert-warning" role="alert">The AI Assistant is temporarily unavailable. Please contact your administrator.</div>
        @else
        {{-- Chat Shell --}}
        <div id="chatbot-shell" style="
            display: grid;
            grid-template-rows: auto 1fr auto;
            height: calc(100vh - 120px);
            background: #ffffff;
            border: 1px solid #d8dee8;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(23,32,42,0.08);
        ">

            {{-- Header --}}
            <div style="
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 20px;
                border-bottom: 1px solid #d8dee8;
                background: #f6f7f9;
            ">
                <div>
                    <strong style="font-size: 1.05rem;">AI Assistant</strong>
                    <div style="font-size: 0.85rem; color: #637083; margin-top: 2px;">
                        {{ $chatbotUser['display_name'] ?? auth()->user()->username }}
                        &nbsp;&bull;&nbsp;
                        Contact #{{ $chatbotUser['contact_id'] ?? '—' }}
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                    @foreach ($chatbotUser['roles'] ?? [] as $role)
                        <span style="
                            display: inline-flex;
                            align-items: center;
                            padding: 3px 10px;
                            border-radius: 999px;
                            background: #e1f5f1;
                            color: #0f766e;
                            font-size: 0.8rem;
                            font-weight: 600;
                            text-transform: capitalize;
                        ">{{ str_replace('_', ' ', $role) }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Messages --}}
            <div id="chatbot-messages" style="
                overflow-y: auto;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 10px;
            " aria-live="polite">
                <div class="chatbot-msg chatbot-msg--bot">
                    Hello! I'm your AI assistant. I'm here to help with information from our philanthropy platform. How can I help today?
                </div>

                {{-- Starter Capability Boxes --}}
                <div id="starter-grid"
                     class="starter-grid {{ $activeTAndC ? 'starter-grid--disabled' : '' }}"
                     role="group"
                     aria-label="Quick actions">
                    <button type="button" class="starter-box" data-prompt="Show my funds" aria-label="Show my funds">
                        💰 Show funds & balances
                    </button>
                    <button type="button" class="starter-box" data-prompt="Search a nonprofit" aria-label="Search a nonprofit">
                        🔍 Search for a nonprofit organization
                    </button>
                    <button type="button" class="starter-box" data-prompt="Show my gift history" aria-label="Show my gift history">
                        📜 View gift history
                    </button>
                    <button type="button" class="starter-box" data-prompt="Show my grant history" aria-label="Show my grant history">
                        📋 View grant history
                    </button>
                    <button type="button" class="starter-box" data-prompt="Show my pending grants" aria-label="Show my pending grants">
                        ⏳ Check pending grants
                    </button>
                </div>
            </div>

            {{-- Accessibility live region for screen readers --}}
            <div id="chatbot-live-region" class="sr-only" aria-live="polite" aria-atomic="true"></div>

            {{-- Composer --}}
            <form id="chatbot-form" style="
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 10px;
                padding: 14px 16px;
                border-top: 1px solid #d8dee8;
                background: #f6f7f9;
            ">
                @csrf
                <textarea
                    id="chatbot-input"
                    {{ $activeTAndC ? 'disabled' : '' }}
                    placeholder="Ask a question..."
                    rows="1"
                    style="
                        resize: none;
                        border: 1px solid #d8dee8;
                        border-radius: 6px;
                        padding: 10px 12px;
                        font-size: 0.95rem;
                        font-family: inherit;
                        line-height: 1.4;
                        max-height: 120px;
                        overflow-y: auto;
                    "
                ></textarea>
                <button
                    id="chatbot-send"
                    type="submit"
                    {{ $activeTAndC ? 'disabled' : '' }}
                    class="btn btn-primary"
                    style="min-width: 80px; align-self: flex-end;"
                >Send</button>
            </form>

        </div>{{-- end shell --}}

        @if ($activeTAndC)
        <div class="modal fade show" id="tcModal" tabindex="-1" role="dialog"
             aria-labelledby="tcModalLabel" aria-modal="true"
             style="display:block; background:rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tcModalLabel">
                            {{ $activeTAndC->title }}
                        </h5>
                        {{-- No close button — intentional (Requirement 1.7) --}}
                    </div>
                    <div class="modal-body" style="max-height:60vh; overflow-y:auto;">
                        {!! $activeTAndC->content !!}
                    </div>
                    <div class="modal-footer" style="display:block; padding: 16px 20px;">
                        <form method="POST" action="{{ route('chatbot.acceptTerms') }}">
                            @csrf
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox"
                                       id="tc-agree-checkbox" name="agreed">
                                <label class="form-check-label" for="tc-agree-checkbox">
                                    I have read and agree to the Terms &amp; Conditions of the AI Assistant
                                </label>
                            </div>
                            <div class="text-center" style="display:flex; justify-content:center; gap:12px;">
                                <button type="button" class="btn btn-secondary" id="tc-cancel-btn"
                                        style="font-weight:500;">
                                    Cancel
                                </button>
                                <button type="submit" id="tc-agree-btn"
                                        class="btn btn-primary" disabled>
                                    Agree &amp; Proceed
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @endif

    </div>
    </div>
</div>
@endsection

@section('footer-scripts')
{{-- Markdown rendering: marked.js (parser) + DOMPurify (XSS protection) --}}
<script src="https://cdn.jsdelivr.net/npm/marked@15.0.4/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.2.4/dist/purify.min.js"></script>

<style>
    /* ─── Base message bubble ─── */
    .chatbot-msg {
        max-width: min(780px, 92%);
        padding: 10px 14px;
        border-radius: 8px;
        line-height: 1.6;
        font-size: 0.93rem;
        word-break: break-word;
    }
    .chatbot-msg--user {
        align-self: flex-end;
        background: #dff3ee;
        margin-left: auto;
        white-space: pre-wrap;
    }
    .chatbot-msg--bot {
        align-self: flex-start;
        background: #eef2f7;
    }
    .chatbot-msg--error {
        align-self: flex-start;
        background: #fde8e8;
        color: #b42318;
        white-space: pre-wrap;
    }
    #chatbot-send:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }
    #chatbot-send.is-loading {
        pointer-events: none;
        opacity: 0.7;
        min-width: 80px;
    }
    #chatbot-send.is-loading .spinner-border {
        width: 16px;
        height: 16px;
        border-width: 2px;
    }
    #tc-cancel-btn {
        min-width: 100px;
        opacity: 1;
    }
    #tc-cancel-btn:hover {
        background: #5a6268;
        border-color: #545b62;
        color: #fff;
    }

    /* ─── Markdown typography inside bot messages ─── */
    .chatbot-msg--bot h1,
    .chatbot-msg--bot h2,
    .chatbot-msg--bot h3,
    .chatbot-msg--bot h4,
    .chatbot-msg--bot h5,
    .chatbot-msg--bot h6 {
        margin: 0.6em 0 0.3em 0;
        font-weight: 600;
        line-height: 1.3;
    }
    .chatbot-msg--bot h1 { font-size: 1.2em; }
    .chatbot-msg--bot h2 { font-size: 1.1em; }
    .chatbot-msg--bot h3 { font-size: 1.05em; }
    .chatbot-msg--bot h4,
    .chatbot-msg--bot h5,
    .chatbot-msg--bot h6 { font-size: 1em; }

    .chatbot-msg--bot p {
        margin: 0.4em 0;
    }
    .chatbot-msg--bot p:first-child {
        margin-top: 0;
    }
    .chatbot-msg--bot p:last-child {
        margin-bottom: 0;
    }

    .chatbot-msg--bot ul,
    .chatbot-msg--bot ol {
        margin: 0.4em 0;
        padding-left: 1.4em;
    }
    .chatbot-msg--bot li {
        margin: 0.2em 0;
    }

    .chatbot-msg--bot strong {
        font-weight: 600;
    }

    .chatbot-msg--bot a {
        color: #0f766e;
        text-decoration: underline;
    }

    /* ─── Tables ─── */
    .chatbot-msg--bot .md-table-wrap {
        overflow-x: auto;
        margin: 0.5em 0;
    }
    .chatbot-msg--bot table {
        border-collapse: collapse;
        width: max-content;
        min-width: 100%;
        font-size: 0.84em;
        white-space: nowrap;
    }
    .chatbot-msg--bot th,
    .chatbot-msg--bot td {
        border: 1px solid #d0d7e0;
        padding: 5px 8px;
        text-align: left;
    }
    .chatbot-msg--bot th {
        background: #e2e8f0;
        font-weight: 600;
    }
    .chatbot-msg--bot tr:nth-child(even) {
        background: #f8fafc;
    }

    /* ─── Code blocks ─── */
    .chatbot-msg--bot code {
        background: #e2e8f0;
        padding: 1px 5px;
        border-radius: 3px;
        font-size: 0.88em;
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    }
    .chatbot-msg--bot pre {
        background: #1e293b;
        color: #e2e8f0;
        padding: 12px 14px;
        border-radius: 6px;
        overflow-x: auto;
        margin: 0.5em 0;
        font-size: 0.85em;
        line-height: 1.5;
    }
    .chatbot-msg--bot pre code {
        background: transparent;
        padding: 0;
        color: inherit;
        font-size: inherit;
    }

    /* ─── Blockquotes ─── */
    .chatbot-msg--bot blockquote {
        border-left: 3px solid #94a3b8;
        margin: 0.5em 0;
        padding: 0.3em 0 0.3em 12px;
        color: #475569;
    }

    /* ─── Horizontal rules ─── */
    .chatbot-msg--bot hr {
        border: none;
        border-top: 1px solid #d0d7e0;
        margin: 0.6em 0;
    }

    /* ─── Starter Grid ─── */
    .starter-grid {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 8px 0;
        margin-top: 4px;
        max-width: 320px;
    }

    .starter-box {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        min-height: 44px;
        border: 1px solid #d8dee8;
        border-radius: 8px;
        background: #ffffff;
        font-family: inherit;
        font-size: 0.9rem;
        color: #1a2332;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
    }

    .starter-box:hover {
        background: #f0f4f8;
        border-color: #0f766e;
    }

    .starter-box:focus-visible {
        outline: 2px solid #0f766e;
        outline-offset: 2px;
    }

    .starter-grid--disabled .starter-box {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    @media (max-width: 576px) {
        .starter-grid {
            max-width: 100%;
        }
        .starter-box {
            width: 100%;
        }
    }

    /* ─── Screen reader only utility ─── */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
</style>

<script>
(function () {
    const form     = document.getElementById('chatbot-form');
    const input    = document.getElementById('chatbot-input');
    const sendBtn  = document.getElementById('chatbot-send');
    const messages = document.getElementById('chatbot-messages');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')
                   || { content: '{{ csrf_token() }}' };

    // ─── Document Proxy Token ────────────────────────────────────────────────
    let docProxyToken = null;

    function fetchDocumentToken() {
        fetch('{{ route("chatbot.documentToken") }}', {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content || '{{ csrf_token() }}',
            },
        })
        .then(function (res) { return res.ok ? res.json() : null; })
        .then(function (data) {
            if (data && data.token) {
                docProxyToken = data.token;
            }
        })
        .catch(function () { /* silent — links will work without token but get 401 */ });
    }

    // Fetch token on page load
    fetchDocumentToken();
    // Refresh token every 50 minutes (token expires in 1 hour)
    setInterval(fetchDocumentToken, 50 * 60 * 1000);

    /**
     * Append document proxy token to proxy links in rendered HTML.
     * Matches links containing /api/documents/ and adds ?token=<jwt>
     */
    function appendTokenToProxyLinks(html) {
        if (!docProxyToken) return html;

        // Match href="...api/documents/..." links and append token
        return html.replace(
            /href="([^"]*\/api\/documents\/[^"?]*)"/g,
            function (match, url) {
                return 'href="' + url + '?token=' + docProxyToken + '"';
            }
        );
    }

    // ─── Marked.js configuration ─────────────────────────────────────────────
    marked.setOptions({
        breaks: true,       // Convert single \n to <br>
        gfm: true,          // GitHub Flavored Markdown (tables, strikethrough)
        headerIds: false,   // No auto-generated IDs on headers
        mangle: false       // Don't mangle email links
    });

    // Custom renderer: wrap tables in a scrollable container
    const renderer = new marked.Renderer();
    renderer.table = function (token) {
        // marked v15 passes a token object with header and rows
        let header = '';
        let body = '';

        // Build header row
        if (token.header && token.header.length) {
            header = '<thead><tr>';
            for (const cell of token.header) {
                const align = cell.align ? ' style="text-align:' + cell.align + '"' : '';
                header += '<th' + align + '>' + this.parser.parseInline(cell.tokens) + '</th>';
            }
            header += '</tr></thead>';
        }

        // Build body rows
        if (token.rows && token.rows.length) {
            body = '<tbody>';
            for (const row of token.rows) {
                body += '<tr>';
                for (const cell of row) {
                    const align = cell.align ? ' style="text-align:' + cell.align + '"' : '';
                    body += '<td' + align + '>' + this.parser.parseInline(cell.tokens) + '</td>';
                }
                body += '</tr>';
            }
            body += '</tbody>';
        }

        return '<div class="md-table-wrap"><table>' + header + body + '</table></div>';
    };

    marked.use({ renderer: renderer });

    // ─── DOMPurify configuration ─────────────────────────────────────────────
    const PURIFY_CONFIG = {
        ALLOWED_TAGS: [
            'h1','h2','h3','h4','h5','h6','p','br','hr',
            'strong','em','b','i','u','s','del',
            'ul','ol','li',
            'table','thead','tbody','tr','th','td',
            'pre','code','blockquote',
            'a','span','div','sub','sup'
        ],
        ALLOWED_ATTR: ['href','target','rel','class','style'],
        ALLOW_DATA_ATTR: false,
        ADD_ATTR: ['target'],       // Allow target on links
        FORBID_TAGS: ['script','iframe','object','embed','form','input'],
        FORBID_ATTR: ['onerror','onload','onclick','onmouseover']
    };

    // Force all links to open in new tab safely
    DOMPurify.addHook('afterSanitizeAttributes', function (node) {
        if (node.tagName === 'A') {
            node.setAttribute('target', '_blank');
            node.setAttribute('rel', 'noopener noreferrer');
        }
    });

    /**
     * Render markdown to safe HTML (bot messages only).
     */
    function renderMarkdown(text) {
        if (!text) return '';
        const rawHtml = marked.parse(text);
        const sanitized = DOMPurify.sanitize(rawHtml, PURIFY_CONFIG);
        return appendTokenToProxyLinks(sanitized);
    }

    // Auto-grow textarea
    input.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Send on Enter (Shift+Enter = newline)
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit', { cancelable: true }));
        }
    });

    function addMessage(text, type) {
        const div = document.createElement('div');
        div.className = 'chatbot-msg chatbot-msg--' + type;

        if (type === 'bot') {
            // Render markdown for bot responses (sanitized)
            div.innerHTML = renderMarkdown(text);
        } else {
            // User messages and errors: plain text (safe, no HTML injection)
            div.textContent = text;
        }

        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function setLoading(loading) {
        sendBtn.disabled = loading;
        if (loading) {
            sendBtn.classList.add('is-loading');
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending';
        } else {
            sendBtn.classList.remove('is-loading');
            sendBtn.textContent = 'Send';
        }
    }

    // ─── Starter Grid Logic ─────────────────────────────────────────────────
    const starterGrid = document.getElementById('starter-grid');
    const liveRegion  = document.getElementById('chatbot-live-region');

    function removeStarterGrid() {
        if (starterGrid && starterGrid.parentNode) {
            starterGrid.remove();
        }
    }

    if (starterGrid && !starterGrid.classList.contains('starter-grid--disabled')) {
        starterGrid.addEventListener('click', function (e) {
            const box = e.target.closest('.starter-box');
            if (!box) return;

            const prompt = box.dataset.prompt;
            if (!prompt) return;

            removeStarterGrid();
            addMessage(prompt, 'user');
            setLoading(true);

            if (liveRegion) {
                liveRegion.textContent = 'Sending: ' + prompt;
            }

            fetch('{{ route("chatbot.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content || '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message: prompt }),
            })
            .then(function (res) {
                return res.json().then(function (data) {
                    return { ok: res.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok) {
                    addMessage(result.data.reply || 'Something went wrong. Please try again.', 'error');
                } else {
                    addMessage(result.data.reply || 'No response received.', 'bot');
                }
            })
            .catch(function () {
                addMessage('Could not reach the AI service. Please check your connection.', 'error');
            })
            .finally(function () {
                setLoading(false);
                input.focus();
            });
        });
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const text = input.value.trim();
        if (!text) return;

        // Remove starter grid on manual message send (Task 4)
        removeStarterGrid();

        addMessage(text, 'user');
        input.value = '';
        input.style.height = 'auto';
        setLoading(true);

        try {
            const res = await fetch('{{ route("chatbot.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken.content || '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message: text }),
            });

            const data = await res.json();

            if (!res.ok) {
                addMessage(data.reply || 'Something went wrong. Please try again.', 'error');
            } else {
                addMessage(data.reply || 'No response received.', 'bot');
            }
        } catch (err) {
            addMessage('Could not reach the AI service. Please check your connection.', 'error');
        } finally {
            setLoading(false);
            input.focus();
        }
    });
})();
</script>
@if ($activeTAndC)
<script>
document.getElementById('tc-agree-checkbox').addEventListener('change', function () {
    document.getElementById('tc-agree-btn').disabled = !this.checked;
});

document.getElementById('tc-cancel-btn').addEventListener('click', function () {
    document.getElementById('tcModal').style.display = 'none';
    // Send button and input remain disabled — T&C not accepted
    document.getElementById('chatbot-send').disabled = true;
    document.getElementById('chatbot-input').disabled = true;
});
</script>
@endif
@endsection

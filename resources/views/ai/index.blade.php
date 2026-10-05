@extends('layout')

@section('content')

<style>
    .ai-chat-wrapper {
        max-width: 950px;
        margin: 25px auto;
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 78vh;
        min-height: 580px;
    }

    /* Header */
    .ai-chat-header {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        color: #ffffff;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Chat Body */
    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 16px;
        scroll-behavior: smooth;
    }

    /* Chat Bubbles */
    .chat-bubble {
        max-width: 82%;
        padding: 14px 18px;
        border-radius: 18px;
        font-size: 0.94rem;
        line-height: 1.65;
        white-space: pre-line;
        animation: fadeInBubble 0.25s ease-out;
    }

    @keyframes fadeInBubble {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .bubble-ai {
        align-self: flex-start;
        background-color: #ffffff;
        color: #212529;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
    }

    .bubble-user {
        align-self: flex-end;
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: #ffffff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 3px 10px rgba(13, 110, 253, 0.2);
    }

    /* Janly ýazylýan kursor animasiýasy */
    .typing-cursor {
        display: inline-block;
        width: 3px;
        height: 15px;
        background-color: #0d6efd;
        margin-left: 4px;
        vertical-align: middle;
        animation: blinkCursor 0.7s infinite;
    }

    @keyframes blinkCursor {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }

    /* 3 nokadyň ýanmagy (Waiting indicator) */
    .dot-flashing {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .dot-flashing span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #0d6efd;
        animation: dotBounce 1.2s infinite ease-in-out both;
    }

    .dot-flashing span:nth-child(1) { animation-delay: -0.32s; }
    .dot-flashing span:nth-child(2) { animation-delay: -0.16s; }

    @keyframes dotBounce {
        0%, 80%, 100% { transform: scale(0); opacity: 0.4; }
        40% { transform: scale(1); opacity: 1; }
    }

    /* Taýyn Soraglar Bölümi (Suggestions Area) */
    .suggestions-panel {
        background-color: #f1f5f9;
        border-top: 1px solid #e2e8f0;
        padding: 10px 18px;
    }

    .category-tag {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
    }

    .suggestion-pill {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        user-select: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .suggestion-pill:hover {
        background-color: #0d6efd;
        color: #ffffff;
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }

    /* Chat Footer */
    .chat-footer {
        padding: 12px 18px;
        background: #ffffff;
        border-top: 1px solid #eef2f6;
    }

    .chat-input {
        border-radius: 30px;
        padding: 12px 20px;
        border: 1px solid #dee2e6;
        font-size: 0.95rem;
    }

    .chat-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    .btn-send {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0d6efd;
        color: #ffffff;
        border: none;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-send:hover {
        background: #0b5ed7;
        transform: scale(1.05);
    }

    .btn-send:disabled {
        background: #adb5bd;
        cursor: not-allowed;
        transform: none;
    }
</style>

<div class="container">
    <div class="ai-chat-wrapper">
        
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; font-size: 1.35rem;">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold">Ulagym AI Smart Assistant</h5>
                    <small class="text-white-50">🇹🇲 Türkmen • 🇷🇺 Русский • 🇬🇧 English</small>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                {{ __('site.home') }}
            </a>
        </div>

        <!-- Chat Body -->
        <div class="chat-body" id="chatBody">
            <!-- Başlangyç Habar -->
            <div class="chat-bubble bubble-ai d-flex gap-3">
                <i class="bi bi-robot text-primary fs-5 mt-1"></i>
                <div>
                    <strong>Ulagym AI:</strong><br>
                    Salam! Men Ulagym platformasynyň emeli aň maslahatçysy. Awtoulag bazary, baha barlagy, tehniki ideg we modeller boýunça size kömek etmäge taýýar.
                    <br><br>
                    Aşakdaky taýyn soraglardan birini saýlap bilersiňiz ýa-da öz soragyňyzy ýazyp bilersiňiz!
                </div>
            </div>
        </div>

        <!-- 💡 Kategoriýalaşdyrylan Taýyn Soraglar (Prompt Suggestions) -->
        <div class="suggestions-panel">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="category-tag"><i class="bi bi-lightbulb-fill text-warning me-1"></i> Berip bolýan meşhur soraglar:</span>
            </div>
            <div class="d-flex gap-2 overflow-auto py-1" id="suggestionBox" style="scrollbar-width: thin;">
                <!-- 1. Barlag -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('Ulag almazdan öň nämeleri barlamaly?')">
                    🔍 Barlag nähili geçmeli?
                </span>
                <!-- 2. Býujet -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('15,000$ býujet üçin nähili ulag bar?')">
                    💰 15,000$ býujet
                </span>
                <!-- 3. Kondisioner -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('Kondisioneri tomus nädip taýýarlamaly?')">
                    ❄️ Kondisioner & Tomus
                </span>
                <!-- 4. Gibrid vs Benzin (RU) -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('Что экономичнее: гибрид или бензин?')">
                    ⚡ Гибрид vs Бензин
                </span>
                <!-- 5. Karopka -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('Wariator bilen awtomatyň tapawudy näme?')">
                    ⚙️ Вариатор vs Автомат
                </span>
                <!-- 6. Camry -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('Toyota Camry näme üçin beýle meşhur?')">
                    ⭐ Toyota Camry
                </span>
                <!-- 7. Ýag çalşyrmak -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('Motor ýagy näçe km-den çalşyrylýar?')">
                    🛢️ Ýag çalşyrmak
                </span>
                <!-- 8. Reliable cars (EN) -->
                <span class="suggestion-pill" onclick="sendQuickPrompt('Which cars are the most reliable in Turkmenistan?')">
                    🇬🇧 Reliable cars
                </span>
            </div>
        </div>

        <!-- Chat Input Footer -->
        <div class="chat-footer">
            <form id="chatForm" class="d-flex gap-2 align-items-center">
                @csrf
                <input type="text" 
                       id="userMessageInput" 
                       class="form-control chat-input" 
                       placeholder="Soragyňyzy ýazyň (TM / RU / EN)..." 
                       autocomplete="off" 
                       required>
                <button type="submit" class="btn-send shadow-sm" id="btnSend">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    const chatBody = document.getElementById('chatBody');
    const chatForm = document.getElementById('chatForm');
    const input = document.getElementById('userMessageInput');
    const btnSend = document.getElementById('btnSend');

    // Taýyn soragy basyp derrew ugratmak
    function sendQuickPrompt(promptText) {
        if (btnSend.disabled) return;
        input.value = promptText;
        chatForm.dispatchEvent(new Event('submit'));
    }

    // Ulanyjynyň ýa-da AI-nyň habaryny ekrana goşmak
    function appendUserMessage(text) {
        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble bubble-user';
        bubble.textContent = text;
        chatBody.appendChild(bubble);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // ⚡ Janly Daktilo (Typewriter) animasiýasy bilen AI jogabyny ýazmak
    function streamTypeAiMessage(fullText, onComplete) {
        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble bubble-ai d-flex gap-3';

        const icon = document.createElement('i');
        icon.className = 'bi bi-robot text-primary fs-5 mt-1';

        const contentDiv = document.createElement('div');
        contentDiv.innerHTML = `<strong>Ulagym AI:</strong><br><span class="ai-text-target"></span><span class="typing-cursor"></span>`;

        bubble.appendChild(icon);
        bubble.appendChild(contentDiv);
        chatBody.appendChild(bubble);

        const targetSpan = contentDiv.querySelector('.ai-text-target');
        const cursor = contentDiv.querySelector('.typing-cursor');

        // Sözme-söz janly ýazylmagy
        const words = fullText.split(/(\s+|\n)/);
        let currentIndex = 0;
        let displayedText = '';

        function typeNextChunk() {
            if (currentIndex < words.length) {
                displayedText += words[currentIndex];
                targetSpan.innerHTML = escapeHtml(displayedText).replace(/\n/g, '<br>');
                chatBody.scrollTop = chatBody.scrollHeight;
                currentIndex++;
                
                // Ýazylyş tizligi (12ms - tebigy we çalt)
                setTimeout(typeNextChunk, 12);
            } else {
                // Ýazylyp gutaransoň kursory aýyrmak
                if (cursor) cursor.remove();
                if (onComplete) onComplete();
            }
        }

        typeNextChunk();
    }

    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    chatForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        // Ulanyjy haty
        appendUserMessage(message);
        input.value = '';

        // "AI jogap taýýarlaýar" indikatory (3 sany bökejek nokat)
        const loadingBubble = document.createElement('div');
        loadingBubble.className = 'chat-bubble bubble-ai d-flex gap-3 align-items-center text-muted';
        loadingBubble.id = 'aiLoadingBubble';
        loadingBubble.innerHTML = `
            <i class="bi bi-robot text-primary fs-5"></i> 
            <div class="dot-flashing">
                <span></span><span></span><span></span>
            </div>
            <small class="ms-1 text-muted">Jogap taýýarlanýar...</small>
        `;
        chatBody.appendChild(loadingBubble);
        chatBody.scrollTop = chatBody.scrollHeight;

        btnSend.disabled = true;

        try {
            const response = await fetch("{{ route('ai.chat') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            const loader = document.getElementById('aiLoadingBubble');
            if (loader) loader.remove();

            if (data.success) {
                // Daktilo animasiýasy bilen ýazdyrmak
                streamTypeAiMessage(data.reply, function () {
                    btnSend.disabled = false;
                    input.focus();
                });
            } else {
                streamTypeAiMessage('Bagyşlaň, jogap döretmekde näsazlyk döredi. Täzeden synanyşyň.', function () {
                    btnSend.disabled = false;
                    input.focus();
                });
            }
        } catch (error) {
            const loader = document.getElementById('aiLoadingBubble');
            if (loader) loader.remove();
            streamTypeAiMessage('Bagyşlaň, serwer bilen birikmede näsazlyk döredi.', function () {
                btnSend.disabled = false;
                input.focus();
            });
        }
    });
</script>

@endsection
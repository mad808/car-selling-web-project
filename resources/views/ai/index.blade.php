@extends('layout')

@section('content')

<style>
    .ai-chat-wrapper {
        max-width: 900px;
        margin: 30px auto;
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 20px;
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 76vh;
        min-height: 560px;
    }

    .ai-chat-header {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        color: #ffffff;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .chat-bubble {
        max-width: 82%;
        padding: 14px 18px;
        border-radius: 18px;
        font-size: 0.94rem;
        line-height: 1.6;
        white-space: pre-line; /* AI sanawlaryny we setirlerini dogry görkezmek üçin */
    }

    .bubble-ai {
        align-self: flex-start;
        background-color: #ffffff;
        color: #212529;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .bubble-user {
        align-self: flex-end;
        background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
        color: #ffffff;
        border-bottom-right-radius: 4px;
    }

    .suggestion-pill {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        color: #495057;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        user-select: none;
    }

    .suggestion-pill:hover {
        background-color: #0d6efd;
        color: #ffffff;
        border-color: #0d6efd;
        transform: translateY(-1px);
    }

    .chat-footer {
        padding: 14px 20px;
        background: #ffffff;
        border-top: 1px solid #eef2f6;
    }

    .chat-input {
        border-radius: 30px;
        padding: 12px 20px;
        border: 1px solid #dee2e6;
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
    }
    .btn-send:hover {
        background: #0b5ed7;
        transform: scale(1.05);
    }
</style>

<div class="container">
    <div class="ai-chat-wrapper">
        
        <!-- Header -->
        <div class="ai-chat-header">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; font-size: 1.35rem;">
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
            <div class="chat-bubble bubble-ai d-flex gap-3">
                <i class="bi bi-robot text-primary fs-5 mt-1"></i>
                <div>
                    <strong>Ulagym AI:</strong><br>
                    Salam! Men Ulagym emeli aň maslahatçysy. Býujetiňize laýyk awtoulag saýlamakda, bahalary derňemekde we modelleri deňeşdirmekde kömek edýärin.
                    <br><br>
                    <em>Soragyňyzy islän diliňizde (Türkmençe, Русский, English) berip bilersiňiz!</em>
                </div>
            </div>
        </div>

        <!-- Taýyn Soraglar (3 Dilde) -->
        <div class="px-3 py-2 bg-light border-top d-flex gap-2 overflow-auto" id="suggestionBox">
            <span class="suggestion-pill" onclick="sendQuickPrompt('15,000$ býujet üçin nähili ulag bar?')">🇹🇲 15,000$ býujet</span>
            <span class="suggestion-pill" onclick="sendQuickPrompt('Какую надежную машину купить за 20000$?')">🇷🇺 До 20,000$</span>
            <span class="suggestion-pill" onclick="sendQuickPrompt('Toyota Camry näme üçin beýle meşhur?')">🇹🇲 Toyota Camry</span>
            <span class="suggestion-pill" onclick="sendQuickPrompt('Что экономичнее: гибрид или бензин?')">🇷🇺 Гибрид vs Бензин</span>
            <span class="suggestion-pill" onclick="sendQuickPrompt('Which cars are the most reliable in Turkmenistan?')">🇬🇧 Reliable cars</span>
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

    function sendQuickPrompt(promptText) {
        input.value = promptText;
        chatForm.dispatchEvent(new Event('submit'));
    }

    function appendMessage(sender, text, isAi = false) {
        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ${isAi ? 'bubble-ai d-flex gap-3' : 'bubble-user'}`;

        if (isAi) {
            bubble.innerHTML = `<i class="bi bi-robot text-primary fs-5 mt-1"></i><div><strong>Ulagym AI:</strong><br>${escapeHtml(text)}</div>`;
        } else {
            bubble.textContent = text;
        }

        chatBody.appendChild(bubble);
        chatBody.scrollTop = chatBody.scrollHeight;
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

        appendMessage('User', message, false);
        input.value = '';

        // Jogap garaşylýar indikatory
        const loadingBubble = document.createElement('div');
        loadingBubble.className = 'chat-bubble bubble-ai d-flex gap-3 text-muted';
        loadingBubble.id = 'aiLoadingBubble';
        loadingBubble.innerHTML = `<i class="bi bi-robot text-primary fs-5"></i> <em>Jogap taýýarlanýar...</em>`;
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
                appendMessage('AI', data.reply, true);
            } else {
                appendMessage('AI', 'Ýalňyşlyk ýüze çykdy. Täzeden synanyşyň.', true);
            }
        } catch (error) {
            const loader = document.getElementById('aiLoadingBubble');
            if (loader) loader.remove();
            appendMessage('AI', 'Birikmede näsazlyk döredi.', true);
        } finally {
            btnSend.disabled = false;
            input.focus();
        }
    });
</script>

@endsection
<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>indenizaAI — Conversa</title>
    <style>
        :root {
            --accent: #1f6feb;
            --muted: #6b7280;
            --bg: #f3f6fb;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial;
            background: var(--bg);
            color: #0f172a;
        }

        .wrap {
            display: flex;
            flex-direction: column;
            height: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 16px 16px;
        }

        .top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* garante que o botão fique na direita */
            gap: 12px;
        }

        .top img {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .top h2 {
            margin: 0;
            font-size: 18px;
        }

        .small {
            font-size: 13px;
            color: var(--muted);
        }

        .chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.06);
            overflow: hidden;
        }

        .messages {
            flex: 1;
            padding: 18px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .msg {
            max-width: 78%;
            padding: 12px;
            border-radius: 10px;
            word-wrap: break-word;
        }

        .msg.user {
            align-self: flex-end;
            background: var(--accent);
            color: #fff;
            border-bottom-right-radius: 2px;
        }

        .msg.agent {
            align-self: flex-start;
            background: #f1f5f9;
            color: #0f172a;
            border-bottom-left-radius: 2px;
        }

        .msg.typing {
            font-style: italic;
            opacity: 0.7;
        }

        .composer {
            display: flex;
            padding: 12px;
            border-top: 1px solid #eef2f7;
            gap: 8px;
        }

        .composer input[type=text] {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
        }

        .composer button {
            background: var(--accent);
            color: #fff;
            border: 0;
            padding: 10px 14px;
            border-radius: 8px;
            cursor: pointer;
        }

        .composer button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-home {
            margin-left: auto;
            padding: 8px 16px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-home:hover {
            background: #155bb5;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="top">
            <div style="display: flex; align-items: center; gap: 12px;">
                <img src="{{ asset('storage/indeza-ai-logo.jpeg') }}" alt="logo">
                <div>
                    <h2>indenizaAI — Conversa</h2>
                    <div class="small">Converse com o agente para coletar informações do caso.</div>
                </div>
            </div>
            <a href="{{ route('home') }}" class="btn-home">Home</a>
        </div>

        <div class="chat">
            <div class="messages" id="messages">
                <div class="msg agent">Agente: Olá — descreva o acidente de trabalho e os principais danos.</div>
            </div>
            <form id="chatForm" class="composer" onsubmit="return false;">
                <input id="input" type="text" placeholder="Digite sua pergunta ou informação do caso..."
                    autocomplete="off">
                <button id="send">Enviar</button>
            </form>
        </div>
    </div>

    <script>
        const messages = document.getElementById('messages');
        const input = document.getElementById('input');
        const send = document.getElementById('send');

        let historico = [];

        function appendMessage(text, cls) {
            const div = document.createElement('div');
            div.className = 'msg ' + cls;
            if (cls === 'agent') div.innerHTML = text;
            else div.textContent = text;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function showTyping() {
            const div = document.createElement('div');
            div.id = 'typing';
            div.className = 'msg agent typing';
            div.textContent = 'Agente está digitando...';
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function hideTyping() {
            const div = document.getElementById('typing');
            if (div) div.remove();
        }

        async function postMessage(text) {
            appendMessage(text, 'user');
            send.disabled = true;
            showTyping();

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('{{ url('/start/message') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        message: text,
                        historico
                    })
                });

                const data = await res.json();
                hideTyping();

                if (res.ok) {
                    appendMessage(data.reply, 'agent');
                    historico = data.historico || historico;
                } else {
                    appendMessage('Erro: ' + (data.error || data.reply || 'Desconhecido'), 'agent');
                }

            } catch (err) {
                hideTyping();
                appendMessage('Erro de rede: ' + err.message, 'agent');
            } finally {
                send.disabled = false;
                input.focus();
            }
        }

        send.addEventListener('click', () => {
            const val = input.value.trim();
            if (!val) return;
            input.value = '';
            postMessage(val);
        });

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                send.click();
            }
        });
    </script>
</body>

</html>

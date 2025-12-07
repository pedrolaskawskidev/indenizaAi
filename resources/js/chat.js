import { Client } from "@gradio/client";

const messagesEl = document.getElementById("messages");
const input = document.getElementById("input");
const sendBtn = document.getElementById("send");

let historico = [];

// Função para adicionar mensagens ao chat
function appendMessage(text, cls) {
    const div = document.createElement("div");
    div.className = "msg " + cls;
    div.textContent = text;
    messagesEl.appendChild(div);
    messagesEl.scrollTop = messagesEl.scrollHeight;
}

// Função para enviar mensagem ao Space
async function postMessage(mensagem) {
    appendMessage(mensagem, "user");

    try {
        const client = await Client.connect("pedrolaskawski/IndenizaAI");

        // Chama a função /responder do Space
        const result = await client.predict("/responder", {
            mensagem: mensagem,
            historico: historico
        });

        // A resposta final está em result.data[1]
        const resposta = result.data[1] ?? "Erro: resposta vazia";

        // Atualiza o histórico
        historico.push({ role: "user", content: mensagem });
        historico.push({ role: "assistant", content: resposta });

        appendMessage(resposta, "agent");

    } catch (err) {
        appendMessage("Erro ao conectar à IA: " + err.message, "agent");
    }
}

// Eventos do chat
sendBtn.addEventListener("click", () => {
    const val = input.value.trim();
    if (!val) return;
    input.value = "";
    postMessage(val);
});

input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        sendBtn.click();
    }
});

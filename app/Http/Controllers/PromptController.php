<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PromptController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function start()
    {
        return view('start');
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'historico' => 'nullable|array'
        ]);

        $mensagem = $request->message;
        $historico = $request->historico ?? [];

        try {
            // 1️⃣ POST inicial para criar evento
            $postResponse = Http::timeout(30)->post('https://pedrolaskawski-indenizaai.hf.space/gradio_api/call/responder', [
                'data' => [$mensagem, $historico]
            ]);

            if ($postResponse->failed()) {
                return response()->json(['reply' => 'Erro: não foi possível criar evento no Space'], 500);
            }

            $eventId = $postResponse->json()['event_id'] ?? null;
            if (!$eventId) {
                return response()->json(['reply' => 'Erro: event_id não retornado'], 500);
            }

            // 2️⃣ GET SSE final
            $getResponse = Http::timeout(120)->get("https://pedrolaskawski-indenizaai.hf.space/gradio_api/call/responder/{$eventId}");
            $body = $getResponse->body();
            $resposta = 'Não foi possível obter resposta do modelo.';

            // Extrai linha com "data:"
            if (preg_match('/data:\s*(.*)/', $body, $matches)) {
                $json = json_decode($matches[1], true);
                if ($json && isset($json[0])) {
                    // Pega o último "assistant" do array
                    $ultimo = end($json[0]);
                    if ($ultimo['role'] === 'assistant' && isset($ultimo['content'])) {
                        $resposta = $this->formatResponse($ultimo['content']);
                    }
                }
            }

            // Atualiza histórico
            $historico[] = ['role' => 'user', 'content' => $mensagem];
            $historico[] = ['role' => 'assistant', 'content' => $resposta];

            return response()->json([
                'reply' => $resposta,
                'historico' => $historico
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'reply' => 'Erro inesperado: ' . $e->getMessage()
            ], 500);
        }
    }

    private function formatResponse(string $text): string
    {
        // Quebra por novas linhas
        $text = nl2br($text);

        // Converte bullets Markdown "* " em <li>
        $text = preg_replace_callback('/(\*{1,2})\s*(.*?)<br>/', function ($matches) {
            return "<li>{$matches[2]}</li>";
        }, $text);

        // Se encontrou <li>, envolve em <ul>
        if (strpos($text, '<li>') !== false) {
            $text = "<ul>{$text}</ul>";
        }

        // Negrito Markdown **texto** → <b>texto</b>
        $text = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $text);

        return $text;
    }
}

<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>indenizaAI — Assistente de Indenização</title>
    <style>
        :root {
            --accent: #1f6feb;
            --muted: #6b7280;
            --bg: #f8fafc
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial;
            line-height: 1.4;
            margin: 0;
            background: var(--bg);
            color: #0f172a
        }

        .container {
            max-width: 1000px;
            margin: 32px auto;
            padding: 24px
        }

        .hero {
            display: flex;
            gap: 24px;
            align-items: center;
            background: #fff;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06)
        }

        .logo {
            width: 160px;
            height: 160px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain
        }

        .hero h1 {
            font-size: 28px;
            margin: 0 0 6px
        }

        .hero p {
            margin: 0;
            color: var(--muted)
        }

        .cta {
            margin-top: 18px
        }

        .btn {
            background: var(--accent);
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            border: 0;
            cursor: pointer;
            font-weight: 600
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 20px
        }

        .card {
            background: #fff;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04)
        }

        .muted {
            color: var(--muted)
        }

        footer {
            margin-top: 26px;
            text-align: center;
            color: var(--muted);
            font-size: 14px
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="hero">
            <div class="logo">
                <img src="{{ asset('storage/indeza-ai-logo.jpeg') }}" alt="indenizaAI logo">
            </div>
            <div>
                <h1>indenizaAI — Assistente para Cálculo de Indenizações</h1>
                <p class="muted">Um agente de IA para apoiar advogados e promotores no cálculo de compensações por
                    acidentes de trabalho: jurisprudência, parâmetros e estimativas rápidas.</p>
                <div class="cta">
                    <a href="/start" style="text-decoration:none"><button class="btn">Começar avaliação</button></a>
                </div>
                <p class="muted" style="margin-top:10px;font-size:13px">Privacidade e precisão são prioridades — revise
                    sempre os resultados antes de apresentar em peças ou termos.</p>
            </div>
        </div>

        <div class="features">
            <div class="card">
                <h3>Entrada de Caso</h3>
                <p class="muted">Insira dados do acidente, lesões, afastamento e documentos para obter uma análise
                    inicial da indenização aplicável.</p>
            </div>
            <div class="card">
                <h3>Base Jurisprudencial</h3>
                <p class="muted">O agente sugere precedentes e percentuais usados em casos similares para suportar
                    cálculos e fundamentações.</p>
            </div>
            <div class="card">
                <h3>Estimativa Rápida</h3>
                <p class="muted">Receba uma estimativa detalhada com componentes de dano material, moral, estético e
                    lucros cessantes.</p>
            </div>
            <div class="card">
                <h3>Exportar Relatório</h3>
                <p class="muted">Gere um relatório pronto para revisão e anexação aos autos ou para envio ao cliente.
                </p>
            </div>
        </div>

        {{-- <footer>
            Desenvolvido para profissionais — versão inicial. Para integração API e recursos, peça suporte.
        </footer> --}}
    </div>
</body>

</html>

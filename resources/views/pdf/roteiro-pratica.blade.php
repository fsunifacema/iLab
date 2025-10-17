<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Roteiro de Aula Prática</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        /* Cabeçalho com logo e missão na mesma linha */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap; /* Permite a quebra de linha em telas menores */
        }

        /* Logo à esquerda */
        .header img {
            max-width: 300px;
            height: auto;
        }

        /* Missão à direita com largura limitada a 50% */
        .mission {
            width: 45%; /* Limita a missão para não ultrapassar metade da página */
            text-align: justify; /* Justifica o texto */
            font-size: 12px;
            color: #0C2E5C;
            font-weight: bold;
            padding-right: 10px;
        }

        /* Conteúdo da página */
        .content {
            margin-top: 20px;
        }

        /* Estilo da tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Rodapé */
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 10px;
        }

        /* Ajustes para garantir boa visualização */
        .flex {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .flex > div {
            flex: 1;
            min-width: 200px;
        }
    </style>
</head>
<body>

<!-- Cabeçalho com logo e missão -->
<div class="header">
    <img src="{{ $imageSrc }}" alt="Logo UniFacema" class="w-[100px]">
    <div class="mission">
        Missão: <span>"Contribuir para o desenvolvimento sustentável por meio do ensino superior inovador em um ambiente de qualidade."</span>
    </div>
</div>

<!-- Conteúdo do Roteiro -->
<div class="content">
    <table>
        <tr>
            <th>Curso/Campus:</th>
            <td>{{ $roteiro->curso }}</td>
        </tr>
        <tr>
            <th>Unidade Curricular</th>
            <td>{{ $roteiro->unidade_curricular }}</td>
        </tr>
        <tr>
            <th>Professor:</th>
            <td>{{ $roteiro->professor }}</td>
        </tr>
        <tr>
            <th>Período:</th>
            <td>{{ $roteiro->periodo }}</td>
        </tr>
        <tr>
            <th>Quantidade de Alunos:</th>
            <td>{{ $roteiro->quantidade_alunos }}</td>
        </tr>
        <tr>
            <th>Data:</th>
            <td>{{ \Carbon\Carbon::parse($roteiro->data)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Horário:</th>
            <td>{{ $roteiro->horario }}</td>
        </tr>
        <tr>
            <th>Carga Horária:</th>
            <td>{{ $roteiro->carga_horaria }}</td>
        </tr>
        <tr>
            <th>Nº de Práticas:</th>
            <td>{{ $roteiro->numero_praticas }}</td>
        </tr>
        <tr>
            <th>Laboratório:</th>
            <td>{{ $roteiro->laboratorio }}</td>
        </tr>
        <tr>
            <th>Conteúdo da aula prática (Conforme plano de ensino):</th>
            <td>{{ $roteiro->conteudo }}</td>
        </tr>
        <tr>
            <th>Tema da aula prática:</th>
            <td>{{ $roteiro->tema }}</td>
        </tr>
        <tr>
            <th>Objetivos:</th>
            <td>{{ $roteiro->objetivos }}</td>
        </tr>
        <tr>
            <th>Metodologia da aula prática:</th>
            <td>{{ $roteiro->metodologia }}</td>
        </tr>
        <tr>
            <th>Recursos e materiais utilizados:</th>
            <td>{{ $roteiro->recursos }}</td>
        </tr>
        <tr>
            <th>Forma de avaliação da atividade prática realizada pelo aluno:</th>
            <td>{{ $roteiro->avaliacao }}</td>
        </tr>
        <tr>
            <th>Observações:</th>
            <td>{{ $roteiro->observacoes }}</td>
        </tr>
        <tr>
            <th>Data de Assinatura:</th>
            <td>{{ \Carbon\Carbon::parse($roteiro->data_assinatura)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Emitido em: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
    </div>
</div>

</body>
</html>

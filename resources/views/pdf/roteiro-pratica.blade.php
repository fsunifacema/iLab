<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Roteiro de Aula Prática</title>
    <style>
        @page {
            margin-top: 2cm;
            margin-bottom: 1cm; 
            margin-left: 2cm;
            margin-right: 3cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border-bottom: 3px solid #0C2E5C;
            padding-bottom: 5px;
        }

        .header .logo-box, .header .mission-box {
            display: table-cell;
            vertical-align: top;
        }

        .header .logo-box {
            width: 30%;
        }

        .header .mission-box {
            width: 70%;
            text-align: right;
            font-size: 8pt;
            color: #0C2E5C;
            font-weight: bold;
            padding-left: 20px;
        }

        .header img {
            max-width: 200px;
            height: auto;
        }

        /* 2. TÍTULO PRINCIPAL */
        .main-title {
            background-color: #D3E0F0;
            color: #0C2E5C;
            text-align: center;
            padding: 7px 0;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 14pt;
            font-weight: bold;
            border: 1px solid #aaa;
        }

        /* TABELAS - Estilo limpo baseado no PDF 2 */
        .data-table, .section-box {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 5px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .data-table th, .section-box th {
            background-color: #F8F8F8; /* Fundo cinza claro para as células de título */
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            font-weight: bold;
            width: 30%;
            font-size: 9pt;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            font-size: 9pt;
        }

        /* Estilo da Caixa de Seção (Conteúdo) */
        .section-header {
            background-color: #E0E0E0; /* Cinza claro/médio */
            border: 1px solid #000;
            border-bottom: 2px solid #0C2E5C;
            font-weight: bold;
            padding: 5px;
            font-size: 10pt;
            text-align: left;
        }
        
        .section-content td {
            padding: 8px;
            line-height: 1.4;
            border-top: none;
            /* Garante bordas laterais e inferiores */
            border-bottom: 1px solid #000;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }


        /* impede quebra entre título e conteúdo */
        .section-box, .data-table {
            page-break-inside: avoid;
        }

        .last-content-block {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        /* 3. RODAPÉ (Estilo original do bloco de endereços, sem 'fixed') */
        .footer-address {
            font-size: 8pt; 
            color: #555; 
            text-align: center; 
            margin-top: 30 px; 
            page-break-inside: avoid; 
            border-top: 1px solid #0C2E5C; 
            padding-top: 5px;
        }
        .footer-address table {
            width:100%; 
            border:none; 
            table-layout: fixed;
        }
        .footer-address td {
            border:none; 
            padding: 0 5px; 
            vertical-align: top; 
            font-size: 7.5pt;
        }
        .center-contact { text-align: center; }
        .right-contact { text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <div class="logo-box">
        <img src="{{ $imageSrc }}" alt="Logo UniFacema">
    </div>
    <div class="mission-box">
        Missão: "Contribuir para o desenvolvimento sustentável por meio do ensino superior inovador em um ambiente de qualidade."
    </div>
</div>

<div class="main-title">
    ROTEIRO DE AULA PRÁTICA <br> UniFacema
</div>

<table class="data-table">
    <tr><th>CURSO / CAMPUS:</th><td>{{ $roteiro->curso }} / CAXIAS</td></tr>
    <tr><th>DISCIPLINA:</th><td>{{ $roteiro->unidade_curricular }}</td></tr>
    <tr><th>PROFESSOR:</th><td>{{ $roteiro->professor }}</td></tr>
    <tr><th>PERÍODO:</th><td>{{ $roteiro->periodo }}</td></tr>
    <tr><th>QUANT. DE ALUNOS:</th><td>{{ $roteiro->quantidade_alunos }}</td></tr>
    <tr><th>DATA:</th><td>{{ \Carbon\Carbon::parse($roteiro->data)->format('d/m/Y') }}</td></tr>
    <tr><th>HORÁRIO:</th><td>{{ $roteiro->horario_inicio }} às {{ $roteiro->horario_fim }} Horas</td></tr>
    <tr><th>CARGA HORÁRIA DA AULA PRÁTICA:</th><td>{{ $roteiro->carga_horaria }}</td></tr>
    <tr><th>Nº DE PRÁTICAS:</th><td>{{ $roteiro->numero_praticas }}</td></tr>
    <tr><th>LABORATÓRIO / CENÁRIO DE PRÁTICA:</th><td>{{ $roteiro->laboratorio }}</td></tr>
</table>

<table class="section-box">
    <tr><td class="section-header">CONTEÚDO DA AULA PRÁTICA (CONFORME PLANO DE ENSINO)</td></tr>
    <tr class="section-content"><td>{{ $roteiro->conteudo }}</td></tr>
</table>

<table class="section-box">
    <tr><td class="section-header">TEMA DA AULA PRÁTICA:</td></tr>
    <tr class="section-content"><td>{{ $roteiro->tema }}</td></tr>
</table>

<table class="section-box">
    <tr><td class="section-header">OBJETIVO(S):</td></tr>
    <tr class="section-content"><td>{{ $roteiro->objetivos }}</td></tr>
</table>

<table class="section-box">
    <tr><td class="section-header">METODOLOGIA DA AULA PRÁTICA</td></tr>
    <tr class="section-content"><td>{{ $roteiro->metodologia }}</td></tr>
</table>

<table class="section-box">
    <tr><td class="section-header">RECURSOS E MATERIAIS UTILIZADOS</td></tr>
    <tr class="section-content"><td>{{ $roteiro->recursos }}</td></tr>
</table>

<table class="section-box">
    <tr><td class="section-header">FORMA DE AVALIAÇÃO DA ATIVIDADE PRÁTICA REALIZADA PELO ALUNO</td></tr>
    <tr class="section-content"><td>{{ $roteiro->avaliacao }}</td></tr>
</table>

<table class="section-box last-content-block">
    <tr><td class="section-header">OBSERVAÇÕES:</td></tr>
    <tr class="section-content"><td>{{ $roteiro->observacoes }}</td></tr>
</table>

<div style="text-align:right; margin-top:10px; font-size:9pt; color:#555;">
    Emitido em: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
</div>

<div style="width:100%; margin-top:70px; border:none;">
    <div style="text-align:center;">
        <hr style="width:200px; margin: 0 auto 5px auto;">
        <p style="margin:0; padding:0;">Professor da disciplina</p>
    </div>
</div>

<div style="width:100%; margin-top:70px; border:none;">
    <div style="text-align:center;">
        <hr style="width:200px; margin: 0 auto 5px auto;">
        <p style="margin:0; padding:0;">Coordenação do Curso</p>
    </div>
</div>

<div style="width:100%; margin-top:70px; border:none;">
    <div style="text-align:center;">
        <hr style="width:200px; margin: 0 auto 5px auto;">
        <p style="margin:0; padding:0;">Coordenação de Laboratórios</p>
    </div>
</div>

<div style="width:100%; margin-top:70px; border:none; margin-bottom: 70px;">
    <div style="text-align:center;">
        <hr style="width:200px; margin: 0 auto 5px auto;">
        <p style="margin:0; padding:0;">Técnico de Laboratório</p>
    </div>
</div>

<div class="footer-address">
    <table class="footer-info-table">
        <tr>
            <td style="text-align: left;">
                Caxias/MA | unifacema.centro<br>
                R. Aarão Reis, 1000<br>
                Bairro Centro, CEP: 65606-020
            </td>
            <td class="center-contact">
                Codó/MA | unifacemacodo<br>
                Av. Santos Dumont, 5132<br>
                Bairro São Sebastião, CEP: 65400-000
            </td>
            <td class="right-contact">
                Presidente Dutra/MA | unifacemadutra<br>
                Av. José Olavo Sampaio, 100<br>
                Bairro Vila Militar, CEP: 65760-000
            </td>
        </tr>
    </table>
</div>

</body>
</html>
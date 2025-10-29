<?php

namespace App\Http\Controllers;

use App\Models\Roteiro;
use App\Models\Laboratorio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Carbon\Carbon; 


class RoteiroController extends Controller
{
    public function index()
    {
        // 1. Inicia a query do modelo Roteiro
        $query = Roteiro::query();

        // 2. Aplica o filtro SE o usuário for Professor
        if (Auth::user()->nivel === \App\Models\User::NIVEL_PROFESSOR) {
            // Adiciona a cláusula WHERE para filtrar por professor
            $query->where('professor', Auth::user()->name);
            // Os coordenadores, técnicos, etc. verão todos, pois não entram neste IF
        } 
        
        // 3. Executa a query final (com ou sem o filtro de professor)
        //    e aplica a ordenação e a paginação para todos os resultados.
        $roteiros = $query->latest()->paginate(10);
        return view('roteiros.index', compact('roteiros'));
    }

    /**
     * Rota de API para o FullCalendar.
     * Retorna os roteiros no formato JSON de eventos, com filtro de acesso.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoteirosAsEvents()
    {
        // 1. Inicia a query do modelo Roteiro
        $query = Roteiro::query();

        // **FILTRO DE ACESSO DO USUÁRIO:**
        // Se for Professor, filtra apenas os seus roteiros.
        if (Auth::user()->nivel === \App\Models\User::NIVEL_PROFESSOR) {
            $query->where('professor', Auth::user()->name);
        }
        
        // Filtro de segurança para ignorar datas obviamente inválidas
        $query->where('data', '>', '2020-01-01');

        $roteiros = $query->get();

        // 3. Formata os dados para o FullCalendar
        $events = $roteiros->map(function ($roteiro) {
            
            $title = "{$roteiro->unidade_curricular} - {$roteiro->tema} ({$roteiro->laboratorio})";

            // CORREÇÃO DEFINITIVA: Usa Carbon para garantir APENAS a data pura (YYYY-MM-DD).
            try {
                $carbonDate = Carbon::parse($roteiro->data);
                $dateOnly = $carbonDate->format('Y-m-d');
            } catch (\Exception $e) {
                // Ignora roteiros com datas inválidas
                return null; 
            }
            
            $start = $dateOnly; 
            $end = null; 
            
            // Lógica de Formatação para ISO 8601 (YYYY-MM-DDTHH:MM:SS)
            if (!empty($roteiro->horario) && str_contains($roteiro->horario, ':')) {
                
                $horarios = explode('-', $roteiro->horario);
                $hora_inicio = trim($horarios[0]);
                
                // GARANTE O FORMATO ISO: YYYY-MM-DDTHH:MM:SS (Sem a hora 00:00:00 extra)
                $start = "{$dateOnly}T{$hora_inicio}:00"; 
                
                // Calcula o 'end'
                if (count($horarios) > 1) {
                    $hora_fim = trim($horarios[1]);
                    $end = "{$dateOnly}T{$hora_fim}:00";
                }
            }


            return [
                'id' => $roteiro->id,
                'title' => $title,
                'start' => $start, 
                'end' => $end,
                'color' => 'rgba(255, 255, 255, 1)', 
                'allDay' => empty($roteiro->horario), 
                
                // Informações adicionais para o modal
                'extendedProps' => [
                    'professor' => $roteiro->professor,
                    'curso' => $roteiro->curso,
                    'unidade_curricular' => $roteiro->unidade_curricular,
                    'periodo' => $roteiro->periodo,
                    'laboratorio' => $roteiro->laboratorio,
                    'carga_horaria' => $roteiro->carga_horaria,
                    'quantidade_alunos' => $roteiro->quantidade_alunos,
                    'tema' => $roteiro->tema,
                ],
            ];
        })->filter()->values(); // Remove any null items from invalid dates

        // 4. Retorna a lista de eventos em JSON
        return response()->json($events);
    }
    // ... (restante dos seus métodos)
    public function create()
    {
        $laboratorios = Laboratorio::all();
        return view('roteiros.create', compact('laboratorios'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'curso' => 'nullable|string|max:255',
            'unidade_curricular' => 'nullable|string|max:255',
            'periodo' => 'nullable|string|max:50',
            'quantidade_alunos' => 'nullable|integer',
            'data' => 'nullable|date',
            'horario' => 'nullable|string|max:100',
            'carga_horaria' => 'nullable|string|max:50',
            'numero_praticas' => 'nullable|integer',
            'laboratorio' => 'nullable|string|max:255',
            'conteudo' => 'nullable|string',
            'tema' => 'nullable|string',
            'objetivos' => 'nullable|string',
            'metodologia' => 'nullable|string',
            'recursos' => 'nullable|string',
            'avaliacao' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'data_assinatura' => 'nullable|date',
        ]);

        // Adiciona automaticamente o professor logado
        $dados['professor'] = auth()->user()->name ?? 'Usuário Indefinido';

        $roteiro = Roteiro::create($dados);

        return redirect()->route('roteiros.show', $roteiro->id)
            ->with('success', 'Roteiro criado com sucesso!');
    }

    public function show(Roteiro $roteiro)
    {
        return view('roteiros.show', compact('roteiro'));
    }

    public function edit(Roteiro $roteiro)
    {
        $laboratorios = Laboratorio::all();
        return view('roteiros.edit', compact('roteiro', 'laboratorios'));
    }

    public function update(Request $request, Roteiro $roteiro)
    {
        $dados = $request->validate([
            'curso' => 'nullable|string|max:255',
            'unidade_curricular' => 'nullable|string|max:255',
            'professor' => 'nullable|string|max:255',
            'periodo' => 'nullable|string|max:50',
            'quantidade_alunos' => 'nullable|integer',
            'data' => 'nullable|date',
            'horario' => 'nullable|string|max:100',
            'carga_horaria' => 'nullable|string|max:50',
            'numero_praticas' => 'nullable|integer',
            'laboratorio' => 'nullable|string|max:255',
            'conteudo' => 'nullable|string',
            'tema' => 'nullable|string',
            'objetivos' => 'nullable|string',
            'metodologia' => 'nullable|string',
            'recursos' => 'nullable|string',
            'avaliacao' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'data_assinatura' => 'nullable|date',
        ]);

        $roteiro->update($dados);

        return redirect()->route('roteiros.show', $roteiro->id)
            ->with('success', 'Roteiro atualizado com sucesso!');
    }

    public function destroy(Roteiro $roteiro)
    {
        $roteiro->delete();
        return redirect()->route('roteiros.index')
            ->with('success', 'Roteiro excluído com sucesso!');
    }

    public function pdf(Roteiro $roteiro)
    {
        // Definir as opções para o DomPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        // Certifique-se de que a imagem existe no seu ambiente
        $imagePath = public_path('img/logo-facema-azul.png');
        if (file_exists($imagePath)) {
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageSrc = 'data:image/png;base64,' . $imageData;
        } else {
            $imageSrc = ''; // Fallback se a imagem não for encontrada
        }


        // Instanciar o DomPDF
        $dompdf = new Dompdf($options);

        // Carregar a view e gerar o conteúdo
        $pdfContent = view('pdf.roteiro-pratica', compact('roteiro', 'imageSrc'))->render();

        // Carregar o conteúdo HTML no DomPDF
        $dompdf->loadHtml($pdfContent);

        // (Opcional) Defina o tamanho da página
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar o PDF
        $dompdf->render();

        // Exibir o PDF no navegador
        return $dompdf->stream("roteiro_{$roteiro->unidade_curricular}_{$roteiro->periodo}.pdf");
    }
}


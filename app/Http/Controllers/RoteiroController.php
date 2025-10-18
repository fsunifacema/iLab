<?php

namespace App\Http\Controllers;

use App\Models\Roteiro;
use App\Models\Laboratorio;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;



class RoteiroController extends Controller
{
    public function index()
    {

        $roteiros = Roteiro::latest()->paginate(10);
        return view('roteiros.index', compact('roteiros'));
    }

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
        $imagePath = public_path('img/logo-facema-azul.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/png;base64,' . $imageData;

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

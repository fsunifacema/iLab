<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SignatureController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'signature_base64' => ['required', 'string'],
        ]);

        $user      = auth()->user();
        $imageData = $request->input('signature_base64');
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
        $decoded   = base64_decode($imageData);

        if ($user->signature_path) {
            Storage::disk('public')->delete($user->signature_path);
        }

        $filename = 'signatures/user_' . $user->id . '_' . time() . '.png';
        Storage::disk('public')->put($filename, $decoded);

        $user->update(['signature_path' => $filename]);

        return back()->with('success', 'Assinatura salva com sucesso!');
    }

    public function delete()
    {
        $user = auth()->user();

        if ($user->signature_path) {
            Storage::disk('public')->delete($user->signature_path);
            $user->update(['signature_path' => null]);
        }

        return back()->with('success', 'Assinatura removida com sucesso!');
    }

    public function show($filename)
    {
        $path = storage_path('app/public/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return Response::file($path);
    }
}

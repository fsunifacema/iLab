<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>
                <x-section-border />
            @endif

            <!-- Seção de Assinatura -->
             @if(auth()->user()->nivel !== \App\Models\User::NIVEL_ADMINISTRADOR)
              <div class="mt-10 sm:mt-0">
                  <div class="md:grid md:grid-cols-3 md:gap-6">

                      {{-- Título e descrição --}}
                      <div class="md:col-span-1">
                          <div class="px-4 sm:px-0">
                              <h3 class="text-lg font-medium text-gray-900">Minha Assinatura</h3>
                              <p class="mt-1 text-sm text-gray-600">
                                  Cadastre ou edite sua assinatura digital. Ela será utilizada automaticamente nos documentos gerados pelo sistema.
                              </p>
                          </div>
                      </div>

                      {{-- Formulário --}}
                      <div class="mt-5 md:mt-0 md:col-span-2">
                          <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">

                              @if(session('success'))
                                  <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                                      {{ session('success') }}
                                  </div>
                              @endif

                              {{-- Assinatura atual --}}
                              @if(auth()->user()->signature_path)
                                  <div class="mb-4">
                                      <p class="text-sm text-gray-600 mb-1">Assinatura atual:</p>
                                      <img src="{{ Storage::url(auth()->user()->signature_path) }}"
                                          height="80"
                                          class="border rounded p-2 bg-white">
                                      <p class="text-xs text-gray-400 mt-1">
                                          Para editar, desenhe uma nova abaixo e salve.
                                      </p>
                                  </div>
                              @endif

                              {{-- Canvas --}}
                              <p class="text-sm text-gray-600 mb-1">
                                  {{ auth()->user()->signature_path ? 'Nova assinatura:' : 'Desenhe sua assinatura:' }}
                              </p>
                              <div class="border border-gray-400 rounded inline-block" style="line-height:0;">
                                  <canvas id="signature-canvas"
                                          width="400"
                                          height="150"
                                          style="width:400px; height:150px; background:#fff; display:block; border-radius:4px; touch-action:none;">
                                  </canvas>
                              </div>

                              <div class="mt-2">
                                  <button type="button"
                                          id="btn-limpar"
                                          class="px-3 py-1 text-sm border border-gray-400 rounded hover:bg-gray-100">
                                      Limpar
                                  </button>
                              </div>
                              {{-- Mensagem de erro --}}
                              <p id="signature-error" class="hidden mt-2 text-sm text-red-600">
                              </p>

                          </div>

                          {{-- Botões de ação --}}
                          <div class="flex items-center justify-end px-4 py-3 bg-gray-50 shadow sm:rounded-bl-md sm:rounded-br-md gap-2">

                              {{-- Formulário salvar --}}
                              <form action="{{ route('profile.signature.save') }}" method="POST" id="form-assinatura">
                                  @csrf
                                  <input type="hidden" name="signature_base64" id="signature_base64">
                                  <button type="submit"
                                          class="px-4 py-2 bg-gray-800 text-white text-sm rounded hover:bg-gray-700">
                                      Salvar Assinatura
                                  </button>
                              </form>

                              {{-- Formulário remover --}}
                              @if(auth()->user()->signature_path)
                                  <form action="{{ route('profile.signature.delete') }}"
                                        method="POST"
                                        onsubmit="return confirm('Deseja remover sua assinatura?')">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit"
                                              class="px-4 py-2 bg-red-100 text-red-700 text-sm rounded hover:bg-red-200">
                                          Remover
                                      </button>
                                  </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
             @endif

<x-section-border />

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>
                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <x-section-border />
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signature-canvas');
            const signaturePad = new SignaturePad(canvas, {
                minWidth: 1,
                maxWidth: 3,
                penColor: '#000000'
            });

            document.getElementById('btn-limpar').addEventListener('click', () => {
                signaturePad.clear();
                esconderErro();
            });

            document.getElementById('form-assinatura').addEventListener('submit', function (e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    mostrarErro('Por favor, desenhe sua assinatura antes de salvar.');
                    return;
                }
                esconderErro();
                document.getElementById('signature_base64').value = signaturePad.toDataURL('image/png');
            });

            function mostrarErro(mensagem) {
                let erro = document.getElementById('signature-error');
                erro.textContent = mensagem;
                erro.classList.remove('hidden');
            }

            function esconderErro() {
                let erro = document.getElementById('signature-error');
                erro.classList.add('hidden');
            }
        });
    </script>
    @endpush

</x-app-layout>

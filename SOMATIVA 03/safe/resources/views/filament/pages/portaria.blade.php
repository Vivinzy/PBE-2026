<x-filament-panels::page>

    {{-- Saídas pendentes --}}
    <div class="space-y-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            Saídas aguardando confirmação
        </h2>

        @php $pendentes = $this->getSaidasPendentes(); @endphp

        @if($pendentes->isEmpty())
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 text-center text-gray-500 dark:text-gray-400">
                Nenhuma saída pendente no momento.
            </div>
        @else
            @foreach($pendentes as $reg)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 flex items-start gap-4">

                    {{-- Avatar --}}
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900 flex items-center justify-center text-amber-800 dark:text-amber-200 font-semibold text-sm">
                        {{ $reg->aluno->iniciais }}
                    </div>

                    {{-- Dados --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $reg->aluno->nome }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $reg->turma->nome }} · Saída: {{ $reg->horario->format('H:i') }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if($reg->faltas_aulas && count($reg->faltas_aulas))
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Faltas: {{ $reg->faltas_formatada }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Sem faltas
                                </span>
                            @endif

                            @if($reg->menor_de_idade)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    ⚠ Menor de idade
                                </span>
                            @endif

                            @if($reg->tem_empresa)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    🏢 {{ $reg->empresa_nome }}
                                </span>
                            @endif

                            @if($reg->responsavel_autorizado)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    ✓ Responsável autorizou
                                </span>
                            @endif
                        </div>

                        @if($reg->motivo)
                            <p class="text-xs text-gray-400 mt-1">Motivo: {{ $reg->motivo }}</p>
                        @endif
                    </div>

                    {{-- Ações --}}
                    <div class="flex flex-col gap-2 flex-shrink-0">
                        <button
                            wire:click="confirmarSaida({{ $reg->id }}, {{ $reg->responsavel_autorizado ? 'true' : 'false' }})"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium bg-green-600 hover:bg-green-700 text-white transition-colors"
                        >
                            ✓ Confirmar saída
                        </button>
                        <button
                            wire:click="negarSaida({{ $reg->id }})"
                            class="px-3 py-1.5 rounded-lg text-sm font-medium border border-red-300 text-red-600 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors"
                        >
                            ✕ Negar
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Saídas confirmadas hoje --}}
    <div class="space-y-3 mt-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            Confirmadas hoje
        </h2>

        @php $confirmadas = $this->getSaidasConfirmadas(); @endphp

        @if($confirmadas->isEmpty())
            <p class="text-sm text-gray-400">Nenhuma saída confirmada ainda hoje.</p>
        @else
            @foreach($confirmadas as $reg)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-3 flex items-center gap-3 opacity-70">
                    <div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-700 dark:text-green-300 font-semibold text-xs">
                        {{ $reg->aluno->iniciais }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $reg->aluno->nome }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $reg->turma->nome }} · Saída: {{ $reg->horario->format('H:i') }}
                            @if($reg->confirmado_at)
                                · Confirmado às {{ $reg->confirmado_at->format('H:i') }}
                                por {{ $reg->confirmadoPor?->name }}
                            @endif
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        ✓ Confirmado
                    </span>
                </div>
            @endforeach
        @endif
    </div>

</x-filament-panels::page>
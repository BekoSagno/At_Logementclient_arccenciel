<div class="space-y-4">
    <div>
        <h3 class="text-sm font-medium text-gray-700 mb-2">Action</h3>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
            @if($record->action === 'created') bg-green-100 text-green-800
            @elseif($record->action === 'updated') bg-blue-100 text-blue-800
            @elseif($record->action === 'deleted' || $record->action === 'force_deleted') bg-red-100 text-red-800
            @elseif($record->action === 'restored') bg-yellow-100 text-yellow-800
            @else bg-gray-100 text-gray-800
            @endif">
            @if($record->action === 'created') Créé
            @elseif($record->action === 'updated') Modifié
            @elseif($record->action === 'deleted') Supprimé
            @elseif($record->action === 'restored') Restauré
            @elseif($record->action === 'force_deleted') Supprimé définitivement
            @else {{ $record->action }}
            @endif
        </span>
    </div>

    @if($record->user)
        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-2">Utilisateur</h3>
            <p class="text-sm text-gray-900">{{ $record->user->name }} ({{ $record->user->email }})</p>
        </div>
    @endif

    <div>
        <h3 class="text-sm font-medium text-gray-700 mb-2">Date</h3>
        <p class="text-sm text-gray-900">{{ $record->created_at->format('d/m/Y à H:i:s') }}</p>
    </div>

    @if($record->changes)
        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-2">Résumé des changements</h3>
            <p class="text-sm text-gray-900">{{ $record->changes }}</p>
        </div>
    @endif

    @if($record->old_data || $record->new_data)
        <div class="grid grid-cols-2 gap-4">
            @if($record->old_data)
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Avant</h3>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($record->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

            @if($record->new_data)
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Après</h3>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <pre class="text-xs text-gray-700 whitespace-pre-wrap">{{ json_encode($record->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

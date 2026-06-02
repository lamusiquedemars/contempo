<div class="space-y-3">
    @if ($deliveries->isEmpty())
        <p class="text-sm text-gray-600">Aucune livraison enregistrée pour ce message.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="px-2 py-1 font-medium">Email</th>
                        <th class="px-2 py-1 font-medium">Statut</th>
                        <th class="px-2 py-1 font-medium">Date</th>
                        <th class="px-2 py-1 font-medium">Erreur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveries as $delivery)
                        <tr class="border-t">
                            <td class="px-2 py-1">{{ $delivery->email }}</td>
                            <td class="px-2 py-1">
                                {{ $delivery->status === 'sent' ? 'Livré' : 'Échec' }}
                            </td>
                            <td class="px-2 py-1">
                                {{ $delivery->sent_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>
                            <td class="px-2 py-1">
                                {{ $delivery->error_message ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

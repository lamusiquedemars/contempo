<p>Nouveau message reçu depuis le site.</p>

<p><strong>Nom :</strong> {{ $messageData->name }}</p>
<p><strong>Email :</strong> {{ $messageData->email }}</p>
@if ($messageData->phone)
    <p><strong>Téléphone :</strong> {{ $messageData->phone }}</p>
@endif
@if ($messageData->subject)
    <p><strong>Sujet :</strong> {{ $messageData->subject }}</p>
@endif
<p><strong>Message :</strong></p>
<p>{{ $messageData->message }}</p>

<p>Bonjour,</p>

<p>Nous avons bien reçu votre message envoyé depuis le site.</p>

<p><strong>Votre email :</strong> {{ $messageData->email }}</p>
@if ($messageData->name)
    <p><strong>Nom :</strong> {{ $messageData->name }}</p>
@endif
@if ($messageData->subject)
    <p><strong>Sujet :</strong> {{ $messageData->subject }}</p>
@endif
<p><strong>Message :</strong></p>
<p>{{ $messageData->message }}</p>

<p>Nous revenons vers vous rapidement.</p>

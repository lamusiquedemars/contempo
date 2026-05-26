<p>Nouveau message recu depuis le site.</p>

<p><strong>Nom :</strong> {{ $submission->name }}</p>
<p><strong>Email :</strong> {{ $submission->email }}</p>
@if ($submission->phone)
    <p><strong>Telephone :</strong> {{ $submission->phone }}</p>
@endif
@if ($submission->subject)
    <p><strong>Sujet :</strong> {{ $submission->subject }}</p>
@endif
<p><strong>Message :</strong></p>
<p>{{ $submission->message }}</p>

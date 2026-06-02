<p>Bonjour{{ $contact->first_name ? ' '.$contact->first_name : '' }},</p>

{!! nl2br(e($segmentMessage->body)) !!}

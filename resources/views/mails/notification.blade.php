@component('mail::message')
<h1>Eingang Beitragsgesuch</h1>
<p>{{ $application->firstname }} {{ $application->lastname }} ({{ $application->name }}) hat ein neues Gesuch eingereicht.
<p>Freundliche Grüsse<br><br>{{ env('APP_NAME') }}</p>
@endcomponent
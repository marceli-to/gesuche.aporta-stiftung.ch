@component('mail::message')
<h1>Bestätigung Beitragsgesuch</h1>
<p>Sehr geehrte {{ $application->firstname }} {{ $application->lastname }}</p>
<p>Wir haben Ihre Daten erhalten und werden diese in der kommenden Zeit prüfen.</p>
<p>Freundliche Grüsse<br><br>{{ env('APP_NAME') }}</p>
@endcomponent
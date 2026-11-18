@component('mail::message')
<h1>Bearbeitung Ihres Beitragsgesuchs</h1>
<p>Sehr geehrte/r {{ $application->firstname }} {{ $application->lastname }}</p>
<p>Über den folgenden Link können Sie Ihr Gesuch «{{ $application->project_title }}» selbst anpassen. Sie müssen kein neues Gesuch einreichen.</p>
@if ($note)
<p>{!! nl2br(e($note)) !!}</p>
@endif
@component('mail::button', ['url' => $url])
Gesuch bearbeiten
@endcomponent
<p>Der Link ist gültig bis {{ $expiresAt->format('d.m.Y') }}. Bis dahin können Sie Ihre Änderungen beliebig oft speichern. Bitte geben Sie den Link nicht weiter.</p>
<p>Falls die Schaltfläche nicht funktioniert, kopieren Sie diese Adresse in Ihren Browser:<br>{{ $url }}</p>
<p>
Freundliche Grüsse<br>
<div>
  <img src="https://gesuche.aporta-stiftung.ch/assets/img/logo.png" height="43" width="100" style="display: block; height: 43px; width: 100px">
</div>
</p>
@endcomponent

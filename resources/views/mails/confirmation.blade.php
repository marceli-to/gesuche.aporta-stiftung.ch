@component('mail::message')
<h1>Bestätigung Beitragsgesuch</h1>
<p>Sehr geehrte/r {{ $application->firstname }} {{ $application->lastname }}</p>
<p>Wir haben Ihr Gesuch heute erhalten. Besten Dank. Über die Verteilung des Reinertrages unseres Stiftungsvermögens wird im nächsten Sommer entschieden. Sie werden in jedem Fall eine Nachricht erhalten.</p>
<p>
Freundliche Grüsse<br>
<div>
  <img src="https://gesuche.aporta-stiftung.ch/assets/img/logo.png" height="43" width="100" style="display: block; height: 43px; width: 100px">
</div>
</p>
@endcomponent
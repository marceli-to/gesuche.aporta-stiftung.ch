@component('mail::message')
<h1>Änderungen gespeichert</h1>
<p>Sehr geehrte/r {{ $application->firstname }} {{ $application->lastname }}</p>
<p>Ihre Änderungen an Ihrem Gesuch «{{ $application->project_title }}» wurden gespeichert. Besten Dank. Der Bearbeitungslink ist damit nicht mehr gültig.</p>
<p>Falls Sie diese Änderungen nicht selbst vorgenommen haben, wenden Sie sich bitte umgehend an die Stiftung.</p>
<p>
Freundliche Grüsse<br>
<div>
  <img src="https://gesuche.aporta-stiftung.ch/assets/img/logo.png" height="43" width="100" style="display: block; height: 43px; width: 100px">
</div>
</p>
@endcomponent

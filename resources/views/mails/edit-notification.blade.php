@component('mail::message')
<p>{{ $application->name }} hat das Gesuch «{{ $application->project_title }}» über den Bearbeitungslink geändert.</p>
@if ($bank)
<p style="padding: 12px; border: 2px solid #c00; color: #c00;"><strong>Achtung: Die Bankangaben wurden geändert.</strong> Bitte prüfen Sie die Änderung, bevor Sie Zahlungen auslösen, zum Beispiel mit einem Rückruf an die bekannte Telefonnummer.</p>
@endif
@if (count($changes))
<table style="width: 100%; border-collapse: collapse;">
<tr><th style="text-align: left; padding: 6px; border-bottom: 1px solid #ccc;">Feld</th><th style="text-align: left; padding: 6px; border-bottom: 1px solid #ccc;">Vorher</th><th style="text-align: left; padding: 6px; border-bottom: 1px solid #ccc;">Neu</th></tr>
@foreach ($changes as $change)
<tr style="{{ $change['bank'] ? 'color: #c00; font-weight: bold;' : '' }}"><td style="padding: 6px; border-bottom: 1px solid #eee; vertical-align: top;">{{ $change['label'] }}</td><td style="padding: 6px; border-bottom: 1px solid #eee; vertical-align: top;">{!! nl2br(e($change['old'])) !!}</td><td style="padding: 6px; border-bottom: 1px solid #eee; vertical-align: top;">{!! nl2br(e($change['new'])) !!}</td></tr>
@endforeach
</table>
@else
<p>Es wurden keine Angaben geändert.</p>
@endif
@endcomponent

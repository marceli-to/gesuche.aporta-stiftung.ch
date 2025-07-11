@include('pdf.partials.header')
@foreach($data as $d)
<div class="content @if (!$loop->last) break @endif">
<div>{{ $d->name }}<br>{{ $d->firstname }} {{ $d->lastname }}<br>{{ $d->street }} {{ $d->street_number ?? $d->street_number }}<br>{{ $d->zip }} {{ $d->city }}</div>
<br><br><br><br><br>
<div>Zürich, 10. Juli 2025 AW/pc</div>
<br><br><br>
<div><strong>Vergabung aus unserem Reinertrag pro 2024<br>Ihr Beitragsgesuch vom {{ $d->created_at_formated }}</strong></div>
<br><br>
<div>{!! $d->salutation ? nl2br($d->salutation)  : 'Sehr geehrte Damen und Herren' !!}</div>
<br>
<div>Wir freuen uns, Ihnen mitteilen zu können, dass der Stiftungsrat anlässlich seiner Sitzung vom 17. Juni 2025 gemäss Vorschlag des Stadtrates von Zürich beschlossen hat, Ihrer Institution aus dem Reinertrag unseres Stiftungsvermögens den Betrag von CHF {{ AppHelper::number($d->project_contribution_approved) }}, als Beitrag an {{ $d->textblock_approval }}, zukommen zu lassen.<br><br>@if ($d->textblock_justification) {{ $d->textblock_justification }} @endif Der Beitrag muss zweckgebunden verwendet werden.</div>
<br>
<div>Wir bitten Sie, uns den Eingang des erwähnten Betrages, welchen wir Ihnen bis Ende Juli 2025 auf Ihr Konto mit IBAN {{ $d->iban_formated ? $d->iban_formated :  $d->bank_account }} überweisen werden, umgehend schriftlich zu bestätigen.</div>
<br>
<div>Weiter sind wir Ihnen dankbar, wenn Sie, dem Wunsch des Stifters entsprechend, im Gönnerverzeichnis Ihres Jahresberichtes vermerken würden, dass es sich um eine Zuwendung unserer Stiftung handelt.</div>
<br>
@if ($d->project_contribution_approved >= '20000')
<div>Bitte reichen Sie uns bis spätestens Ende März 2026 Ihren Schlussbericht ein. Genauere Informationen entnehmen Sie bitte der Beilage.</div>
<br>
@endif
<div>Freundliche Grüsse</div>
<br>
<div>DR. STEPHAN À PORTA-STIFTUNG</div>
<br><br><br>
<div>Andrea Wolfer</div>
@if ($d->project_contribution_approved >= '20000')
<br><br><br>
<div>Beilage:<br>Merkblatt Schlussbericht</div>
@endif
</div>
@endforeach
@include('pdf.partials.footer')
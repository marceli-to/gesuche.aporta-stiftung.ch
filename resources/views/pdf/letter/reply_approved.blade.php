@include('pdf.partials.header')
@foreach($data as $d)
<div class="content @if (!$loop->last) break @endif">
<p>
  {{ $d->name }}<br>
  {{ $d->firstname }} {{ $d->lastname }}<br>
  {{ $d->street }} {{ $d->street_number }}<br>
  {{ $d->zip }} {{ $d->city }}
</p>
<p style="margin-top: 15mm">Zürich, {{ strftime('%e. %B %Y', time()) }} AI/pc</p>
<p style="margin-top: 10mm"><strong>Vergabung aus unserem Reinertrag pro 2021<br>Ihr Beitragsgesuch vom {{ $d->created_at_formated }}</strong></p>
<p>{{ $d->salutation ? $d->salutation  : 'Sehr geehrte Damen und Herren'}}</p>
<p>Wir freuen uns, Ihnen mitteilen zu können, dass der Stiftungsrat anlässlich seiner Sitzung vom 28. Juni 2022 gemäss Vorschlag des Stadtrates von Zürich beschlossen hat, Ihrer Institution aus dem Reinertrag unseres Stiftungsvermögens den Betrag von CHF {{ $d->project_contribution_approved }}, als Beitrag an {{ $d->textblock_approval }} zukommen zu lassen. Der Beitrag muss zweckgebunden verwendet werden.</p> 
<p>Wir bitten Sie, uns den Eingang des erwähnten Betrages, welchen wir Ihnen bis Ende Juli 2022 auf Ihr {{ $d->financial_account ? $d->financial_account :  $d->bank_account }} überweisen werden, umgehend schriftlich zu bestätigen.</p>
<p>Weiter sind wir Ihnen dankbar, wenn Sie, dem Wunsch des Stifters entsprechend, im Gönnerverzeichnis Ihres Jahresberichtes vermerken würden, dass es sich um eine Zuwendung unserer Stiftung handelt.</p>
@if ($d->project_contribution_approved >= '20000')
<p>Bitte reichen Sie uns bis spätestens Ende März 2023 Ihren Schlussbericht ein. Genauere Informationen entnehmen Sie bitte der Beilage.</p>
@endif
<p>Freundliche Grüsse<br><br>DR. STEPHAN À PORTA-STIFTUNG</p>
<p>Armin Isler</p>
@if ($d->project_contribution_approved >= '20000')
<p style="margin-top: 5mm">Beilage:<br>Merkblatt Schlussbericht</p>
@endif
</div>
@endforeach
@include('pdf.partials.footer')
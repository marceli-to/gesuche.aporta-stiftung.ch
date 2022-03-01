@include('pdf.partials.header')
@foreach($data as $d)
<div class="content break">
<p>
  {{ $d->name }}<br>
  {{ $d->firstname }} {{ $d->lastname }}<br>
  {{ $d->street }} {{ $d->street_number }}<br>
  {{ $d->zip }} {{ $d->city }}
</p>
<p style="margin-top: 20mm">Zürich, {{ date('j. F Y', time()) }}</p>
<p><strong>Vergabung aus unserem Reinertrag pro 2020<br>Ihr Beitragsgesuch vom Dezember 2020</strong></p>
<p>Sehr geehrte Damen und Herren</p>
<p>Wir freuen uns, Ihnen mitteilen zu können, dass der Stiftungsrat anlässlich seiner Sitzung vom 14. Juni 2021 gemäss Vorschlag des Stadtrates von Zürich beschlossen hat, Ihrer Institution aus dem Reinertrag unseres Stiftungsvermögens den Betrag von CHF 30'000.--, als Beitrag an Ersatz Infrastruktur Werkstatt, zukommen zu lassen. Der Beitrag muss zweckgebunden verwendet werden.</p>
<p>Wir bitten Sie, uns den Eingang des erwähnten Betrages, welchen wir Ihnen bis Ende Juli 2021 auf Ihr Postkonto Nr. 80-60450-7 (IBAN CH42 0900 0000 8006 0450 7) überweisen werden, umgehend schriftlich zu bestätigen.</p>
<p>Weiter sind wir Ihnen dankbar, wenn Sie, dem Wunsch des Stifters entsprechend, im Gönnerverzeichnis Ihres Jahresberichtes vermerken würden, dass es sich um eine Zuwendung unserer Stiftung handelt.</p>
<p>Bitte reichen Sie uns bis spätestens Ende März 2022 Ihren Schlussbericht ein. Genauere Informationen entnehmen Sie bitte der Beilage.</p>
<p>Freundliche Grüsse<br><br>DR. STEPHAN À PORTA-STIFTUNG</p>
<p>Armin Isler</p>
</div>
@endforeach


@include('pdf.partials.footer')
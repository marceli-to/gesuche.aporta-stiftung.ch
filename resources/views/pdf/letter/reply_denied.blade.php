@include('pdf.partials.header')
@foreach($data as $d)
<div class="content @if (!$loop->last) break @endif">
<div>{{ $d->name }}<br>{{ $d->firstname }} {{ $d->lastname }}<br>{{ $d->street }} {{ $d->street_number }}<br>{{ $d->zip }} {{ $d->city }}</div>
<br><br><br><br><br>
<div>Zürich, {{ strftime('%e. %B %Y', time()) }} AI/pc</div>
<br><br><br>
<div><strong>Gesuch um finanzielle Unterstützung</strong></div>
<br><br>
<div>{!! $d->salutation ? nl2br($d->salutation)  : 'Sehr geehrte Damen und Herren' !!}</div>
<br>
<div>Wir nehmen Bezug auf Ihr Gesuch vom {{ $d->created_at_formated }}.</div>
<br>
<div>Unser Stiftungsrat hat in seiner Sitzung vom 27. Juni 2023 über die Zuwendungen aus dem Reinertrag des Stiftungsvermögens pro 2022 Beschluss gefasst.</div>
<br>
<div>Leider konnten wir Ihr Gesuch nicht berücksichtigen. {{ $d->textblock_denial }}</div>
<br>
<div>Wir bedauern, Ihnen keinen positiven Bescheid geben zu können und hoffen, dass Sie anderweitig den gewünschten Erfolg haben werden.</div>
<br>
<div>Freundliche Grüsse</div>
<br>
<div>DR. STEPHAN À PORTA-STIFTUNG</div>
<br><br><br>
<div>Armin Isler</div>
</div>
@endforeach
@include('pdf.partials.footer')
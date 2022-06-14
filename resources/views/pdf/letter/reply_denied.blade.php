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
<p style="margin-top: 10mm"><strong>Gesuch um finanzielle Unterstützung</strong></p>
<p>{{ $d->salutation ? $d->salutation  : 'Sehr geehrte Damen und Herren'}}</p>
<p>Wir nehmen Bezug auf Ihr Gesuch vom {{ $d->created_at_formated }}.</p>
<p>Unser Stiftungsrat hat in seiner Sitzung vom 20. Juni 2022 über die Zuwendungen aus dem Reinertrag des Stiftungsvermögens pro 2021 Beschluss gefasst.</p>
<p>Leider konnten wir Ihr Gesuch nicht berücksichtigen. {{ $d->textblock_denial }}</p>
<p>Wir bedauern, Ihnen keinen positiven Bescheid geben zu können und hoffen, dass Sie anderweitig den gewünschten Erfolg haben werden.</p>
<p>Freundliche Grüsse<br><br>DR. STEPHAN À PORTA-STIFTUNG</p>
<p>Armin Isler</p>
</div>
@endforeach
@include('pdf.partials.footer')
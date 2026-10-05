<?php
namespace App\Exports;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ApplicationExport implements FromCollection, WithHeadings, WithEvents, WithColumnWidths
{
  public function __construct(string $state, $archived = FALSE, $year = NULL)
  {
    $this->state = $state;
    $this->archived = $archived;
    $this->year = $year;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    // Number applications by date of receipt (oldest = 1), same as the backend list
    $numbers = $this->numbers();

    if (auth()->user()->isAdmin())
    {
      $query = Application::orderBy('created_at', 'ASC');

      if ($this->archived != 'false')
      {
        $query->archive();
      }
      else
      {
        $query->current();
      }

      if ($this->state == 'export_new')
      {
        $query->where('application_state_id', 1);
      }

      if ($this->state == 'export_denied')
      {
        $query->where('application_state_id', 5);
      }

      if ($this->state == 'export_approved')
      {
        $query->where('application_state_id', 6);
      }

      if ($this->year && $this->year != 'null')
      {
        $query->where('year', $this->year);
      }

      $applications = $query->get();
    }
    else
    {
      $applications = Application::current()->where('application_state_id', '>', 1)->orderBy('created_at', 'ASC')->get();
    }
    
    $data = [];
    foreach($applications as $s)
    {
      $data[] = [
        'Nummer' => $numbers[$s->id] ?? '',
        'Name Organisation' => $s->name,
        'Vorheriger Name Organisation' => $s->former_name,
        'Titel (Projekt)' => $s->project_title,
        'Projektinhalt' => '',
        'Stadtzürcher Anteil in %' => $s->proportion_residents_benefit_program,
        'Stadtzürcher Anteil in absoluten Zahlen' => $s->number_residents_benefit_program,
        'Nutzen für Zielgruppe' => '',
        'Gesamtkosten CHF' => $s->project_cost_total,
        'Beantragter Beitrag CHF' => $s->project_contribution_requested,
        'Vorschlag Stiftung CHF' => $s->project_contribution_approved_temporary,
        'Vorschlag Stadt ZH CHF' => '',
      ];
    }
    return collect($data);
  }

  private function numbers(): array
  {
    if (auth()->user()->isAdmin())
    {
      $query = $this->archived != 'false' ? Application::archive() : Application::current();
    }
    else
    {
      $query = Application::current()->editor();
    }

    return $query->orderBy('created_at', 'ASC')
      ->pluck('id')
      ->flip()
      ->map(fn($index) => $index + 1)
      ->all();
  }

  public function headings(): array
  {
    $year = $this->year && $this->year != 'null' ? $this->year : date('Y');

    return [
      ['Dr. Stephan à Porta-Stiftung_Vorschlag für die Zuwendungen aus dem Reinertrag ' . $year . '_Tabelle Gesuche_Zuwendungen - (Stand ' . date('d.m.Y') . ')'],
      [''],
      [
        'Nummer',
        'Name Organisation',
        'Vorheriger Name Organisation',
        'Titel (Projekt)',
        'Projektinhalt (Infrastruktur, Bau, IT, Projekt, Betriebsbeitrag)',
        'Stadtzürcher Anteil in % (projektbezogen)',
        'Stadtzürcher Anteil in absoluten Zahlen (projektbezogen)',
        'Nutzen für Zielgruppe',
        'Gesamtkosten CHF',
        'Beantragter Beitrag CHF',
        'Vorschlag Stiftung CHF',
        'Vorschlag Stadt ZH CHF',
      ],
    ];
  }

  public function columnWidths(): array
  {
    return [
      'A' => 9,
      'B' => 40,
      'C' => 36,
      'D' => 40,
      'E' => 18,
      'F' => 18,
      'G' => 16,
      'H' => 16,
      'I' => 12,
      'J' => 12,
      'K' => 12,
      'L' => 12,
    ];
  }

  /**
   * @return array
   */
  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function(AfterSheet $event) {
        $sheet = $event->sheet->getDelegate();
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A3:L3')->getFont()->setBold(true);
        $sheet->getStyle('A3:L' . $sheet->getHighestRow())->getAlignment()->setWrapText(true)->setVertical('top');
      },
    ];
  }
}

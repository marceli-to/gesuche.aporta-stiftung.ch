<?php
namespace App\Exports;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;

class ApplicationExportByYear implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
  public function __construct(int $year)
  {
    $this->year = $year;
  }

  public function collection()
  {
    $applications = Application::where('year', $this->year)->get();
    $data = [];
    foreach($applications as $s)
    {
      $data[] = [
        'Eingang' => $s->created_at_formated,
        'Organisation' => $s->name,
        'Kosten Total' => $s->project_cost_total,
        'Eigenleistung' => $s->project_own_contribution,
        'Beitrag Beantragt' => $s->project_contribution_requested, 
        'Beitrag Vorgeschlagen' => $s->project_contribution_approved_temporary,
        'Beitrag Bewilligt' => $s->project_contribution_approved,
        'Kontakt' => $s->firstname . ' ' . $s->lastname,
        'E-Mail' => $s->email,
      ];
    }
    return collect($data);
  }

  public function headings(): array
  {
    return [
      'Eingang',
      'Organisation',
      'Kosten Total',
      'Eigenleistung',
      'Beitrag Beantragt', 
      'Beitrag Vorgeschlagen',
      'Beitrag Bewilligt',
      'Kontakt',
      'E-Mail',
    ];
  }

  /**
   * @return array
   */
  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function(AfterSheet $event) {
        $cellRange = 'A1:I1';
        $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
      },
    ];
  }
}

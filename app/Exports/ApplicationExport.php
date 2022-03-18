<?php
namespace App\Exports;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ApplicationExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    if (auth()->user()->isAdmin())
    {
      $applications = Application::orderBy('created_at', 'ASC')->get();
    }
    else
    {
      $applications = Application::where('application_state_id' > 2)->orderBy('created_at', 'ASC')->get();
    }
    
    $data = [];
    foreach($applications as $s)
    {
      $data[] = [
        'Eingang' => $s->created_at_formated,
        'Organisation' => $s->name,
        'Kosten Total' => $s->project_cost_total,
        'Eigenleistung' => $s->project_own_contribution,
        'Betrag Beantragt' => $s->project_contribution_requested, 
        'Betrag Vorgeschlagen' => $s->project_contribution_approved_temporary,
        'Betrag Bewilligt' => $s->project_contribution_approved,
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
      'Betrag Beantragt', 
      'Betrag Vorgeschlagen',
      'Betrag Bewilligt',
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

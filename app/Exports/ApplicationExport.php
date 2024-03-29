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
    if (auth()->user()->isAdmin())
    {
      $query = Application::with('comments.user')->orderBy('created_at', 'ASC');

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
      $applications = Application::current()->with('comments.user')->where('application_state_id', '>', 1)->orderBy('created_at', 'ASC')->get();
    }
    
    $data = [];
    foreach($applications as $s)
    {
      // Build a string out of all comments
      // Author, Comment
      if ($s->comments)
      {
        $comments = '';
        foreach($s->comments as $comment)
        {
          $comments .= $comment->user->firstname . ' ' . $comment->user->name . ': ' . $comment->comment . " / ";
        }
        // remove the last occurence of " / "
        $comments = substr($comments, 0, strrpos($comments, ' / '));
      }

      $data[] = [
        'Eingang' => $s->created_at_formated,
        'Titel' => $s->project_title,
        'Organisation' => $s->name,
        'Kosten Total' => $s->project_cost_total,
        'Eigenleistung' => $s->project_own_contribution,
        'Beitrag Beantragt' => $s->project_contribution_requested, 
        'Beitrag Vorgeschlagen' => $s->project_contribution_approved_temporary,
        'Beitrag Bewilligt' => $s->project_contribution_approved,
        'Kontakt' => $s->firstname . ' ' . $s->lastname,
        'E-Mail' => $s->email,
        'Anteil Stadtzürcher*innen' => $s->remarks_direct_benefits_to_target_group,
        'Ausserordentlichkeit Vorhaben' => $s->remarks_exceptionality_of_project ? 'Ja' : 'Nein',
        'Weitere relevante Informationen' => $s->remarks_additional_relevant_information,
        'Inhaltliche Zuordnung' => $s->remarks_content_allocation,
        'Begründung' => $s->justification_funds,
        'Kommentare' => $comments ?? ''
      ];
    }
    return collect($data);
  }

  public function headings(): array
  {
    return [
      'Eingang',
      'Titel',
      'Organisation',
      'Kosten Total',
      'Eigenleistung',
      'Beitrag Beantragt', 
      'Beitrag Vorgeschlagen',
      'Beitrag Bewilligt',
      'Kontakt',
      'E-Mail',
      'Anteil Stadtzürcher*innen',
      'Ausserordentlichkeit Vorhaben',
      'Weitere relevante Informationen',
      'Inhaltliche Zuordnung',
      'Begründung',
      'Kommentare'
    ];
  }

  /**
   * @return array
   */
  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function(AfterSheet $event) {
        $cellRange = 'A1:P1';
        $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
      },
    ];
  }
}

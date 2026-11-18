<?php
namespace App\Services;
use App\Models\Application;
use App\Models\ApplicationFile;

/**
 * Builds the list of changes an applicant made through the edit link.
 * Each change is ['field', 'label', 'old', 'new', 'bank'], stored in the
 * log entry and shown in the notification mail.
 */
class ApplicationChanges
{
  public const LABELS = [
    'name' => 'Rechtlicher Name der Institution',
    'former_name' => 'Vorheriger rechtlicher Name der Institution',
    'legal_form' => 'Rechtsform',
    'website' => 'Webseite',
    'bank_account' => 'Bankkonto',
    'iban' => 'IBAN',
    'beneficiary' => 'Begünstigter',
    'street' => 'Strasse',
    'street_number' => 'Nummer',
    'zip' => 'PLZ',
    'city' => 'Ort',
    'firstname' => 'Vorname',
    'lastname' => 'Name',
    'phone' => 'Telefon',
    'email' => 'E-Mail',
    'portrait' => 'Kurzportrait',
    'project_title' => 'Projekttitel',
    'project_description' => 'Beschreibung und Begründung des Projekts',
    'project_finance' => 'Finanzierung',
    'justification_funds' => 'Kurzbegründung Mittelbedarf',
    'project_beneficiaries' => 'Begünstigte des Projekts',
    'proportion_residents_benefit_program' => '% Anteil begünstigte Stadtzürcherinnen und Stadtzürcher',
    'number_residents_benefit_program' => 'Anzahl begünstigte Stadtzürcherinnen und Stadtzürcher',
    'project_time' => 'Projektdauer/Zeitraum',
    'project_cost_total' => 'Projektkosten Total (CHF)',
    'project_own_contribution' => 'Eigenleistungen Total (CHF)',
    'project_contribution_requested' => 'Beantragter Beitrag (CHF)',
    'project_contribution_further_requested' => 'Weitere beantragte Beiträge (CHF)',
    'project_income' => 'Budgetierte Einnahmen (CHF)',
    'project_income_remarks' => 'Wie kommen die Einnahmen zustande?',
    'project_add_instit_2' => 'Weitere Institution 1',
    'project_add_instit_3' => 'Weitere Institution 2',
    'project_add_instit_4' => 'Weitere Institution 3',
    'project_add_instit_5' => 'Weitere Institution 4',
    'project_add_instit_total_2' => 'Weitere Institution 1, Betrag (CHF)',
    'project_add_instit_total_3' => 'Weitere Institution 2, Betrag (CHF)',
    'project_add_instit_total_4' => 'Weitere Institution 3, Betrag (CHF)',
    'project_add_instit_total_5' => 'Weitere Institution 4, Betrag (CHF)',
    'project_add_instit_answer_2' => 'Weitere Institution 1, Antwort',
    'project_add_instit_answer_3' => 'Weitere Institution 2, Antwort',
    'project_add_instit_answer_4' => 'Weitere Institution 3, Antwort',
    'project_add_instit_answer_5' => 'Weitere Institution 4, Antwort',
  ];

  public const FILE_LABELS = [
    'tax_exemption' => 'Datei: Steuerbefreiung',
    'annual_report' => 'Datei: Jahresbericht',
    'annual_financial_report' => 'Datei: Jahresrechnung',
    'budget' => 'Datei: Budget',
    'bylaws' => 'Datei: Statuten',
    'project_description' => 'Datei: Projektbeschreibung',
    'project_estimated_costs' => 'Datei: Kostenvoranschlag',
  ];

  /**
   * Changes of the applicant fields, computed from the dirty attributes of a
   * filled but not yet saved application.
   */
  public static function fields(Application $application): array
  {
    $changes = [];
    foreach (Application::APPLICANT_FIELDS as $field) {
      $old = $application->getOriginal($field);
      $new = $application->getAttribute($field);
      if (self::equal($old, $new)) {
        continue;
      }
      $changes[] = [
        'field' => $field,
        'label' => self::LABELS[$field] ?? $field,
        'old' => self::display($field, $old),
        'new' => self::display($field, $new),
        'bank' => in_array($field, Application::BANK_FIELDS),
      ];
    }
    return $changes;
  }

  /**
   * A file change: $old and $new are file names (null if none).
   */
  public static function file(string $key, ?string $old, ?string $new): array
  {
    return [
      'field' => 'file_' . $key,
      'label' => self::FILE_LABELS[$key] ?? ('Datei: ' . (ApplicationFile::FORM_KEYS[$key] ?? $key)),
      'old' => $old ?? '',
      'new' => $new ?? '',
      'bank' => false,
    ];
  }

  public static function hasBankChanges(array $changes): bool
  {
    foreach ($changes as $change) {
      if (!empty($change['bank'])) {
        return true;
      }
    }
    return false;
  }

  /**
   * null and '' are the same; amounts ignore trailing decimal zeros
   * ("1000" == "1000.00"). Strings are compared exactly, never as floats:
   * an IBAN is numeric too, and floats would round two IBANs to one value.
   */
  private static function equal($old, $new): bool
  {
    return self::normalize($old) === self::normalize($new);
  }

  private static function normalize($value): string
  {
    $value = $value === null ? '' : trim((string) $value);
    if (preg_match('/^-?\d+\.\d+$/', $value)) {
      $value = rtrim(rtrim($value, '0'), '.');
    }
    return $value;
  }

  private static function display(string $field, $value): string
  {
    if ($value === null || $value === '') {
      return '';
    }
    if ($field === 'iban') {
      return trim(chunk_split('CH' . $value, 4, ' '));
    }
    return (string) $value;
  }
}

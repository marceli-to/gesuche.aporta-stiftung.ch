<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Services\Pdf;
use Illuminate\Console\Command;

class GenerateSamplePdf extends Command
{
    /**
     * php artisan pdf:sample              -> approval letter
     * php artisan pdf:sample reply_denied -> denial letter
     */
    protected $signature = 'pdf:sample {type=reply_approved : reply_approved or reply_denied}';

    protected $description = 'Generate a one-page sample serial letter with dummy data for layout testing';

    public function handle()
    {
        $type = $this->argument('type');

        if (!in_array($type, ['reply_approved', 'reply_denied'])) {
            $this->error("Unknown type '{$type}'. Use reply_approved or reply_denied.");
            return self::FAILURE;
        }

        $result = (new Pdf())->createMany(collect([$this->sampleApplication()]), $type);

        $this->info('Sample PDF generated:');
        $this->line($result['path']);

        return self::SUCCESS;
    }

    /**
     * Build an unsaved Application with sample data covering every field the
     * letter templates render (long name to test the two-line address wrap,
     * contribution >= 20000 to show the Schlussbericht block).
     */
    protected function sampleApplication(): Application
    {
        $app = new Application();

        $app->name = 'Verein für nachhaltige Quartierentwicklung und soziale Integration Zürich';
        $app->firstname = 'Erika';
        $app->lastname = 'Mustermann';
        $app->street = 'Musterstrasse';
        $app->street_number = '42';
        $app->zip = '8001';
        $app->city = 'Zürich';

        $app->created_at = '2025-09-08';
        $app->salutation = "Sehr geehrte Frau Mustermann";

        $app->iban = '2100700012006152207';
        $app->bank_account = '';

        $app->project_contribution_approved = 25000;
        $app->textblock_approval = 'das Projekt «Quartiertreff für alle»';
        $app->textblock_justification = 'Wir freuen uns, Ihr Engagement unterstützen zu dürfen.';
        $app->textblock_denial = 'Die zur Verfügung stehenden Mittel waren in diesem Jahr leider stark begrenzt.';

        return $app;
    }
}

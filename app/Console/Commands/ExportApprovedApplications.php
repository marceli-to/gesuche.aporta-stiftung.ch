<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\ApplicationState;
use Illuminate\Console\Command;

class ExportApprovedApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:approved-applications {--file=approved_applications.csv : The output CSV file name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export approved applications (state 6) to CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->option('file');
        
        $this->info('Exporting approved applications to CSV...');
        
        // Get all approved applications that are not soft deleted
        $applications = Application::where('application_state_id', ApplicationState::APPROVED)
            ->select('name', 'city', 'firstname', 'lastname', 'email', 'year', 'approved_at')
            ->orderBy('name')
            ->orderBy('year', 'desc')
            ->get();
            
        if ($applications->isEmpty()) {
            $this->warn('No approved applications found.');
            return;
        }
        
        // Create CSV content
        $csvContent = "Name,City,Firstname,Lastname,Email,Year,Approved At\n";
        
        foreach ($applications as $application) {
            $approvedAt = $application->approved_at ? $application->approved_at->format('d.m.Y') : '';
            
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $this->escapeCsvField($application->name),
                $this->escapeCsvField($application->city),
                $this->escapeCsvField($application->firstname),
                $this->escapeCsvField($application->lastname),
                $this->escapeCsvField($application->email),
                $this->escapeCsvField($application->year),
                $this->escapeCsvField($approvedAt)
            );
        }
        
        // Write to file in public storage for easy download access
        $filePath = storage_path('app/public/' . $filename);
        file_put_contents($filePath, $csvContent);
        
        // Generate download URL
        $downloadUrl = url('storage/' . $filename);
        
        $this->info("Successfully exported {$applications->count()} approved applications to: {$filePath}");
        $this->info("Download link: {$downloadUrl}");
    }
    
    /**
     * Escape CSV field to handle commas, quotes, and newlines
     */
    private function escapeCsvField($field)
    {
        if ($field === null) {
            return '';
        }
        
        // If field contains comma, quote, or newline, wrap in quotes and escape existing quotes
        if (strpos($field, ',') !== false || strpos($field, '"') !== false || strpos($field, "\n") !== false) {
            return '"' . str_replace('"', '""', $field) . '"';
        }
        
        return $field;
    }
}

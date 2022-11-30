<?php
namespace App\Console\Commands;
use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Console\Command;

class Files extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'application:files';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Moves files from application to application_files';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $applications = Application::get();

    foreach($applications as $application)
    {
      $file = ApplicationFile::create([
        'uuid' => \Str::uuid(),
        'name' => $application->file_portrait,
        'title' => 'Portrait',
        'application_id' => $application->id,
        'user_id' => 1, 
      ]);

      $file = ApplicationFile::create([
        'uuid' => \Str::uuid(),
        'name' => $application->file_annual_report,
        'title' => 'Jahresbericht',
        'application_id' => $application->id,
        'user_id' => 1, 
      ]);

      $file = ApplicationFile::create([
        'uuid' => \Str::uuid(),
        'name' => $application->file_annual_financial_report,
        'title' => 'Jahresrechnung',
        'application_id' => $application->id,
        'user_id' => 1, 
      ]);

      $file = ApplicationFile::create([
        'uuid' => \Str::uuid(),
        'name' => $application->file_budget,
        'title' => 'Budget',
        'application_id' => $application->id,
        'user_id' => 1, 
      ]);
      
      $file = ApplicationFile::create([
        'uuid' => \Str::uuid(),
        'name' => $application->file_bylaws,
        'title' => 'Statuen',
        'application_id' => $application->id,
        'user_id' => 1, 
      ]);

      $file = ApplicationFile::create([
        'uuid' => \Str::uuid(),
        'name' => $application->file_project_description,
        'title' => 'Projekt',
        'application_id' => $application->id,
        'user_id' => 1, 
      ]);
      
      $file = ApplicationFile::create([
        'uuid' => \Str::uuid(),
        'name' => $application->file_project_estimated_costs,
        'title' => 'KV',
        'application_id' => $application->id,
        'user_id' => 1, 
      ]);
    }

  }
}

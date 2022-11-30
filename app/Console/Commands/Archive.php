<?php
namespace App\Console\Commands;
use App\Models\Application;
use App\Models\ApplicationFile;
use Illuminate\Console\Command;

class Archive extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'application:archive';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Archives all applications and set year';

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
    $year = $this->ask('Set year to?');
    $this->info($year);

    $applications = Application::get();
    foreach($applications as $application)
    {
      if ($year)
      {
        $this->info('setting year to ' . $year);
        $application->year = $year;
      }

      $this->info('setting archive ' . true);
      $application->archive = 1;
      $application->save();
    }
  }
}

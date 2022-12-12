<?php
namespace App\Console\Commands;
use App\Models\MailQueue;
use Illuminate\Console\Command;

class MailClear extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'mail:clear';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clear the mail queue';

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
    MailQueue::truncate();
  }
}

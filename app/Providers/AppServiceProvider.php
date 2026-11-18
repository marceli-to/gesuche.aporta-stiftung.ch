<?php
namespace App\Providers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->preventRealMailLocally();
  }

  /**
   * Local development must never send real mail: unless SMTP points at a
   * local catcher (MailHog), fall back to the log mailer.
   */
  protected function preventRealMailLocally(): void
  {
    if (!$this->app->environment('local'))
    {
      return;
    }

    $mailer = config('mail.default');
    $host = config("mail.mailers.$mailer.host");
    $isLocalSmtp = config("mail.mailers.$mailer.transport") === 'smtp'
      && in_array($host, ['127.0.0.1', 'localhost', 'mailhog', 'mailpit'], true);

    if (!$isLocalSmtp && !in_array($mailer, ['log', 'array'], true))
    {
      config(['mail.default' => 'log']);
    }
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    setLocale(LC_ALL, 'de_CH.UTF-8');
    \Carbon\Carbon::setLocale('de_CH.UTF-8');
    if (!app()->isProduction())
    {
      Mail::alwaysTo(config('mail.to'));
    }
  }
}

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
    //
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
    Mail::alwaysTo(env('MAIL_TO'));

    // if (!app()->isProduction())
    // {
    //   Mail::alwaysTo(env('MAIL_TO'));
    // }
  }
}

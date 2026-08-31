<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Company name
  |--------------------------------------------------------------------------
  |
  */

  'company' => env('APORTA_COMPANY_NAME', 'à Porta Stiftung'),

  /*
  |--------------------------------------------------------------------------
  | E-Mail settings
  |--------------------------------------------------------------------------
  |
  */

  'email' => [
    'from' => env('APORTA_MAIL_FROM', 'marcel@jamon.digital'),
    'recipient' => env('APORTA_MAIL_RECIPIENT', 'm@marceli.to'),
    'bcc' => env('APORTA_MAIL_BCC', 'm@marceli.to'),
    'recipient_test' => env('APORTA_MAIL_RECIPIENT_TEST', 'm@marceli.to')
  ],

  /*
  |--------------------------------------------------------------------------
  | Password protection for the public application form (/formular)
  |--------------------------------------------------------------------------
  |
  | Set APORTA_FORM_PASSWORD=true in .env to require a password before the
  | form can be filled in. Passwords are managed in
  | storage/app/application-passwords.json.
  |
  */

  'form_password' => (bool) env('APORTA_FORM_PASSWORD', false),

  /*
  |--------------------------------------------------------------------------
  | Domain
  |--------------------------------------------------------------------------
  |
  */

  'domain' => env('APORTA_DOMAIN', 'https://gesuche.aporta-stiftung.ch'),

  /*
  |--------------------------------------------------------------------------
  | Chunk size for cron jobs
  |--------------------------------------------------------------------------
  |
  */

  'cron_chunk_size' => 3,
]
?>
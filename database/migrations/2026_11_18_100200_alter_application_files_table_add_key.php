<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Form field a file was uploaded for (tax_exemption, budget …). Only set
     * on files the applicant uploaded with the form; admin uploads stay null.
     */
    public function up(): void
    {
      Schema::table('application_files', function (Blueprint $table) {
        $table->string('key', 50)->nullable()->after('title');
      });

      // Backfill: the form stores a fixed title per field. Applicant files are
      // created by user 1 in the same request as the application, so only
      // files created within 5 minutes of their application count. The first
      // file per application and key wins.
      $titles = [
        'Steuerbefreiung' => 'tax_exemption',
        'Jahresbericht' => 'annual_report',
        'Jahresrechnung' => 'annual_financial_report',
        'Budget' => 'budget',
        'Statuten' => 'bylaws',
        'Statuen' => 'bylaws',
        'Projekt' => 'project_description',
        'KV' => 'project_estimated_costs',
        'Portrait' => 'portrait',
      ];

      $files = DB::table('application_files')
        ->join('applications', 'applications.id', '=', 'application_files.application_id')
        ->where('application_files.user_id', 1)
        ->whereIn('application_files.title', array_keys($titles))
        ->orderBy('application_files.id')
        ->get(['application_files.id', 'application_files.application_id', 'application_files.title', 'application_files.created_at', 'applications.created_at as application_created_at']);

      $seen = [];
      foreach ($files as $file) {
        if (!$file->created_at || !$file->application_created_at) {
          continue;
        }
        if (abs(strtotime($file->created_at) - strtotime($file->application_created_at)) > 300) {
          continue;
        }
        $key = $titles[$file->title];
        if (isset($seen[$file->application_id][$key])) {
          continue;
        }
        $seen[$file->application_id][$key] = true;
        DB::table('application_files')->where('id', $file->id)->update(['key' => $key]);
      }
    }

    public function down(): void
    {
      Schema::table('application_files', function (Blueprint $table) {
        $table->dropColumn('key');
      });
    }
};

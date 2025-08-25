<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('applications', function (Blueprint $table) {
      $table->text('project_description')->nullable()->after('project_time');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('applications', function (Blueprint $table) {
      $table->dropColumn('project_description');
    });
  }
};

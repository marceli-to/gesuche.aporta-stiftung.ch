<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log entries written by applicants have no user. `details` holds the
     * list of changed fields as JSON.
     */
    public function up(): void
    {
      Schema::table('application_logs', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->change();
        $table->text('details')->nullable()->after('action');
      });
    }

    public function down(): void
    {
      Schema::table('application_logs', function (Blueprint $table) {
        $table->dropColumn('details');
        $table->foreignId('user_id')->nullable(false)->change();
      });
    }
};

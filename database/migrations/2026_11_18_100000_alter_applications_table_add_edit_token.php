<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Edit link for applicants.
     * edit_token holds the SHA-256 hash of the token, never the token itself.
     */
    public function up(): void
    {
      Schema::table('applications', function (Blueprint $table) {
        $table->string('edit_token', 64)->nullable()->unique();
        $table->timestamp('edit_token_expires_at')->nullable();
        $table->timestamp('edited_by_applicant_at')->nullable();
      });
    }

    public function down(): void
    {
      Schema::table('applications', function (Blueprint $table) {
        $table->dropUnique(['edit_token']);
        $table->dropColumn(['edit_token', 'edit_token_expires_at', 'edited_by_applicant_at']);
      });
    }
};

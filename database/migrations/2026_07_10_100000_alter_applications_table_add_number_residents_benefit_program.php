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
        $table->string('number_residents_benefit_program', 255)->nullable()->after('proportion_residents_benefit_program');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('applications', function (Blueprint $table) {
        $table->dropColumn('number_residents_benefit_program');
      });
    }
};

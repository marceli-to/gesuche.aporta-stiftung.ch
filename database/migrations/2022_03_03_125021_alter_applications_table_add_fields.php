<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicationsTableAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('applications', function (Blueprint $table) {
        $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
        $table->foreign('approved_by')->references('id')->on('users');
        $table->dateTime('denied_at')->nullable()->after('approved_by');
        $table->unsignedBigInteger('denied_by')->nullable()->after('denied_at');
        $table->foreign('denied_by')->references('id')->on('users');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

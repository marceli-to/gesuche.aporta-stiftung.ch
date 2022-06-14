<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterApplicationsTableAddTextfields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('applications', function (Blueprint $table) {
        $table->text('textblock_approval')->nullable()->after('project_title');
        $table->text('textblock_denial')->nullable()->after('textblock_approval');
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

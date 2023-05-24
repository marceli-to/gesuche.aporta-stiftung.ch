<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('applications', function (Blueprint $table) {
        $table->text('remarks_percentage_of_residents_zurich')->nullable()->after('file_project_estimated_costs');
        $table->text('remarks_direct_benefits_to_target_group')->nullable()->after('remarks_percentage_of_residents_zurich');
        $table->tinyInteger('remarks_exceptionality_of_project')->nullable()->default(0)->after('remarks_direct_benefits_to_target_group');
        $table->text('remarks_additional_relevant_information')->nullable()->after('remarks_exceptionality_of_project');
        $table->text('remarks_content_allocation')->nullable()->after('remarks_additional_relevant_information');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      -Schema::table('applications', function(Blueprint $table) {
        $table->dropColumn('remarks_percentage_of_residents_zurich');
        $table->dropColumn('remarks_direct_benefits_to_target_group');
        $table->dropColumn('remarks_exceptionality_of_project');
        $table->dropColumn('remarks_additional_relevant_information');
        $table->dropColumn('remarks_content_allocation');
      });
    }
};

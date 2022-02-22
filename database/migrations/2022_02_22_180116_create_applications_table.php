<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('applications', function (Blueprint $table) {
        $table->id();
        $table->string('name', 255);
        $table->string('legal_form', 255);
        $table->string('website', 255)->nullable();
        $table->string('bank_account', 255);
        $table->string('street', 255);
        $table->string('street_number', 255);
        $table->string('zip', 255);
        $table->string('city', 255);
        $table->string('firstname', 255);
        $table->string('lastname', 255);
        $table->string('phone', 255);
        $table->string('email', 255);
        $table->string('project_title', 255);
        $table->text('justification_funds');
        $table->text('project_beneficiaries');
        $table->text('proportion_residents_benefit_program');
        $table->text('project_time');
        $table->decimal('project_cost_total', 12, 2)->default(0.00);
        $table->decimal('project_own_contribution', 12, 2)->default(0.00);
        $table->decimal('project_contribution_requested', 12, 2)->default(0.00);
        $table->decimal('project_contribution_further_requested', 12, 2)->default(0.00);
        $table->decimal('project_income', 12, 2)->default(0.00);
        $table->decimal('project_finance', 12, 2)->default(0.00);
        $table->string('project_add_instit_2', 255)->nullable();
        $table->decimal('project_add_instit_total_2', 12, 2)->default(0.00);
        $table->string('project_add_instit_answer_2', 255)->nullable();
        $table->string('project_add_instit_3', 255)->nullable();
        $table->decimal('project_add_instit_total_3', 12, 2)->default(0.00);
        $table->string('project_add_instit_answer_3', 255)->nullable();
        $table->string('project_add_instit_4', 255)->nullable();
        $table->decimal('project_add_instit_total_4', 12, 2)->default(0.00);
        $table->string('project_add_instit_answer_4', 255)->nullable();
        $table->string('project_add_instit_5', 255)->nullable();
        $table->decimal('project_add_instit_total_5', 12, 2)->default(0.00);
        $table->string('project_add_instit_answer_5', 255)->nullable();
        $table->decimal('project_add_instit_final_total', 12, 2)->default(0.00);
        $table->string('file_portrait', 255);
        $table->string('file_anunal_report', 255);
        $table->string('file_annual_financial_report', 255);
        $table->string('file_budget', 255);
        $table->string('file_bylaws', 255);
        $table->string('file_project_description', 255);
        $table->string('file_project_estimated_costs', 255);
        $table->softDeletes();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('applications');
    }
}

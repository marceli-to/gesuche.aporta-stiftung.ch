  <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class AlterApplicationsTableDropFieldFinancialAccount extends Migration
  {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('applications', function (Blueprint $table) {
      $table->dropColumn('financial_periode');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
  }
}

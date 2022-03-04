<?php
namespace App\Http\Controllers;
use App\Http\Controllers\BaseController;
use App\Services\Media;
use App\Models\Application;
use Illuminate\Http\Request;

class DevController extends BaseController
{
  public function __construct()
  {
    parent::__construct();
  }

  // public function downloads()
  // {
  //   $apps = Application::get();
  //   foreach($apps as $a)
  //   {
  //     $path = storage_path('app/files/' . $a->uuid);

  //     if (!file_exists($path .'/'. $a->file_portrait)) {
  //       \Log::warning('missing file for: ' . $a->name . ', (file_portrait)');
  //     }
  //     if (!file_exists($path .'/'. $a->file_annual_report)) {
  //       \Log::warning('missing file for: ' . $a->name . ', (file_annual_report)');
  //     }
  //     if (!file_exists($path .'/'. $a->file_annual_financial_report)) {
  //       \Log::warning('missing file for: ' . $a->name . ', (file_annual_financial_report)');
  //     }
  //     if (!file_exists($path .'/'. $a->file_budget)) {
  //       \Log::warning('missing file for: ' . $a->name . ', (file_budget)');
  //     }
  //     if (!file_exists($path .'/'. $a->file_bylaws)) {
  //       \Log::warning('missing file for: ' . $a->name . ', (file_bylaws)');
  //     }
  //     if (!file_exists($path .'/'. $a->file_project_description)) {
  //       \Log::warning('missing file for: ' . $a->name . ', (file_project_description)');
  //     }
  //     if (!file_exists($path .'/'. $a->file_project_estimated_costs)) {
  //       \Log::warning('missing file for: ' . $a->name . ', (file_project_estimated_costs)');
  //     }
  //   }
  // }

  // public function import()
  // {
  //   $path = '/Users/marceli.to/Jamon.digital/Webroot/gesuche.aporta-stiftung.ch/public/';
   
  //   $output = [];

  //   if ($file = fopen($path . "_import.txt", "r")) {
  //     while(!feof($file))
  //     {
  //       $line = fgets($file);
  //       $a = explode(',', $line);
  //       Application::create([
  //         'uuid' => \Str::uuid(),
  //         'name' => $a[0],
  //         'legal_form' => $a[1],
  //         'website' => $a[2],
  //         'bank_account' => $a[3],
  //         'financial_periode' => $a[4],
  //         'financial_account' => $a[5],
  //         'street' => $a[6],
  //         'street_number' => $a[7],
  //         'zip' => $a[8],
  //         'city' => $a[9],
  //         'firstname' => $a[10],
  //         'lastname' => $a[11],  
  //         'salutation' => $a[12],
  //         'phone' => $a[13],
  //         'email' => $a[14],
  //         'project_title' => $a[15],
  //         'justification_funds' => $a[16],
  //         'project_beneficiaries' => $a[17],
  //         'proportion_residents_benefit_program' => $a[18],
  //         'project_time' => $a[19],
  //         'project_cost_total' => $a[20],
  //         'project_own_contribution' => $a[21],
  //         'project_contribution_requested' => $a[22],
  //         'project_contribution_approved' => $a[23],  
  //         'project_contribution_approved_temporary' => $a[24],
  //         'project_contribution_further_requested' => $a[25],
  //         'project_income' => (float) $a[26],
  //         'project_finance' => $a[27],
  //         'project_add_instit_2' => $a[28],
  //         'project_add_instit_total_2' => (float) $a[29],
  //         'project_add_instit_answer_2' => $a[30],
  //         'project_add_instit_3' => $a[31],
  //         'project_add_instit_total_3' => (float) $a[32],
  //         'project_add_instit_answer_3' => $a[33],
  //         'project_add_instit_4' => $a[34],
  //         'project_add_instit_total_4' => (float) $a[35],  
  //         'project_add_instit_answer_4' => $a[36],
  //         'project_add_instit_5' => $a[37],
  //         'project_add_instit_total_5' => (float) $a[38],
  //         'project_add_instit_answer_5' => $a[39],
  //         'project_add_instit_final_total' => $a[40],
  //         'file_portrait' => $a[41],
  //         'file_annual_report' => $a[42],
  //         'file_annual_financial_report' => $a[43],
  //         'file_budget' => $a[44],
  //         'file_bylaws' => $a[45],
  //         'file_project_description' => $a[46],
  //         'file_project_estimated_costs' => $a[47],  
  //         'archive' => $a[48],  
  //         'application_state_id' => $a[49],  
  //         'approved_at' => null,  
  //         'approved_by' => null,  
  //         'denied_at' => null,  
  //         'denied_by' => null,  
  //         'deleted_at' => null,  
  //         'created_at' => $a[55],  
  //         'updated_at' => null,  
  //       ]);
  //     }
  //     fclose($file);

  //     // print_r($output);
  //     // die();

  //   }  
  // }

}

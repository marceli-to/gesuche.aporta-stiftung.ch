<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;

class ApplicationFile extends Model
{
  use HasFactory;

  /**
   * File fields of the public form: key => title. The title is what the
   * form has always stored; the key identifies the field.
   */
  public const FORM_KEYS = [
    'tax_exemption' => 'Steuerbefreiung',
    'annual_report' => 'Jahresbericht',
    'annual_financial_report' => 'Jahresrechnung',
    'budget' => 'Budget',
    'bylaws' => 'Statuten',
    'project_description' => 'Projekt',
    'project_estimated_costs' => 'KV',
  ];

  protected $fillable = [
    'uuid',
    'name',
    'title',
    'key',
    'comment',
    'application_id',
    'user_id',
  ];

  protected $casts = [
    'created_at' => 'date:d.m.Y',
  ];

  public function user()
  {
    return $this->hasOne(User::class, 'id', 'user_id');
  }

  public function application()
  {
    return $this->belongsTo(Application::class, 'application_id', 'id');
  }

}

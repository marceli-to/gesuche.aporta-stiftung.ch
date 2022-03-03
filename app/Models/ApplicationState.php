<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;

class ApplicationState extends Base
{
  use HasFactory;

  protected $fillable = [
    'description',
  ];

  public const OPEN = 1;
  public const PENDING_APPROVAL = 2;
  public const APPROVED_EXTERNAL = 3;
  public const DENIED_EXTERNAL = 4;
  public const DENIED = 5;
  public const APPROVED = 6;


  /**
   * Local scopes 
   */

  public function scopeByPermission($query)
  {
    if (auth()->user()->isAdmin()) {
      return $query;
    }
    return $query->where('id', '>', 2);
  }

}

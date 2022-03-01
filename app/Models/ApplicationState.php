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

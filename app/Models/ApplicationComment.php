<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Base;

class ApplicationComment extends Model
{
  use HasFactory;

  protected $fillable = [
    'uuid',
    'subject',
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

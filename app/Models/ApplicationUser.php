<?php
namespace App\Models;
use App\Models\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationUser extends Base
{
  use HasFactory;

  protected $table = 'application_user';

	protected $fillable = [
		'application_id',
		'user_id',
    'user_uuid',
  ];

  /**
   * Relations
   */

	public function application()
	{
		return $this->belongsTo(application::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}

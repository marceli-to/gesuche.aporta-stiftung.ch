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

}

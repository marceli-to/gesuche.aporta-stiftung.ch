<?php
namespace App\Http\Middleware;
use Closure;

class CheckRole
{
  /**
   * Only users with exactly the given role pass (e.g. role:admin).
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string  $role
   * @return mixed
   */
  public function handle($request, Closure $next, $role)
  {
    $user = $request->user();
    if (!$user || $user->role !== $role)
    {
      abort(403);
    }
    return $next($request);
  }
}

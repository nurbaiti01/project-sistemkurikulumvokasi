<?php

namespace App\Actions\Fortify;
use Illuminate\Http\Request;
class SetDefaultRoleSession
{
    public function handle(Request $request, $next)
    {
        $user = auth()->user();

        if($user && !$request->session()->has('active_role')) {
            $firstRole = $user->roles()->first();

            if($firstRole){
                $request->session()->put('active_role', $firstRole->name);
                $request->session()->put('active_role_id', $firstRole->id);
            }
        }
        return $next($request);
    }
}

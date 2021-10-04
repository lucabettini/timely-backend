<?php

namespace App\Providers;

use App\Models\RevokedToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('jwt', function (Request $request) {

            $jwt = $request->header('jwt');

            // Check if token is present
            if (!$jwt) return null;

            // Decode the token 
            try {
                $secret = config('auth.jwt_secret');
                $token = JWT::decode($jwt, $secret, array('HS256'));
            } catch (Exception $e) {
                return null;
            }


            // Check if token is valid
            $issuead_at = Carbon::createFromTimestampUTC($token->iat);
            if ($issuead_at->addDay()->lessThan(Carbon::now())) {
                return null;
            }

            // Check if token is present on blacklist
            $blacklist = RevokedToken::where('token', $token->jti)->get();
            if ($blacklist->isEmpty()) {
                // Retrieve and return the correct user 
                return User::where('email', $token->user)->first();
            }


            return null;
        });

        ResetPassword::createUrlUsing(function ($user, string $token) {
            return env('CLIENT_URL') . '/resetPassword?token=' . $token . '&email=' . $user->email;
        });
    }
}

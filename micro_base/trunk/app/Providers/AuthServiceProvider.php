<?php

namespace App\Providers;

use App\Model\CommUserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            $auth_token = $request->header("autoken")
                ? $request->header("authToken")
                : $request->input('authToken');
            if(empty($auth_token)){
                $auth_token = $request->header("token")
                    ? $request->header("token")
                    : $request->input('token');
            }
//            Log::info('header--------------'.$request->header("authToken"));
//            Log::info('input--------------'.$request->input("authToken"));
//            Log::info('header--------------'.$request->header("token"));
//            Log::info('input--------------'.$request->input("token"));
            //开发模式 默认用户id
            if(env('DEV_USER')){
                //$auth_token = 'eyJpZCI6IjExNDI4ODk2ODk4MjU1OTQxMTMiLCJuYW1lIjoiIiwicm9sZSI6IiIsImlhdCI6MTU5NzMzNDk2NywiZXhwIjoxNTk5OTI2OTY3fQ==.8af6ee37e6abdadff252453d4febe5c8';
                $token = Token();
                $token->setId(env('DEV_USER'));
                $claims = $token->claims;
                $user = new CommUserInfo();
                $user->id = env('DEV_USER');
                $user->claims= $claims;
                return $user;
            }
            $claims = Token()->verifyToken($auth_token);
            $user = new CommUserInfo();
            $user->id = $claims->id;
            $user->claims= $claims;
            $user->token = md5($auth_token);
            return $user;
        });
    }
}

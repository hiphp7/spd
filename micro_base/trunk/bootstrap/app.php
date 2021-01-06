<?php

require_once __DIR__ . '/../vendor/autoload.php';
try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
    $env = ".".env('APP_ENV','product');
    (new Dotenv\Dotenv(__DIR__ . '/../env/',$env))->overload();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);


//开启门店模式
$app->withFacades();
$app->withEloquent();



/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the Service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

//异常处理,重置响应报文
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Handlers\ExceptionsHandler::class
);
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/
$app->register(SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class);
//$app->register(Nxp\Wechat\WechatServiceProivder::class);
$bootstrap = env('BOOTSTRAP_CONFIG','bootstrap');
$app->configure($bootstrap);
$app->middleware(config($bootstrap.'.middleware'));
$app->routeMiddleware(config($bootstrap.'.routeMiddleware'));
$app->configure('const_user');
$app->configure('const_sms');
$app->configure('parameter');
$app->configure('common');
$app->configure('interface');
$app->configure('finance');
$app->configure('const_response');
$app->configure('scout_elastic');
$app->configure('const_share');
/*
|--------------------------------------------------------------------------
| Register Services Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's Service providers which
| are used to bind services into the container. Services providers are
| totally optional, so you are not required to uncomment this line.
|
*/

foreach ($app->make('config')->get($bootstrap.'.service_provider') as $provider){
    $app->register($provider);
}

return $app;

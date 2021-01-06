<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/1/7
 * Time: 13:25
 */

namespace Micro\Common\Provider;

use Micro\Common\Common\Validation\Validation;
use Illuminate\Support\ServiceProvider;
use Micro\Common\Base\BaseModule;
use Illuminate\Support\Facades\Validator;

class BaseProvider extends ServiceProvider
{

    public function register()
    {
        app()->singleton('micro-base', function () {
            return app()->make(BaseModule::class);
        });
    }

    public function boot()
    {
        app()->configure('base');

        Validator::resolver(function($translator, $data, $rules, $messages){
            return new Validation($translator, $data, $rules, $messages);
        });

    }

}
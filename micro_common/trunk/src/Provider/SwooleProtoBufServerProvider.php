<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/25
 * Time: 15:55
 */

namespace Micro\Common\Provider;

use Illuminate\Database\DatabaseManager;
use Illuminate\Queue\QueueManager;
use Micro\Common\Console\SwooleProtoBufServer;
use Micro\Common\Contract\ServiceProvider;
use Micro\Common\Protobuf\Server\SwooleTcpServerManager;
use SwooleTW\Http\Coroutine\Connectors\ConnectorFactory;
use SwooleTW\Http\Coroutine\MySqlConnection;
use SwooleTW\Http\Helpers\FW;
use SwooleTW\Http\Middleware\AccessLog;
use SwooleTW\Http\Server\Facades\Server;
use SwooleTW\Http\Server\Manager;
use SwooleTW\Http\Server\PidManager;
use SwooleTW\Http\Task\Connectors\SwooleTaskConnector;

class SwooleProtoBufServerProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * @var \Swoole\Server
     */
    protected static $server;


    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishFiles();
        $this->loadConfigs();
        $this->mergeConfigs();
        $config = $this->app->make('config');
        if ($config->get('swoole_tcp.server.access_log')) {
            $this->pushAccessLogMiddleware();
        }
    }



    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServer();
        $this->registerManager();
        $this->registerCommands();
        $this->registerPidManager();
        $this->registerDatabaseDriver();
        $this->registerSwooleQueueDriver();
    }





    /**
     * Publish files of this package.
     */
    protected function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/../config/swoole_tcp.php' => base_path('config/swoole_tcp.php'),
        ], 'laravel-swoole');
    }

    /**
     * Load configurations.
     */
    protected function loadConfigs()
    {
        $this->app->configure('swoole_tcp');
    }

    /**
     * Merge configurations.
     */
    protected function mergeConfigs()
    {
        $path = __DIR__ . '/../../config/swoole_tcp.php';
        $this->mergeConfigFrom($path, 'swoole_http');
    }



    /**
     * Register pid manager.
     *
     * @return void
     */
    protected function registerPidManager(): void
    {
        $this->app->singleton(PidManager::class, function() {
            return new PidManager(
                $this->app->make('config')->get('swoole_tcp.server.options.pid_file')
            );
        });
    }


    /**
     * Register commands.
     */
    protected function registerCommands()
    {
        $this->commands([
            SwooleProtoBufServer::class,
        ]);
    }


    /**
     * Create swoole server.
     */
    protected function createSwooleServer()
    {
        $server =  \Swoole\Server::class;
        $config = $this->app->make('config');
        $host = $config->get('swoole_tcp.server.host');
        $port = $config->get('swoole_tcp.server.port');
        $socketType = $config->get('swoole_tcp.server.socket_type', SWOOLE_SOCK_TCP);
        $processType = $config->get('swoole_tcp.server.process_type', SWOOLE_PROCESS);

        static::$server = new $server($host, $port, $processType, $socketType);
    }

    /**
     * Set swoole server configurations.
     */
    protected function configureSwooleServer()
    {
        $config = $this->app->make('config');
        $options = $config->get('swoole_tcp.server.options');

        // only enable task worker in websocket mode and for queue driver
        if ($config->get('queue.default') !== 'swoole' ) {
            unset($options['task_worker_num']);
        }

        static::$server->set($options);
    }



    /**
     * Register manager.
     *
     * @return void
     */
    protected function registerServer()
    {
        $this->app->singleton(Server::class, function () {
            if (is_null(static::$server)) {
                $this->createSwooleServer();
                $this->configureSwooleServer();
            }

            return static::$server;
        });
        $this->app->alias(Server::class, 'swoole.server');
    }

    protected function registerManager()
    {
        $this->app->singleton(SwooleTcpServerManager::class, function ($app) {
            return new SwooleTcpServerManager($app, 'lumen');
        });

        $this->app->alias(SwooleTcpServerManager::class, 'swoole.tcp_manager');
    }

    /**
     * Register database driver for coroutine mysql.
     */
    protected function registerDatabaseDriver()
    {
        $this->app->extend(DatabaseManager::class, function (DatabaseManager $db) {
            $db->extend('mysql-coroutine', function ($config, $name) {
                $config['name'] = $name;

                $connection = new MySqlConnection(
                    $this->getNewMySqlConnection($config, 'write'),
                    $config['database'],
                    $config['prefix'],
                    $config
                );

                if (isset($config['read'])) {
                    $connection->setReadPdo($this->getNewMySqlConnection($config, 'read'));
                }

                return $connection;
            });

            return $db;
        });
    }

    protected function getNewMySqlConnection(array $config, string $connection = null)
    {
        if ($connection && isset($config[$connection])) {
            $config = array_merge($config, $config[$connection]);
        }

        return ConnectorFactory::make(FW::version())->connect($config);
    }

    /**
     * Register queue driver for swoole async task.
     */
    protected function registerSwooleQueueDriver()
    {
        $this->app->afterResolving('queue', function (QueueManager $manager) {
            $manager->addConnector('swoole', function () {
                return new SwooleTaskConnector($this->app->make(Server::class));
            });
        });
    }

    protected function pushAccessLogMiddleware()
    {
        $this->app->middleware(AccessLog::class);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: wangjh
 * Date: 2019/7/25
 * Time: 15:54
 */

namespace Micro\Common\Console;

use Micro\Common\Protobuf\Server\SwooleTcpServerManager;
use Swoole\Process;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;
use SwooleTW\Http\Helpers\OS;
use SwooleTW\Http\HotReload\FSEvent;
use SwooleTW\Http\HotReload\FSOutput;
use SwooleTW\Http\HotReload\FSProcess;
use SwooleTW\Http\Middleware\AccessLog;
use SwooleTW\Http\Server\AccessOutput;
use SwooleTW\Http\Server\Facades\Server;
use SwooleTW\Http\Server\PidManager;
use Symfony\Component\Console\Output\ConsoleOutput;

class SwooleProtoBufServer extends Command
{
    protected $signature = 'swoole:protobuf {action : start|stop|restart|reload|infos}';
    protected $description = 'swoole protobuf rpc server controller';
    /**
     * The console command action. start|stop|restart|reload
     *
     * @var string
     */
    protected $action;

    /**
     *
     * The pid.
     *
     * @var int
     */
    protected $currentPid;

    /**
     * The configs for this package.
     *
     * @var array
     */
    protected $config;


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->checkEnvironment();
        $this->loadConfigs();
        $this->initAction();
        $this->runAction();
    }


    /**
     * Load configs.
     */
    protected function loadConfigs()
    {
        $this->config = $this->laravel->make('config')->get('swoole_tcp');
    }

    /**
     * Run action.
     */
    protected function runAction()
    {
        $this->{$this->action}();
    }


    /**
     * Run swoole_http_server.
     */
    protected function start()
    {
        if ($this->isRunning()) {
            $this->error('Failed! swoole_http_server process is already running.');

            return;
        }

        $host = Arr::get($this->config, 'server.host');
        $port = Arr::get($this->config, 'server.port');
        
        $hotReloadEnabled = Arr::get($this->config, 'hot_reload.enabled');
        $accessLogEnabled = Arr::get($this->config, 'server.access_log');

        $this->info('Starting swoole TCP Pserver...');
        $this->info("Swoole tcp server started: <http://{$host}:{$port}>");
        if ($this->isDaemon()) {
            $this->info(
                '> (You can run this command to ensure the ' .
                'swoole_tcp_server process is running: ps aux|grep "swoole")'
            );
        }

        $manager = $this->laravel->make(SwooleTcpServerManager::class);
        $server = $this->laravel->make(Server::class);

        if ($accessLogEnabled) {
            $this->registerAccessLog();
        }

        if ($hotReloadEnabled) {
            $manager->addProcess($this->getHotReloadProcess($server));
        }

        $this->consulServiceRegister($port);
        $manager->run();
    }

    protected function stop()
    {
        if (! $this->isRunning()) {
            $this->error("Failed! There is no swoole_tcp_server process running.");

            return;
        }

        $this->info('Stopping swoole tcp server...');

        $isRunning = $this->killProcess(SIGTERM, 15);

        if ($isRunning) {
            $this->error('Unable to stop the swoole_tcp_server process.');

            return;
        }

        // I don't known why Swoole didn't trigger "onShutdown" after sending SIGTERM.
        // So we should manually remove the pid file.
        $this->laravel->make(PidManager::class)->delete();

        $this->info('> success');
    }

    /**
     * Restart swoole http server.
     */
    protected function restart()
    {
        if ($this->isRunning()) {
            $this->stop();
        }

        $this->start();
    }

    protected function reload()
    {
        if (! $this->isRunning()) {
            $this->error("Failed! There is no swoole_tcp_server process running.");

            return;
        }

        $this->info('Reloading swoole_tcp_server...');

        if (! $this->killProcess(SIGUSR1)) {
            $this->error('> failure');

            return;
        }

        $this->info('> success');
    }
    /**
     * Display PHP and Swoole misc info.
     */
    protected function infos()
    {
        $this->showInfos();
    }



    public function consulServiceRegister($port)
    {
        //注册本服务
        $client = new Client();
        $url = config('base.register')."/v1/agent/service/register";
        $data = [
            "ID" => config('base.service_id'),
            'Name' => config('base.service'),
            'Tags' =>[config('base.service')],
            'Address'=>config('base.host'),
            'Port' =>(Int)$port,
            'EnableTagOverride' =>false,
            'Check' =>[
                'DeregisterCriticalServiceAfter' =>'90m',
                'TCP' =>(String)(config('base.host').":".$port),
                'Interval' =>'10s'
            ]
        ];
         $client->request("PUT",$url,['json'=>$data]);
    }


    /**
     * Check running enironment.
     */
    protected function checkEnvironment()
    {
        if (OS::is(OS::WIN)) {
            $this->error('Swoole extension doesn\'t support Windows OS.');

            exit(1);
        }

        if (! extension_loaded('swoole')) {
            $this->error('Can\'t detect Swoole extension installed.');

            exit(1);
        }

        if (! version_compare(swoole_version(), '4.3.1', 'ge')) {
            $this->error('Your Swoole version must be higher than `4.3.1`.');

            exit(1);
        }
    }

    /**
     * Display PHP and Swoole miscs infos.
     */
    protected function showInfos()
    {
        $isRunning = $this->isRunning();
        $host = Arr::get($this->config, 'server.host');
        $port = Arr::get($this->config, 'server.port');
        $reactorNum = Arr::get($this->config, 'server.options.reactor_num');
        $workerNum = Arr::get($this->config, 'server.options.worker_num');
        $taskWorkerNum = Arr::get($this->config, 'server.options.task_worker_num');
        $hasTaskWorker = Arr::get($this->config, 'queue.default') === 'swoole';
        $logFile = Arr::get($this->config, 'server.options.log_file');
        $pids = $this->laravel->make(PidManager::class)->read();
        $masterPid = $pids['masterPid'] ?? null;
        $managerPid = $pids['managerPid'] ?? null;

        $table = [
            ['PHP Version', 'Version' => phpversion()],
            ['Swoole Version', 'Version' => swoole_version()],
            ['Laravel Version', $this->getApplication()->getVersion()],
            ['Listen IP', $host],
            ['Listen Port', $port],
            ['Server Status', $isRunning ? 'Online' : 'Offline'],
            ['Reactor Num', $reactorNum],
            ['Worker Num', $workerNum],
            ['Task Worker Num', $hasTaskWorker ? $taskWorkerNum : 0],
            ['Master PID', $isRunning ? $masterPid : 'None'],
            ['Manager PID', $isRunning && $managerPid ? $managerPid : 'None'],
            ['Log Path', $logFile],
        ];

        $this->table(['Name', 'Value'], $table);
    }


    /**
     * Initialize command action.
     */
    protected function initAction()
    {
        $this->action = $this->argument('action');

        if (! in_array($this->action, ['start', 'stop', 'restart', 'reload', 'infos'], true)) {
            $this->error(
                "Invalid argument '{$this->action}'. Expected 'start', 'stop', 'restart', 'reload' or 'infos'."
            );

            return;
        }
    }
    /**
     * Register access log services.
     */
    protected function registerAccessLog()
    {
        $this->laravel->singleton(OutputStyle::class, function () {
            return new OutputStyle($this->input, $this->output);
        });

        $this->laravel->singleton(AccessLog::class, function () {
            return new AccessOutput(new ConsoleOutput());
        });

        $this->laravel->singleton(AccessLog::class, function (Container $container) {
            return new AccessLog($container->make(AccessOutput::class));
        });
    }

    protected function getHotReloadProcess($server)
    {
        $recursively = Arr::get($this->config, 'hot_reload.recursively');
        $directory = Arr::get($this->config, 'hot_reload.directory');
        $filter = Arr::get($this->config, 'hot_reload.filter');
        $log = Arr::get($this->config, 'hot_reload.log');

        $cb = function (FSEvent $event) use ($server, $log) {
            $log ? $this->info(FSOutput::format($event)) : null;
            $server->reload();
        };

        return (new FSProcess($filter, $recursively, $directory))->make($cb);
    }
    public function isRunning()
    {
        $pids = $this->laravel->make(PidManager::class)->read();

        if (! count($pids)) {
            return false;
        }

        $masterPid = $pids['masterPid'] ?? null;
        $managerPid = $pids['managerPid'] ?? null;

        if ($managerPid) {
            // Swoole process mode
            return $masterPid && $managerPid && Process::kill((int) $managerPid, 0);
        }

        // Swoole base mode, no manager process
        return $masterPid && Process::kill((int) $masterPid, 0);
    }
    protected function killProcess($sig, $wait = 0)
    {
        Process::kill(
            Arr::first($this->laravel->make(PidManager::class)->read()),
            $sig
        );

        if ($wait) {
            $start = time();

            do {
                if (! $this->isRunning()) {
                    break;
                }

                usleep(100000);
            } while (time() < $start + $wait);
        }

        return $this->isRunning();
    }
    protected function isDaemon(): bool
    {
        return Arr::get($this->config, 'server.options.daemonize', false);
    }
}
<?php

namespace App\Console\Commands;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class WorkerManCommand extends Command
{

    protected $signature = 'workman {action} {--d}';

    protected $description = 'Start a WorkerMan server.';
    protected $configs;
    public function __construct()
    {
        parent::__construct();
        $this->configs = config('workman',[]);
    }
    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'wk';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    private function start()
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    private function startGateWay()
    {
        $config= $this->configs;
        if('ws' == $config['default']??''){
            $gateway = new Gateway("websocket://0.0.0.0:".$config['run_port']??6688);
        }else{
            $context = $config['context']??[];
            $gateway = new Gateway("websocket://0.0.0.0:".$config['run_port']??6688,$context);
            $gateway->transport = 'ssl';
        }

        $gateway->name = 'Gateway';
        $gateway->count = 1;
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2300;
        $gateway->pingInterval = 30;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData = '{"type":"ping"}';
        $gateway->registerAddress = '127.0.0.1:'.$config['reg_port']??1688;
    }

    private function startBusinessWorker()
    {
        $config= $this->configs;
        $worker = new BusinessWorker();
        $worker->name = 'BusinessWorker';
        $worker->count = 1;
        $worker->registerAddress = '127.0.0.1:'.$config['reg_port']??1688;
        $worker->eventHandler = \App\Workerman\Events::class;
    }

    private function startRegister()
    {
        $config= $this->configs;
        new Register('text://0.0.0.0:'.$config['reg_port']??1688);
    }
}

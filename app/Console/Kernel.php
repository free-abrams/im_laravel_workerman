<?php

namespace App\Console;

use App\Console\Commands\WorkermanCommand;
use App\Services\LoginAuthService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        WorkermanCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')-
	    $login = new LoginAuthService('A111000', ['time' => time()]);
	    $token = $login->encryptionToken();
	    echo 'token: '.$token.PHP_EOL;
	    $bool = $login->verifyToken($token);
	    echo 'verifyToken: '.$bool?'true':'false'.PHP_EOL;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

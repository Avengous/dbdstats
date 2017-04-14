<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Http\Controllers\V1\MatchController as Match;
use App\Traits\Queries;

class Kernel extends ConsoleKernel
{
	use Queries;
	
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
		
		$summoners = $this->summoners(['summonerId', 'name']);
		foreach ($summoners as $summoner) {
			$schedule->call(function () {
				Match::verifySummonerMatchList($summoner->name, false, true);
			})->everyThirtyMinutes();
		}
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

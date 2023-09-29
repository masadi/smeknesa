<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\KirimWaka;
use App\Console\Commands\KirimWalas;
use App\Console\Commands\KirimBk;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(KirimWaka::class)->dailyAt('17:00');
        $schedule->command(KirimWalas::class)->dailyAt('17:00');
        $schedule->command(KirimBk::class)->weekly()->mondays()->at('17:00');
        //dailyAt('17:00');
        // $schedule->command('inspire')->hourly();
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

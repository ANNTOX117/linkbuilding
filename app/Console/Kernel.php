<?php

namespace App\Console;

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
        'App\Console\Commands\AlreadyExists',
        'App\Console\Commands\Batches',
        'App\Console\Commands\DownLinks',
        'App\Console\Commands\ExpiredLinks',
        'App\Console\Commands\Publication',
        'App\Console\Commands\ReminderLinks',
        'App\Console\Commands\RemoveLinks'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('check:links')->withoutOverlapping()->daily();
        $schedule->command('send:batches')->withoutOverlapping()->hourly();
        $schedule->command('down:links')->withoutOverlapping()->daily();
        $schedule->command('publish:links')->withoutOverlapping()->daily();
        $schedule->command('send:expired')->withoutOverlapping()->weekly();
        $schedule->command('send:reminder')->withoutOverlapping()->daily();
        $schedule->command('remove:links')->withoutOverlapping()->daily();
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

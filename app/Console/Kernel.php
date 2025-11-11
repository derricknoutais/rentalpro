<?php

namespace App\Console;

use App\Jobs\CreateMetricEntries;
use App\Jobs\EnvoieRapportJournalierVente;
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
        Commands\SendContractExpiryAlerts::class,
        Commands\SendDocumentExpirationAlerts::class,
        Commands\SendReservationReminderEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->job(new CreateMetricEntries)->everyMinute();
        $schedule->job(new EnvoieRapportJournalierVente())->dailyAt('18:00')->timezone('Africa/Libreville');
        $schedule->command('documents:alert-expiring')->dailyAt('08:00')->timezone('Africa/Libreville');
        $schedule->command('reservations:remind-upcoming')->dailyAt('08:05')->timezone('Africa/Libreville');
        $schedule->command('contracts:alert-expiring')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

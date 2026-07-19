<?php

namespace App\Console;

use App\Models\Offers\Offer;
use App\Models\Payments\Target;
use App\Models\Users\AppLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        $schedule->call(fn() => AppLog::expired()->delete())
            ->environments(['production'])->dailyAt('3:00');
        $schedule->call(fn() => Offer::cleanOffersDirectory())
            ->environments(['production'])->dailyAt('4:00');
        $schedule->call(function () {
            // targets are processed once per profile (marginal brackets across the profile's targets)
            $ranProfiles = [];
            /** @var Target */
            foreach (Target::onlyToday()->with('comm_profile')->get() as $t) {
                if (in_array($t->comm_profile_id, $ranProfiles)) continue;
                if ($t->is_due) {
                    $ranProfiles[] = $t->comm_profile_id;
                    $t->comm_profile->processTargetPayments();
                }
            }
        })
            ->environments(['production'])->dailyAt('4:00');
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

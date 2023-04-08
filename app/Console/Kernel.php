<?php

namespace App\Console;
use App\Console\Commands\AutoCancelFreezeDay;
use App\Console\Commands\CheckItemStatus;
use App\Console\Commands\chooseRandomFood;
use App\Console\Commands\EnablePackage;
use App\Console\Commands\EnableOtherPackage;
use App\Console\Commands\NotifyExpirePackageUser;
use App\Console\Commands\NotifyBirthDayWishUser;
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
	     CheckItemStatus::class,
         chooseRandomFood::class,
         EnablePackage::class,
	 	 EnableOtherPackage::class,
         NotifyExpirePackageUser::class,
		 NotifyBirthDayWishUser::class,
		 AutoCancelFreezeDay::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {          $schedule->command('CheckItemStatus:items')->everyMinute();
               $schedule->command('chooseRandomFood:client')->everyMinute();
               $schedule->command('enablePackage:client')->dailyAt("23:30");
			   $schedule->command('enableOtherPackage:client')->dailyAt("23:30");
			   $schedule->command('freeze:cancel')->dailyAt("03:30");
               $schedule->command('notifyExpirePackageUser:client')->everyMinute();
			   $schedule->command('notifyBirthdayWishUser:client')->everyMinute();
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

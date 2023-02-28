<?php

namespace App\Console\Commands;

use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\User;
use DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnablePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enablePackage:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'enable Package user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("run cron Job for enable package");
        $today=date("Y-m-d");
        $date = strtotime("+3 day", strtotime($today));
        $enableDayPackage=date("Y-m-d", $date);
        $result=DB::table("renew_future")->where("starting_date",$enableDayPackage)->get();
        foreach ($result as $item) {
            $package=Package::find($item->package_id);
            $packageDurationId=PackageDurations::find($item->package_duration_id);
            //&& isset($packageDurationId)
            if(isset($package) ){
                $user=User::find($item->user_id);
                if(isset($user)){
                    $user->package_id=$package->id;
                    $user->package_duration_id=$item->package_duration_id;
                    if(empty($user->membership_start)){
					$user->membership_start=$enableDayPackage;
					}
                    $user->save();
                }
				
				Log::info("cron-same package user-weekprogress removed, userId = ".$item->user_id);
				
                DB::table("user_week_progress")->where('user_id',$item->user_id)->delete();
                $countWeek = ceil($packageDurationId->package_duration / 7);
                for ($i = 1; $i <= $countWeek; $i++) {
                    DB::table('user_week_progress')->insert(['user_id' => $item->user_id, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
					
					Log::info("cron-another user-weekprogress added, userId = ".$item->user_id.",Week=".$i);
                }
            }
             DB::table("renew_future")->where("id",$item->id)->delete();


        }
    }
}

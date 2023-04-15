<?php
namespace App\Console\Commands;

use App\Models\CancelFreezeDay;
use App\Models\Clinic\UserDate;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutoCancelFreezeDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freeze:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Cancel Freeze Day';

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
        $date = Carbon::now()->addDays(3);
        $cancelDays = CancelFreezeDay::query()->whereDate('freezed_ending_date' , $date)->get();
        foreach ($cancelDays as $cancelDay) {
            $user          = $cancelDay->user;
            $userId        = $user->id;
            $firstValidDay = $cancelDay->freezed_ending_date;

            //check resume date should not override the available date
            $resumeDay = UserDate::where('user_id', $user->id)->where('freeze', 1)->orderBy('date', 'asc')->first();
            if (!empty($resumeDay->id)) {
                $date1  = Carbon::createFromFormat('Y-m-d', $resumeDay->date);
                $date2  = Carbon::createFromFormat('Y-m-d', $firstValidDay);
                $reDate = $date1->lte($date2);
                if (empty($reDate)) {
                    continue;
                }
            }

            $countExistFreeze = UserDate::where('user_id', $user->id)->where('date', '>=', $user->membership_start)->where('freeze', 1)->get();
            $count = $countExistFreeze->count();
            if ($count <= 0) {
                continue;
            }

            CancelFreezeDay::query()->updateOrCreate(['user_id' => $user->id] , [
                'freezed_ending_date' => null,
                'isFreezed' => false,
                'isAutoUnFreezed' => false,
                'freezed_starting_date' => null,
            ]);
            $i = 0 ;
            foreach ($countExistFreeze as $key => $freezDate) {
                $i = 0 ;
                do {
                    $addDay = $i + $key ;
                    $newDay = date("Y-m-d", strtotime("+$addDay day", strtotime($firstValidDay)));
                    //check date exist
                    $i++;
                    $existDayE = UserDate::where('date', $newDay)->where('user_id', $userId)->first();
                } while ( ! empty($existDayE->id) and $existDayE->freeze == 1 );
                if (!empty($existDayE->id)) {
                    $existDayE->freeze         = 0;
                    if (!empty($this->isOrderExist($existDayE->id, $userId))) {
                        $existDayE->isMealSelected = 1;
                    } else {
                        $existDayE->isMealSelected = 0;
                    }
                    if(!empty($user->package_id) && empty($existDayE->package_id)){
                        $existDayE->package_id = $user->package_id;
                    }

                    $existDayE->save();
                } else {
                    $existDayE = UserDate::where('id', $freezDate->id)->where('user_id', $userId)->first();
                    $existDayE->date           = $newDay;
                    $existDayE->freeze         = 0;
                    $existDayE->update_status  = 'user';
                    if (!empty($this->isOrderExist($existDayE->id, $userId))) {
                        $existDayE->isMealSelected = 1;
                    } else {
                        $existDayE->isMealSelected = 0;
                    }

                    if(!empty($user->package_id) && empty($existDayE->package_id)){
                        $existDayE->package_id = $user->package_id;
                    }

                    $existDayE->save();
                }

                $this->makeAdminLog("V3:unfreez by cron job ,PackageID=".$user->package_id." date==>" . $newDay . "-----" . $freezDate->id . "  userId==>" . $userId, null, $userId, "unfreeze days  and attach days");
            }

            $this->checkpendingdays($firstValidDay, $userId);

        }
    }
}

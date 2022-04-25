<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Estate;
use App\Models\User;
use App\Models\UsersShift;
use Carbon\Carbon;

class CheckUsersShifts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:usersshifts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check users shifts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $createShifts = UsersShift::where('date_from', date('Y-m-d'))->get([
            'id',
            'user_id',
            'substitute_user_id',
            'temp_changes'
        ]);

        if(count($createShifts) > 0) {
            foreach($createShifts as $shift) {
                $estatesToChange = Estate::where('supervisor_user_id', $shift->user_id);
                $shift->temp_changes = json_encode($estatesToChange->pluck('id')->toArray());
                $shift->update();
                $estatesToChange->update(['supervisor_user_id' => $shift->substitute_user_id]);
            }
        }
  
        $restoreShifts = UsersShift::where('date_to', Carbon::now()->subDays(1)->format('Y-m-d'))->get([
            'id',
            'user_id',
            'substitute_user_id',
            'temp_changes'
        ]);

        if(count($restoreShifts) > 0) {
            foreach($restoreShifts as $shift) {
                $estatesIds = $shift->temp_changes = json_decode($shift->temp_changes);
                Estate::whereIn('id', $estatesIds)->update(['supervisor_user_id' => $shift->user_id]);
            }
        }

        dd('done');
    }
}

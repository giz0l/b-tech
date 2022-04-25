<?php 

namespace App\Services;
use App\Models\Estate;
use App\Models\User;
use App\Models\UsersShift;
use DB;

class UsersShiftsService
{
    public function createShifts()
    {
        $createShifts = UsersShift::createshifts();

        if(count($createShifts) > 0) {
            foreach($createShifts as $shift) {
                try {
                    DB::beginTransaction();

                    $estatesToChange = Estate::where('supervisor_user_id', $shift->user_id);
                    $shift->temp_changes = json_encode($estatesToChange->pluck('id')->toArray());
                    $shift->update();
                    $estatesToChange->update(['supervisor_user_id' => $shift->substitute_user_id]);

                    DB::commit();
                } catch (\Exception $exception) {

                    DB::rollBack();
                    dd($exception);
                    
                }
            }
        }
    }

    public function restoreShifts()
    {
        $restoreShifts = UsersShift::restoreshifts();

        if(count($restoreShifts) > 0) {
            foreach($restoreShifts as $shift) {
                try {
                    DB::beginTransaction();

                    $estatesIds = $shift->temp_changes = json_decode($shift->temp_changes);
                    Estate::whereIn('id', $estatesIds)->update(['supervisor_user_id' => $shift->user_id]);

                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    dd($exception);
                }
            }
        }
    }
}
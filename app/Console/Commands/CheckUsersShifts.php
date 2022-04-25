<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UsersShiftsService;

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
        $usersShifts = new UsersShiftsService();
        $usersShifts->createShifts();
        $usersShifts->restoreShifts();
    }
}

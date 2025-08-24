<?php

namespace App\Console\Commands;

use App\Models\Shift;
use Illuminate\Console\Command;
use App\Models\TreasuryTransation;

class updateTreasuryBalance extends Command
{

    protected $signature = 'app:update-treasury-balance';

    protected $description = 'Command description';


   public function handle()
{
    $allShifts = Shift::with('treasury')->where('shift_status', 'active')->get();

    foreach ($allShifts as $shift) {
        $treasury_balance = TreasuryTransation::where('shift_id', $shift->auto_serial)->sum('cash_amount');

       
    }
}

}

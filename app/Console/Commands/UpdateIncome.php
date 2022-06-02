<?php

namespace App\Console\Commands;

use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_income:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating all Incomes on Database';

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
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $today = Carbon::now()->format('Y-m-d');
        $thisMonth = $now->format('m');
        $prevMonth = $now->subMonth()->format('m');
        $thisYear = $now->format('Y');

        if ($thisMonth != 1) {
            $prevYear = $now->format('Y');
        } else {
            $prevYear = $now->subYear()->format('Y');
        }

        $rec_income = Income::select('*', DB::raw('MAX(date) as date'))
            ->groupBy('org_id')
            ->whereNotNull('rec_income')
            ->latest()
            ->get();

        foreach ($rec_income as $value) {
            if ($value->date->format('m') == $prevMonth) {
                $copy = $value->replicate()->fill(
                    [
                        'date' => $today,
                        'org_id' => $value->id
                    ]
                );
                $copy->save();
            }

        }

        /////////////////
        /*
                $income = Income::whereYear('date', $thisYear)
                    ->whereMonth('date', '<', $thisMonth)
                    ->whereNotNull('rec_income')
                    ->groupBy('org_id')
                    ->orderBy('date', 'desc')
                    ->latest()
                    ->get();



                foreach($income as $value) {
                    $copy = $value->replicate()->fill(
                        [
                            'date' => $today,
                            'org_id' => $value->id,
                            'details' => "refresh income"
                        ]
                    );
                    $copy->save();
                }
        */


    }
}

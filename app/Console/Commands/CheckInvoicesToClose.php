<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Expense;
use App\Models\CreditCard;
use Illuminate\Http\Request;
use App\Models\CreditParcels;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class CheckInvoicesToClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkinvoicetoclose:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for any invoice on close date.';

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
        $period = Carbon::now();

        $banks  = Bank::all();

        foreach ($banks as $bank) {
            $cc_info = CreditCard::where('bank_id', $bank->id);

            $start_date = Carbon::parse($period)->startOfMonth()->subMonth()->setDay($cc_info->value('close_invoice'))->format('Y-m-d');
            $end_date   = Carbon::parse($period)->startOfMonth()->setDay($cc_info->value('close_invoice'))->format('Y-m-d');

            $cc_parcels = CreditParcels::where('bank_id', $bank->id)
                ->whereBetween('date', [$start_date, $end_date])
                ->get();

            $cc_expenses = Expense::where([
                ['bank_id', $bank->id],
                ['payment_method', 1],
                ['parcels', NULL]
            ])
                ->whereBetween('date', [$start_date, $end_date])
                ->get();

            if (Carbon::parse()->setDay($cc_info->value('close_invoice'))->addDay()->format('d') == $period->format('d')) {

                $c_due_date = now()->setDay($cc_info->value('due_date'));

                if ($c_due_date < $period) {
                    $c_due_date->addMonth();
                }

                Invoice::create([
                    'user_id' => $bank->user_id,
                    'bank_id' => $bank->id,
                    'value' => ($cc_parcels->sum('parcel_vl') + $cc_expenses->sum('value')),
                    'due_date' => $c_due_date
                ]);
            }
        }
    }
}

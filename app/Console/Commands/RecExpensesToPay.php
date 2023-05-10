<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Expense;
use App\Mail\NewRecExpense;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class RecExpensesToPay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rec_expenses_to_pay:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if there are recurring expenses to be paid in the next 7 days, or on the current date.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        $expenses = Expense::whereNotNull('rec_expense')
            ->where('payment_method', 2)
            ->orWhere('payment_method', 3)
            ->get();

        foreach ($expenses as $expense) {

            $day_toPay = Carbon::parse($expense->date)->setMonth($now->format('m'))->setYear($now->format('Y'));

            // Create Notification on DB
            if (($now < $day_toPay) && ($day_toPay->diffInDays($now) == 6)) { //prev
                $description = __('expenses.notification.todue_decription', ['expense_details' => $expense->details, 'due_date' => $day_toPay->format('d/m/Y')]);

                Notification::create([
                    'user_id' => $expense->user_id,
                    'model' => 'App\Notification',
                    'description' => $description
                ]);
            }
            if ($now->format('Y-m-d') == $day_toPay->format('Y-m-d')) { //today
                $description = __('expenses.notification.duetoday_decription', ['expense_details' => $expense->details, 'due_date' => $day_toPay->format('d/m/Y')]);

                Notification::create([
                    'user_id' => $expense->user_id,
                    'model' => 'App\Notification',
                    'description' => $description
                ]);

                //Send Mail to Group
                Mail::to($expense->user->email)->queue(new NewRecExpense($expense));
            }
        }
    }
}

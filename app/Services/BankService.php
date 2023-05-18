<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\CreditCard;
use App\Models\CreditParcels;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Transfer;
use Illuminate\Support\Facades\Auth;

class BankService
{
    private UserService $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getBalance($bank_id)
    {
        $balance = 0;

        $income_prevMonth = Income::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->where('confirmed', 1)
            ->whereBetween('date', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ])
            ->sum('value');

        $income_thisMonth = Income::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->where('confirmed', 1)
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->sum('value');

        $incomepending_thisMonth = Income::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->where('confirmed', 0)
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->sum('value');
        ///////////////////

        /**
         * Balance Calc
         */
        $total_income = Income::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->where('confirmed', 1)
            ->sum('value');

        $transfer_rec = Transfer::where($this->userService->getUserAndGroup())
            ->where('dest_bank_id', $bank_id)
            ->sum('value');

        $transfer_send = Transfer::where($this->userService->getUserAndGroup())
            ->where('org_bank_id', $bank_id)
            ->sum('value');

        $invoices_payed = Invoice::where('user_id', Auth::user()->id)
            ->where('bank_id', $bank_id)
            ->where('payment_status', 1)
            ->sum('value');

        $no_parcels_expenses = Expense::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->whereNull('parcels')
            ->whereIn('payment_method', [2, 3])
            ->sum('value');

        $balance += (($total_income + $transfer_rec) - ($transfer_send + $invoices_payed + $no_parcels_expenses));

        return [
            'income_prevMonth' => $income_prevMonth,
            'income_thisMonth' => $income_thisMonth,
            'incomepending_thisMonth' => $incomepending_thisMonth,
            'balance' => $balance
        ];
    }
}

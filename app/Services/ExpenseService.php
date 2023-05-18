<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpenseService
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

    public function getExpenses($bank_id)
    {
        $total = Expense::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->whereNull('parcels')
            ->get();

        $thisMonth = Expense::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->whereNull('parcels')
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->get();

        $rec_expenses = Expense::where($this->userService->getUserAndGroup())
            ->where('bank_id', $bank_id)
            ->whereNotNull('rec_expense')
            ->get();

        return [
            'total' => $total,
            'thisMonth' => $thisMonth,
            'rec_exp' => $rec_expenses
        ];
    }
}

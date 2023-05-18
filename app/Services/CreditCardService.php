<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\CreditCard;
use App\Models\CreditParcels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditCardService
{
    private UserService $userService;
    private ExpenseService $expenseService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, ExpenseService $expenseService)
    {
        $this->userService = $userService;
        $this->expenseService = $expenseService;
    }

    public function getCreditExpenses($bank_id)
    {
        $creditexpenses = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('payment_method', 1);

        // EXPENSES WITH CREDIT (NO PARCELS)
        $get_creditexpenses = with(clone $creditexpenses)
            ->where('bank_id', $bank_id)
            ->whereNull('parcels')
            ->where(function ($c) use ($bank_id) {
                $cc = CreditCard::where('bank_id', $bank_id);
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth()->setDay($cc->value('close_invoice')),
                        Carbon::now()->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get();

        return [
            'get_creditexpenses' => $get_creditexpenses
        ];
    }

    public function getCreditParcelsThisMonth($bank_id)
    {
        $parcels_this_month = CreditParcels::join('credit_cards', 'credit_parcels.bank_id', '=', 'credit_cards.bank_id')
            ->join('banks', 'credit_parcels.bank_id', '=', 'banks.id')
            ->where(function ($query) {
                $query->where('banks.user_id', Auth::user()->id)
                    ->orWhereIn('banks.group_id', explode(" ", Auth::user()->group_id));
            })
            ->where('credit_parcels.bank_id', $bank_id)
            ->where(function ($c) use ($bank_id) {
                $cc = CreditCard::where('bank_id', $bank_id);
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth()->setDay($cc->value('close_invoice')),
                        Carbon::now()->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get();

        return $parcels_this_month;
    }

    public function getCreditParcelsPrevMonth($bank_id)
    {
        $parcels_prev_month = CreditParcels::join('credit_cards', 'credit_parcels.bank_id', '=', 'credit_cards.bank_id')
            ->join('banks', 'credit_parcels.bank_id', '=', 'banks.id')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
            ->where('credit_parcels.bank_id', $bank_id)
            ->where(function ($c) use ($bank_id) {
                $cc = CreditCard::where('bank_id', $bank_id);
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth(2)->setDay($cc->value('close_invoice')),
                        Carbon::now()->subMonth(1)->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get();

        return $parcels_prev_month;
    }

    public function getCreditCardInvoice($bank_id)
    {
        $parcels = $this->getCreditParcelsThisMonth($bank_id);
        $expenses = $this->getCreditExpenses($bank_id);
        $rec_expenses = $this->expenseService->getExpenses($bank_id)['rec_exp']->where('payment_method', 1);

        $total_invoice = ($parcels->sum('parcel_vl') + $expenses['get_creditexpenses']->sum('value') + $rec_expenses->sum('value'));

        $get_creditcardinvoice = [
            'parcels' => $parcels,
            'expenses' => $expenses,
            'rec_expenses' => $rec_expenses,
            'total_invoice' => $total_invoice
        ];

        return $get_creditcardinvoice;
    }
}

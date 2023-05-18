<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Budget;
use App\Models\Wallet;
use App\Models\Expense;
use App\Models\CreditCard;
use App\Models\Investment;
use App\Models\CreditParcels;
use App\Services\BankService;
use App\Services\CreditCardService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private UserService $userService;
    private BankService $bankService;
    private CreditCardService $creditcardService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, CreditCardService $creditcardService, BankService $bankService)
    {
        $this->userService = $userService;
        $this->bankService = $bankService;
        $this->creditcardService = $creditcardService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_toPay = 0;
        $income_prevMonth = 0;
        $income_thisMonth = 0;
        $incomepending_thisMonth = 0;
        $balance = 0;

        // Logged User Banks
        $banks = Bank::where($this->userService->getUserAndGroup())->get();

        // Services
        $creditcard_svc = $this->creditcardService;
        $bank_svc = $this->bankService;

        // SUM values
        foreach ($banks as $bank) {

            $total_toPay += $creditcard_svc->getCreditCardInvoice($bank->id)['total_invoice'];
            //*/

            $income_thisMonth += $bank_svc->getBalance($bank->id)['income_thisMonth'];
            $income_prevMonth += $bank_svc->getBalance($bank->id)['income_prevMonth'];
            //*/

            $incomepending_thisMonth += $bank_svc->getBalance($bank->id)['incomepending_thisMonth'];
            //*/

            $balance += $bank_svc->getBalance($bank->id)['balance'];
            //*/
        }


        $cred_parcels = CreditParcels::where(function ($query) {
            $query->where('banks.user_id', Auth::user()->id)
                ->orWhereIn('banks.group_id', explode(" ", Auth::user()->group_id));
        })
            ->join('credit_cards', 'credit_parcels.bank_id', '=', 'credit_cards.bank_id')
            ->join('banks', 'credit_parcels.bank_id', '=', 'banks.id')
            ->join('expenses', 'credit_parcels.expense_id', '=', 'expenses.id');


        $cred_parcels1 = clone $cred_parcels;
        $cred_parcels2 = clone $cred_parcels;
        $cred_parcels3 = clone $cred_parcels;

        $parcels_byMonth = $cred_parcels1
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('credit_parcels.bank_id'));
                $c->where(
                    'credit_parcels.date',
                    '>=',
                    Carbon::now()->subMonth()->setDay($cc->value('close_invoice'))
                );
            })
            ->select(
                DB::raw('sum(credit_parcels.parcel_vl) as total'),
                DB::raw("month(credit_parcels.date) as month"),
                DB::raw("monthname(credit_parcels.date) as monthname"),
                DB::raw("year (credit_parcels.date) as year"),
            )
            ->groupby('year', 'month')
            ->offset(0)
            ->limit(12)
            ->get();

        $parcels_thisMonth = $cred_parcels2
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('credit_parcels.bank_id'));
                $c->whereBetween(
                    'credit_parcels.date',
                    [
                        Carbon::now()->subMonth()->setDay($cc->value('close_invoice')),
                        Carbon::now()->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get([
                'budget_id' => 'expenses.budget_id',
                'parcel_vl' => 'credit_parcels.parcel_vl'
            ]);

        $parcels_prevMonth = $cred_parcels3
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('credit_parcels.bank_id'));
                $c->whereBetween(
                    'credit_parcels.date',
                    [
                        Carbon::now()->subMonth(2)->setDay($cc->value('close_invoice')),
                        Carbon::now()->subMonth(1)->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get([
                'budget_id' => 'expenses.budget_id',
                'parcel_vl' => 'credit_parcels.parcel_vl'
            ]);

        $rec_expenses = Expense::where($this->userService->getUserAndGroup())
            ->whereNotNull('rec_expense')
            ->get([
                'budget_id',
                'value'
            ]);

        // EXPENSES ON DEBIT OR CASH
        $getExpenses = Expense::where($this->userService->getUserAndGroup())
            ->whereNull('parcels')
            ->whereNull('rec_expense');

        $getExpenses1 = clone $getExpenses;
        $getExpenses2 = clone $getExpenses;
        $getExpenses3 = clone $getExpenses;
        $getExpenses4 = clone $getExpenses;

        // This Month
        $deb_expenses_thisMonth = $getExpenses1
            ->whereIn('payment_method', [2, 3])
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->get([
                'budget_id',
                'value'
            ]);

        // EXPENSES ON CREDIT
        $cred_expenses_thisMonth = $getExpenses2
            ->where('payment_method', 1)
            ->join('credit_cards', 'expenses.bank_id', '=', 'credit_cards.bank_id')
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('expenses.bank_id'));
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth()->setDay($cc->value('close_invoice')),
                        Carbon::now()->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get([
                'budget_id',
                'value'
            ]);

        // Prev Month
        $deb_expenses_prevMonth = $getExpenses3
            ->whereIn('payment_method', [2, 3])
            ->whereBetween('date', [
                Carbon::now()->subMonth(2)->startOfMonth(),
                Carbon::now()->subMonth(1)->endOfMonth()
            ])
            ->get([
                'budget_id',
                'value'
            ]);

        // EXPENSES ON CREDIT
        $cred_expenses_prevMonth = $getExpenses4
            ->where('payment_method', 1)
            ->join('credit_cards', 'expenses.bank_id', '=', 'credit_cards.bank_id')
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('expenses.bank_id'));
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth(2)->setDay($cc->value('close_invoice')),
                        Carbon::now()->subMonth(1)->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get([
                'budget_id',
                'value'
            ]);
        /**
         * TOTAL EXPENSES ON MONTH
         */
        $total_expenses_thisMonth = ($deb_expenses_thisMonth->sum('value') + $cred_expenses_thisMonth->sum('value') + $parcels_thisMonth->sum('parcel_vl') + $rec_expenses->sum('value'));

        /*
         * BUDGETS
         */
        $budgets = Budget::where($this->userService->getUserAndGroup())
            ->where('status', 1)
            // ->where('operation', 'OUT')
            ->OrderBy('budget', 'desc')
            ->get();

        /**
         * EXPENSES
         */
        $last_expenses = Expense::where($this->userService->getUserAndGroup())
            ->OrderBy('date', 'desc')
            ->take(5)
            ->get([
                'date',
                'value',
                'bank_id',
                'payment_method',
                'budget_id',
                'details'
            ]);

        /**
         * EXPENSES BY CATEGORY
         */
        $exp_by_cat_thisMonth = Expense::where($this->userService->getUserAndGroup())
            ->with('category')
            ->whereBetween('expenses.date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->select(
                'category_id',
                DB::raw("SUM( ( CASE WHEN category_id THEN value END ) ) AS total")
            )
            ->groupBy('category_id')
            ->get();

        $exp_without_cat_thisMonth = Expense::where($this->userService->getUserAndGroup())
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->select(
                DB::raw("SUM( ( CASE WHEN category_id is null THEN value END ) ) AS total")
            )
            ->get();

        /**
         * INVESTMENTS
         */
        $investments = Investment::where($this->userService->getUserAndGroup())
            ->where('operation', 'IN')
            ->whereNull('org_id')
            ->get();

        $investments_prevMonth = with($investments)
            ->whereBetween('date', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth()
            ]);

        $investments_thisMonth = with($investments)
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);

        $investments_byMonth = Investment::where($this->userService->getUserAndGroup())
            ->where('operation', 'IN')
            ->whereNull('org_id')
            ->selectRaw('year(date) year, month(date) m, monthname(date) month, sum(value) as value')
            ->groupBy('year', 'month')
            ->orderBy('m', 'desc')
            ->take(12)
            ->get();

        $investments_div = Investment::where($this->userService->getUserAndGroup())
            ->whereNotNull('org_id')
            ->where('operation', 'IN')
            ->orderBy('date', 'desc')
            ->take(12)
            ->get();

        /**
         * INITIAL VERIFICATION
         */
        $ck_budget = Budget::where($this->userService->getUserAndGroup())
            ->where('status', 1)
            ->count();

        $ck_bank = Bank::where($this->userService->getUserAndGroup())->count();

        $ck_wallet = Wallet::where($this->userService->getUserAndGroup())->count();

        if ($ck_budget == 0) {
            $fc = [__('messages.first_run.no_budget'), route('budgets.create')];;
        } elseif ($ck_wallet == 0) {
            $fc = [__('messages.first_run.no_wallet'), route('wallets.index')];
        } elseif ($ck_bank == 0) {
            $fc = [__('messages.first_run.no_bank'), route('banks.create')];;
        } else {
            $fc = [];
        }

        $first_run = $fc;

        return view(
            'home',
            compact(
                [
                    'total_expenses_thisMonth',
                    'income_thisMonth',
                    'income_prevMonth',
                    'incomepending_thisMonth',
                    'last_expenses',
                    'deb_expenses_prevMonth',
                    'cred_expenses_prevMonth',
                    'deb_expenses_thisMonth',
                    'cred_expenses_thisMonth',
                    'rec_expenses',
                    'total_toPay',
                    'parcels_byMonth',
                    'parcels_prevMonth',
                    'parcels_thisMonth',
                    'balance',
                    'investments',
                    'investments_prevMonth',
                    'investments_thisMonth',
                    'investments_byMonth',
                    'investments_div',
                    'exp_by_cat_thisMonth',
                    'exp_without_cat_thisMonth',
                    'budgets',
                    'first_run',
                ]
            )
        );
    }
}

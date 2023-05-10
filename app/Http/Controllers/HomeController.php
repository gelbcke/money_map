<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Budget;
use App\Models\CreditCard;
use App\Models\CreditParcels;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $last_expenses = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->OrderBy('date', 'desc')
            ->take(5)
            ->get();

        $sum_investments_month = Investment::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('operation', 'IN')
            ->select(
                DB::raw('sum(value) as value'),
                DB::raw("DATE_FORMAT(date,'%M %Y') as months")
            )
            ->groupBy('months')
            ->take(12)
            ->get();

        $rec_expenses = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereNotNull('rec_expense')
            ->OrderBy('budget_id', 'desc')
            ->get();

        /*
         * EXPENSES BY MONTH
         */
        $exp_by_budget_this_month = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->orWhere('rec_expense', 1)
            ->select(
                'budget_id',
                DB::raw("SUM( ( CASE WHEN parcels is null THEN value END ) ) AS total")
            )
            ->groupBy('budget_id')
            ->get();

        $exp_by_budget_prev_month = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereBetween('date', [
                Carbon::now()->subMonthsNoOverflow()->startOfMonth(),
                Carbon::now()->subMonthsNoOverflow()->endOfMonth()
            ])
            ->orWhere('rec_expense', 1)
            ->select(
                'budget_id',
                DB::raw("SUM( ( CASE WHEN parcels is null THEN value END ) ) AS total")
            )
            ->groupBy('budget_id')
            ->get();

        /**
         * EXPENSES BY CATEGORY
         */
        $exp_by_cat_this_month = Expense::select('*')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->where(function ($query) {
                $query->where('expenses.user_id', Auth::user()->id)
                    ->orWhereIn('expenses.group_id', explode(" ", Auth::user()->group_id));
            })
            ->whereBetween('expenses.date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->orWhere('rec_expense', 1)
            ->select(
                'category_id',
                DB::raw("SUM( ( CASE WHEN category_id THEN value END ) ) AS total")
            )
            ->groupBy('category_id')
            ->get();

        $exp_without_cat_this_month = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->orWhere('rec_expense', 1)
            ->select(
                DB::raw("SUM( ( CASE WHEN category_id is null THEN value END ) ) AS total")
            )
            ->get();

        /*
         * INVESTMENTS BY MONTH
         */
        $save_by_budget_prev_month = Investment::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereBetween('date', [
                Carbon::now()->subMonthsNoOverflow()->startOfMonth(),
                Carbon::now()->subMonthsNoOverflow()->endOfMonth()
            ])
            ->select(
                'budget_id',
                DB::raw('sum(value) as total')
            )
            ->groupBy('budget_id')
            ->get();

        $save_by_budget_this_month = Investment::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->select(
                'budget_id',
                DB::raw('sum(value) as total')
            )
            ->groupBy('budget_id')
            ->get();

        /*
         * BUDGETS
         */
        $get_out_budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('status', 1)
            ->where('operation', 'OUT')
            ->OrderBy('budget', 'desc')
            ->get();

        $get_save_budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('status', 1)
            ->where('operation', 'SAVE')
            ->OrderBy('budget', 'desc')
            ->get();

        /***/
        $get_income_prev_month = Income::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('confirmed', 1)
            ->whereBetween('date', [
                Carbon::now()->subMonthsNoOverflow()->startOfMonth(),
                Carbon::now()->subMonthsNoOverflow()->endOfMonth()
            ])
            ->sum('value');

        $get_income_this_month = Income::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('confirmed', 1)
            ->whereBetween('date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->sum('value');

        /**
         * CREDIT CARD EXPENSES - PARCELS
         */
        $parceled_expenses = CreditParcels::join('credit_cards', 'credit_parcels.bank_id', '=', 'credit_cards.bank_id')
            ->join('banks', 'credit_parcels.bank_id', '=', 'banks.id')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('credit_parcels.bank_id'));
                $c->whereDate(
                    'date',
                    '>=',
                    Carbon::now()->subMonth()->setDay($cc->value('close_invoice'))
                );
            })
            ->select(
                DB::raw('sum(parcel_vl) as total'),
                DB::raw("month(date) as month"),
                DB::raw("monthname(date) as monthname"),
                DB::raw("year (date) as year"),
            )
            ->groupby('year', 'month')
            ->offset(0)
            ->limit(12)
            ->get();

        $sum_parcels_this_month = CreditParcels::join('credit_cards', 'credit_parcels.bank_id', '=', 'credit_cards.bank_id')
            ->join('banks', 'credit_parcels.bank_id', '=', 'banks.id')
            ->where(function ($query) {
                $query->where('banks.user_id', Auth::user()->id)
                    ->orWhereIn('banks.group_id', explode(" ", Auth::user()->group_id));
            })
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('credit_parcels.bank_id'));
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth()->setDay($cc->value('close_invoice')),
                        Carbon::now()->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get();

        $sum_parcels_prev_month = CreditParcels::join('credit_cards', 'credit_parcels.bank_id', '=', 'credit_cards.bank_id')
            ->join('banks', 'credit_parcels.bank_id', '=', 'banks.id')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
            ->where(function ($c) {
                $cc = CreditCard::where('bank_id', $c->value('credit_parcels.bank_id'));
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth(2)->setDay($cc->value('close_invoice')),
                        Carbon::now()->subMonth(1)->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->get();

        /***/
        $investments = Investment::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })->get();

        /***/
        $total_income = Income::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('confirmed', 1)
            ->sum('value');

        $total_expenses = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereNull('parcels')
            ->sum('value');

        $parcels_payed = CreditParcels::select('*')
            ->join('expenses', 'credit_parcels.expense_id', '=', 'expenses.id')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
            ->whereBetween('credit_parcels.date', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->sum('credit_parcels.parcel_vl');

        $balance = $total_income - ($total_expenses + $parcels_payed + $rec_expenses->sum('value'));

        /***/
        $ck_budget = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('status', 1)
            ->count();

        $ck_bank = Bank::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })->count();

        $ck_wallet = Wallet::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })->count();

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
                    'exp_by_budget_this_month',
                    'exp_by_budget_prev_month',
                    'exp_by_cat_this_month',
                    'exp_without_cat_this_month',
                    'save_by_budget_prev_month',
                    'save_by_budget_this_month',
                    'investments',
                    'balance',
                    'sum_parcels_this_month',
                    'sum_parcels_prev_month',
                    'get_out_budgets',
                    'get_save_budgets',
                    'get_income_this_month',
                    'get_income_prev_month',
                    'last_expenses',
                    'sum_investments_month',
                    'rec_expenses',
                    'parceled_expenses',
                    'first_run',
                ]
            )
        );
    }
}

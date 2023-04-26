<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Budget;
use App\Models\CreditParcels;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $thisMonth = $now->format('m');
        $prevMonth = $now->subMonth()->format('m');
        $thisYear = $now->format('Y');

        if ($thisMonth == 1) {
            $prevYear = $now->subYear()->format('Y');
        } else {
            $prevYear = $now->format('Y');
        }

        $last_expenses = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->OrderBy('date', 'desc')
            ->take(5)
            ->get();

        $sum_expenses_month = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereNull('parcels')
            ->select(
                DB::raw('sum(value) as value'),
                DB::raw("DATE_FORMAT(date,'%M %Y') as months")
            )
            ->groupBy('months')
            ->take(12)
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
            ->OrderBy('budget_id', 'desc');

        $parceled_expenses = CreditParcels::select('*')
            ->join('expenses', 'credit_parcels.expense_id', '=', 'expenses.id')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
            ->whereYear('credit_parcels.date', '>=', $thisYear)
            ->whereMonth('credit_parcels.date', '>=', $thisMonth)
            ->select(
                DB::raw('sum(credit_parcels.parcel_vl) as total'),
                DB::raw("month (credit_parcels.date) as month"),
                DB::raw("year (credit_parcels.date) as year"),
            )
            ->groupby('year', 'month')
            ->get();

        /*
         * EXPENSES BY MONTH
         */

        $exp_by_budget_this_month = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereYear('date', $thisYear)
            ->whereMonth('date', $thisMonth)
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
            ->whereYear('date', $prevYear)
            ->whereMonth('date', $prevMonth)
            ->select(
                'budget_id',
                DB::raw("SUM( ( CASE WHEN parcels is null THEN value END ) ) AS total")
            )
            ->groupBy('budget_id')
            ->get();

        /*
         * INVESTMENTS BY MONTH
         */
        $save_by_budget_prev_month = Investment::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereYear('date', $thisYear)
            ->whereMonth('date', $prevMonth)
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
            ->whereYear('date', $thisYear)
            ->whereMonth('date', $thisMonth)
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

        $get_parcels = Expense::where(function ($query) {
            $query->where('expenses.user_id', Auth::user()->id)
                ->orWhere('expenses.group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereNotNull('expenses.parcels')
            ->whereMonth('expenses.end_parcels', '>=', $thisMonth)
            ->groupBy('expenses.budget_id')
            ->join('budgets', 'expenses.budget_id', '=', 'budgets.id')
            ->select([
                'expenses.id',
                'expenses.budget_id',
                DB::raw('sum(expenses.parcel_vl) AS total'),
                'budgets.name'
            ])
            ->get();

        /***/
        $get_income_prev_month = Income::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('confirmed', 1)
            ->whereYear('date', $thisYear)
            ->whereMonth('date', $prevMonth)
            ->sum('value');

        $get_income_this_month = Income::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('confirmed', 1)
            ->whereYear('date', $thisYear)
            ->whereMonth('date', $thisMonth)
            ->sum('value');
        /*
        $get_expense_month_1 = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->whereYear('date', $thisYear)
            ->whereMonth('date', $thisMonth)
            ->sum('value');
*/
        $sum_parcels_this_month = CreditParcels::select('*')
            ->join('expenses', 'credit_parcels.expense_id', '=', 'expenses.id')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
            ->whereYear('credit_parcels.date', $thisYear)
            ->whereMonth('credit_parcels.date', $thisMonth)
            ->groupBy('credit_parcels.expense_id')
            ->get();

        $sum_parcels_prev_month = CreditParcels::select('*')
            ->join('expenses', 'credit_parcels.expense_id', '=', 'expenses.id')
            ->where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
            ->whereYear('credit_parcels.date', $thisYear)
            ->whereMonth('credit_parcels.date', $prevMonth)
            ->groupBy('credit_parcels.expense_id')
            ->get();

        //$get_expense_month = $get_expense_month_1 + $sum_parcels_this_month;
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
            ->whereYear('credit_parcels.date', $thisYear)
            ->whereMonth('credit_parcels.date', $thisMonth)
            ->sum('credit_parcels.parcel_vl');

        $balance = $total_income - ($total_expenses + $parcels_payed);
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
                    'save_by_budget_prev_month',
                    'save_by_budget_this_month',
                    'investments',
                    'balance',
                    'get_parcels',
                    'sum_parcels_this_month',
                    'sum_parcels_prev_month',
                    'get_out_budgets',
                    'get_save_budgets',
                    'get_income_this_month',
                    'get_income_prev_month',
                    //'get_expense_month',
                    'last_expenses',
                    'sum_expenses_month',
                    'sum_investments_month',
                    'rec_expenses',
                    'parceled_expenses',
                    'first_run',
                ]
            )
        );
    }
}

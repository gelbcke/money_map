<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\CreditParcels;
use App\Models\Expense;
use App\Models\ExpenseGroup;
use App\Models\Wallet;
use App\Models\Bank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class ExpenseController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $expenses = Expense::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->OrderBy('date', 'desc')
            ->get();

        if ($request->has('rec_exp')) {
            $expenses = Expense::where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
            })
                ->orWhereNotNull('rec_expense')
                ->OrderBy('date', 'desc')
                ->get();
        }

        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $banks = Bank::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('wallet_id', '!=', 0)
            ->get();

        $budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('status', 1)
            ->get();

        return view('expenses.create', compact('banks', 'budgets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = Auth::user()->id;
        $end_parcels = NULL;

        if (Auth::user()->group_id != NULL) {
            $group_id = Auth::user()->group_id;
        } else {
            $group_id = NULL;
        }

        $request->validate([
            'date' => 'required',
            'value' => 'required',
            'bank_id' => 'required',
            'budget_id' => 'required',
            'details' => 'required',
            'rec_expense' => 'max:1'
        ]);

        if ($request->has('parcels')) {
            $end_parcels = Carbon::parse($request->input('date'))->addMonths($request->input('parcels'));
        }

        $expense = Expense::create($request->all() + [
            'user_id' => $user,
            'end_parcels' => $end_parcels,
            'group_id' => $group_id
        ]);

        if ($request->input('parcels') != NULL) {
            $request->validate([
                'bank_id' => 'required|integer',
                'date' => 'required|date_format:Y-m-d',
                'parcels' => 'required|integer',
                'parcel_vl' => 'required|numeric',
            ]);

            for ($i = 1; $i <= $request->input('parcels'); $i++) {
                $parcels[] = [
                    'bank_id' => $request->input('bank_id'),
                    'expense_id' => $expense->id,
                    'date' => Carbon::parse($request->input('date'))->subMonth(1)->addMonths($i),
                    'parcel_nb' => $i,
                    'parcel_vl' => $request->input('parcel_vl'),
                    //'group_id' => $group_id
                ];
            }
            CreditParcels::insert($parcels);
        }

        return redirect()->route('expenses.index')
            ->with('success', 'Despesa registrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
        $exp_details = Expense::where('id', $expense->id)->get();
        return view('expenses.details', compact('expense', 'exp_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
        $request->validate([
            'date' => 'required',
            'value' => 'required',
            'bank_id' => 'required',
            'budget_id' => 'required'
        ]);
        $expense->update($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Despesa atualizada!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
        //$expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Despesa ' . $expense->value . ' excluída com sucesso');
    }
}

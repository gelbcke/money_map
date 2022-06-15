<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $incomes = Income::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->orderBy('date', 'desc')
            ->get();
        return view('incomes.index', compact('incomes'));
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

        return view('incomes.create', compact('banks'));
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

        $request->validate([
            'date' => 'required',
            'value' => 'required',
            'bank_id' => 'required'
        ]);

        $income = Income::create($request->all() + ['user_id' => $user]);
        if (Auth::user()->group_id != NULL) {
            $income->group_id = Auth::user()->group_id;
        }
        $income->org_id = $income->id;
        $income->save();

        return redirect()->route('incomes.index')
            ->with('success', 'Despesa registrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Income $income
     * @return \Illuminate\Http\Response
     */
    public function show(Income $income)
    {
        //
        $income_details = Income::where('id', $income->id)->get();
        return view('incomes.details', compact('income', 'income_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Income $income
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Income $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        //
    }

    /**
     * Cancel the recurency update in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Income $income
     * @return \Illuminate\Http\Response
     */
    public function cancel_rec($id)
    {
        //
        Income::where('org_id', $id)
            ->update(['rec_income' => NULL]);

        return redirect()->route('incomes.index')
            ->with('message', 'Recurrency canceled');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Income $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Income $income)
    {
        //
    }

    public function confirm_recepit($id)
    {
        Income::where('org_id', $id)
            ->update(['confirmed' => 1]);

        return redirect()->route('incomes.index')
            ->with('message', 'Income confirmed!');
    }
}

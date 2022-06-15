<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->OrderBy('budget', 'desc')
            ->get();
        return view('budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhere('group_id', explode(" ",Auth::user()->group_id));
        })
            ->OrderBy('budget', 'desc')
            ->get();

        return view('budgets.create', compact('budgets'));
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

        $budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->where('status', 1)
            ->sum('budget');

        $sum_budget = $budgets + $request->input('budget');

        $request->validate([
            'name' => 'required',
            'budget' => 'required'
        ]);

        if(Auth::user()->group_id != NULL){
            $group_id = Auth::user()->group_id;
        }else
        {
            $group_id = NULL;
        }

        if($sum_budget <= 100) {
            Budget::create($request->all() + ['user_id' => $user, 'group_id' => $group_id]);

            return redirect()->route('budgets.index')
                ->with('success', 'Orçamento registrado com sucesso!');
        }else{
            return redirect()->route('budgets.index')
                ->with('error', 'A soma dos orçamentos é superior á 100% (Você possuí '. 100-$budgets.'% disponível para alocar)');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function show(Budget $budget)
    {
        $budget_details = Expense::where('budget_id', $budget->id)->get();

        return view('budgets.show', compact('budget_details'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function edit(Budget $budget)
    {
        //
        $budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->OrderBy('budget', 'desc')
            ->get();

        return view('budgets.edit', compact('budget', 'budgets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Budget $budget)
    {
        //
        $user = Auth::user()->id;

        $request->validate([
            'name' => 'required',
            'budget' => 'required'
        ]);

        $budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->where('status', 1)
            ->where('id', '!=', $budget->id)
            ->sum('budget');

        if(Auth::user()->group_id != NULL){
            $group_id = Auth::user()->group_id;
        }else
        {
            $group_id = NULL;
        }

        if(($budgets + $request->input('budget')) <= 100) {
            $budget->update($request->all() + ['user_id' => $user, 'group_id' => $group_id]);

            return redirect()->route('budgets.edit', $budget->id)
                ->with('success', 'Orçamento Editado com sucesso!');
        }else{
            return redirect()->route('budgets.edit', $budget->id)
                ->with('error', 'A soma dos orçamentos é superior á 100%');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Budget $budget)
    {
        //
    }

    /**
     * Disable the specified resource from storage.
     *
     * @param \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function disable(Budget $budget, $id)
    {
        //
        $budget->where('id', $id)->update(['status' => 0]);
        return redirect()->route('budgets.index')
            ->with('success', 'Orçamento desativado com sucesso!');
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function enable(Budget $budget, $id)
    {
        //
        $budget->where('id', $id)->update(['status' => 1]);
        return redirect()->route('budgets.index')
            ->with('success', 'Orçamento reativado com sucesso!');
    }

    /**
     * Insert Default budget of system for user.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Budget $budget
     * @return \Illuminate\Http\Response
     */
    public function default_budget(Request $request, Budget $budget)
    {
        //
        $user = Auth::user()->id;

        if(Auth::user()->group_id != NULL){
            $group_id = Auth::user()->group_id;
        }else
        {
            $group_id = NULL;
        }

        $budget->insert([
            [
                'user_id' => $user,
                'group_id' => $group_id,
                'name' => 'Essential',
                'budget' => '50',
                'operation' => 'OUT',
                'description' => 'Necessary expenses such as: Rent, Education, Food, Internet, Energy, Water...',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $user,
                'group_id' => $group_id,
                'name' => 'Leisure',
                'budget' => '35',
                'operation' => 'OUT',
                'description' => 'Recreational expenses such as: Parties, Streaming, Games, Movie Theater...',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $user,
                'group_id' => $group_id,
                'name' => 'Investment',
                'budget' => '15',
                'operation' => 'SAVE',
                'description' => 'Investments where the money has some profitability. For example: Real Estate Funds, Stocks, Commodities...',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        return redirect()->route('budgets.create')
            ->with('success', 'Orçamentos Registrados com sucesso!');
    }
}
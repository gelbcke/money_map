<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Budget;
use GuzzleHttp\Client;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\ApiClientFactory;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $investments = Investment::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->groupBy('ticker')
            ->whereNull('org_id')
            ->get();

        $banks = Bank::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereNotNull('f_invest')
            ->get();

        // Create a new client from the factory
        $client = ApiClientFactory::createApiClient();

        return view('investments.index', compact('investments', 'banks',  'client'));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function fetchSymbols(Request $request)
    {
        $client = ApiClientFactory::createApiClient();
        $data['symbol'] = $client->search($request->symbol);

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $investments = Investment::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereNull('org_id')
            ->get();

        $banks = Bank::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->whereNotNull('f_invest')
            ->get();

        $budgets = Budget::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('operation', 'SAVE')
            ->get();

        // Create a new client from the factory
        $client = ApiClientFactory::createApiClient();

        return view(
            'investments.create',
            compact('investments', 'banks', 'budgets', 'client')
        );
    }

    public function symbolSearch(Request $request)
    {
        $symbols = [];

        if ($request->has('q')) {
            $search = $request->q;
            $symbols = ApiClientFactory::createApiClient()->search($search);
        }

        return response()->json($symbols);
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
            'bank_id' => 'required',
            'ticker' => 'required'

        ]);

        if (Auth::user()->group_id != NULL) {
            $group_id = Auth::user()->group_id;
        } else {
            $group_id = NULL;
        }

        $investment = Investment::create($request->all() + [
            'user_id' => $user,
            'operation' => 'IN',
            'group_id' => $group_id
        ]);

        $investment->save();

        return redirect()->route('investments.index')
            ->with('success', 'Investimento registrado com sucesso!');
    }

    /**
     * Insert a yield of investment.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function insert_yield(Request $request, Investment $investment, $id)
    {
        //
        $user = Auth::user()->id;

        $request->validate([
            'date' => 'required',
            'value' => 'required',
        ]);

        $investment = Investment::find($id);

        if ($investment->user_id == $user) {
            $yield = $investment->replicate();
            $yield->org_id = $id;
            $yield->value = $request->input('value');
            $yield->date = $request->input('date');
            $yield->details = $request->input('details');
            $yield->quantity = $investment->whereNull('org_id')->sum('quantity');
            $yield->save();
            return redirect()->route('investments.index')
                ->with('success', 'Rendimento registrado com sucesso!');
        } else {
            return redirect()->route('investments.index')
                ->with('error', 'Você não possuí permissão para inserir esses dados!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Investment $investment
     * @return \Illuminate\Http\Response
     */
    public function show(Investment $investment)
    {
        //
        $investment_rec = Investment::where('bank_id', $investment->bank_id)
            ->where('ticker', $investment->ticker)
            ->orderBy('date', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        $client = ApiClientFactory::createApiClient();

        return view('investments.details', compact('investment', 'investment_rec', 'client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Investment $investment
     * @return \Illuminate\Http\Response
     */
    public function edit(Investment $investment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Investment $investment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Investment $investment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Investment $investment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Investment $investment)
    {
        //
    }

    public function calculator()
    {
        return view('investments.calculator')->render();
    }
}

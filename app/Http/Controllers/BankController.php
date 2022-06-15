<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CreditCard;
use App\Models\CreditParcels;
use App\Models\Expense;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $banks = Bank::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->get();

        return view('banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $wallets = Wallet::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->where('status', 1)
            ->get();

        return view('banks.create', compact('wallets'));
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
        $f_deb = NULL;
        $f_cred = NULL;
        $f_invest = NULL;

        $request->validate([
            'name' => 'required',
            'wallet_id' => 'required'
        ]);

        if(Auth::user()->group_id != NULL){
            $group_id = Auth::user()->group_id;
        }else
        {
            $group_id = NULL;
        }

        $bank = Bank::create($request->all() + [
                'user_id' => $user,
                'f_deb' => $f_deb,
                'f_cred' => $f_cred,
                'f_invest' => $f_invest,
                'group_id' => $group_id
            ]);

        if ($request->input('f_cred')) {
            CreditCard::create([
                'bank_id' => $bank->id,
                'due_date' => $request->input('due_date'),
                'close_invoice' => $request->input('close_invoice'),
                'credit_limit' => $request->input('credit_limit'),
            ]);
        }

        return redirect()->route('banks.index')
            ->with('success', 'Banco/Conta registrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Bank $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank, CreditCard $creditCard)
    {
        //
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $thisMonth = $now->format('m');
        $prevMonth = $now->subMonth()->format('m');
        $thisYear = $now->format('Y');

        if ($thisMonth != 1) {
            $prevYear = $now->format('Y');
        } else {
            $prevYear = $now->subYear()->format('Y');
        }

        $cc_info = CreditCard::where('bank_id', $bank->id);

        $start_date = $now->startOfMonth()->setDay($cc_info->value('close_invoice'))->format('Y-m-d');
        $end_date = $now->startOfMonth()->addMonth()->setDay($cc_info->value('close_invoice'))->format('Y-m-d');

        $cc_parcels = CreditParcels::where('bank_id', $bank->id)
            ->whereBetween('date', [$start_date, $end_date])
            //->unique('expense_id');
            ->get();

        $cc_expenses = Expense::where([
            ['bank_id', $bank->id],
            ['payment_method', 1],
            ['parcels', NULL]
        ])
            ->whereBetween('date', [$start_date, $end_date])
            ->get();

        $invoice_this_month = ($cc_parcels->sum('parcel_vl') + $cc_expenses->sum('value'));


        return view('banks.creditcard_details', compact('bank', 'cc_info', 'creditCard', 'cc_parcels', 'cc_expenses', 'invoice_this_month'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Bank $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bank $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Bank $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
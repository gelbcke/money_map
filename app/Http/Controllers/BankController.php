<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Wallet;
use App\Models\Expense;
use App\Models\CreditCard;
use Illuminate\Http\Request;
use App\Models\CreditParcels;
use App\Services\BankService;
use App\Services\CreditCardService;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    private BankService $bankService;
    private CreditCardService $creditcardService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BankService $bankService, CreditCardService $creditcardService)
    {
        $this->bankService = $bankService;
        $this->creditcardService = $creditcardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = Bank::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->get();

        $bank = $this->bankService;

        return view('banks.index', compact('banks', 'bank'));
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
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
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
        $user = Auth::user();
        $f_deb = NULL;
        $f_cred = NULL;
        $f_invest = NULL;

        $request->validate([
            'name' => 'required',
            'wallet_id' => 'required'
        ]);

        if ($user->group_id) {
            $group_id = $user->group_id;
        } else {
            $group_id = NULL;
        }

        $bank = Bank::create($request->all() + [
            'user_id' => $user->id,
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
        $bank_details = $this->bankService;

        $cc_parcels = $this->creditcardService->getCreditParcelsThisMonth($bank->id);

        $cc_expenses = Expense::where([
            ['bank_id', $bank->id],
            ['payment_method', 1],
            ['parcels', NULL]
        ])
            ->where(function ($c) use ($bank) {
                $cc = CreditCard::where('bank_id', $bank->id);
                $c->whereBetween(
                    'date',
                    [
                        Carbon::now()->subMonth()->setDay($cc->value('close_invoice')),
                        Carbon::now()->setDay($cc->value('close_invoice'))
                    ]
                );
            })
            ->orderBy('date', 'desc')
            ->get();

        $rec_expenses = Expense::where([
            ['bank_id', $bank->id],
            ['rec_expense', 1],
            ['parcels', NULL]
        ])
            ->orderBy('date', 'desc')
            ->get();

        $debit_expenses = Expense::where([
            ['bank_id', $bank->id],
            ['payment_method', 2],
            ['parcels', NULL]
        ])
            ->whereBetween(
                'date',
                [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]
            )
            ->orderBy('date', 'desc')
            ->get();

        //$invoice_this_month = ($cc_parcels->sum('parcel_vl') + $cc_expenses->sum('value'));
        $invoice_this_month = $this->creditcardService->getCreditCardInvoice($bank->id)['total_invoice'];

        return view('banks.details', compact('bank', 'bank_details',  'rec_expenses', 'creditCard', 'cc_parcels', 'cc_expenses', 'debit_expenses', 'invoice_this_month'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Bank $bank
     * @return \Illuminate\Http\Response
     */
    public function show_credit_card(Bank $bank, CreditCard $creditCard)
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
        return view('banks.edit', compact('bank'));
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
        $f_deb = NULL;
        $f_cred = NULL;
        $f_invest = NULL;

        $bank->update($request->all() + [
            'f_deb' => $f_deb,
            'f_cred' => $f_cred,
            'f_invest' => $f_invest
        ]);

        if ($request->input('f_cred')) {
            CreditCard::where('bank_id', $bank->id)->update([
                'due_date' => $request->input('due_date'),
                'close_invoice' => $request->input('close_invoice'),
                'credit_limit' => $request->input('credit_limit'),
            ]);
        } else {
            CreditCard::where('bank_id', $bank->id)->update([
                'due_date' => NULL,
                'close_invoice' => NULL,
                'credit_limit' => NULL,
            ]);
        }

        return redirect()->route('banks.index')
            ->with('success', 'Banco/Conta atualizada com sucesso!');
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

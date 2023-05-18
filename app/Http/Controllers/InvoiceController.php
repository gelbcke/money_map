<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\CreditCard;
use Illuminate\Http\Request;
use App\Models\CreditParcels;
use App\Services\BankService;
use App\Services\CreditCardService;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
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
        //
        $banks = Bank::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('f_cred', 1)
            ->get();

        $invoices = Invoice::where('user_id', Auth::user()->id)
            ->whereNull('payment_status')
            ->get();

        $now = Carbon::now()->format('m-Y');

        $cc_parcels = $this->creditcardService;

        return view('invoices.index', compact('banks', 'now', 'invoices', 'cc_parcels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($bank_id, Request $request)
    {
        //
        $dateMonthArray = explode('-', $request->date);
        $month = $dateMonthArray[0];
        $year = $dateMonthArray[1];
        $period = Carbon::createFromDate($year, $month)->startOfMonth();

        $user   = Auth::user();
        $banks  = Bank::where('id', $bank_id)->get();

        foreach ($banks as $bank) {
            $cc_info = CreditCard::where('bank_id', $bank->id);


            $cc_parcels = CreditParcels::where('bank_id', $bank->id)
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


            $cc_expenses = Expense::where([
                ['bank_id', $bank->id],
                ['payment_method', 1],
                ['parcels', NULL]
            ])
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


            $rec_expenses = $this->creditcardService->getCreditCardInvoice($bank_id)['rec_expenses'];

            $invoices = $this->creditcardService->getCreditCardInvoice($bank_id)['total_invoice'];
        }

        return view('invoices.details', compact('request', 'invoices', 'cc_expenses', 'rec_expenses', 'cc_parcels', 'user', 'cc_info', 'period'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function to_pay()
    {
        //
        $invoices = Invoice::where('user_id', Auth::user()->id)
            ->whereNull('payment_status')
            ->get();

        return view('invoices.to_pay', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function submitpayment($id)
    {
        //
        Invoice::where('id', $id)
            ->update(['payment_status' => 1]);

        return redirect()->route('invoices.to_pay')
            ->with('message', 'Invoices Payed canceled');
    }
}

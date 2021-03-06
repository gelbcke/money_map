<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\CreditCard;
use App\Models\CreditParcels;
use App\Models\Expense;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
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
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->where('f_cred', 1)
            ->get();


        return view('invoices.index', compact('banks'));
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
    public function show($id)
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

        $user   = Auth::user();
        $banks  = Bank::where('id', $id)->get();

        foreach ($banks as $bank) {
            $cc_info = CreditCard::where('bank_id', $bank->id);

            $start_date = Carbon::now()->startOfMonth()->setDay($cc_info->value('close_invoice'))->format('Y-m-d');
            $end_date   = Carbon::now()->startOfMonth()->addMonth()->setDay($cc_info->value('close_invoice'))->format('Y-m-d');

            //dd($start_date . " - " . $end_date);

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

            $invoices = ($cc_parcels->sum('parcel_vl') + $cc_expenses->sum('value'));
        }

        return view('invoices.details', compact('invoices', 'cc_expenses', 'cc_parcels', 'user', 'cc_info', 'now'));
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
}

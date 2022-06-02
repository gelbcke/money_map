<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
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
    public function index()
    {
        //
        $transfers = Transfer::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhere('group_id', Auth::user()->group_id);
        })
            ->OrderBy('created_at', 'desc')
            ->get();

        return view('transfers.index', compact('transfers'));
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
                ->orWhere('group_id', Auth::user()->group_id);
        })
            ->OrderBy('name', 'desc')
            ->get();

        return view('transfers.create', compact('banks'));
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
            'org_bank_id' => 'required',
            'dest_bank_id' => 'required'
        ]);
        if(Auth::user()->group_id != NULL){
            $group_id = Auth::user()->group_id;
        }else
        {
            $group_id = NULL;
        }
        Transfer::create($request->all() + ['user_id' => $user, 'group_id' => $group_id]);


        return redirect()->route('transfers.index')
            ->with('success', 'Transferência registrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Transfer $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(Transfer $transfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Transfer $transfer
     * @return \Illuminate\Http\Response
     */
    public function edit(Transfer $transfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Transfer $transfer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Transfer $transfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transfer $transfer)
    {
        //
    }
}

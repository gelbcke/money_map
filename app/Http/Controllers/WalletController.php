<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $wallets = Wallet::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->get();

        return view('wallets.index', compact('wallets'));
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
        $user = Auth::user()->id;

        $request->validate([
            'name' => 'required'
        ]);
        if(Auth::user()->group_id != NULL){
            $group_id = Auth::user()->group_id;
        }else
        {
            $group_id = NULL;
        }
        Wallet::create($request->all() + ['user_id' => $user, 'status' => 1, 'group_id' => $group_id]);

        return redirect()->route('wallets.index')
            ->with('success', 'Carteira cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function show(Wallet $wallet)
    {
        //
        $wallets = Wallet::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ",Auth::user()->group_id));
        })
            ->get();

        return view('wallets.show', compact('wallets'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Wallet $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wallet $wallet)
    {
        //
    }
}
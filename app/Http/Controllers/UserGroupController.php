<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user_groups = UserGroup::where('owner_id', Auth::user()->id)->get();

        return view('user_groups.index', compact('user_groups'));
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

        $create_group = UserGroup::create($request->all() + ['owner_id' => $user]);

        //Verifica todas as colunas que pertencem ao usuário, e adiciona ID do Grupo
        $columns = 'Tables_in_' . env('DB_DATABASE');
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            //Verifica se a tabela possuí a coluna
            if(Schema::hasColumn($table->$columns, 'group_id') && Schema::hasColumn($table->$columns, 'user_id')) {
                DB::table($table->$columns)->where('user_id', $user)->update(['group_id' => $create_group->id]);
            }
        }

        return redirect()->route('user_groups.index')
            ->with('success', 'Grupo de usuários cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\UserGroup $userGroup
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, UserGroup $userGroup)
    {
        //
        $userGroup->get();

        $users_gp   = User::where('group_id', $userGroup->id)->get();

        $add_users  = User::orWhere('group_id', '!=', $userGroup->id)
            ->orWhereNull('group_id')
            ->get();

        return view('user_groups.show', compact('users_gp',  'add_users', 'userGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\UserGroup $userGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(UserGroup $userGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\UserGroup $userGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserGroup $userGroup)
    {
        //
        $request->validate([
            'users_id'=>'required',
        ]);

        User::where('id', $request->input('users_id'))
            ->update(['group_id' => $userGroup->id]);

        return redirect()->route('user_groups.show', $userGroup->id)
            ->with('success', 'User Added successfully');
    }

    /**
     * Remove user from group.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\UserGroup $userGroup
     * @return \Illuminate\Http\Response
     */
    public function remove_user($id, $group_id)
    {
        //
        if(UserGroup::find($group_id)->owner_id == Auth::user()->id) {
            User::where('id', $id)
                ->update(['group_id' => 0]);
            return redirect()->route('user_groups.show', $group_id)
                ->with('success', 'Usuário removido com sucesso');
        }else{
            return redirect()->route('user_groups.show', $group_id)
                ->with('error', 'Você não possuí permissão para remover usuários desse grupo!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\UserGroup $userGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserGroup $userGroup)
    {
        //Remove o grupo do banco
        $userGroup->delete();

        //Verifica todas as colunas que pertencem ao grupo, e remove
        $columns = 'Tables_in_' . env('DB_DATABASE');
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            //Verifica se a tabela possuí a coluna
            if(Schema::hasColumn($table->$columns, 'group_id')) {
                DB::table($table->$columns)->where('group_id', $userGroup->id)->update(['group_id' => NULL]);
            }
        }

        return redirect()->route('user_groups.index')
            ->with('success', 'Grupo removido com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserGroup;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $user_groups = UserGroup::where('owner_id', Auth::user()->id)->get();

        $user_belongTo = User::where('id', Auth::user()->id)->get();

        return view('user_groups.index', compact('user_groups', 'user_belongTo'));
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
        $user = Auth::user();

        $request->validate([
            'name' => 'required'
        ]);

        $create_group = UserGroup::create(
            $request->all() +
                [
                    'owner_id' => $user->id
                    //'user_id' => ["$user->id"]
                ]
        );

        //Insert user on own group
        User::where('id', $user->id)->update(['group_id' => $create_group->id]);

        $this->GetInGroup($create_group->id, $user->id);

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
        $request->validate([
            'users_id' => 'required',
        ]);

        $user = User::where('id', $request->input('users_id'));

        if ($user->value('group_id') == NULL) {
            User::where('id', $request->input('users_id'))
                ->update(['group_id' => $userGroup->id]);

            return redirect()->route('user_groups.show', $userGroup->id)
                ->with('success', 'User Added successfully');
        } else {
            return back()->with('error', 'This user already belongs to another group!');
        }
    }

    /**
     * Remove user from group.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\UserGroup $userGroup
     * @return \Illuminate\Http\Response
     */
    public function remove_user($group_id, $user_id)
    {
        try {
            if (UserGroup::find($group_id)->owner_id == Auth::user()->id) {
                User::where('id', $user_id)
                    ->update(['group_id' => NULL]);

                $this->GetOutGroup($group_id, $user_id);

                return redirect()->route('user_groups.show', $group_id)
                    ->with('success', 'Usuário removido com sucesso');
            } else {
                return redirect()->route('user_groups.show', $group_id)
                    ->with('error', 'Você não possuí permissão para remover usuários desse grupo!');
            }
        } catch (Exception $e) {
            return back()->withError($e->getMessage())
                ->withInput();
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
        try {
            foreach (User::where('group_id', $userGroup->id)->get() as $user) {
                $this->GetOutGroup($userGroup->id, $user->id);
            }

            User::where('group_id', $userGroup->id)
                ->update(['group_id' => NULL]);

            //Remove o grupo do banco
            $userGroup->delete();

            return redirect()->route('user_groups.index')
                ->with('success', 'Grupo removido com sucesso!');
        } catch (Exception $e) {
            return back()->withError($e->getMessage())
                ->withInput();
        }
    }

    public function GetInGroup($group_id, $user_id)
    {
        try {
            $getGroup = UserGroup::where('id', $group_id);

            if ($getGroup->value('user_id') == NULL) {
                //Insert Group Creator
                $getGroup->update(['user_id' => ["$user_id"]]);
            } else {
                //Insert Accepet groupIn
                $getUsersIds = $getGroup->value('user_id');
                $users_id = json_encode([
                    implode("[]", $getUsersIds), $user_id
                ]);
                $getGroup->update(['user_id' => $users_id]);
            }

            //Check all Tables
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            if (UserGroup::where('id', $group_id)->exists()) {
                foreach ($tables as $table) {
                    //Verifica se a tabela possuí a coluna
                    if ((Schema::hasColumn($table, 'group_id')) && (Schema::hasColumn($table, 'user_id'))) {
                        DB::table($table)
                            ->where('user_id', $user_id)
                            ->update(['group_id' => $group_id]);
                    }
                }
                return back()
                    ->with('success', 'Ingressado no Grupo!');
            } else {
                return back()->with('error', 'Esse Grupo não existe!');
            }
        } catch (\Throwable $e) {
            return back()->withError($e->getMessage())
                ->withInput();
        }
    }

    public function GetOutGroup($group_id, $user_id)
    {
        try {
            //Insert Reject/Delete 
            $getGroup = UserGroup::where('id', $group_id);
            $getOutGroup = array_diff($getGroup->value('user_id'), array($user_id));
            $getGroup->update(['user_id' => $getOutGroup]);

            User::where([
                ['group_id', $group_id],
                ['id', $user_id]
            ])
                ->update(['group_id' => NULL]);

            //Check all Tables
            $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
            if (UserGroup::where('id', $group_id)->exists()) {
                foreach ($tables as $table) {
                    //Verifica se a tabela possuí a coluna
                    if ((Schema::hasColumn($table, 'group_id')) && (Schema::hasColumn($table, 'user_id'))) {
                        DB::table($table)
                            ->where([
                                ['user_id', $user_id],
                                ['group_id', $group_id]
                            ])
                            ->update(['group_id' => NULL]);
                    }
                }
            }

            return back()
                ->with('success', 'Saída do grupo realizada com sucesso!');
        } catch (\Throwable $e) {
            return back()->withError($e->getMessage())
                ->withInput();
        }
    }
}

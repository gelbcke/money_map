<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $categories = Category::where(function ($query) {
            $query->where('user_id', Auth::user()->id)
                ->orWhereIn('group_id', explode(" ", Auth::user()->group_id));
        })
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->group_id) {
            $group_id = $user->group_id;
        } else {
            $group_id = NULL;
        }

        Category::create($request->all() + [
            'user_id' => $user->id,
            'group_id' => $group_id
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoria de Despesa criada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category, Request $request)
    {
        //
        $dateMonthArray = explode('-', $request->date);
        $month = $dateMonthArray[0];
        $year = $dateMonthArray[1];
        $period = Carbon::createFromDate($year, $month)->startOfMonth();

        $start_date = Carbon::parse($period)->startOfMonth()->setDay(1)->format('Y-m-d');
        $end_date   = Carbon::parse($period)->startOfMonth()->setDay(31)->format('Y-m-d');

        $exp_details = Expense::where('category_id', $category->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('categories.details', compact('category', 'exp_details', 'request'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {

        $request->validate([
            'name' => 'required|max:50'
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Categoria de Despesa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }

    /**
     * Add a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $user = Auth::user();

        if ($user->group_id) {
            $group_id = $user->group_id;
        } else {
            $group_id = NULL;
        }

        Category::create($request->all() + [
            'user_id' => $user->id,
            'group_id' => $group_id
        ]);
    }
}

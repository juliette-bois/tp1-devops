<?php

namespace App\Http\Controllers;
use App\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $budgets = Budget::all();
        return response()->json($budgets);
    }

    public function create(Request $request)
    {
        $budget = new Budget;
        $budget->name= $request->name;
        $budget->price = $request->price;
        $budget->creator= $request->creator;
        $budget->description= $request->description;
        $budget->tags= $request->tags;
        $budget->users= $request->users;

        $budget->save();
        return response()->json($budget);
    }

    public function show($id)
    {
        $budget = Budget::find($id);
        return response()->json($budget);
    }

    public function update(Request $request, $id)
    {
        $budget= Budget::find($id);

        $budget->name = $request->input('name');
        $budget->price = $request->input('price');
        $budget->creator = $request->input('creator');
        $budget->description = $request->input('description');
        $budget->tags = $request->input('tags');
        $budget->users = $request->input('users');

        $budget->save();
        return response()->json($budget);
    }

    public function destroy($id)
    {
        $budget = Budget::find($id);
        $budget->delete();
        return response()->json('Le budget a bien été supprimé');
    }

}

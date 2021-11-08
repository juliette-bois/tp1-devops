<?php

namespace App\Http\Controllers;


use App\Budget;
use App\Exceptions\InvalidParamException;
use App\Exceptions\JsonException;
use App\Group;
use App\Permission;
use App\Spent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpentsController extends Controller
{

    public function getSpents() {
        return response()->json(Spent::all(), 200);
    }

    public function getSpentsByBudget($id_group, $id_budget) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        /** @var Budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        if (!Auth::user()->hasPerm(Permission::PERM_VIEW, $budget)) {
            throw new JsonException(403, "You must have the view permission for this budget");
        }

        return response()->json($budget->spents()->get(), 200);
    }

    public function postSpent($id_group, $id_budget, Request $request) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        /** @var Budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        if (!Auth::user()->hasPerm(Permission::PERM_EDIT, $budget)) {
            throw new JsonException(403, "You must have the edit permission for this budget");
        }

        if ($request->get('name') == null) {
            throw new InvalidParamException('name');
        }

        if ($request->get('name') == null) {
            throw new InvalidParamException('name');
        }

        if ($request->get('amount') == null) {
            throw new InvalidParamException('amount');
        }

        $spent = new Spent();
        $spent->budget_id = $budget->id;
        $spent->name = $request->get('name');
        $spent->amount = $request->get('amount');
        $spent->comment = $request->get('comment');
        $spent->save();

        return response()->json($spent, 201);
    }

    public function deleteSpent($id_group, $id_budget, $id_spent) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        /** @var Budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        if (!Auth::user()->hasPerm(Permission::PERM_EDIT, $budget)) {
            throw new JsonException(403, "You must have the edit permission for this budget");
        }

        /** @var Spent $spent */
        $spent = Spent::where('id', $id_spent)->first();

        if (empty($spent)) {
            throw new JsonException(404, "Spent with id $id_spent not found");
        }

        $spent->delete();

        return response()->json($spent, 204);
    }

    public function patchSpent($id_group, $id_budget, $id_spent, Request $request) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        /** @var Budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        if (!Auth::user()->hasPerm(Permission::PERM_EDIT, $budget)) {
            throw new JsonException(403, "You must have the edit permission for this budget");
        }

        /** @var Spent $spent */
        $spent = Spent::where('id', $id_spent)->first();

        if (empty($spent)) {
            throw new JsonException(404, "Spent with id $id_spent not found");
        }

        if ($request->get('name') == null) {
            throw new InvalidParamException('name');
        }

        if ($request->get('name') == null) {
            throw new InvalidParamException('name');
        }

        if ($request->get('amount') == null) {
            throw new InvalidParamException('amount');
        }

        $spent->name = $request->get('name');
        $spent->amount = $request->get('amount');
        $spent->comment = $request->get('comment');
        $spent->budget_id = $id_budget;
        $spent->save();

        return response()->json($spent, 200);
    }

}

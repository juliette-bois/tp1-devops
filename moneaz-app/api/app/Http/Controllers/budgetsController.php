<?php

namespace App\Http\Controllers;
use App\Budget;
use App\Exceptions\InvalidParamException;
use App\Exceptions\JsonException;
use App\Group;
use App\Permission;
use App\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class budgetsController extends Controller
{

    public function getBudgets($id_group)
    {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        /** @var Budget $budget */
        $budgets = $group->budgets()->get();
        foreach ($budgets as $budget) {

            /** @var Permission $perms */
            $perms = Permission::where('budget_id', $budget->id)->get();

            foreach ($perms as $perm) {
                $perm->user = $perm->user()->get();
            }

            $budget->perms = $perms;
        }
        return response()->json($budgets);
    }

    public function postBudgets($id_group, Request $request) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        if (!Auth::user()->isAdmin($group)) {
            throw new JsonException(403, "You must be a group admin to add a new budgets of the group");
        }

        if ($request->get('name') == null) {
            throw new InvalidParamException('name');
        }

        $budget = new Budget();
        $budget->name = $request->get('name');
        $budget->group_id = $group->id;

        if ($request->get('budget_parent_id') != null) {
            $budget_parent = Budget::where('id', $request->get('budget_parent_id'))->first();
            if (empty($budget_parent)) {
                throw new JsonException(404, $request->get('budget_parent_id'). 'parent budget doesn\'t exist');
            }
            $budget->parent = $request->get('budget_parent_id');
        }

        $budget->save();

        return response()->json($budget, 201);
    }

    public function deleteBudget($id_group, $id_budget) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        if (!Auth::user()->isAdmin($group)) {
            throw new JsonException(403, "You must be a group admin to delete a budget");
        }

        /** @var Budget $budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        $budget->delete();

        return response()->json($budget, 204);
    }

    public function patchBudget($id_group, $id_budget, Request $request) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member to see budgets of the group");
        }

        if (!Auth::user()->isAdmin($group)) {
            throw new JsonException(403, "You must be a group admin to delete a budget");
        }

        /** @var Budget $budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        if ($request->get('name') == null) {
            throw new InvalidParamException('name');
        }

        $budget->name = $request->get('name');
        $budget->save();

        return response()->json($budget, 200);
    }

}

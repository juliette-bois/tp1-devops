<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Exceptions\JsonException;
use App\Group;
use App\Permission;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserSpentsController extends Controller
{

    public function getUsers($id_group, $id_budget) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member");
        }

        if (!Auth::user()->isAdmin($group)) {
            throw new JsonException(403, "You must be a group administator");
        }

        /** @var Budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        $users = $group->users()->get();

        /** @var User $user */
        foreach ($users as $key => $user) {

            $user->perm = $user->getPermsForBudget($budget);
            $users[$key] = $user;
        }

        return response()->json($users, 200);

    }

    public function putUserPerm($id_group, $id_budget, $user_id, $perm) {

        /** @var Group $group */
        $group = Group::where('id', $id_group)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id_group group doesn't exist");
        }

        if (!Auth::user()->isInGroup($group)) {
            throw new JsonException(403, "You must be a group member");
        }

        if (!Auth::user()->isAdmin($group)) {
            throw new JsonException(403, "You must be a group administator");
        }

        /** @var Budget $budget */
        $budget = Budget::where('id', $id_budget)->first();

        if (empty($budget)) {
            throw new JsonException(404, "$id_budget budget doesn't exist");
        }

        $user = User::where('id', $user_id)->first();

        if (empty($user)) {
            throw new JsonException(404, "User with user_id $user_id not found");
        }

        if (!$user->isInGroup($group)) {
            throw new JsonException(400, "User with user_id $user_id is not in group $group->id");
        }

        if ($perm != Permission::PERM_EDIT && $perm != Permission::PERM_VIEW && $perm != Permission::PERM_NONE) {
            throw new JsonException(400, "Permission must be one of those : "
                . Permission::PERM_EDIT . ',' . Permission::PERM_VIEW . ',' . Permission::PERM_NONE
                . ". '$perm' was sent");
        }

        $permObject = Permission::where(['user_id' => $user_id, 'budget_id' => $id_budget])->first();

        if (empty($permObject)) {
            $permObject = new Permission();
            $permObject->user_id = $user_id;
            $permObject->budget_id = $id_budget;
            $permObject->perm = $perm;
        } else {
            $permObject->perm = $perm;
        }

        $permObject->save();

        return response()->json($permObject, 200);

    }

}

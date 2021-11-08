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

class GroupUsersController extends Controller
{

    private function loadGroup($group_id): Group
    {
        $group = Group::where('id', $group_id)->first();

        if (empty($group)) {
            throw new JsonException(404, "$group_id group doesn't exist");
        }

        return $group;
    }

    private function loadEmail($user_email): User
    {
        $user_exists = User::where(['email' => $user_email])->first();

        if (empty($user_exists)) {
            throw new JsonException(404, "user with $user_email doesn't exist");
        }

        return $user_exists;
    }

    public function get($group_id)
    {
        $group = $this->loadGroup($group_id);
        return response()->json($group->users()->get());
    }

    public function post($group_id, Request $request)
    {
        $group = $this->loadGroup($group_id);
        $user_email = $request->get('user_email');
        $user = $this->loadEmail($user_email);

        if ($user->isInGroup($group)) {
            throw new JsonException(409, "$user_email already in group $group->name");
        }

        $group->users()->attach($user->id, ['role' => 'admin']);

        return response()->json($group, 201);
    }

    public function delete($group_id, $user_email)
    {
        $group = $this->loadGroup($group_id);
        $user = $this->loadEmail($user_email);

        if (!$user->isInGroup($group)) {
            throw new JsonException(404, "$user_email not in group $group->name");
        }

        if ($user->isAdmin($group)) {
            throw new JsonException(405, "cannot remove $user_email because admin of $group->name");
        }

        $group->users()->detach($user->id);
        return response()->json($group, 204);
    }

}

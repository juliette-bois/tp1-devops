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

class GroupsController extends Controller
{

    public function get()
    {
        return response()->json(Auth::user()->groups);
    }

    public function getGroup($id) {

        /** @var Group $group */
        $group = Group::where('id', $id)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id group doesn't exist");
        }

        $users = $group->users()->get();

        /** @var User $user */
        foreach ($users as $user) {
            $user->role = $user->getRoleForGroup($group);
        }

        return response()->json([
            'group' => $group,
            'users' => $users,
        ], 200);

    }

    public function patchGroup(Request $request, $id) {

        /** @var User $user */
        $user = auth::user();

        $group = Group::where('id', $id)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id group doesn't exist");
        }

        if ($request->get('new_name') == null) {
            throw new InvalidParamException('new_name');
        }

        if ($user->isAdmin($group)) {
            $group->name = $request->get('new_name');
            $group->save();

            return response()->json($group);
        }

        throw new JsonException(403, "You must be a group administrator");
    }

    public function post(Request $request)
    {

        if ($request->get('name') === null) {
            throw new InvalidParamException('name');
        }

        $group = new Group();
        $group->name = $request->get('name');
        $group->save();
        $group->users()->attach(Auth::user()->id, ['role' => 'admin']);

        return response()->json($group, 201);

    }

    public function delete($id)
    {
        $group = Group::where('id', $id)->first();

        if (empty($group)) {
            throw new JsonException(404, "$id group doesn't exist");
        }

        $group->delete();

        return response()->json($group, 204);
    }

}

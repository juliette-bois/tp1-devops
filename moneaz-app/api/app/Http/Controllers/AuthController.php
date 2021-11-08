<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Exceptions\InvalidParamException;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
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

    public function getToken(Request $request)
    {

        $email = $request->get('email');
        $password = $request->get('password');
        $user = User::where(['email' => $email])->first();

        if (empty($user)) {
            return response()->json(['error' => 'User does not exist'], 404);
        }

        if (Hash::check($password, $user->password)) {
            $user->api_token = Str::random(100);
            $user->save();
            return response()->json($user);
        }

        return response()->json([], 401);
    }

    public function register(Request $request)
    {

        $user_exists = User::where(['email' => $request->get('email')])->first();

        if (!empty($user_exists)) {
            return response()->json(['error' => 'Email address already taken'], 409);
        }

        if ($request->get('name') === null) {
            throw new InvalidParamException('name');
        }

        if ($request->get('password') === null) {
            throw new InvalidParamException('name');
        }

        if ($request->get('email') === null) {
            throw new InvalidParamException('name');
        }

        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = Hash::make($request->get('password'));
        $user->api_token = Str::random(100);
        $user->save();

        $group = new Group();
        $group->name = $request->get('name');
        $group->save();

        $group->users()->attach($user->id, ['role' => 'admin']);

        return response()->json($user, 201);

    }


}

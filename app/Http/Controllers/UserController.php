<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SetUserRequest;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index()
    {
        Gate::authorize('viewAny-user');

        $data['users'] = User::all();
        $data['types'] = User::TYPES;

        return view('users.show', $data);
    }

    public function show($id)
    {
        /** @var User */
        $user = User::findOrFail($id);
        Gate::authorize('view-user', $user);

        $data['users'] = User::all();
        $data['types'] = User::TYPES;

        return view('users.profile', $data);
    }

    public function create()
    {
        Gate::authorize('create-user');
        $data['types'] = User::TYPES;
        return view('users.profile', $data);
    }

    public function insert(SetUserRequest $request)
    {
        $validated = $request->validated();
        $newUser = User::newUser(
            $validated['username'],
            $validated['first_name'],
            $validated['last_name'],
            $validated['type'],
            $validated['password'],
            $validated['email'],
            $validated['phone']
        );

        if (is_a($newUser, User::class)) {
            return redirect()->action([self::class, 'index']);
        } else {
            return redirect()->action([self::class, 'create'])->withInput(['insert_error' => 'Failed to create user. Please check application logs']);
        }
    }

    public function update(SetUserRequest $request)
    {
        $validated = $request->validated();
        /** @var User */
        $user = User::findOrFail($validated['id']);

        $res = $user->editInfo(
            $validated['username'],
            $validated['first_name'],
            $validated['last_name'],
            $validated['type'],
            $validated['email'],
            $validated['phone']
        );

        if ($res) {
            return redirect()->action([self::class, 'show', [$user->id]]);
        } else {
            return redirect()->action([self::class, 'create'])->withInput(['edit_error' => 'Failed to edit user. Please check application logs']);
        }
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            "id"    =>  "required|exists:users",
            "password"  =>  "required"
        ]);
        /** @var User */
        $user = User::findOrFail($validated['id']);
        Gate::authorize('update-user', $user);
        $res = $user->changePassword($validated['password']);

        if ($res) {
            return redirect()->action([self::class, 'show', [$user->id]]);
        } else {
            return redirect()->action([self::class, 'create'])->withInput(['edit_error' => 'Failed to edit user. Please check application logs']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithoutToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        return new UserResource($user);
    }

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return ['data' => $user];
    }

    public function changeProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'string',
            'avatar' => 'image|max:5048',
            'phone_number' => 'string',
            'email' => 'string',
            'instance' => 'string',
        ]);

        if ($data['avatar']) {
            $url = $request->file('avatar')->storePublicly('public/avatars');
            $data['avatar'] = str_replace('public/', '', $url);
        }

        $user->update($data);

        return new UserWithoutToken($user);

      }
}

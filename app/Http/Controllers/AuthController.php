<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
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
    public function profileedit($id, Request $request){

        //validator place
      
        $users = user::find($id);
        $users->name = $request->firstName;
        $users->thumbnail = $request->avatar->store('avatars','public');
        $users->save();
      
        $data[] = [
          'id'=>$users->uid,
          'name'=>$users->name,
          'avatar'=>Storage::url($users->thumbnail),
          'status'=>200,
        ];
        return response()->json($data);
      
      }
}

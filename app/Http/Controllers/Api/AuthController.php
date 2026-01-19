<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\FileUtils;
use App\Utils\ResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use FileUtils, ResponseHandler;

    public function createAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:70',
            'email' => 'required|email|unique:users',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg',
            'password' => 'required|string|min:6',
        ]);

        $input = $request->only('name', 'email');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $input['photo_url'] = $this->saveUploadedFile($photo, null, 'users');
        }

        $input['password'] = Hash::make($request->password);

        $user = User::create($input);

        return $this->createResponse(
            ['user' => $user, 'token' => auth('api')->login($user)],
            200,
            'Account created successfully',
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (!Hash::check($request->password, $user->password)) {
                return $this->createResponse(null, 401, 'Invalid password');
            }

            return $this->createResponse(
                ['user' => $user, 'token' => auth('api')->login($user)],
                200,
                'Login successful',
            );
        }

        return $this->createResponse(null, 401, 'Invalid email or password');
    }

    public function getAuthUser(Request $request)
    {
        $user = $request->user();

        return $this->createResponse($user, 200, 'Success');
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|required|string|max:70',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg',
            'password' => 'sometimes|required|string|min:6',
        ]);

        $input = $request->only('name', 'email');

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');

            $input['photo_url'] = $this->saveUploadedFile($photo, null, 'users');
        }

        $user->update($input);
        return $this->createResponse($user, 200, 'Saved successfully');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        $user = $request->user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->createResponse(null, 200, 'Password changed successfully');
        }

        return $this->createResponse(null, 422, 'Wrong current password');
    }

    public function logout(Request $request)
    {
        auth('api')->logout();
        return $this->createResponse(null, 200, 'Logged out successfully');
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return $this->createResponse(null, 200, 'Account deleted successfully');
    }
}

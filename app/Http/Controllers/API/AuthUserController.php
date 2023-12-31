<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUserController extends Controller
{
    public function login(Request $request)
    {
        try {
            // validasi request
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // cek credentials login
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            // jika hash/password tidak sesuai maka beri error
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            };

            //jika berhasil cek password maka login
            $tokenResult = $user->CreateToken('authtoken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticaced');
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            //validate request
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'confirmation_password' => 'required|min:8|string'
            ]);

            //cek kondisi password dan konfirm password
            if ($request->password != $request->confirmation_password) {
                return ResponseFormatter::error([
                    'message' => 'Password not macth'
                ], 'Authenticated Failed', 500);
            }

            //jika berhasil maka buat user baru
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // get data user
            $user = User::where('email', $request->email)->first();

            //create token user
            $tokenResult = $user->CreateToken('authtoken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'User Registered Successfully');
        } catch (\Error $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        // menghaous token pada saat login
        $token = $request->user()->currentAccessTolen()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'current_password' => 'required',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $validator->errors(),
            ], 'Update Password Failed', 500);
        }

        $user = Auth::user();

        if (!Hash::check($data['current_password'], $user->password, [])) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $validator->errors(),
            ], 'Update Password Failed', 500);
        }

        if ($data['password'] != $data['password_confirmation']) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $validator->errors(),
            ], 'Update Password Failed', 500);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        return ResponseFormatter::success([
            'user' => $user,
        ], 'Update Password Success');
    }
}

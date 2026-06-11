<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private const DEFAULT_ADMIN_USERNAME = 'Mourice';
    private const DEFAULT_ADMIN_PASSWORD = '#M442137844m';

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation', 'role');

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|string',
            'role' => 'sometimes|string|in:admin,viewer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'viewer',
        ]);

        $token = $user->createToken('main')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $data = $request->only('email', 'password');

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('main')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()?->delete();
        }

        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function adminLogin(Request $request)
    {
        $data = $request->only('username', 'password');

        $validator = Validator::make($data, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (
            $data['username'] !== self::DEFAULT_ADMIN_USERNAME ||
            $data['password'] !== self::DEFAULT_ADMIN_PASSWORD
        ) {
            return response()->json(['message' => 'Access denied'], 401);
        }

        $admin = User::updateOrCreate(
            ['email' => 'mourice.admin@faithpod.local'],
            [
                'name' => self::DEFAULT_ADMIN_USERNAME,
                'password' => Hash::make(self::DEFAULT_ADMIN_PASSWORD),
                'role' => 'admin',
            ]
        );

        $token = $admin->createToken('admin')->plainTextToken;

        return response()->json(['user' => $admin, 'token' => $token]);
    }

    public function adminDashboard(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(['message' => '', 'user' => $user]);
    }

    public function adminLogout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->currentAccessToken()?->delete();
        }

        return response()->json(['message' => 'Admin logged out']);
    }
}

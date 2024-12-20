<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function index()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    /**
     * Proses autentikasi pengguna.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $message = 'Login success!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => Auth::user()
                ], 200);
            }

            Alert::success('Success', $message);
            return Auth::user()->role === 'admin'
                ? redirect()->intended('/')
                : redirect()->intended('/dashboard1');
        }

        $message = 'Login failed!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 401);
        }

        Alert::error('Error', $message);
        return redirect('/login');
    }

    /**
     * Menampilkan halaman registrasi.
     */
    public function register()
    {
        return view('auth.register', ['title' => 'Register']);
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'passwordConfirm' => 'required|same:password',
        ]);

        $validated['password'] = Hash::make($request['password']);
        $validated['role'] = 'user';

        $user = User::create($validated);

        $this->updateJsonFile();

        $message = 'User has been registered successfully!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $user
            ], 201);
        }

        Alert::success('Success', $message);
        return redirect('/login');
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $message = 'Logout success!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ], 200);
        }

        Alert::success('Success', $message);
        return redirect('/login');
    }

    /**
     * Menampilkan daftar semua pengguna.
     */
    public function indexUsers(Request $request)
    {
        $users = User::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ], 200);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Menyimpan data pengguna baru.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        $validated['password'] = Hash::make($request['password']);
        $user = User::create($validated);

        $this->updateJsonFile();

        $message = 'New user created successfully!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $user
            ], 201);
        }

        Alert::success('Success', $message);
        return redirect()->route('users.index');
    }

    /**
     * Memperbarui data pengguna.
     */
    public function updateUser(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id_user . ',id_user',
            'email' => 'required|email|unique:users,email,' . $id_user . ',id_user',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request['password']);
        }

        $user->update($validated);

        $this->updateJsonFile();

        $message = 'User updated successfully!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $user
            ], 200);
        }

        Alert::success('Success', $message);
        return redirect()->route('users.index');
    }

    /**
     * Menghapus data pengguna.
     */
    public function destroyUser(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();

        $this->updateJsonFile();

        $message = 'User deleted successfully!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ], 200);
        }

        Alert::success('Success', $message);
        return redirect()->route('users.index');
    }

    /**
     * Memperbarui file JSON dengan data terbaru dari database.
     */
    private function updateJsonFile()
    {
        try {
            $users = User::all();

            $data = $users->map(function ($user) {
                return [
                    'id_user' => $user->id_user,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            })->toArray();

            $filePath = public_path('assets/users_data.json');

            if (!file_exists(public_path('assets'))) {
                mkdir(public_path('assets'), 0775, true);
            }

            file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
         
        }
    }
}

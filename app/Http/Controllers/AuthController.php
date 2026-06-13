<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;


class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if (auth()->user()->role == 'guru') {
                return redirect('/dashboard-guru');
            } elseif (auth()->user()->role == 'siswa') {
                return redirect('/dashboard');
            }
        } else {
            return redirect('login')->with('error', 'Email atau Kata sandi salah !');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Menghapus autentikasi pengguna saat ini

        $request->session()->invalidate(); // Mematikan sesi pengguna

        $request->session()->regenerateToken(); // Menghasilkan token sesi baru

        return redirect('/')->with('status', 'You have been logged out successfully.'); // Redirect ke halaman utama dengan pesan sukses
    }

    public function form_register()
    {

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validate_data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        
        $validate_data['password'] = Hash::make($validate_data['password']);
        $user = User::create($validate_data);
        $user->assignRole('siswa');

        return redirect('login',)->with('status', 'Registration successful. Please login.');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


    public function index(Request $request)
    {
        $students = User::query()
            ->where('role', 'user')
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('username', 'like', "%{$request->search}%")
                        ->orWhere('kelas', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('users.siswa', compact('students'));
    }

    /**
     * Form tambah siswa
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan siswa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'username' => ['required', 'unique:users,username'],
            'password' => ['required', 'min:6'],
            'kelas' => ['required']
        ]);

        User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'kelas' => $validated['kelas'],
            'role' => 'siswa',
        ]);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Form edit siswa
     */
    public function edit(User $siswa)
    {
        return view('users.siswa.edit', compact('siswa'));
    }

    /**
     * Update siswa
     */
    public function update(Request $request, User $siswa)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'username' => [
                'required',
                'unique:users,username,' . $siswa->id
            ],
            'kelas' => ['required']
        ]);

        $siswa->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'kelas' => $validated['kelas']
        ]);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Hapus siswa
     */
    public function destroyUser (User $siswa)
    {
        $siswa->delete();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus');
    }

    /**
     * Reset password siswa
     */
    public function resetPassword(User $siswa)
    {
        $siswa->update([
            'password' => Hash::make('123456')
        ]);

        return back()
            ->with('success', 'Password berhasil direset menjadi 123456');
    }

    /**
     * Tidak digunakan
     */
    public function show(User $siswa)
    {
        return redirect()->route('siswa.index');
    }
}

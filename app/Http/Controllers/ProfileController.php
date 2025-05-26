<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $idGuru = Auth::user()->id;
        $guru = Guru::with('user', 'jabatan')->where('id_user', $idGuru)->first();
        $jabatan = Jabatan::all();
        return view('profile.edit', [
            'user' => $request->user(),
            'guru' => $guru,
            'jabatan' => $jabatan
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $idUser = Auth::user()->id;
        $request->validate([
            'name' => ['string'],
            'email' => ['email'],
            'jenis_kelamin' => [],
        ]);

        $updateUser = User::find($idUser);
        $updateUser->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $updateGuru = Guru::where('id_user', $idUser)->first();
        $updateGuru->update([
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        if ($updateUser && $updateGuru) {
            session()->flash('berhasil', 'Profil berhasil diupdate!');
            return redirect()->route('profile.edit');
        } else {
            return redirect()->back();
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user()->id;
        $user = User::find($user);

        // pastikan current password cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $updatePassword = $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        if ($updatePassword) {
            session()->flash('berhasil', 'Password berhasil diupdate!');
            return redirect()->route('profile.edit');
        } else {
            return redirect()->back();
        }
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

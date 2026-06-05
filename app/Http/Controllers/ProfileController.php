<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function index()
    {
        return view('profile'); 
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB lang
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('profile_image')) {
            
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_pictures', 'public');
            
            $data['profile_image'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Account details updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        // 1. I-validate ang mga fields. Ang 'confirmed' rule ay awtomatikong maghahanap ng field na may '_confirmation' sa dulo.
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed', // Minimum 8 characters at dapat kaparehas ng confirm field
        ]);

        $user = Auth::user();

        // 2. I-tsek kung tama ang in-input na kasalukuyang password kumpara sa database
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
        }

        // 3. I-save ang bagong password na naka-hash/encrypted
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function updateSettings(Request $request)
{
    $user = Auth::user();

    // I-validate ang mga nire-request na settings fields
    $request->validate([
        'desktop_notifications' => 'required|boolean',
        'alert_sounds' => 'required|boolean',
        'auto_delete_interval' => 'required|integer|in:0,7,30',
    ]);

    // I-update ang user record sa database
    $user->update([
        'desktop_notifications' => $request->desktop_notifications,
        'alert_sounds' => $request->alert_sounds,
        'auto_delete_interval' => $request->auto_delete_interval,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Workspace preferences updated successfully!'
    ]);
}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Afficher la page Mon compte
     */
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email_notifications_enabled' => ['boolean'],
            'system_notifications_enabled' => ['boolean'],
        ]);

        // Gestion de l'avatar
        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar s'il existe
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Stocker le nouvel avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        } else {
            unset($validated['avatar']);
        }

        // Gestion des checkboxes
        $validated['email_notifications_enabled'] = $request->has('email_notifications_enabled');
        $validated['system_notifications_enabled'] = $request->has('system_notifications_enabled');

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return redirect()->route('profile.index')->with('success', 'Photo de profil supprimée avec succès !');
    }
}

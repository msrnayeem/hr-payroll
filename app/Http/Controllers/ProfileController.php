<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function show()
    {
        return view('auth.profile');
    }
    public function edit()
    {
        return view('auth.edit');
    }
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:15',
        ]);

        // Prepare data for update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // Handle password update if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Generate a filename based on employee ID
            $extension = $request->file('profile_image')->getClientOriginalExtension(); // Get file extension
            $fileName = $user->id . '.' . $extension; // Example: "5.jpg"

            // Store the image in 'public/employee' folder with the new filename
            $path = $request->file('profile_image')->storeAs('employee', $fileName, 'public');
            $data['profile_image'] = $path;
        }

        // Update the user
        $user->update($data);

        // Redirect back to profile with success message
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }
}

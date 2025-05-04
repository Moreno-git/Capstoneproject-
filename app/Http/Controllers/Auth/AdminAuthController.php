<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.admin.login');
    }

    /**
     * Handle the login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.admin.register');
    }

    /**
     * Handle the registration request
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('admin')->login($admin);

        return redirect(route('admin.dashboard'));
    }

    /**
     * Handle the logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    /**
     * Show admin profile
     */
    public function profile()
    {
        return view('auth.admin.profile');
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // Max 1MB
        ];

        // Add password validation if password is being updated
        if ($request->filled('current_password')) {
            $rules['current_password'] = ['required', 'current_password:admin'];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules);

        // Update profile photo if provided
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($admin->profile_photo) {
                Storage::disk('public')->delete($admin->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $admin->profile_photo = $path;
        }

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function dashboard()
    {
        // Get total donations
        $totalDonations = \App\Models\Donation::sum('amount');
        $donationCount = \App\Models\Donation::count();

        // Get active campaigns count (including both 'active' and 'ongoing' status)
        $activeCampaigns = \App\Models\Campaign::whereIn('status', ['active', 'ongoing'])->count();

        // Get recent activities (donations in last 24 hours)
        $recentActivities = \App\Models\Donation::where('created_at', '>=', now()->subDay())->count();

        // Get total unique donors
        $totalDonors = \App\Models\Donation::distinct('donor_name')->count('donor_name');

        // Get recent donations
        $recentDonations = \App\Models\Donation::with('campaign')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('auth.admin.dashboard', compact(
            'totalDonations',
            'donationCount',
            'activeCampaigns',
            'recentActivities',
            'totalDonors',
            'recentDonations'
        ));
    }
} 
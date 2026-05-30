<?php
namespace App\Http\Controllers;

use App\Services\LoanService;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $loanService = app(LoanService::class);
        $loans = $loanService->getUserLoans($user);

        $stats = [
            'total_loans' => $loans->count(),
            'active_loans' => $loans->where('status', 'active')->count(),
            'returned_loans' => $loans->where('status', 'returned')->count(),
            'overdue_loans' => $loans->filter(fn($l) => $l->status === 'active' && $l->due_date->isPast())->count(),
        ];

        return view('profile.show', compact('user', 'loans', 'stats'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            ...($validated['password'] ? ['password' => bcrypt($validated['password'])] : []),
        ]);

        return back()->with('success', 'Profil mis à jour !');
    }
}

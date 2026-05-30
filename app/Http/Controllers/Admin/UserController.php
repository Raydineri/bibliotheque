<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->withCount(['loans', 'activeLoans']);

        if ($request->search) {
            $query->where('name', 'ilike', "%{$request->search}%")
                ->orWhere('email', 'ilike', "%{$request->search}%");
        }
        if ($request->role) {
            $query->role($request->role);
        }

        $users = $query->orderByDesc('created_at')->paginate(15);
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|exists:roles,name',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => bcrypt($validated['password']),
            'phone'        => $validated['phone'] ?? null,
            'member_since' => now(),
            'is_active'    => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès !');
    }

    public function show(User $user)
    {
        $user->load(['roles', 'loans.book.author']);
        $loanService = app(LoanService::class);
        $loans = $loanService->getUserLoans($user);
        return view('admin.users.show', compact('user', 'loans'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role'      => 'required|exists:roles,name',
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        $user->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'is_active' => $request->has('is_active'),
            ...($validated['password'] ? ['password' => bcrypt($validated['password'])] : []),
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour !');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Compte {$status} avec succès.");
    }
}

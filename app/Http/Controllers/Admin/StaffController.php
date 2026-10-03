<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StaffRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return view('Admin.staff.index', [
            'users' => User::query()->withCount('orders')->orderBy('role')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('Admin.staff.create', [
            'member' => new User(['role' => UserRole::Cashier]),
        ]);
    }

    public function store(StaffRequest $request): RedirectResponse
    {
        $member = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => $request->enum('role', UserRole::class),
        ]);

        return redirect()
            ->route('admin.staff.index')
            ->with('status', $member->name.' was added as '.$member->role->label().'.');
    }

    public function edit(User $user): View
    {
        return view('Admin.staff.edit', ['member' => $user]);
    }

    public function update(StaffRequest $request, User $user): RedirectResponse
    {
        $role = $request->enum('role', UserRole::class);

        if ($user->isAdmin() && $role !== UserRole::Admin && $this->isLastAdmin($user)) {
            return back()->withInput()->withErrors(['role' => 'This is the only admin account, so it must stay an admin.']);
        }

        $user->fill([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'role' => $role,
        ]);

        if ($request->filled('password')) {
            $user->password = $request->validated('password');
        }

        $user->save();

        return redirect()
            ->route('admin.staff.index')
            ->with('status', $user->name.' was updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors(['staff' => 'You cannot delete your own account while logged in.']);
        }

        if ($user->isAdmin() && $this->isLastAdmin($user)) {
            return back()->withErrors(['staff' => 'The last admin account cannot be deleted.']);
        }

        $user->delete();

        return back()->with('status', $user->name.' was removed.');
    }

    private function isLastAdmin(User $user): bool
    {
        return ! User::query()
            ->where('role', UserRole::Admin->value)
            ->whereKeyNot($user->getKey())
            ->exists();
    }
}

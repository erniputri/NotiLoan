<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {
    }

    // Halaman index dipakai super admin untuk mencari, memantau, dan mengelola akun admin yang aktif di sistem.
    public function index(Request $request)
    {
        $search = $request->string('search')->trim()->value();

        $userQuery = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        $users = $userQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.user.index', [
            'users' => $users,
            'search' => $search,
            'totalUsers' => User::count(),
            'totalAdmins' => User::where('role', User::ROLE_ADMIN)->count(),
            'totalSuperAdmins' => User::where('role', User::ROLE_SUPER_ADMIN)->count(),
        ]);
    }

    public function create()
    {
        return view('pages.user.create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createAdmin($request->validated());

        return redirect()
            ->route('user.index')
            ->with('success', "User admin {$user->name} berhasil ditambahkan.");
    }

    public function show(User $user)
    {
        return view('pages.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()
                ->route('user.index')
                ->with('info', 'Akun super admin hanya dapat dikelola dari seeder sistem.');
        }

        return view('pages.user.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $updatedUser = $this->userService->updateAdmin($user, $request->validated());

        return redirect()
            ->route('user.index')
            ->with('success', "Data user {$updatedUser->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $this->userService->deleteAdmin($user, request()->user());

        return redirect()
            ->route('user.index')
            ->with('success', 'User berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Models\User;
use App\Services\Users\UserService;
use App\Services\Users\UserExportService;
use App\Services\Users\UserQueryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;
    protected UserExportService $userExportService;
    protected UserQueryService $userQueryService;

    public function __construct(UserService $userService, UserExportService $userExportService, UserQueryService $userQueryService)
    {
        $this->userService = $userService;
        $this->userExportService = $userExportService;
        $this->userQueryService = $userQueryService;
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        [$users, $sort, $direction] = $this->userQueryService->listUsers($request);

        return view('users.index', compact('users', 'sort', 'direction'));
    }

    public function exportCsv(Request $request)
    {
        return $this->userExportService->export($request);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->userService->create($request->validated());

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->loadCount('requisitions');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->update($user, $request->validated());

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $this->userService->delete($user);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display the table view.
     *
     * @return View
     */
    public function index(): View
    {
        $users = User::all();
        return view('tables.users', compact('users'));
    }

    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user === Auth::user()) {
            // Cannot disable your own account
            throw ValidationException::withMessages([
                'disable' => trans('auth.autoDisabled'),
            ]);
        }
        else {
            // This action will not disconnect the user if he is already logged in
            $user->status = !$user->status;
            $user->save();
            return back();
        }
    }
}

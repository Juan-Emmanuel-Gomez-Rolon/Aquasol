<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class WebController extends Controller
{
    public function dashboard()
    {
        $sales = Sale::all();
        // dd($sales[0]->products);
        return Inertia::render('CashRegister/Register', [
            'csrf' => csrf_token(),
        ]);
    }

    public function users()
    {
        $users = User::withTrashed()->get();
        return Inertia::render('Users/Index', [
            'db_users' => $users,
            'csrf' => csrf_token(),
        ]);
    }

    // api endpoints
    public function store_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:admin,user', // 'in' rule is a custom rule that checks if the value is 'admin' or 'user
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $user = User::create($validator->validated());

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

    public function update_user(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:admin,user',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        // if self, cannot update role
        if ($user->id === auth()->user()->id) {
            unset($validated['role']);
        }

        $user->update($validated);

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

    public function delete_user(User $user)
    {
        // cannot delete self
        if ($user->id === auth()->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Cannot delete self'], 422);
        }

        $user->delete();

        return response()->json(['status' => 'success'], 200);
    }

    public function enable_user($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->restore();

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }
}

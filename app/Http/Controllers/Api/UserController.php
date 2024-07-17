<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role) {
            $user->assignRole($request->role);
        }else{
            $user->assignRole(env('DEFAULT_ROLE'));
        }

        return response()->json($user, 201);
    }

    public function index()
    {
        return User::all();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    public function assignRole(Request $request, User $user)
    {
        $user->assignRole($request->role);
        return response()->json(['message' => 'Role assigned successfully'], 200);
    }
    

    public function removeRole(Request $request, User $user)
    {
        Validator::validate($request->all(), [
            'role' => 'required|string|exists:roles,name',
        ]);

        $role = $request->input('role');

        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return response()->json(['message' => 'Role removed successfully'], 200);
        } else {
            return response()->json(['message' => 'User does not have the specified role'], 404);
        }
    }
}